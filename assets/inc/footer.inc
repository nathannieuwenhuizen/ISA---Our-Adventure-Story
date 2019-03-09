</section>
	</div>

	<footer>
			<p id="viewCount">You are the  <?php 
			$views = file_get_contents("views.txt");
			$views += 1;
			file_put_contents("./views.txt", $views);
			echo $views;
?>th visitor!</p>
			<p> - Made by Nathan Nieuwenhuizen!</p>

	</footer> 