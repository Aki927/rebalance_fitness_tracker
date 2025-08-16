<?php
/**
 * ReBalance Fitness Tracker
 * Jerome Laranang, July 1, 2025
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
 * @var PDO $db
 */
// Clear the errors array of any existing errors
$errors = array();

// Get the customer details from the form
$name = filter_input(INPUT_POST, 'name');
$muscle_id = filter_input(INPUT_POST, 'muscle_id');

// Validation logic
if (!$name) $errors['name'] = 'Required.';

// Send errors via JSON encode if any invalid inputs exist
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'failure',
        'errors' => $errors
    ]);
    exit();
} else {
    // Access the database once
    require_once('../model/database.php');
    require_once('../model/exercise_db.php');

    // Query to insert a new exercise using data from the form
    $success = ExerciseDB::add_exercise($db, $name, $muscle_id);
    if ($success) { include('exercise_manager.php'); }

    // Set the response type to JSON
    header('Content-Type: application/json');

    if ($success) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'exercise' => [
                'name' => $name,
                'muscle_id' => $muscle_id,
            ]
        ]);
    } else {
        // Otherwise, send an error message upon failed query
        http_response_code(500);
        echo json_encode([
            'status' => 'query failure',
            'message' => 'DB process failed. Please try again later.'
        ]);
    }
}

