<?php
/* Displays user information and some useful messages */
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);

session_start();
require '../assets/php/globalfunctions.php';
if ( $_SESSION['logged_in'] == 1 ) {
    // Makes it easier to read
    $userID = $_SESSION['userID'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active']; 
}
function CheckLogin() {
  if ( $_SESSION['logged_in'] != 1 ) {
    $_SESSION['message'] = "You must log in before viewing your profile page!";
    header("location: error.php");    
  }
}

$stringname = 'user';
$selectedUser;
if ( isset($_GET[$stringname]) || !empty($_GET[$stringname]))
{
    $selectedUser = $_GET[$stringname];
} else {
  CheckLogin();
  $selectedUser = $userID;
}

$isOwnProfile = $selectedUser == $userID;


$favOffset  = GetURLVariable('favoffset',0, -1);
$sqlFavOffset = $favOffset * 10;


$writtenOffset = GetURLVariable('writtenoffset',0, -1);
$sqlWrittenOffset = $writtenOffset * 10;


require("../assets/php/connect.php");

//get size of favourites, nothing more
$amountOfFavourites = 0;
$sql = "SELECT COUNT(userID) FROM `likes` WHERE `userID` = $selectedUser GROUP BY userID";
$result = mysqli_query($conn, $sql);

if ($result != null) {

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $amountOfFavourites += $row["COUNT(userID)"];
    }
} 
}

//get amount of written partes
$amountOfWrittenParts = 0;
$sql = "SELECT COUNT(authorID) FROM `storyparts` WHERE authorID = $selectedUser GROUP BY authorID";
$result = mysqli_query($conn, $sql);

if ($result != null) {
  if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $amountOfWrittenParts += $row["COUNT(authorID)"];
      }
  } 
}

//get user name and when the account is made.
$selectedUsername;
$dateCreated;
$profileImage;
$sql = "SELECT `username`, profileImage, date FROM users WHERE id = $selectedUser LIMIT 1";
$result = mysqli_query($conn, $sql);
if ($result != null) {
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $selectedUsername = $row["username"];
      $dateCreated = $row["date"];
      $profileImage = $row["profileImage"];
    }
  }
}



//get favourites id at the offset
$sql = "SELECT `storypartID` FROM likes WHERE userID = $selectedUser ORDER BY `Date` DESC LIMIT 10 OFFSET $sqlFavOffset";
$result = mysqli_query($conn, $sql);
$id = "";
if ($result != null) {
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      if ($row["storypartID"] != -1) {
        $id .= $row["storypartID"] . ", ";
      }

    }
  }
}

//get the info of the favourites
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

$favouriteList = PartList($result);


//written part list
$sql = "SELECT ID, option_text, Date, image, authorID FROM storyparts WHERE authorID = $selectedUser  ORDER BY Date DESC LIMIT 10 OFFSET $sqlWrittenOffset";
$result = mysqli_query($conn, $sql);
$writtenList = PartList($result);
require '../assets/php/global.php';


?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title> <?php echo $selectedUsername . " | Profile" ?></title>
  <link rel="icon" type="image/png" href="../assets/img/logo.png" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="../assets/style.css?v=<?php  echo $appVersion; ?>" type="text/css" />
	<script src="../app.js?v=<?php  echo $appVersion; ?>"></script>
  <link rel="icon" type="image/png" href="./assets/img/logo.png" />

</head>
<body>
  <div class="wrapper">
    <?php 
    	include '../assets/inc/nav_login.inc'; ?>

    <div class="profileWrapper">

    <div class = "half" style="width: auto;">
      <div class ="profileImage" style="background-image: url( 
        <?php if ($profileImage == NULL && $profileImage == "") { 
          echo '../assets/img/profileIcon.jpg'; }
          else { 
            echo "'". $profileImage ."'"; } ?>); "> 

          <?php if ($isOwnProfile) {
            echo "<div class='profileImageEdit'><p> Edit </p> </div>";
          } ?>
      </div>
    </div>


    <div class = "half">
      <h1><?php if ($isOwnProfile) {
        echo "Welcome ";
      }
      echo $selectedUsername; 
      ?> </h1>

      <p>
        <?php 
     
          // Display message about account verification link only once
          if ( isset($_SESSION['message']) && $isOwnProfile )
          {
              echo $_SESSION['message'];
              
              // Don't annoy the user with more messages upon page refresh
              unset( $_SESSION['message'] );
          }
          
          ?>
      </p>

      <?php
          // Keep reminding the user this account is not active, until they activate
          if ( !$active && $isOwnProfile ){
              echo
              '<div class="info">
              Account is unverified, please confirm your email by clicking
              on the email link! <br>
              </div>              To resend the mail, click <a href="./newRegisterMail.php">here</a>
              ';
          }
          ?>

      <ul>Created at: <?php echo $dateCreated; ?></ul>
      <ul>Wrote <?php echo $amountOfWrittenParts; ?> parts </ul>
      <ul>Has <?php echo $amountOfFavourites; ?> Favourites </ul>
        </div>
        <div style="clear: both;"></div> 

        <form class="ProfileImageForm hide" action="../assets/php/user/profileImageUpdate.php" method="post"> 
        <p>Image url (max 5mb in size) <input type="text" name="image" placeholder="www.... jpg, png, gif" required /></p>
        <p><input type="submit" name="submit" class="createButton" value="Upload"/></p>
        </form>

      <div class=" added-parts half">

      <h3>Written parts</h3>
      <hr>
      <?php NaviagationButtons($writtenOffset, $amountOfWrittenParts, "?user=".$selectedUser."&favoffset=". $favOffset ."&writtenoffset=");?>
      <ul class="storyList">
        <?php echo  $writtenList;?> 
      </ul>
      </div>

      <div class=" added-parts half">

      <h3>Favourites </h3>
      <hr>
      <?php NaviagationButtons($favOffset, $amountOfFavourites, "?user=".$selectedUser."&writtenoffset=". $writtenOffset ."&favoffset=");?>
      <ul class="storyList">
        <?php echo  $favouriteList;?> 
      </ul>
      </div>
      <div style="clear: both;"></div> 
      <hr>
      <h3>Made story </h3>
      <?php 
			require '../assets/php/patreon/src/API.php';
			require '../assets/php/patreon/src/OAuth.php';
      include '../assets/php/patreon/patreonCalls.php';
      
          
      $myStories = getStoryList($conn, true, "../");
      // echo "is pledger: " .IsPledger(100);
			if (IsPLedger(100)) { 
				if ($myStories != "") {
					echo "<ul class='myList'> " . $myStories. "</ul>";
				} else if ($isOwnProfile) {

					echo '<a class="newStoryButton" href="../newStory.php"> Start a new Story</a>';
				} else {
          echo "<p>This user has no stories yet made. </p>";
        }
			} else if ($isOwnProfile) {
				CreateUnlockButton("../");
      } else {
        echo "<p>This user has made no stories. </p>";
      }			
			 ?>
<br>
<hr>
      <br>
      <?php if ($isOwnProfile) {
        echo   '<a href="logout.php"><button class="button button-block" name="logout" />Log Out</button></a>';
      } ?>

  </div>
  <?php 	include '../assets/inc/footer.inc'; ?>

</body>

</html>
