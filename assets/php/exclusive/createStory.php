<?php
//MySQL Database Connect 
include '../connect.php'; 
include '../globalfunctions.php'; 

include '../patreon/patreonCalls.php'; 
include '../patreon/src/API.php'; 
include '../patreon/src/OAuth.php'; 

ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);

session_start();
/*
Creates new story
*/
$storyTitle = htmlspecialchars( removeAllTags( $_POST['storyTitle']), ENT_QUOTES);
$storyDescription = htmlspecialchars(removeScriptTags( $_POST['storyDescription']), ENT_QUOTES);

/*
Creates the new story part
*/
$consequence = htmlspecialchars(removeScriptTags( $_POST['consequence']), ENT_QUOTES);
$question = htmlspecialchars(removeScriptTags($_POST['question']),ENT_QUOTES);
$image = htmlspecialchars($_POST['image']);
$layer = 1;
$message = 'failed to create a story';

//check for duplicate tokens
if (!DuplicateTokens($conn)) {
    //check if the user already made story before.
    if (getStoryList($conn, true) == "") {
        if (IsPLedger(100)) {
            echo "I'm pledged";
            if (!strictEmpty($consequence) && !strictEmpty($storyDescription) && !strictEmpty($storyTitle) && !strictEmpty($question)) {
                $storyID = CreateStory($storyTitle, $storyDescription, $conn);
                if ($storyID != -1) {
                    CreateBeginPath($consequence, $question, $image, $storyID, $conn);
                } else {
                    $message = "Failed to create a story";
                }
            } else {
                $message = "Some of your submissions are empty";
            }
        } else {
            echo "I'm not pledged";

            $message = "You aren't pledged or your pledge amount isn't high enough";
        }
    } else {
        $message = "You have exceeded the maximum of stories for your pledge level";
    }
} else {
    $message = "There is another account that has your patreon information";
}

$_SESSION['message'] = $message;
header("location: ../../../user/error.php");
 

function CreateStory($storyTitle, $storyDescription, $conn) {
    //check if a story already is submitted with the same values
    $sql = "SELECT * FROM storyInfo WHERE (`Name` = $storyTitle AND `Description` = '$storyDescription')  LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // echo "<br>A part already exist.<br>";
    } else {
        //creates the new story
        $sql = "INSERT INTO `storyinfo` (`ID`, `Name`, `Description`, `Date`, `AuthorID`) VALUES (NULL, '$storyTitle', '$storyDescription', NOW(), ". $_SESSION['userID']. ");";
        if ($conn->query($sql) === TRUE) {
            return $conn->insert_id;
        }
    }
    return -1;

}
function SetStartID($id, $storyID, $conn) {
    $sql = "UPDATE `storyInfo` SET `Introduction_ID` = $id WHERE `ID` = $storyID LIMIT 1"; 
    if ($conn->query($sql) === TRUE) {
        echo "<br>New part created successfully.<br>";
        // header("location: ../../../storyinfo.php?ID=". $storyID ."&offset=0");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
    }
}

function CreateBeginPath($consequence, $question, $image, $storyID, $conn) {
    //this prevents duplicate entries
    $sql = "SELECT * FROM storyparts WHERE (content_text = '$consequence' AND question_text = '$question' AND storyID = $storyID)  LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // echo "<br>A part already exist.<br>";
    } else {
        
        $author = -1;
        if (isset($_SESSION['userID'])) {
            $author = $_SESSION['userID'];
        }
        $sql = "INSERT INTO `storyparts` (`ID`, `start`, `end`, `option_text`, `content_text`, `question_text`, `option_IDs`, `Date`, `layer`, `image`, `parentID`, `storyID`, `authorID`)" .
    " VALUES (NULL, '1', '0', '', '$consequence', '$question', '', NOW(), 1, '$image', 0, $storyID, $author);";

        $last_id = -1;


        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            SetStartID($last_id, $storyID, $conn);
            // echo "<br>New part created successfully.<br>";
        } else {
            // echo "Error: " . $sql . "<br>" . $conn->error . "<br>";
        }

        //backup
        //echo "<br><a href='../../?storypart=". $last_id."'>go back to page</a>";
        /*
        Redirect ot the new page
        */
        die();
    }
}

// use Patreon\API; 
// use Patreon\OAuth;

// function IsPLedger($amount) {

//     require '../patreon/src/API.php';
//     require '../patreon/src/Oauth.php';

//     $access_token;
//     if (isset($_SESSION['access_token']));{
//         $access_token = $_SESSION['access_token'];
//     }    

//     if (isset($access_token)) {

//         $api_client = new API($access_token);
    
//         // Return from the API can be received in either array, object or JSON formats by setting the return format. It defaults to array if not specifically set. Specifically setting return format is not necessary. 
//         // Below is shown as an example of having the return parsed as an object. If there is anyone using Art4 JSON parser lib or any other parser, they can just set the API return to JSON and then have the return 
//         // parsed by that parser
    
//         // You dont need the below line if you simply want the result as an array
//         $api_client->api_return_format = 'object';
    
//         // Now get the current user:
//         $patron_response = $api_client->fetch_user();
    
//         $myAmount = $patron_response->included[0]->attributes->currently_entitled_amount_cents;
//         // echo '<br><br>';
//         // print_r ($amount);
//         if ($myAmount >= $amount) {
//             return true;
//         } 
//         // $data = json_encode((array)$patron_response);
//         // print_r($data);
    
//     }
//     return false;
// }

function DuplicateTokens($conn) {

    $myEmail = getEmailFromToken($_SESSION['access_token']);
    
    $sql = "SELECT `ID`, `access_token` FROM `users` WHERE NOT `access_token` = ''";
    $result = mysqli_query($conn, $sql); 
    if ($result && mysqli_num_rows($result) > 0) { 
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $email = getEmailFromToken($row['access_token']);

            if ($email != "") {
                echo "<br> refreshed token: ". $row['access_token'] ." and email: ".$email."<br>";
                echo "<br> refreshed token: ". $_SESSION['access_token']." and email: ".$myEmail."<br>";
                echo "is pledher". IsPLedger(100);
                // $data = json_encode((array)$tokens);
                // print_r($data);
                if ($myEmail == $email) {
                    return true;
                }
                }
        }
    } else {
        echo $conn->error;
    }
    return false;
}



?>