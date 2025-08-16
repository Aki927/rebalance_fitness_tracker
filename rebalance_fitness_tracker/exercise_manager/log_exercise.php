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
// Update an exercise set by rep and weight entries
function update_exercise_set($key, $reps, $lbs) : bool {
    $reps = (int) $reps;
    $lbs = (int) $lbs;
    if (isset($_SESSION['workout_session'][$key])) {
        if ($reps <= 0 && $lbs <= 0) {
            unset($_SESSION['workout_session'][$key]);
        } else {
            $_SESSION['workout_session'][$key]['reps'] = $reps;
            $_SESSION['workout_session'][$key]['lbs'] = $lbs;
            $velocity = $_SESSION['workout_session'][$key]['reps'] *
                $_SESSION['workout_session'][$key]['lbs'];
            $_SESSION['workout_session'][$key]['velocity'] = $velocity;
        }
    }
    return true;
}

// Add an exercise set data to session to use later in DB
function add_exercise_set($name, $exer_id): bool {
    $reps = 1;
    $lbs = 1;
    $velocity = $reps * $lbs;

    $exercise_set = [
        'name' => $name,
        'reps' => $reps,
        'lbs' => $lbs,
        'velocity' => $velocity,
        'exer_id' => $exer_id
    ];

    // Allow for duplicate workouts in the workout log
    $_SESSION['workout_session'][] = $exercise_set;
    return true;
}

// Get total volume
function get_volume () {
    $sum = 0;
    foreach ($_SESSION['workout_session'] as $exercise) {
        $sum += $exercise['velocity'];
    }
    return $sum;
}