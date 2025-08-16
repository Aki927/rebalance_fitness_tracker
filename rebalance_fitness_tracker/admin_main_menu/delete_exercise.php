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
// Response is in JSON
header('Content-Type: application/json');

// Get exer ID and validate to ensure exer ID was sent via POST.
$exer_id = filter_input(INPUT_POST, 'exer_id', FILTER_VALIDATE_INT);
if (!$exer_id) {
    // Respond with a client error if exer ID is invalid
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid input'
    ]);
    exit();
} else {
    // Access the database and exercise table once
    require_once('../model/database.php');
    require_once('../model/exercise_db.php');

    // Delete technician from DB
    $success = ExerciseDB::delete_exercise($db, $exer_id);

    // If the query is successful, send a response 200 with a confirmation.
    if ($success) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => 'Exercise deleted successfully'
        ]);
    } else {
        // Otherwise, respond with an error
        http_response_code(500);
        echo json_encode([
            'status' => 'error',
            'message' => 'Something went wrong. Unable to delete.'
        ]);
    }
}
