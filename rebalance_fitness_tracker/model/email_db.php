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
 * @throws Exception
 */
// Get a user using an email
function get_user_by_email($db, string $user_email): array
{
    try {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $user_email);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $user;
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}

/**
 * Validate the user using email and password by comparing the password with a hash
 * @throws Exception
 */
function is_valid_user_login($db, $user_email, $user_password): bool
{
    try {
        $query = "SELECT email, password 
                    FROM users
                    WHERE email = :email";
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $user_email);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        $hash = $row['password'];
        return $user_password === $hash;
    } catch (PDOException $e) {
        throw new Exception('Database error: ' . $e->getMessage());
    }
}
