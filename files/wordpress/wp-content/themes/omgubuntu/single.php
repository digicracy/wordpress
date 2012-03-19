<?php get_header(); ?>
		<div id="container">	
			<div id="content">
<?php the_post(); ?>
<?php
	if ( $prevPost  = get_previous_post() ) {
		$prevURL = get_permalink($prevPost->ID);
		$navlinks = "\t\t\t\t\t<a class=\"nav-previous\" title=\"{$prevPost->post_title}\" href=\"$prevURL\">Previous</a>\n";
	}
	if ( $prevPost  = get_previous_post() && $nextPost  = get_next_post() ) $navlinks .= "\t\t\t\t\t<span class=\"nav-sep\">/</span>\n";
	if ( $nextPost ) {
		$nextURL = get_permalink($nextPost->ID);
		$navlinks .= "\t\t\t\t\t<a class=\"nav-next\" title=\"{$nextPost->post_title}\" href=\"$nextURL\">Next</a>\n";
	}
?>
<!-- #nav-above -->

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-inner">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					
					<div class="entry-meta">
						<span class="meta-prep meta-prep-author"><?php _e('By ', 'omgubuntu'); ?></span>
						<span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s', 'omgubuntu' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span><span class="meta-sep">, </span>
						<span class="meta-prep meta-prep-entry-date"><?php _e('Published ', 'omgubuntu'); ?></span>
						<span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time( get_option( 'date_format' ) ); ?></abbr></span>
						<?php edit_post_link( __( 'Edit', 'omgubuntu' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>						
					</div><!-- .entry-meta -->

					<div class="entry-share">
						<div class="share-text"><span>Share:</span></div>
						
						<div class="share-googleplus"><!-- Place this tag where you want the +1 button to render -->
<g:plusone size="medium"></g:plusone>

<!-- Place this tag after the last plusone tag -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></g:plusone></div>

						<div class="share-book"><iframe src="http://www.facebook.com/widgets/like.php?href=<?php the_permalink(); ?>&amp;layout=button_count"
        						scrolling="no" frameborder="0"
        						style="border:none; width:100px; height:20px"></iframe></div>

<div class="share-tweet"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="omgubuntu">Tweet</a></div>

<div id="share-flattr" style="padding-top:1px;"><?php the_flattr_permalink() ?></div>

				</div>
					<div class="entry-content">
<?php 
// convert characters using output buffering.. not ideal, I know.
function c($c) { return str_replace(array('â€˜','â€™','Ã©','Â','â'),array("'","'","&eacute;","",""),$c); }
ob_start('c');

the_content();

ob_end_flush(); ?>
<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'omgubuntu' ) . '&after=</div>') ?>
					</div><!-- .entry-content -->
					
					<div class="entry-utility">
					<div class="tag-links"><?php the_tags('<ul><li>','</li><li>','</li></ul>'); ?></div>
						<div class="social-stuff">

<?php foreach( array("email", "facebook", "stumbleupon", "reddit", "twitter") as $n ) {
	print "<div class='social-stuff-$n'><a target=\"_blank\" href=\"http://www.addthis.com/bookmark.php?s=$n&amp;url=";
	the_permalink();
	print '&amp;title=';
	the_title();
	print "\">$n</a></div>";
}
?>		

						</div>
<?php if ( ('open' == $post->comment_status) && ('open' == $post->ping_status) ) : // Comments and trackbacks open ?>
						<?php printf( __( '<a class="comment-link" href="#dsq-new-post" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'omgubuntu' ), get_trackback_url() ) ?>
<?php elseif ( !('open' == $post->comment_status) && ('open' == $post->ping_status) ) : // Only trackbacks open ?>
						<?php printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'omgubuntu' ), get_trackback_url() ) ?>
<?php elseif ( ('open' == $post->comment_status) && !('open' == $post->ping_status) ) : // Only comments open ?>
						<?php _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'omgubuntu' ) ?>
<?php elseif ( !('open' == $post->comment_status) && !('open' == $post->ping_status) ) : // Comments and trackbacks closed ?>
						<?php _e( 'Both comments and trackbacks are currently closed.', 'omgubuntu' ) ?>
<?php endif; ?>
<?php edit_post_link( __( 'Edit', 'omgubuntu' ), "\n\t\t\t\t\t<span class=\"edit-link\">", "</span>" ) ?>
					</div><!-- .entry-utility -->
					
					<div id="comments"><?php comments_template('', true); ?></div>
				</div>
					<div class="left-block">
<!-- AUTHOR GRAVATAR-->
<a href='/author/<?=$authordata->user_nicename;?>' class='author-avatar'><img src='http://www.gravatar.com/avatar/<?=md5($authordata->user_email);?>?s=58' />&nbsp;</a>
<!-- END AUTHOR -->
						<div class="cat-links"><?php foreach( get_the_category() as $cat ) echo '<a rel="category" class="cat-' . $cat->slug . '" title="View all posts in ' . $cat->name . '" href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a>'; ?></div>
					</div>									
				</div><!-- #post-<?php the_ID(); ?> -->
				<div id="nav-below" class="navigation">
					<span class="nav-label">More from OMG! Ubuntu!</span>
<?php echo $navlinks ?>
				</div><!-- #nav-below -->					
			</div><!-- #content -->		
		</div><!-- #container -->
		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>