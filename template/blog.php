<?php 
//set cookie lifetime for 100 days (60sec * 60mins * 24hours * 7days)
ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
session_start(); ?>

<!DOCTYPE HTML>
<HTML>
<HEAD>
	<TITLE>About | Our <?php echo $websiteName ?></TITLE>
	<link rel="icon" type="image/png" href="./assets/img/logo.png" />
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link id="stylesheet" rel="stylesheet" href="assets/style.css" type="text/css" />
	<script src="app.js"></script>
</HEAD>

<BODY>
    
	<div class="wrapper">
	<?php 	include 'assets/inc/nav.inc'; ?>
		<div class="blogWrapper">
        <h2>Blog</h2>
        <hr>


		<h3> Account system | 11-8-2019</h3>
        <p> I've made a user account system so that you and I can get to know eachother! It has an e-mail verify system and even an password forgot functionality. :D</p>
        <p> There is also a profile page on where you can only your username and a log-out button. But thats not all! I've also made a favourite list where you can see your favourite story parts! You can add a story part to your favourites by clicking on the star right on the page of the corresponding part. </p>
        <p> And for the writers out there, your username will finally be displayed when you write a part.</p>
        <p> The user account system is made using the help of the youtube channel 'Clever Techie'. The tutorial video can be found here: <a href="https://www.youtube.com/watch?v=Pz5CbLqdGwM"> link </a> </p>
<p> Other tweaks  are as follows: </P>
<li> Empty strings aren't allowed anymore for submissions in order to prevent trolls.</li>
<li> Small style tweaks in the info page.</li>
<img src="https://i.gyazo.com/7a7593759d0226a6a949fb7d7b4662b5.png" width="80%" alt="Image from Gyazo"/>
		<hr>
<h3> Circular branch tree | 16-7-2019</h3>
        <p> I've made a circular branch tree where you can navigate towards the selected parts. That way it will be easier to navigate complicated narative branches like the one down under. You can see the tree branch by clicking the 'tree branch' button in the story information page and in every story page.
</p>
<p> I've also made some other fixes and tweaks which are as follows: </P>
<li> In the story info page you can see an image icon on recently added parts which have an image icon.</li>
<li>Duplication story parts bug is fixed, you may still see it in older parts, but new ones wont have this isue.</li>
<li>script tags entries have been removed, that was one dangorous bug!</li>
<br>
		<img src="https://i.gyazo.com/a05f50bc0d88496887df5571811281f6.gif" alt="Image from Gyazo"/>
		<hr>
        <h3> Second itteration | 7-6-2019</h3>
        <p>
        The website has been updated. It is therfore the end of my induvidual study for tihs project. I'll probably work on it later in July when I have summer vacation. <br>
The most important features are as follows:<br>
1. You can edit a story that you have just written if images do not work or if there are grammatical errors.
Removal of a part is not possible to prevent trolls.<br>
2. You can see the information about the statistics of the story per story, you can also see if recent pieces have been written.<br>
3. There is an additional story beginning where you can write too!<br><br>
Enjoy!
</p>
<hr>

<h3> First prototype | 18-5-2019</h3>
        <p> First release for the first test session. 
            You can read a story part, choose and go to the option, and make one yoursdelf when you go to that option.
</p>
<hr>

</div>			
<?php 	include 'assets/inc/footer.inc'; ?>

</BODY>

</HTML>