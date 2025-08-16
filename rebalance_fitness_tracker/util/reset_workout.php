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
 */
session_start();

// Set content type to JSON
header('Content-Type: application/json');

//if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//    http_response_code(400);
//    echo json_encode([
//        'success' => false,
//        'message' => 'Unable to reset workout.']);
//    exit;
//}

try {
    // Call the reset function
    ResetWorkout::reset();
    echo json_encode([
        'success' => true,
        'message' => 'Workout reset successfully'
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error resetting workout: ' . $e->getMessage()
    ]);
}
