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
 *
 * @var string $db
 */
// - CONTROLLER -
session_start();

// Create a table of exercises
$exercises = [];

// Access workout and log exercise functions
require_once('../model/database.php');
require_once('log_exercise.php');
require_once('../model/workouts_db.php');
require_once('../model/exercise_set_db.php');
require_once('../model/exercise_db.php');

// Get the action to perform. Default to show add exercise.
$action = filter_input(INPUT_POST, 'action')
    ?? filter_input(INPUT_GET, 'action')
    ?? 'show_add_exercise';

// Add or update cart as needed
switch($action) {
    // User can explicitly choose to abort in-progress workout and start new
    case 'abort_old_create_new':
        // Send user to login page if not logged in.
        $user_id = $_SESSION['is_valid_user']['user_id'];
        if (!$user_id) {
            header('Location: ../authentication/user_login.php');
            exit();
        }
        
        // Use workout id to delete a workout from the database
        $current_workout_id = $_SESSION['workout_session']['workout_id'] ?? null;
        if (!empty($current_workout_id)) {
            $aborted = WorkoutDB::abortCurrentWorkout($db, $current_workout_id);
            if ($aborted) {
                // Clear old workout session
                unset($_SESSION['workout_session']);

                // Create new workout session
                $workout_id = WorkoutDB::createWorkout($db, $user_id);
                $_SESSION['workout_session'] = [
                    'workout_id' => $workout_id,
                    'started' => true
                ];
            }
        } else {
            // If no existing workout, create a new one
            $workout_id = WorkoutDB::createWorkout($db, $user_id);
            $_SESSION['workout_session'] = [
                'workout_id' => $workout_id,
                'started' => true
            ];
        }
        include('add_exercise_view.php');
        break;
    // Add an exercise set is triggered when user clicks the add button
    case 'add_exercise_set':
        $user_id = $_SESSION['is_valid_user']['user_id'];
        if (!$user_id) {
            header('Location: ../authentication/user_login.php');
            exit();
        }

        // Get the exercise id and name to create a new exercise set
        $exer_id = filter_input(INPUT_POST, 'exer_id');
        $name = filter_input(INPUT_POST, 'name');

        // Check if workout exists. If it doesn't, create a new workout ID.
        if (empty($_SESSION['workout_session']['workout_id'])) {
            $workout_id = WorkoutDB::createWorkout($db, $user_id);
            $_SESSION['workout_session'] = [
                'workout_id' => $workout_id,
                'started' => true
            ];
        }
        // Adds an exercise set to the current session and assigns it to a variable
        $added = add_exercise_set($name, $exer_id);
        if ($added) {
            // Create an exercise set using workout id and exercise id
            $set_id = ExerciseSetDB::createExerciseSet(
                $db,
                $_SESSION['workout_session']['workout_id'],
                $exer_id,
            );

            // Get the set id of the last exercise set added then add it to the session
            $last_set_key = array_key_last($_SESSION['workout_session']);
            if (is_numeric($last_set_key)) {
                $_SESSION['workout_session'][$last_set_key]['set_id'] = $set_id;
            }
        }

        // Show the added exercise in the workout log after its added
        include('log_exercise_view.php');
        break;
    // Update an exercise set when the user clicks the update button or changes rep/weight values in the log
    case 'update_exercise_set':
        $reps_list = filter_input(INPUT_POST, 'reps', FILTER_VALIDATE_INT,
            FILTER_REQUIRE_ARRAY | FILTER_NULL_ON_FAILURE);
        $lbs_list = filter_input(INPUT_POST, 'weight', FILTER_VALIDATE_FLOAT,
            FILTER_REQUIRE_ARRAY | FILTER_NULL_ON_FAILURE);

        // Loop through the rep list
        foreach ($reps_list as $key => $reps) {
            $lbs = $lbs_list[$key] ?? $_SESSION['workout_session'][$key]['lbs'];
            $exer_id = $_SESSION['workout_session'][$key]['exer_id'];

            // If any of the reps/weight values are changed, update the exercise set table
            if ($_SESSION['workout_session'][$key]['reps'] != $reps
                || $_SESSION['workout_session'][$key]['lbs'] != $lbs) {

                // If the session is updated with the new log data, update the database as well
                $updated = update_exercise_set($key, $reps, $lbs);
                if ($updated && isset($_SESSION['workout_session'][$key]['set_id'])) {
//                if ($updated) {
//                    ExerciseSetDB::updateExerciseSet(
//                        $db,
//                        $_SESSION['workout_session']['workout_id'],
//                        $exer_id,
//                        $lbs,
//                        $reps
//                    );
                    ExerciseSetDB::updateExerciseSetById(
                        $db,
                        $_SESSION['workout_session'][$key]['set_id'],
                        $lbs,
                        $reps
                    );
                }
            }
        }
        include('log_exercise_view.php');
        break;
    case 'show_add_exercise':
        include('add_exercise_view.php');
        break;
    // Finish the workout session by resetting the session and directing user to the main menu
    case 'clear_workout':
        unset($_SESSION['workout_session']);
        header('Location: ../main_menu/user_home_page.php');
        break;
    // Unset user sessions and logout
    case 'logout':
        echo $action;
        unset($_SESSION['is_valid_user']);
        unset($_SESSION['workout_session']);
        header("Location: ../index.php");
        break;
}