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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ReBalance Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="../main.css"/>
</head>
<body>
<?php include('../view/header.php'); ?>
<main>
    <h1>Workout Log</h1>
    <?php if (empty($_SESSION['workout_session']) || count($_SESSION['workout_session']) == 0) : ?>
        <p>There are no items in your cart.</p>
    <?php else: ?>
        <!-- Log exercise by entering numbers for reps and weight -->
        <form action="." method="POST">
            <input type="hidden" name="action" value="update_exercise_set">
            <table>
                <tr id="cart_header">
                    <th class="left">Exercise</th>
                    <th class="right">Reps</th>
                    <th class="right">Weight(lbs)</th>
                    <th class="right">Volume</th>
                </tr>
                <?php foreach ($_SESSION['workout_session'] as $key => $exercise):
                    if (!is_array($exercise) || !isset($exercise['name'])) continue;
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($exercise['name']); ?></td>
                        <td class="right">
                            <input type="text" class="cart_qty"
                                   name="reps[<?php echo $key; ?>]"
                                   value="<?php echo htmlspecialchars($exercise['reps']); ?>">
                        </td>
                        <td class="right">
                            <input type="text" class="cart_qty"
                                   name="weight[<?php echo $key; ?>]"
                                   value="<?php echo htmlspecialchars($exercise['lbs']); ?>">
                        </td>
                        <td class="right"><?php echo htmlspecialchars($exercise['velocity']); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr id="cart_footer">
                    <td colspan="3"><b>Total Volume</b></td>
                    <td><?php echo get_volume(); ?></td>
                </tr>
                <tr>
                    <td colspan="4" class="right">
                        <input type="submit" value="Update">
                    </td>
                </tr>
            </table>
            <p>Enter a value for the number of reps and the amount of weight you completed in your set.
                Click the update button to save your changes. Enter 0 for both reps and weight to delete.
            </p>
        </form>
    <?php endif; ?>
    <p><a href=".?action=show_add_exercise">Add Exercise</a></p>
    <p><a href=".?action=clear_workout">Finish Workout</a></p>
    <h2>Login Status</h2>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['is_valid_user']['full_name']); ?></p>
    <p>Current workout session: <?php echo htmlspecialchars($_SESSION['workout_session']['workout_id']); ?></p>
    <p>Session ID: <?php echo session_id(); ?></p>
    <form method="POST" action="../authentication/user_logout.php">
        <button type="submit">Logout</button>
    </form>
</main>
</body>
</html>
