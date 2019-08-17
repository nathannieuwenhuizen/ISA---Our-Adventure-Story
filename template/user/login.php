<?php
/* User login process, checks if user exists and password is correct */

// Escape email to protect against SQL injections
$email = $conn->escape_string($_POST['email']);
$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "User with that email doesn't exist!";
    header("location: error.php");
}
else { // User exists
    $user = $result->fetch_assoc();

    if ( password_verify($_POST['password'], $user['password']) ) {
        
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['active'] = $user['active']; 
        $_SESSION['userID'] = $user['id'];
        $_SESSION['access_token'] = $user['access_token'];
        $_SESSION['refresh_token'] = $user['refresh_token'];

        $_SESSION['message'] = "You are logged in.";

        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;

        header("location: profile.php");
    }
    else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
        header("location: error.php");
    }
}

