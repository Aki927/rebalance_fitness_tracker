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
 * @var string $user_email
 * @var string $user_password
 */
session_start();

// Redirect to the select exercise page if the user is already logged in
if (isset($_SESSION['is_valid_user'])) {
    header("Location: ../main_menu/user_home_page.php");
    exit();
}
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
    <h1>User Login</h1>
    <p>You must login before you can log your workout.</p>
    <form id="user_login_form" autocomplete="off">
        <!-- Using a valid email/password, the form's data will be sent via POST request to the select exercise page -->
        <div class="form-row">
            <label for="e_mail">Email:</label>
            <input id="e_mail" type="text" name="e_mail"
                   value="<?php echo htmlspecialchars($user_email); ?>">
        </div>
        <div class="form-row">
            <label for="password">Password:</label>
            <input id="password" type="password" name="password"
                   value="<?php echo htmlspecialchars($user_password); ?>">
        </div>
        <div class="form-row">
            <label for="login-button">&nbsp;</label>
            <input id="login-button" type="button" value="Login">
        </div>
        <div class="form-row">
            <p id="login-error-p"></p>
        </div>
    </form>
    <!-- Login status -->
    <h2>Login Status</h2>
    <p>Logged in as <?php echo htmlspecialchars($_SESSION['is_valid_user']['full_name']); ?></p>
    <p>Current workout session: <?php echo htmlspecialchars($_SESSION['workout_session']['workout_id']); ?></p>
</main>
<!-- Header view -->
<?php include('../view/footer.php'); ?>

<script type="text/javascript">
    // Wait for HTML to fully load all elements
    document.addEventListener('DOMContentLoaded', () => {
        // Listener for the search button
        document.querySelector('#login-button').addEventListener('click', function (e) {
            e.preventDefault();
            // Get the email from the form element then process the login.
            // Show an error message upon invalid login
            const email = document.getElementById('e_mail').value.trim();
            const password = document.getElementById('password').value.trim();
            if (email && password) {
                processLogin(email, password);
            } else if (!email) {
                document.getElementById('login-error-p').textContent = 'Email is required';
            } else if (!password) {
                document.getElementById('login-error-p').textContent = 'Password is required';
            }
        });
    });

    // AJAX technique to process the login or show an error without reloading the page
    function processLogin(email, password) {
        // URL to be sent via POST request to the server
        const url = `../email_verification/verify_user.php`;

        // Get the p element to show errors later
        const errorMessage = document.getElementById('login-error-p');

        // Reset the errors if there are any.
        errorMessage.textContent = '';

        // Create a headers object
        const myHeader = new Headers();
        myHeader.append('Content-Type', 'application/json');
        myHeader.append('Accept', 'application/json');

        // Use a GET request to fetch email from the database and POST the form to the register product page.
        // Otherwise, show an error.
        fetch(url, {                                                // Use Fetch API as alt. to XMLHttpRequest
            method: 'POST',
            headers: myHeader,
            body: JSON.stringify({email, password}),
            credentials: 'include'
        })
            .then(response => response.json())                      // Set response to JSON
            .then(result => {
                if (result.status === 'db_error') {
                    // Redirect to the HTML error page and pass the message as a query param
                    window.location.href = `../error/database_error.php?message=${encodeURIComponent(result.message)}`;
                } else if (result.status === 'success') {
                    // Handle result from server
                    if (result.user) {
                        // Create a hidden form to append in original HTML and post to main menu page
                        const form = document.createElement('form');
                        form.action = '../main_menu/user_home_page.php';
                        form.method = 'POST';
                        // Loop through the returned JSON user data
                        for (const key in result.user) {
                            console.log(key);
                            if (result.user.hasOwnProperty(key)) {
                                const input_element = document.createElement('input');
                                input_element.type = 'hidden';
                                input_element.name = key
                                input_element.value = result.user[key];
                                form.appendChild(input_element);
                            }
                        }
                        document.body.appendChild(form);
                        form.submit();
                    } else {
                        // Show a validation error with invalid emails
                        document.getElementById('login-error-p').textContent = result.message;
                    }
                } else if (result.status === 'unauthorized') {
                    // Unauthorized message upon invalid login
                    document.getElementById('login-error-p').textContent = result.message;
                } else if (result.status === 'input error') {
                    // User input error message
                    errorMessage.textContent = result.message;
                }
            }).catch(error => { errorMessage.textContent = error; });
    }
</script>
</body>
</html>