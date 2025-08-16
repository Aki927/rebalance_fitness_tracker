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
class ExerciseDB
{
    // Search an exercise by its name
    public static function search_exercise_by_name($db, $exercise_name): array
    {
        try {
            $query = "SELECT * FROM exercises 
                      WHERE name LIKE :exercise_name 
                      ORDER BY name 
                      LIMIT 20";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':exercise_name', '%' . $exercise_name . '%');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    // Recommend least-used exercises
    public static function recommend_exercise_by_user($db, $user_id): array
    {
        try {
            $query = "SELECT e.name, e.exer_id, e.muscle_id, e.img_url, COUNT(*) AS frequency
                  FROM users u
                  JOIN workouts w
                      ON u.user_id = w.user_id
                  JOIN exercise_set s 
                      ON w.workout_id = s.workout_id
                  JOIN exercises e 
                      ON s.exer_id = e.exer_id
                  WHERE w.user_id = :user_id
                  GROUP BY e.name, e.exer_id, e.muscle_id, e.img_url
                  ORDER BY frequency
                  LIMIT 5";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->execute();
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);
            $statement->closeCursor();
            return $results;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    // Add an exercise to the db by name and muscle id
    public static function add_exercise($db, $name, $muscle_id): bool
    {
        try {
            $query = 'INSERT INTO exercises (exer_id, name, muscle_id)
                VALUES (DEFAULT, :name, :muscle_id)';
            $statement = $db->prepare($query);
            $statement->bindValue(':name', $name);
            $statement->bindValue(':muscle_id', $muscle_id);
            $statement->execute();
            $statement->closeCursor();
            return true;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    // Delete an exercise using exercise id
    public static function delete_exercise($db, $exer_id): bool
    {
        try {
            $query = 'DELETE FROM exercises 
            WHERE exer_id = :exer_id';
            $statement = $db->prepare($query);
            $statement->bindValue(':exer_id', $exer_id);
            $statement->execute();
            $statement->closeCursor();
            return true;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'db_error',
                'message' => 'Database error: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    // Get an array of exercises
    public static function get_exercises($db): array
    {
        try {
            $query = 'SELECT * FROM exercises 
            ORDER BY exer_id';
            $statement = $db->prepare($query);
            $statement->execute();
            $exercises = $statement->fetchall();
            $statement->closeCursor();
            return $exercises;
        } catch (PDOException $e) {
            $error_message = $e->getMessage();
            include('../error/database_error.php');
            exit();
        }
    }

}