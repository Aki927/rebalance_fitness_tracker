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
session_start();
// Access the database and exercise set table once
require_once('../model/database.php');
require_once('../model/exercise_set_db.php');

// Get the last 50 workout sessions
$workouts = ExerciseSetDB::getExerciseHistory($db, $_SESSION['is_valid_user']['user_id']);
$workouts_by_date = [];
foreach ($workouts as $workout) {
    $date = $workout['date'];
    $workout_id = $workout['workout_id'];
    if (!isset($workouts_by_date[$date]))
        $workouts_by_date[$date] = [];
    if (!isset($workouts_by_date[$date][$workout_id]))
        $workouts_by_date[$date][$workout_id] = [];
    $workouts_by_date[$date][$workout_id][] = $workout;
}

// Limit: render last 50 workout sessions only
$render_limit = array_slice($workouts_by_date, 0, 50, true);
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
    <!-- Renders a history of the last 20 sets and their date -->
    <h1>Workout History</h1>
    <section>
        <?php if (!empty($render_limit)) : ?>
            <?php foreach ($render_limit as $date => $row) : ?>
                <div class="workout-date">
                    <h2><?php echo htmlspecialchars(date('n/j/Y', strtotime($date))); ?></h2>

                    <?php foreach ($row as $workout_id => $sets) : ?>
                        <div class="workout-session">
                            <h3>Workout ID: <?php echo htmlspecialchars($workout_id); ?></h3>

                            <table class="exercise-table">
                                <thead>
                                <tr>
                                    <th>Exercise</th>
                                    <th>Weight</th>
                                    <th>Reps</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($sets as $set) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($set['exercise']); ?></td>
                                        <td><?php echo htmlspecialchars($set['weight']); ?> lbs</td>
                                        <td><?php echo htmlspecialchars($set['reps']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>No workout history found.</p>
        <?php endif; ?>
        <!-- Login Status -->
        <h2>Login Status</h2>
        <p>Logged in as <?php echo htmlspecialchars($_SESSION['is_valid_user']['full_name']); ?></p>
        <p>Current workout session: <?php echo htmlspecialchars($_SESSION['workout_session']['workout_id']); ?></p>
        <p>Session ID: <?php echo session_id(); ?></p>
        <form method="POST" action="../authentication/user_logout.php">
            <button type="submit">Logout</button>
        </form>
    </section>
</main>
<?php include('../view/footer.php'); ?>
