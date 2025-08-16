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
 * @var PDO $db
 */
// Start session
session_start();

// Access database and exercise set db once
require_once('../model/database.php');
require_once('../model/exercise_set_db.php');

// Get the user id for exercise set db
$user_id = $_SESSION['is_valid_user']['user_id'];

// Get exercise stats from DB
$results = ExerciseSetDB::getExerciseStats($db, $user_id);

// Volume = sets completed * reps completed * weight lifted
// Get an array of volume by user id and render in the dashboard
$volume = ExerciseSetDB::getVolumeByUser($db, $user_id);
$total_vol = 0;
foreach ($volume as $row) {
    $total_vol += $row['total_volume'];
}

// Get the running total for sets
$total_sets = 0;
foreach ($results as $row) {
    $total_sets += $row['total_sets'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Fitness App</title>
    <link rel="stylesheet" type="text/css" href="../main.css"/>
</head>
<body>
<!-- the header section -->
<?php include '../view/header.php'; ?>
<main>
    <div class="container">
        <h1>Weekly Dashboard (Sun–Sat)</h1>
        <!-- Dashboard: shows the sets completed in a week -->
        <div class="dashboard">
            <div class="stats-summary">
                <div class="total-sets"><?php echo htmlspecialchars($total_sets); ?></div>
                <div class="sets-label">sets were completed this week</div>
            </div>
            <div class="stats-summary">
                <p style="font-style: italic">[ volume = sets completed x reps completed x weight lifted ]</p>
                <div class="total-sets"><?php echo htmlspecialchars($total_vol); ?></div>
                <div class="sets-label">is your current total volume this week</div>
            </div>
            <?php foreach ($results as $row) : ?>
                <div class="muscle-card fade-in">
                    <div class="muscle-header">
                        <div class="muscle-name"><?php echo htmlspecialchars($row['muscle_group']) ?></div>
                    </div>
                    <div class="sets-count"><?php echo htmlspecialchars($row['total_sets']);?></div>
                    <div class="sets-label">Exercise Sets</div>
                    <div class="progress-bar">
                        <div class="progress-fill"
                             style="width:<?php echo htmlspecialchars($row['total_sets']/$total_sets*100); ?>%">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>
<!-- the footer section -->
<?php include '../view/footer.php'; ?>
</body>
</html>
