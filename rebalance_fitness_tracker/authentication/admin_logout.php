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
 */
session_start();
// Clear session data from memory
if (isset($_SESSION['is_valid_admin'])) {
    // Clear session data from memory
    unset($_SESSION['is_valid_admin']);
}

//// Clean up session ID
//session_destroy();
//// Delete the cookie for the session
//$name = session_name();                         // Get name of the session cookie
//$expire = strtotime('-1 year');                 // Create expiration date in the past
//$params = session_get_cookie_params();          // Get session params
//$path = $params['path'];
//$domain = $params['domain'];
//$secure = $params['secure'];
//$httponly = $params['httponly'];
//setcookie($name, '', $expire, $path, $domain, $secure, $httponly);

// Redirect to the login page
header("Location: ../index.php");
exit();
