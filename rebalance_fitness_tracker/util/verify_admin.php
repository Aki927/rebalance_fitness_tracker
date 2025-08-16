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
 * @var string $admin_username
 * @var string $admin_password
 */
// Start a session
session_start();

// Get the email from the form
$input = json_decode(file_get_contents('php://input'), true);

// Get the username and password from the form
$admin_username = trim($input['username']);
$admin_password = trim($input['password']);

// Validate the username or return a client error if invalid
if (!$admin_username) {
    http_response_code(400);
    echo json_encode([
        'status' => 'input error',
        'message' => 'Username invalid.'
    ]);
    exit();
} else {
    // Access the database and admin table
    require_once('../model/database.php');
    require_once('../model/admin_db.php');

    // Get admin details
    $admin = get_admin_by_username($db, $admin_username);

    // Query the user using the username entered in the form
    $valid = is_valid_admin_login($db, $admin_username, $admin_password);

    // Set return type to JSON
    header('Content-Type: application/json');

    // Otherwise, get the email from the database and return the result for the respective user
    if ($valid) {
        $_SESSION['is_valid_admin']['username'] = $admin['username'];
        // Return a JSON of the user
        echo json_encode([
            'status' => 'success',
            'admin' => $admin
        ]);
    } else {
        echo json_encode([
            'status' => 'unauthorized',
            'message' => 'Invalid email address.'
        ]);
    }
}
