<?php

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start();
if ( $_SESSION['logged_in'] == 1 ) {
    // Makes it easier to read
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email']; 
    $active = $_SESSION['active'];
}
$imageURL = htmlspecialchars($_POST['image']);

function get_remote_file_info($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);
    $data = curl_exec($ch);
    $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    $httpResponseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [
        'fileExists' => (int) $httpResponseCode == 200,
        'fileSize' => (int) $fileSize
    ];
}

include '../connect.php'; 

if  (get_remote_file_info($imageURL)["fileSize"] > 3000000) {
    $_SESSION['message'] = "File from url is too big";
    header("location: ../../../user/profile.php?user=" .$userID);
} else {
    $sql = "UPDATE `users` SET `profileImage`='$imageURL' WHERE id = $userID LIMIT 1";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Profile Image succesfully changed.";

    // echo "<br>New part created successfully.<br>";
    } else {
        $_SESSION['message'] = "Something went wrong when uploading it to the database";

        //echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }

    header("location: ../../../user/profile.php?user=" .$userID);


}



?>