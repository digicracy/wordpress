	</div><!-- #main -->

	<div id="footer-new">
		<!--<ul id="ohso-social">
			<li><a href="http://www.omgubuntu.co.uk/feed">RSS</a></li>
			<li><a href="http://www.twitter.com/omgubuntu">Twitter</a></li>
			<li><a href="http://www.facebook.com/omgubuntu">Facebook</a></li>
			<li><a href="http://www.youtube.com/omgubuntu">YouTube</a></li>
		</ul>-->
				<?php wp_nav_menu( array('menu' => 'Footer Menu 1' )); ?>


		<div id="ohso-copy">
			&copy; 2011 <a href="http://ohso.co">Ohso Ltd</a>. All rights reserved.<br/>
			<a href="<?php bloginfo( 'url' ) ?>/" title="<?php bloginfo( 'name' ) ?>" rel="home"><?php bloginfo( 'name' ) ?></a> is a member of the Ohso Ltd Network.<br/>
			<a href="http://www.ubuntu.com">Ubuntu</a> is a registered trademark of <a href="http://www.canonical.com">Canonical Ltd.</a>
		</div>
		<a id="ohso-logo" href="http://www.ohso.co">Ohso Ltd.</a>
	</div>
<!-- #footer -->
</div><!-- #wrapper -->	

<!-- start gaug.es -->
<script type="text/javascript">
  var _gauges = _gauges || [];
  (function() {
    var t   = document.createElement('script');
    t.type  = 'text/javascript';
    t.async = true;
    t.id    = 'gauges-tracker';
    t.setAttribute('data-site-id', '4f7ac1bef5a1f518e2000048');
    t.src = '//secure.gaug.es/track.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(t, s);
  })();
</script>
<!-- end gaug.es -->

<?php if ( is_single() ) { ?>
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script type="text/javascript">
	(function() {
	var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
	s.type = 'text/javascript';
	s.async = true;
	s.src = 'http://widgets.digg.com/buttons.js';
	s1.parentNode.insertBefore(s, s1);
	})();
</script>
<?php } ?>
<?php wp_footer(); ?>

<script type="text/javascript" charset="utf-8">
  var is_ssl = ("https:" == document.location.protocol);
  var asset_host = is_ssl ? "https://s3.amazonaws.com/getsatisfaction.com/" : "http://s3.amazonaws.com/getsatisfaction.com/";
  document.write(unescape("%3Cscript src='" + asset_host + "javascripts/feedback-v2.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript" charset="utf-8">
  var feedback_widget_options = {};

  feedback_widget_options.display = "overlay";  
  feedback_widget_options.company = "ohso";
  feedback_widget_options.placement = "right";
  feedback_widget_options.color = "#4d1f41";
  feedback_widget_options.style = "idea";
  
  var feedback_widget = new GSFN.feedback_widget(feedback_widget_options);
</script>

</body>
</html>
