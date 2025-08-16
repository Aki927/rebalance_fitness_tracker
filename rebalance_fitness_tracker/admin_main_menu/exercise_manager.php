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
 * @var PDO $db
 */
session_start();

// Access the database and execise db once
require_once('../model/database.php');
require_once('../model/exercise_db.php');

// Get an array of exercises
$exercises = ExerciseDB::get_exercises($db);
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
    <h1>Exercise List</h1>
    <section>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <!-- Load a list of exercises from the database -->
            <?php foreach ($exercises as $exercise) : ?>
                <tr>
                    <td><?php echo $exercise['exer_id']; ?></td>
                    <td><?php echo $exercise['name']; ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars($exercise['img_url']); ?>"
                             alt="<?php echo htmlspecialchars($exercise['name']); ?>"
                             style="width:100px;height:auto;">
                    </td>
                    <td>
                        <form class="delete_exercise_form">
                            <input type="button" value="Delete" class="delete-button"
                                   data-code="<?php echo htmlspecialchars($exercise['exer_id']); ?>">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <form method="POST" action="add_exercise_form.php">
            <button>Add Exercise</button>
        </form>
        <h2>Login Status</h2>
        <p>You are logged in as <?php echo htmlspecialchars($_SESSION['is_valid_admin']['username']); ?></p>
        <form action="../authentication/admin_logout.php" method="POST">
            <button type="submit">Logout</button>
        </form>
    </section>
</main>
<!-- Footer -->
<?php include('../view/footer.php'); ?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {                   // Wait for HTML to fully load all elements
        document.querySelectorAll('.delete-button').forEach(button => {     // Gets all the delete buttons
            button.addEventListener('click', function (event) {             // Listener for the delete button that is clicked
                const exer_id = this.dataset.code;                          // Get the exer ID from input element
                // Delete the exercise row in the HTML
                delete_exercise(event, exer_id);
            });
        });
    });

    // AJAX technique for dynamically deleting an exercise without reloading the page
    function delete_exercise(event, exer_id) {
        const data = "exer_id=" + encodeURIComponent(exer_id);

        // Creating a headers object
        const myHeaders = new Headers();
        myHeaders.append('Content-Type', 'application/x-www-form-urlencoded');
        myHeaders.append('Accept', 'application/json');

        // Use a POST request to send the tech ID
        fetch('delete_exercise.php', {                                      // Use Fetch API alt. to XMLHttpRequest
            method: 'POST',
            headers: myHeaders,
            body: data
        })
            .then(response => response.json())                              // Set response to JSON
            .then(result => {                                               // Handle result from server
                if (result.status === 'db_error') {
                    window.location.href = `../error/database_error.php`;
                } else if (result.status === "success") {                   // Remove the row from HTML
                    alert(result.message);
                    const row = event.target.closest("tr");                 // Propagate to the closest to parent to delete row
                    row.remove();
                } else {
                    alert("Error: " + result.message);
                }
            })
            .catch(error => {
                console.error('There was an error with performing AJAX', error);
                window.location.href = "../error/database_error.php";
            });
    }
</script>
</body>
</html>
