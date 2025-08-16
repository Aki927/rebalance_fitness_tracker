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
 * @var string $error_message
 */
// Error message first checks if error message is passed via non AJAX error before checking for AJAX error and default.
$error_message = $error_message ?? $_GET['message'] ?? 'Oops something went wrong. Error code 500.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ReBalance Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="../main.css" />
</head>
<body>
<!-- Header -->
<?php include('../view/header.php'); ?>

<!-- Error message for database  -->
<main>
    <h1>Database Error</h1>
    <p>An error occurred while attempting to work with the database</p>
    <p>Message: <?php echo $error_message; ?></p>
    <p>&nbsp;</p>
</main>
<!-- Header -->
<?php include('../view/footer.php'); ?>
</body>
</html>