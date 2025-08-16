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
// Add and delete from the workouts table
class WorkoutDB {
    public static function createWorkout($db, $user_id) : int | false {
        try {
            $query = "INSERT INTO workouts (user_id, workout_date)
                    VALUES (:user_id, :workout_date)";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_id', $user_id);
            $statement->bindValue(':workout_date', date('Y-m-d'));
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

    public static function abortCurrentWorkout($db, $current_workout_id) : bool {
        try {
            $query = "DELETE FROM workouts WHERE workout_id = :workout_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':workout_id', $current_workout_id);
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
}