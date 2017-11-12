<?php
    // Acura Customer table integration into new user table
    require_once('functions.inc');

    // Database connection
    include('templates/db_cxn.php');

    $query = "SELECT * FROM Customer";
    $result = $mysqli->query($query);
    if (!$result) {
        die($mysqli->error);
    }

    $counter = 400;
    while ($ref = $result->fetch_assoc()) {
        echo "user: " .$ref['userID']."<br>";

        $user_id_orig = $ref['userID'];
        $user_name = $ref['welr_username'];
        $user_type_id = 1; // SOS
        $user_team_id = 1; // 1 == Acura
        $user_dealer_id = 0; // SOS users have a 0 assignment
        $user_pass = $ref['password'];
        $user_fname = $ref['first_name'];
        $user_lname = $ref['last_name'];
        $user_email = $ref['email'];
        $user_active = $ref['active'];
        $user_admin = $ref['admin_user'];
        // Note: user_id 400 is the admin user
        $registered_by = 400;
        $create_date = $ref['create_date'];

        $query = "INSERT INTO user (user_id, user_id_orig, user_name,
            user_type_id, user_team_id, user_dealer_id, user_pass,
            user_fname, user_lname, user_email, user_active, user_admin,
            registered_by, create_date)
            VALUES ($counter, $user_id_orig, '$user_name', $user_type_id,
            $user_team_id, $user_dealer_id, '$user_pass', '$user_fname',
            '$user_lname', '$user_email', $user_active, $user_admin,
            $registered_by, '$create_date')";

        // Execute query
        if (!$res = $mysqli->query($query)) {
            die('Query error: ' . $mysqli->error);
        }

        // Show number of rows affected
        echo 'Rows affected: ' , $mysqli->affected_rows;

        // Increment counter for new user_id (note: original userID's retained in user_id_orig field)
        $counter++;
    }
    echo 'Script completed!';
?>