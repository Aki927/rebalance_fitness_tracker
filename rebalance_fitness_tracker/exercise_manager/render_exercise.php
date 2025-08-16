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
session_start();
// Get the last name
$exercise_name = filter_input(INPUT_GET, 'name');

// If the exercise name input is empty return a client error response
if (empty($exercise_name)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Enter an exercise name'
    ]);
    exit();
} else {
    // Access the database once
    require_once('../model/exercise_db.php');
    require_once('../model/database.php');

    // Get customer data from the customers table
    $exercise_names = ExerciseDB::search_exercise_by_name($db, $exercise_name);

    // Set response type to JSON
    header('Content-Type: application/json');

    // If the query was successful, return response 200 and the customers array from the query
    if ($exercise_names) {
        echo json_encode([
            'status' => 'success',
            'exercises' => $exercise_names
        ]);
    } else {
        echo json_encode([
            'status' => 'failure',
            'message' => 'No results found'
        ]);
    }
}


