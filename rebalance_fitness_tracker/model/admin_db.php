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
 */
// query and get the admin using the username
function get_admin_by_username($db, string $username): array
{
    try {
        $query = "SELECT * FROM administrators WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $admin = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        return $admin;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'db_error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit();
    }
}

// Validate the username, and password using a hash
function is_valid_admin_login($db, $username, $password): bool
{
    try {
        $query = "SELECT username, password 
                    FROM administrators 
                    WHERE username = :username";
        $statement = $db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        $hash = $row['password'];
        return $password === $hash;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'status' => 'db_error',
            'message' => 'Database error: ' . $e->getMessage()
        ]);
        exit();
    }
}
