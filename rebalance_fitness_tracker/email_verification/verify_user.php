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
 * @var string $login_email
 */
// Start a session
session_start();
// Set return type to JSON
header('Content-Type: application/json');
try {
    // Get the email from the form
    $input = json_decode(file_get_contents('php://input'), true);

    // Get the email and password from the form
    $user_email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
    $user_password = trim($input['password']);

    // Validate the email or return a client error response 400 if invalid.
    if (!$user_email) {
        http_response_code(400);
        echo json_encode([
            'status' => 'input error',
            'message' => 'Email format invalid.'
        ]);
        exit();
    } else {
        // Access the database and email table once
        require_once('../model/database.php');
        require_once('../model/email_db.php');
        // Get user details
        $user = get_user_by_email($db, $user_email);
        // Query the user using the email entered in the form
        $valid = is_valid_user_login($db, $user_email, $user_password);
        // Otherwise, get the email from the database and return the result for the respective user
        if ($valid) {
            $_SESSION['is_valid_user']['user_id'] = $user['user_id'];
            $_SESSION['is_valid_user']['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            // Return a JSON of the user
            echo json_encode([
                'status' => 'success',
                'user' => $user
            ]);
        } else {
            echo json_encode([
                'status' => 'unauthorized',
                'message' => 'Invalid email address.'
            ]);
        }
    }
} catch (Exception $e) {
    // Catch the database exceptions
    http_response_code(500);
    echo json_encode([
        'status' => 'db_error',
        'message' => $e->getMessage()
    ]);
}
exit();
