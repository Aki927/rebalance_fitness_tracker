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
 * @var string $db
 * @var array $recommendations
 */
session_start();
require_once('../model/database.php');

// Get an array of recommended, least-used exercises
$recommendations = ExerciseDB::recommend_exercise_by_user($db, $_SESSION['is_valid_user']['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ReBalance Fitness Tracker</title>
    <link rel="stylesheet" type="text/css" href="../main.css"/>
</head>
<body>
<!-- Header view -->
<?php include('../view/header.php'); ?>
<main>
    <h1>Search Exercise</h1>
    <!-- Customer Search Form using AJAX to dynamically populate search results -->
    <form id="exercise_search_form">
        <label for="exercise_name">Search Exercise:</label>
        <input id="exercise_name" type="text" name="exercise_name"><br>
        <input type="button" value="Search" class="search-button"><br>
    </form>

    <!-- Results section -->
    <section>
        <h1>Search Results</h1>
        <table id="result-table">
            <!-- AJAX rendering goes here -->
        </table>
    </section>

    <!-- My Recommended Exercises section -->
    <section>
        <h1>Revisit</h1>
        <?php if (!empty($recommendations)) : ?>
            <h3>It's been a while since you did these</h3>
            <table>
                <tr>
                    <th>Name</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                <!-- Loop through the recommended exercises to create a list -->
                <?php foreach ($recommendations as $exercise) : ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($exercise['name']); ?>
                        </td>
                        <td>
                            <!-- Exercise image -->
                            <img src="<?php echo htmlspecialchars($exercise['img_url']); ?>"
                                 alt="<?php echo htmlspecialchars($exercise['name']); ?>"
                                 style="width:100px;height:auto;">
                        </td>
                        <td>
                            <!-- Add exercise by clicking the add button -->
                            <form method="POST" action=".">
                                <input type="hidden" name="action" value="add_exercise_set">
                                <input type="hidden" name="exer_id"
                                       value="<?php echo htmlspecialchars($exercise['exer_id']); ?>">
                                <input type="hidden" name="name"
                                       value="<?php echo htmlspecialchars($exercise['name']); ?>">
                                <input type="hidden" name="muscle_id"
                                       value="<?php echo htmlspecialchars($exercise['muscle_id']); ?>">
                                <button type="submit">Add</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </section>
    <!-- Login status -->
    <h2>Login Status</h2>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['is_valid_user']['full_name']); ?></p>
    <p>Current workout session: <?php echo htmlspecialchars($_SESSION['workout_session']['workout_id']); ?></p>
    <p>Session ID: <?php echo session_id(); ?></p>
    <form action="../authentication/user_logout.php">
        <button type="submit">Logout</button>
    </form>
</main>
<!-- Footer view -->
<?php include('../view/footer.php'); ?>
<!-- AJAX script for searching and selecting exercises  -->
<script type="text/javascript">
    // Wait for HTML to fully load all elements
    document.addEventListener('DOMContentLoaded', () => {
        // Listener for the select button that is clicked
        document.querySelector('.search-button').addEventListener('click', function () {
            // Get the exercise name from user input
            const exercise_name = document.getElementById('exercise_name').value;
            // Pass the last name from the field as a parameter to find the customer in database
            searchExercise(exercise_name);
        })
    })
    // AJAX technique for dynamically searching a customer without reloading the page
    function searchExercise(exercise_name) {
        const url = `render_exercise.php?name=${encodeURIComponent(exercise_name)}`;
        // Creating a headers object
        const myHeaders = new Headers();
        myHeaders.append('Content-Type', 'application/x-www-form-urlencoded');
        myHeaders.append('Accept', 'application/json');
        // Use a GET request to obtain customer data based on last name
        fetch(url, {
            method: 'GET',
            headers: myHeaders
        })                                                                  // Use Fetch API as alt. to XMLHttpRequest
            .then(response => response.json())                              // Set response to JSON
            .then(result => {                                               // Handle result from server
                // Change the inner HTML of the result table element
                const table = document.getElementById('result-table');
                table.innerHTML = `<tr>
                                        <th>Name</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>`;

                if (result.status === 'db_error') {
                    window.location.href = `../error/database_error.php`;
                } else if (!result.exercises || result.exercises.length === 0) {
                    // If the result contains an empty array from the query, display a message for no results
                    table.innerHTML += `<tr><td colspan='2'>No results found.</td></tr>`;
                } else {
                    // Otherwise fill in the table data with the customer details whose last name contains text field data.
                    // Then POST the form data to the view/update page
                    result.exercises.forEach(exercise => {
                        table.innerHTML += `
                                            <tr>
                                                <td>${exercise.name}</td>
                                                <td>
                                                    <img src="${exercise.img_url}" alt="${exercise.name}" style="width:100px;height:auto;">
                                                </td>
                                                <td>
                                                    <form method="POST" action="?action=add_exercise_set">
                                                        <input type="hidden" name="exer_id" value="${exercise.exer_id}">
                                                        <input type="hidden" name="name" value="${exercise.name}">
                                                        <input type="hidden" name="muscle_id" value="${exercise.muscle_id}">
                                                        <button type="submit">Add</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        `;
                    });
                }
            }).catch(error => { console.log(error); })
    }
</script>
</body>
</html>
