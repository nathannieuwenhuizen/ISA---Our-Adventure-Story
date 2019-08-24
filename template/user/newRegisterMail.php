<?php 
/* Reset your password form, sends reset.php password link */
require 'db.php';
require '../assets/php/global.php';
session_start();

$email = $_SESSION['email'];
$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if ( $result->num_rows == 0 ) // User doesn't exist
{ 
    $_SESSION['message'] = "User with that email doesn't exist!";
    header("location: error.php");
}
else { // User exists (num_rows != 0)

    $user = $result->fetch_assoc(); // $user becomes array with user data
    
    $email = $user['email'];
    $hash = $user['hash'];
    $username = $user['username'];

    // Session message to display on success.php
    $_SESSION['message'] = "<p>Please check your email <span>$email</span>"
    . " for a confirmation link to complete your password reset!</p>";

    // Send registration confirmation link (reset.php)
    $to      = $email;
    $subject = 'Account Verification | ' . $websiteName;
    $message_body = '
    Hello '.$username.', 

    Thank you for signing up!

    Please click this link to activate your account:

    '.$websiteURL.'user/verify.php?email='.$email.'&hash='.$hash;  

    mail( $to, $subject, $message_body );

    header("location: profile.php");
}
