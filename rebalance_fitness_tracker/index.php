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
 * Technologies: XAMPP(PHP, MySQL/MariaDB, Apache), AJAX, Javascript, Fetch API
 * Images: https://Wger.de/api/v2/, Wger SwaggerUI documentation
 *
 */
session_start();
?>
<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ReBalance Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="main.css"/>
</head>
<body>
<header>
    <img src="images/rebalance_logo.png" alt="ReBalance Logo" style="width:100px;height:auto;"/>
    <!-- Use a relative path so Home can be clicked from anywhere in the project -->
    <h3><a href="index.php">Home</a></h3>
</header>
    <main>
        <nav>
            <h2>Welcome to ReBalance Fitness Tracker!</h2>
            <ul>
                <li><a href="authentication/user_login.php">User Login</a></li>
                <li><a href="authentication/admin_login.php">Admin Login</a></li>
            </ul>
        </nav>
    </main>
<?php include 'view/footer.php'; ?>
</body>
</html>
