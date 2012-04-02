<h5>Learn more about...</h5>
<ul class="subnav" <?=isset($subnav_style) ?  "style='$subnav_style'" : "";?>>
	<?php foreach( array("what"=>"what is unity?", "launcher"=>"launcher", "dash"=>"the dash", "software"=>"software", "panel"=>"the panel") as $k=>$v ) {
		if($page == $k) continue;
		print "<li><a href='$k'>$v</a></li>";
	} ?>	
</ul>