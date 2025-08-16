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

// Check if the user is already logged in and send them to the login page if they're not
if (!isset($_SESSION['is_valid_user'])) {
    header('Location: ../authentication/user_login.php');
    exit();
}
$workout_in_progress = isset($_SESSION['workout_session']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ReBalance Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="../main.css"/>
</head>
<body>
<?php include '../view/header.php'; ?>
<main>
    <nav>
        <h2>User Home Page</h2>
        <ul>
            <!-- Menu -->
            <li><a href="../exercise_manager/index.php">Start New Workout</a></li>
            <li><a href="../dashboard_manager/dashboard.php">Dashboard</a></li>
            <li><a href="../display_manager/workout_history.php">Workout History</a></li>
        </ul>
    </nav>
    <!-- Option to start a new workout session or continue in-progress workout -->
    <?php if ($workout_in_progress) : ?>
        <div>
            <h3>You have an ongoing workout session.</h3>
            <p>Would you like to continue or start a new one?</p>
            <button id="continue-btn">Continue Workout</button>
            <button id="new-btn">New Workout</button>
        </div>
    <?php endif; ?>
    <h2>Login Status</h2>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['is_valid_user']['full_name']); ?></p>
    <p>Current workout session: <?php echo htmlspecialchars($_SESSION['workout_session']['workout_id']); ?></p>
    <p>Session ID: <?php echo session_id(); ?></p>
    <form method="POST" action="../authentication/user_logout.php">
        <button type="submit">Logout</button>
    </form>
</main>
<?php include '../view/footer.php'; ?>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // AJAX event listeners for the continue button and new button
        document.querySelector('#continue-btn').addEventListener('click', () => {
            const continueBtn = document.getElementById("continue-btn");
            // Continue in-progress workout
            if (continueBtn)
                window.location.href = "../exercise_manager/index.php?action=show_add_exercise";
        })
        document.querySelector('#new-btn').addEventListener('click', async () => {
            // Start new workout and abort in-progress
            const newBtn = document.getElementById("new-btn");
            if (newBtn) {
                window.location.href = "../exercise_manager/index.php?action=abort_old_create_new";
            }
        })
    });
</script>
