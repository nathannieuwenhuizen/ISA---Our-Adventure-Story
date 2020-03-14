<?php
/* Displays user information and some useful messages */
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);

session_start();
require '../assets/php/globalfunctions.php';
// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  header("location: error.php");    
}
else {
    // Makes it easier to read
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
}


$stringname = 'offset';
$offset = 0;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $offset = $_GET[$stringname];
}
if ($offset < 0) {
    $offset = 0;
}
$sqlOffset = $offset * 10;

require("../assets/php/connect.php");
//get favourites id
$sql = "SELECT `storypartID` FROM likes WHERE userID = $userID ORDER BY `Date` DESC LIMIT 10 OFFSET $sqlOffset";
$result = mysqli_query($conn, $sql);
$id = "";
if ($result != null) {
  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      if ($row["storypartID"] != -1) {
        $id .= $row["storypartID"] . ", ";
      }

    }
  }
}


$amountOfParts = 0;
//layer count info
$sql = "SELECT COUNT(userID) FROM `likes` WHERE `userID` = $userID GROUP BY userID";
$result = mysqli_query($conn, $sql);

if ($result != null) {

if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        $amountOfParts += $row["COUNT(userID)"];
    }

} 
}

$favArray = explode(",", $id);

$sql = "SELECT `ID`, `option_text`, `Date`, `image` FROM `storyparts` WHERE ID IN ( ";
for ($i = 0; $i < sizeof($favArray); $i++) {
    $sql .= "'". (int)$favArray[$i] . "'";
    if ($i != sizeof($favArray) - 1) {
        $sql .= ", ";
    }
} 
$sql .= ")";
$result = mysqli_query($conn, $sql);

$addedPartsList = "";

//if there is any result
if (mysqli_num_rows($result) > 0) {
    // output data of each row
    $now = new DateTime();

    while($row = mysqli_fetch_assoc($result)) {
        $addeddate = GetIntervalRounded($row["Date"]);
        //echo "id: " . $row["ID"]. "<br>";
        $option =  $row["option_text"];
        if ($option == "") {
            $option = "-start of the story-";
        }
        $image = "";
        if ($row["image"] != "") {
            $image .= "<section></section>";
        }
        $addedPartsList .= "<a href='../story.php?storypart=" . $row["ID"] . "'><li>  <b>". $option ." </b> <p>Added " .  $addeddate . " ago </p> ". $image." </li> </a>";

    }

} else {
    //there are no results
    $addedPartsList = "0 results";
}


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Welcome <?= $_SESSION['username'] ?></title>
  <link rel="icon" type="image/png" href="../assets/img/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="../assets/style.css?v=2" type="text/css" />
	<script src="../app.js?v=2"></script>
  <link rel="icon" type="image/png" href="./assets/img/logo.png" />

</head>

<body>
  <div class="wrapper">
    <?php 
    	include '../assets/inc/nav_login.inc'; ?>

    <div class="form">

      <h1>Welcome <?php echo $_SESSION['username']; ?> </h1>

      <p>
        <?php 
     
          // Display message about account verification link only once
          if ( isset($_SESSION['message']) )
          {
              echo $_SESSION['message'];
              
              // Don't annoy the user with more messages upon page refresh
              unset( $_SESSION['message'] );
          }
          
          ?>
      </p>

      <?php
          // Keep reminding the user this account is not active, until they activate
          if ( !$active ){
              echo
              '<div class="info">
              Account is unverified, please confirm your email by clicking
              on the email link! <br>
              </div>              To resend the mail, click <a href="./newRegisterMail.php">here</a>
              ';
          }
          ?>
      <div class=" added-parts">

        <h3> Your favourites! </h3>
        <hr>

        <div class="navigationButtons">

          <a class=<?php 
    if ($offset <= 0) {
        echo "hide";
    } else {
        echo "something";
    }
?> href="?offset=0">
            << </a> <a class=<?php 
if ($offset <= 0) {
    echo "hide";
} else {
    echo "something";
}?> href="?offset=<?php echo $offset - 1; ?>">
              < </a> <b> <?php echo $offset + 1  ?> / <?php echo Round($amountOfParts / 10); ?> </b>

                <a class=<?php 
if ($offset + 1 >= Round( $amountOfParts / 10)) {
    echo "hide";
} else {
    echo "something";
}?> href="?offset=<?php echo $offset + 1; ?>"> ></a>
                <a class=<?php 
if ($offset + 1 >= Round( $amountOfParts / 10)) {
    echo "hide";
} else {
    echo "something";
}?> href="?offset=<?php echo Round($amountOfParts / 10) - 1; ?>"> >></a>
        </div>
        <ul class="storyList">
          <?php echo  $addedPartsList;?>
        </ul>
      </div>

      <hr>
      <h3> My story </h3>
      <?php 
			require '../assets/php/patreon/src/API.php';
			require '../assets/php/patreon/src/OAuth.php';
		
      include '../assets/php/patreon/patreonCalls.php';
      
          
      $myStories = getStoryList($conn, true, "../");
      // echo "is pledger: " .IsPledger(100);
			if (IsPLedger(100)) { 
				if ($myStories != "") {
					echo "<ul class='myList'> " . $myStories. "</ul>";
				} else {
					echo '<a class="newStoryButton" href="../newStory.php"> Start a new Story</a>';
				}
			} else {
				CreateUnlockButton("../");
      }			
			 ?>
<br>
<hr>

      <br>
  <a href="logout.php"><button class="button button-block" name="logout" />Log Out</button></a>

  </div>
  <?php 	include '../assets/inc/footer.inc'; ?>

</body>

</html>
