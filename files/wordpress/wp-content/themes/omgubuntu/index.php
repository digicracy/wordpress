<?php get_header(); ?>
		<div id="container">

			<div id="content">		
			
<?php while ( have_posts() ) : the_post() ?>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="post-inner">
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( __('Carry on to %s', 'omgubuntu'), the_title_attribute('echo=0') ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					
					<div class="entry-meta">
						<span class="meta-prep meta-prep-author"><?php _e('by ', 'omgubuntu'); ?></span>
						<span class="author vcard"><a class="url fn n" href="<?php echo get_author_link( false, $authordata->ID, $authordata->user_nicename ); ?>" title="<?php printf( __( 'View all posts by %s', 'omgubuntu' ), $authordata->display_name ); ?>"><?php the_author(); ?></a></span><span class="meta-sep">,</span>
						<span class="meta-prep meta-prep-entry-date"><?php _e('posted ', 'omgubuntu'); ?></span>
						<span class="entry-date"><abbr class="published" title="<?php the_time('Y-m-d\TH:i:sO') ?>"><?php the_time( get_option( 'date_format' ) ); ?></abbr></span>
						<?php edit_post_link( __( 'Edit', 'omgubuntu' ), "<span class=\"meta-sep\">|</span>\n\t\t\t\t\t\t<span class=\"edit-link\">", "</span>\n\t\t\t\t\t" ) ?>
					</div><!-- .entry-meta -->
					
					<div class="entry-content excerpt">	
<?php the_excerpt(); ?>
<?php wp_link_pages('before=<div class="page-link">' . __( 'Pages:', 'omgubuntu' ) . '&after=</div>') ?>
						<div class="read-more"><a href="<?php the_permalink(); ?>">Continue Reading</a></div>
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
						<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'omgubuntu' ), __( '1 Comment', 'omgubuntu' ), __( '% Comments', 'omgubuntu' ) ) ?></span>
					</div><!-- #entry-utility -->
					
				</div>
				<div class="left-block">
					<div class="cat-links"><?php foreach( get_the_category() as $cat ) echo '<a rel="category" class="cat-' . $cat->slug . '" title="View all posts in ' . $cat->name . '" href="' . get_category_link($cat->term_id) . '">' . $cat->name . '</a>'; ?></div>
				</div>
				</div><!-- #post-<?php the_ID(); ?> -->
				
<?php comments_template(); ?>				
	
<?php endwhile; ?>		

<?php global $wp_query; $total_pages = $wp_query->max_num_pages; if ( $total_pages > 1 ) { ?>
				<div id="nav-below" class="navigation">
					<div class="nav-previous"><?php next_posts_link(__( 'Older posts', 'omgubuntu' )) ?></div>
					<div class="nav-next"><?php previous_posts_link(__( 'Newer posts', 'omgubuntu' )) ?></div>
				</div><!-- #nav-below -->
<?php } ?>			
			
			</div><!-- #content -->		
		</div><!-- #container -->
		
<?php get_sidebar(); ?>	
<?php get_footer(); ?>
