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
 * @var array $muscles
 * @var string $name
 * @var PDO $db
 */
session_start();
// Access the database and muscle table once
require_once('../model/database.php');
require_once('../model/muscle_db.php');

// Get a list of muscles to render in a select element
$muscles = MuscleDB::get_muscles($db);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ReBalance Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="../main.css"/>
</head>
<body>
<!-- Header -->
<?php include('../view/header.php'); ?>
<main>
    <h1>Add Exercise</h1>
    <!-- Add an exercise -->
    <form id="add_exercise_form" action="add_exercise.php" method="POST">
        <label for="exer_name">Name:</label>
        <input id="exer_name" type="text" name="name"
               value="<?php echo htmlspecialchars($name); ?>"><br>
        <label for="muscle-select">Muscle:</label>
        <select id="muscle-select" name="muscle_id">
            <!-- Loop through the different muscles. Load the list of muscles with chest as the default muscle -->
            <?php foreach ($muscles as $muscle) : ?>
                <option value="<?php echo htmlspecialchars($muscle['muscle_id']); ?>"
                    <?php if ($muscle['name'] === 'Chest') echo 'selected'; ?>>
                    <?php echo htmlspecialchars($muscle['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <label for="exercise_button">&nbsp;</label>
        <input id="exercise_button" type="submit" value="Add Exercise"><br>
    </form>
    <p><a href="exercise_manager.php">View Exercise List</a></p>
    <h2>Login Status</h2>
    <p>You are logged in as <?php echo htmlspecialchars($_SESSION['is_valid_admin']['username']); ?></p>
    <form action="../authentication/admin_logout.php" method="POST">
        <button type="submit">Logout</button>
    </form>
</main>
<!-- Footer -->
<?php include('../view/footer.php'); ?>

</body>
</html>
