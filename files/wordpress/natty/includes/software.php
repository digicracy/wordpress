<div class="title">Your guide to software in Ubuntu 11.04</div>

<div style="margin-top: 30px">
	<div class="left">
		<h3>The Software Center is improved in 11.04</h3>
		<h4>Ubuntu already comes with a wide range of quality software pre-installed, but now the Software Center has great new features in Ubuntu 11.04 to make finding software even easier than before.</h4>

		<p>The Ubuntu Software Center in 11.04 boasts the following improvements over Ubuntu 10.10:</p>
		<p style="margin-left: 30px; line-height: 25px">
			New ratings and reviews system<br/>
			Recommended apps based on usage trends<br/>	
			Much faster startup time<br/>	
			All of your favourite open source apps<br/>	
			More apps and games available for purchase<br/>
		</p>
	</div>
	<img class="right" src="images/sc-right.jpg" />
	<?php include "_social.php"; ?>
</div>
<hr />
<div class="icons">
	<?php foreach(array("firefox", "chrome", "flash", "transmission", "evolution", "banshee") as $icon)
				print "<img src='images/app-icons/$icon.png' alt='".ucwords($icon)."' />";
	?>
</div>

<div>
	<div class="left">
		<h6>New ratings and reviews system</h6>
		<p>Applications in the Software Center now have ratings out of 5 stars, and you can read user reviews before you install the application.</p>
		<p>You're also able to rate apps yourself and write reviews using your Ubuntu Single Sign On account.</p>
		<p>Just click the button to review an application when you're viewing it in the Software Center.</p>
	</div>
	<img class="right shadow" src="images/sc-ratings.jpg" />
</div>
<div>
	<div class="right">
		<h6>Recommendations based on usage</h6>
		<p>Ubuntu is smart enough to recommend you applications based on what you use the most already.</p>
		<p>To see your recommendations, just fire up the Software Center and at the top of the front page you should see a sentence linking to your personal recommendations.</p>

		<h6>Faster startup time</h6>
		<p>The Software Center starts up much faster in Ubuntu 11.04 and also handles manual installation of downloaded .deb packages easily.</p>
	</div>
	<img class="left shadow" src="images/sc-recommendations.jpg" style="margin-top: 60px"/>
</div>

<?php video('s1oztl5Evfg'); ?>
<div>
	<?php $social_dir = "left"; $social_style = "margin-top: 55px;"; include "_social.php"; ?>
	<div class="right" style=" max-width: 55%">
		<?php include "_subnav.php"; ?>
	</div>
</div>