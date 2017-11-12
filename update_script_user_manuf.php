<?php
    // Acura user_manuf table integration into new user table
    require_once('functions.inc');

    // Database connection
    include('templates/db_cxn.php');

    $query = "SELECT * FROM user_manuf";
    $result = $mysqli->query($query);
    if (!$result) {
        die($mysqli->error);
    }

    // Note: this is the next increment after the last Customer record integration
    $counter = 411;
    while ($ref = $result->fetch_assoc()) {
        $user_id_orig = $ref['userID'];
        $user_name = $ref['username'];
        $user_type_id = 2; // Manufacturer type
        $user_team_id = 1; // Acura team
        $user_dealer_id = 0;
        $user_pass = $ref['password'];
        $user_fname = $ref['first_name'];
        $user_lname = $ref['last_name'];
        $user_email = $ref['email'];
        $user_active = $ref['active'];
        $user_admin = $ref['admin_user'];
        $create_date = $ref['create_date'];
        // Note: user_id 400 is the admin user. Will override previous records
        $registered_by = 400;

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
?>