<!DOCTYPE html>
<html>
	<head>
		<title><?=$title;?></title>
		<link rel="stylesheet" type="text/css" href="css/style.css" />

		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		<script type="text/javascript" src="script.js"></script>
		
		<link rel='index' title="OMG! Ubuntu!'s guide to Unity in 11.04" href='http://www.omgubuntu.co.uk/natty/' /> 
		<link rel="icon" type="image/x-icon" href="http://cdn.omgubuntu.co.uk/wp-content/themes/omgubuntu/favicon.ico" />
	</head>
</html>
<body>
	<?php include "../wp-content/tabs.php"; ?>
	<div id="header">
		<div id="masthead">
			<div id="branding">
				<a href="http://www.omgubuntu.co.uk/" title="You should click here to go back home" rel="home">OMG!Ubuntu!</a>
				<h1 id="blog-description">Everything Ubuntu. Daily.</h1>
			</div>

			<div id="access-wrap">
				<div class="skip-link"><a href="#content" title="Skip to content">Skip to content</a></div>
		
				<ul id="menu-primary-menu" class="menu">
					<li class="icon-app"><a href="/category/app/">Apps</a></li> 
					<li class="icon-review"><a href="/category/review/">Reviews</a></li> 
					<li class="icon-editorials"><a href="/category/editorial/">Editorials</a></li> 
					<li class="icon-news"><a href="/category/news/">News</a></li> 
					<li class="icon-interviews"><a href="/category/interview/">Interviews</a></li> 
				</ul>

				<form role="search" method="get" id="searchform" action="http://www.omgubuntu.co.uk/" > 
					<input type="text" value="" name="s" id="s" /> 
					<input type="submit" id="searchsubmit" value="Search" /> 
				</form>
			</div>

			<ul id="menu-secondary-menu" class="menu">
				<li><a title="The latest and greatest about 11.04" href="http://www.omgubuntu.co.uk/tag/natty/">Natty Updates</a></li> 
				<li><a title="Gets the latest posts via RSS" href="http://feeds.feedburner.com/d0od">RSS Feed</a></li> 
				<li><a title="Follow us @omgubuntu on Twitter" href="http://twitter.com/omgubuntu">Twitter</a></li> 
				<li><a title="Find out more about us!" href="/about/">About Us</a></li> 
				<li><a title="Know something we don&#8217;t?" href="/tip-time/" style="color: #dd4814">Submit a Tip</a></li> 
			</ul>
		</div>
	</div>
	<div id="body">
		<div id="header">
			<img src="images/header.png" />
			<h1>
				<span>Your one stop guide for</span>
				<span>Learn everything about</span>
				<span>Your guide to Unity in</span>
				<span>An introduction to</span>
			</h1>
			<h2>Ubuntu 11.04 Natty Narwhal</h2>
		</div>

		<ul id="nav">
			<?php foreach( array("what"=>"what is unity?", "launcher"=>"launcher", "dash"=>"the dash", "software"=>"software", "panel"=>"the panel") as $k=>$v ) {
				$a = ($page == $k) ? " class='active'" : '';
				print "<li $a><a href='$k'>$v</a></li>";
			} ?>
		</ul>