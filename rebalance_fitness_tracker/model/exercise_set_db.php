<?php
/**
 * ReBalance Fitness Tracker
 *  Jerome Laranang, July 1, 2025
 *
 * This PHP and MySQL app is a fitness tracker that allows users to log their workouts while at
 * the gym. Users can search for exercises, and log the reps and weight each set. The app recommends
 * a list of exercises using a least-used formula that helps the user balance their exercise routine.
 * The user can also access a dashboard of their weekly (Sun-Sat) progress and see their workout history.
 * A role-based access control gives the admin the ability to add and remove exercises.
 *
 * Technologies: XAMPP(PHP, MySQL/MariaDB, Apache), AJAX, Javascript
 * Images: https://Wger.de/api/v2/, Wger SwaggerUI documentation
 */
session_start();
class ExerciseSetDB
{
    // Create an exercise set using workout and exercise id
    public static function createExerciseSet($db, $workout_id, $exer_id, $weight_lifted = 1, $reps_completed = 1) {
        try {
            $query = "INSERT INTO exercise_set (
                          workout_id, 
                          exer_id, 
                          weight_lifted, 
                          reps_completed
                        )
                    VALUES (:workout_id, :exer_id, :weight_lifted, :reps_completed)";
            $statement = $db->prepare($query);
            $statement->bindValue(':workout_id', $workout_id);
            $statement->bindValue(':exer_id', $exer_id);
            $statement->bindValue(':weight_lifted', $weight_lifted);
            $statement->bindValue(':reps_completed', $reps_completed);
            $statement->execute();
            $statement->closeCursor();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    public static function updateExerciseSet($db, $workout_id, $exer_id, $weight_lifted, $reps_completed): bool {
        try {
            $query = "UPDATE exercise_set
                  SET weight_lifted = :weight_lifted,
                      reps_completed = :reps_completed
                  WHERE workout_id = :workout_id AND exer_id = :exer_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':weight_lifted', $weight_lifted);
            $statement->bindValue(':reps_completed', $reps_completed);
            $statement->bindValue(':workout_id', $workout_id);
            $statement->bindValue(':exer_id', $exer_id);
            $statement->execute();
            $statement->closeCursor();
            return true;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit();
        }
    }

    // Update an exercise set using a set id
    public static function updateExerciseSetById($db, $set_id, $weight_lifted, $reps_completed): bool {
        try {
            $query = "UPDATE exercise_set
                      SET weight_lifted = :weight_lifted,
                          reps_completed = :reps_completed
                      WHERE set_id = :set_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':weight_lifted', $weight_lifted);
            $statement->bindValue(':reps_completed', $reps_completed);
            $statement->bindValue(':set_id', $set_id);
            $statement->execute();
            $statement->closeCursor();
            return true;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
            exit();
        }
    }

    // Get an exercise set using a user id
    public static function getExerciseStats($db, $user_id): array {
        try {
            $query = "SELECT 
                        u.user_id,
                        CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                        pm.name AS muscle_group,
                        COUNT(es.set_id) AS total_sets
                    FROM users u
                    JOIN workouts w ON u.user_id = w.user_id
                    JOIN exercise_set es ON w.workout_id = es.workout_id
                    JOIN exercises e ON es.exer_id = e.exer_id
                    JOIN primary_muscle pm ON e.muscle_id = pm.muscle_id
                    WHERE u.user_id = :user_id 
                      AND w.workout_date BETWEEN 
                          DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) % 7 DAY) 
                          AND DATE_ADD(DATE_SUB(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) % 7 DAY),
                              INTERVAL 6 DAY
                          )           
                    GROUP BY u.user_id, pm.muscle_id, pm.name;";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
            return $result;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    public static function getVolumeByUser($db, $user_id) : array {
        try {
            $query = "SELECT 
                        u.first_name AS name,
                        (COUNT(s.set_id) * SUM(s.weight_lifted) * SUM(s.reps_completed)) AS total_volume
                        FROM users u
                        JOIN workouts w ON u.user_id = w.user_id
                        JOIN exercise_set s ON w.workout_id = s.workout_id
                        JOIN exercises e ON s.exer_id = e.exer_id
                        WHERE u.user_id = :user_id 
                            AND w.workout_date BETWEEN DATE_SUB(
                                CURDATE(), 
                                INTERVAL (WEEKDAY(CURDATE()) + 1) % 7 DAY) 
                            AND DATE_ADD(DATE_SUB(
                                CURDATE(), 
                                INTERVAL (WEEKDAY(CURDATE()) + 1) % 7 DAY),
                                INTERVAL 6 DAY
                              )";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
            return $result;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    // Join tables to compile an exercise history
    public static function getExerciseHistory($db, $user_id): array {
        try {
            $query = "SELECT
                    w.workout_id AS workout_id,
                    w.workout_date AS date,
                    e.name AS exercise,
                    s.weight_lifted AS weight,
                    s.reps_completed AS reps
                    FROM users u 
                    JOIN workouts w ON u.user_id = w.user_id
                    JOIN exercise_set s ON w.workout_id = s.workout_id
                    JOIN exercises e ON s.exer_id = e.exer_id
                    WHERE u.user_id = :user_id
                    ORDER BY w.workout_date DESC, w.workout_id, e.name";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
            return $result;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

}