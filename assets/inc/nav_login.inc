<?php require '../assets/php/global.php'?>

<div class="navigation">
	<ul>
		<a href="../user/">
			<li class="index">
                <?php 
                if (isset($_SESSION['username'])) {
                    echo $_SESSION['username'];
                } else {
                    echo 'login';
                }  ?>
            </li>
		</a>
		<a href="../blog.php">
			<li class="blog">blog</li>
		</a>
		<a href="../about.php">
			<li class="about">About</li>
		</a>
		<!--<a href="../">
			<li class="index">Stories</li>
		</a>-->
	</ul>

	<h1> <a href="../">
			<img src="../assets/img/logo.png">
			Our <?php echo $websiteName ?></a></h1>
	<h4>Interactive <?php echo $websiteName ?> created by the internet</h4>
</div>