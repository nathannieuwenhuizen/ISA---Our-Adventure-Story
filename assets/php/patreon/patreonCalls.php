<?php

use Patreon\API; 
use Patreon\OAuth;

// Set the redirect url where the user will land after the flow. That url is where the access code will be sent as a _GET parameter. 
// This may be any url in your app that you can accept and process the access code and login

// In this case, say, /patreon_login request uri. This doesnt need to be your final redirect uri. You can send your final redirect uri in your state vars to Patreon, receive it back, 
// and then send your user to that final redirect uri
function CreateUnlockButton($basePath = "./") {

    if (isset($_SESSION['userID'])) {
        $client_id = '-28JiJ67Xl_o-Am0bkLZnrN0NHxsh0WvBf5sDK9aXbecN46KNt5VMR_bFSJoVIdN';      // Replace with your data
        $client_secret = 'RzhFVDmt0dlbxSRuLhg2FKmGofsYwaydmpC0KtGwLeZ4h3fzKsQRSrVV_4-WDXIM';  // Replace with your data
        
        $redirect_uri = "http://ourinteractivetgcaption.hostingerapp.com/user/success.php";

    
        // Min cents is the amount in cents that you locked your content or feature with. Say, if a feature or content requires $5 to access in your site/app, 
        // then you send 500 as min cents variable. Patreon will ask the user to pledge $5 or more.
        $min_cents = '100';
    
        // Scopes! You must request the scopes you need to have the access token.
        // In this case, we are requesting the user's identity (basic user info), user's email
        // For example, if you do not request email scope while logging the user in, later you wont be able to get user's email via /identity endpoint when fetching the user details
        // You can only have access to data identified with the scopes you asked. Read more at https://docs.patreon.com/#scopes
    
        // Lets request identity of the user, and email.
        $scope_parameters = '&scope=identity%20identity'.urlencode('[email]');
    
        // Generate the unified flow url - this is different from oAuth login url. oAuth login url just processes oAuth login. 
        // Unified flow will do everything.
        $href = 'http://www.patreon.com/oauth2/become-patron?response_type=code&min_cents=' . $min_cents . '&client_id=' . $client_id . $scope_parameters . '&redirect_uri=' . urlencode($redirect_uri);
    
        // You can send an array of vars to Patreon and receive them back as they are. Ie, state vars to set the user state, app state or any other info which should be sent back and forth. 
        $state = array();
    
        $state['final_redirect'] = 'http://ourinteractivetgcaption.hostingerapp.com/'; //locked content
    
        // Or, http://mydomain.com/premium-feature. Or any url at which a locked feature or content will be unlocked after the user is verified to become a qualifying member 
    
        // Add any number of vars you need to this array by $state['key'] = variable value
    
        // Prepare state var. It must be json_encoded, base64_encoded and url encoded to be safe in regard to any odd chars. When you receive it back, decode it in reverse of the below order - urldecode, 
        // base64_decode, json_decode (as array)
        $state_parameters = '&state=' . urlencode( base64_encode( json_encode( $state ) ) );
    
        // Append it to the url 
        $href .= $state_parameters;
    
        // Now place the url into a flow link. Below is a very simple login link with just text. in assets/images folder, there is a button image made with official Patreon assets (unlock_with_patreon.png). 
        // You can also use this image as the inner html of the <a> tag instead of the text provided here
    
        // Simply echoing it here. You can present the login link/button in any other way.
    
        echo '<a href="'.$href.'"><img class="patreonButton" src="'. $basePath.'assets/php/patreon/assets/images/unlock_with_patreon.png"></a>';
    } else {
        $href = "user/";
        echo '<a href="'.$href.'"><img class="patreonButton" src="'.$basePath.'assets/php/patreon/assets/images/unlock_with_patreon.png"></a>';
    }
   
}

// The below code snippet needs to be active wherever the the user is landing in $redirect_uri parameter above. It will grab the auth code from Patreon and get the tokens via the oAuth client
function CheckGetVariable() {
    $redirect_uri = "http://ourinteractivetgcaption.hostingerapp.com/user/success.php";

    $client_id = '-28JiJ67Xl_o-Am0bkLZnrN0NHxsh0WvBf5sDK9aXbecN46KNt5VMR_bFSJoVIdN';      // Replace with your data
    $client_secret = 'RzhFVDmt0dlbxSRuLhg2FKmGofsYwaydmpC0KtGwLeZ4h3fzKsQRSrVV_4-WDXIM';  // Replace with your data

    if (isset( $_GET['code'])) {
        if ( $_GET['code'] != '' ) {
            
            // From this part on, its no different from oAuth login example. Just do whatever you need.
            
            $oauth_client = new OAuth($client_id, $client_secret);	 
                
            $tokens = $oauth_client->get_tokens($_GET['code'], $redirect_uri);
    
            if (isset($tokens['access_token']) && isset($tokens['refresh_token'])) {
                $access_token = $tokens['access_token'];
                $refresh_token = $tokens['refresh_token'];

                updateTokens($access_token, $refresh_token);    
                // Here, you should save the access and refresh tokens for this user somewhere. Conceptually this is the point either you link an existing user of your app with his/her Patreon account, or, 
                // if the user is a new user, create an account for him or her in your app, log him/her in, and then link this new account with the Patreon account. More or less a social login logic applies here. 
                
                // Here you can decode the state var returned from Patreon, and use the final redirect url to redirect your user to the relevant unlocked content or feature in your site/app.
            } 
        }
    }    
}

function updateTokens ($access_token, $refresh_token) {
    require '../assets/php/connect.php';

    $redirect_uri = "http://ourinteractivetgcaption.hostingerapp.com/user/success.php";
    
    $client_id = '-28JiJ67Xl_o-Am0bkLZnrN0NHxsh0WvBf5sDK9aXbecN46KNt5VMR_bFSJoVIdN';      // Replace with your data
    $client_secret = 'RzhFVDmt0dlbxSRuLhg2FKmGofsYwaydmpC0KtGwLeZ4h3fzKsQRSrVV_4-WDXIM';  // Replace with your data
    
    if (isset($_SESSION['userID']) && isset($access_token) && isset($refresh_token)) {

        // $DuplicateTokens = false;

        // $sql = "SELECT `ID`, `access_token` FROM `users` WHERE NOT `access_token` = '' OR NOT `refresh_token` = ''";

        // $result = mysqli_query($conn, $sql);
        // if ($result && mysqli_num_rows($result) > 0) { 
        //     // output data of each row
        //     while($row = mysqli_fetch_assoc($result)) {
        //         $email = getEmailFromToken($row['access_token']);
        //         // echo "<br> refreshed token: ". $row['access_token'] ." and email: ".$email."<br>";

        //         // $data = json_encode((array)$tokens);
        //         // print_r($data);
    
        //         if ($_SESSION['userID'] != $row['ID']) {
        //             $DuplicateTokens = true;
        //         }
        //     }
        // } else {
        //     echo $conn->error;
        // }
        if (true) {
            $sql = "UPDATE `users` SET `access_token`='$access_token', `refresh_token` = '$refresh_token' WHERE id = " . $_SESSION['userID'] .";";
            if ($conn->query($sql) === TRUE) {
                // echo "<br>User update created successfully.<br>";
                $_SESSION['access_token'] = $access_token;
                $_SESSION['refresh_token'] = $refresh_token;    
                $_SESSION['message'] = "your account is now connected with patreon";
                header("location: ./success.php");

            } else {
                // echo "<br><br>Error: " . $sql . "<br>" . $conn->error . "<br>";
            }
        } else {
            // echo "Duplicate tokens";
        }
    }
}

function StoryIsOpen($conn, $storyID) {
    //checks if the creator is pledged at all.
    $sql = "SELECT `AuthorID` FROM `storyinfo` WHERE  ID = $storyID";
    $result = mysqli_query($conn, $sql);
    $creatorID = "";
    if ($result && mysqli_num_rows($result) > 0) { 
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $creatorID = $row['AuthorID'];
        }
    }
    //if there is a creator id
    if ($creatorID > 0) {
        //get access token
        $sql = "SELECT `access_token` FROM `users` WHERE  ID = $creatorID";
        $result = mysqli_query($conn, $sql);
        $creatorToken;
        if (mysqli_num_rows($result) > 0) { 
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $creatorToken = $row['access_token'];
            }
        }
    
        if (isset($creatorToken)) {
            if (IsPledger(100, $creatorToken)) {
                // echo "creator is pledged!";
                return true;
            } else {
                // echo "creator isnt pledged, story is closed...";
            }
        }
    } else {

        return true; // nobody is a creator, so it is an community one.
    }
    return false;
}

function IsPLedger($amount, $token = "") {

    //return true;
    $access_token = $token;
    if ($access_token == "") {
        if (isset($_SESSION['access_token'])){
            $access_token = $_SESSION['access_token'];
        }    
    }
    // echo "<br>mail from ispledger function: " . getEmailFromToken($access_token);


    if (isset($access_token) && $access_token != "") {

        // echo $access_token;
        $api_client = new API($access_token); 

        // Return from the API can be received in either array, object or JSON formats by setting the return format. It defaults to array if not specifically set. Specifically setting return format is not necessary. 
        // Below is shown as an example of having the return parsed as an object. If there is anyone using Art4 JSON parser lib or any other parser, they can just set the API return to JSON and then have the return 
        // parsed by that parser

        // You dont need the below line if you simply want the result as an array
        $api_client->api_return_format = 'object';

        // Now get the current user:
        $patron_response = $api_client->fetch_user();
        if (isset($patron_response->included[0]->attributes->currently_entitled_amount_cents)) {
            $myAmount = $patron_response->included[0]->attributes->currently_entitled_amount_cents;
            // print_r ($amount);
            $data = json_encode((array)$patron_response->data->attributes->email);
            // echo $access_token . $data;

            if ($myAmount >= $amount) {
                return true;
            } 

        }    
     
    }
    return false;
}

// function duplicatePatreonEmails
function getEmailFromToken($access_token) {
    // echo $access_token;

    $api_client = new API($access_token);
    $api_client->api_return_format = 'object';

    // echo $api_client;
    $patron_response = $api_client->fetch_user();
    // echo $patron_response;
    if (isset($patron_response->data->attributes->email)) {
        $data = json_encode((array)$patron_response->data->attributes->email);
        // echo $access_token . $data;
        return $data;
    }    
    return "";
}
