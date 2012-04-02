<?php $d = (isset($social_dir) ? $social_dir : "right"); ?>
<div class="<?=$d;?> social" <?=isset($social_style) ?  "style='$social_style'" : "";?>>
	<b>share:</b>
	<a class="twitter" href="http://twitter.com/?status=<?=$title;?> <?=urlencode("http://".$_SERVER['SERVER_NAME'] . str_replace("&ajax","",$_SERVER['REQUEST_URI']));?> %23ubuntu" target="_blank">twitter</a>
	<?php foreach(array("facebook","tumblr","stumbleupon","identica","digg","reddit") as $n) {
		print "<a class='$n' href='http://www.addthis.com/bookmark.php?s=$n&amp;title=".urlencode($title)."&amp;url=http://".$_SERVER['SERVER_NAME'] . str_replace("&ajax","",$_SERVER['REQUEST_URI'])."' target='_blank'>$n</a>";
	} ?>
</div>