<?php
/**
 * COMP 3541 Project - ReBalance Fitness Tracker
 * Jerome Laranang, T00635622, July 1, 2025
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

Class ResetWorkout {
    public static function reset() : void {
        $reset = [];
        $_SESSION['workout_session'] = $reset;
        header('Location: ../main_menu/user_home_page.php');
    }
}
