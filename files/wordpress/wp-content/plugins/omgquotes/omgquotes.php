<?php
// function plugin_dir_path() { return "./"; }
// function add_action($a,$b) { return; }
// function add_filter($a,$b) { return; }
// function get_option($a) { return array(); }

/*
Plugin Name: Oh My Quotes
Plugin URI: http://www.omgubuntu.co.uk/
Description: Displays quotes.
Author: Richard Lyon
Version: 0.01
Requires at least: 2.8
Author URI: http://richardlyon.co.uk
License: GPL
*/

// This plugin was originally based on Rich Boakes' Analytics plugin: http://boakes.org/analytics, and then on the GA plugin

// Determine the location
function ohmy_plugin_path() {
	return plugins_url('', __FILE__).'/';
}

/*
 * Admin User Interface
 */

if ( ! class_exists( 'OhMyQuotes' ) ) {

	require_once plugin_dir_path(__FILE__).'yst_plugin_tools.php';
	
	class OhMyQuotes extends YoastOMQ {

		var $name_str   = "Oh My Quotes";
		var $shortname	=  'Oh My Quotes';
		var $longname	=  'Oh My Quotes Configuration';

		var $hook 		= 'omgquotes';
		var $filename	= 'omgquotes/omgquotes.php';
		var $optionname = 'ohmyquote';
		var $homepage	= 'http://richardlyon.co.uk/';
		var $toc		= '';

		function OhMyQuotes() {
			add_action( 'admin_menu', array(&$this, 'register_settings_page') );
			add_filter( 'plugin_action_links', array(&$this, 'add_action_link'), 10, 2 );
			
			add_action('admin_print_scripts', array(&$this,'config_page_scripts'));
			add_action('admin_print_styles', array(&$this,'config_page_styles'));	
			
			add_action('wp_dashboard_setup', array(&$this,'widget_setup'));	

			add_action('admin_head', array(&$this,'config_page_head'));

			add_action('admin_init', array(&$this,'save_settings'));

			# add_action('plugins_loaded', )
			register_sidebar_widget($this->name_str, array(&$this, 'render') );
		}

		function render($args)
		{
			if( current_user_can('manage_options') == false )
				return;

			$options = get_option( $this->optionname );

			$this->load_cache($options['categories'], $options['ttl']);

		// print pre-wrapping
			extract($args);
			print $before_widget . $before_title. $options['title'] . $after_title;

		// select quote to display into $q
			$q = $this->get_question($this->cache->cache->quotes, $options);			

		// print out $q
			print "<blockquote>".$q->text."</blockquote>";
			print "<cite>".$q->name."&nbsp;</cite>";
			print "<a href='".str_replace( "omgubuntu.dreamhosters.com", "omgubuntu.co.uk", $q->url )."'>source</a>";

			print $after_widget;

		}

		function get_question($quotes, $options)
		{
			if( $options['mode'] == 'latest' )
			{
				$q = $this->get_latest($quotes);
			}
			else
			{
				$list = $this->limit_list($quotes, $options);
				$q = $list[ floor(rand(0, count($list) ) ) ];
			}
			return $q;	
		}

		function get_latest($list)
		{
			foreach( $list as $quote )
				if( ! $quote->auto )
					$q = $quote;
			return $q;
		}

		function limit_list($quotes, $options)
		{
			if( $options['mode'] == 'all' )
				return $quotes;
			
			$list = array();
			foreach( $quotes as $quote )
			{
				if( ($options['mode'] == 'manual' && $quote->auto == false) || ( $options['mode'] == 'articles' && $quote->auto ) )
					$list[] = $quote;
			}
			
			return $list;
		}
				
		function save_settings() {
			$options = get_option( $this->optionname );
			
			if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] == "true" && isset($_REQUEST['plugin']) && $_REQUEST['plugin'] == $this->hook) {
				$options = $this->set_defaults();
				$options['msg'] = "<div class=\"updated\"><p>'.$this->name_str.' settings reset.</p></div>\n";
			} elseif ( isset($_POST['submit']) && isset($_POST['plugin']) && $_POST['plugin'] == $this->hook) {
				if (!current_user_can('manage_options')) die(__('You cannot edit the '.$this->name_str.' options.'));
				$options = array();

				# update options here
				// $checks = array('override','mains','articles');
				// foreach( $checks as $key ) $options[$key] = isset($_POST[$key]);

				$texts = array('categories'=>'all','ttl'=>1440, 'title'=>'Quote of the week', 'mode'=>'all');
				foreach( $texts as $key=>$default ) $options[$key] = isset($_POST[$key]) ? $_POST[$key] : $default;


				
				$this->load_cache($options['categories'],$options['ttl'], '../cache');
				$cache =& $this->cache->cache;
				$quotes =& $this->cache->cache->quotes;

				$incs = $dels = array();
				foreach($_POST['inc'] as $v) $incs[$v] = true;
				foreach($_POST['del'] as $v) $dels[$v] = true;				

				foreach( $_POST['qids'] as $i=>$qid )
				{
					$q =& $quotes[$qid];
					
					// delete?
					if( isset($dels[$i]) || !isset($_POST['text'][$i]) )
						unset($this->cache->cache->quotes[$qid]);
					
					$q->text = stripslashes($_POST['text'][$i]);
					$q->name = isset($_POST['name'][$i]) ? stripslashes($_POST['name'][$i]) : false;
					$q->url  = isset($_POST['url'][$i]) ? stripslashes($_POST['url'][$i]) : 'http://www.omgubuntu.co.uk';
					$q->inc  = isset($incs[$i]);
				}
				//$this->cache->write_cache();
										
				$options['msg'] = "<div id=\"updatemessage\" class=\"updated fade\"><p>Oh My Quotes <strong>settings updated</strong>.</p></div>\n";
				$options['msg'] .= "<script type=\"text/javascript\">setTimeout(function(){jQuery('#updatemessage').hide('slow');}, 3000);</script>";
			}
			update_option($this->optionname, $options);

		}
		
		function save_button() {
			return '<div class="alignright"><input type="submit" class="button-primary" name="submit" value="Update Settings &raquo;" /></div><br class="clear"/>';
		}
		
		function config_page_head() {
			if (isset($_GET['page']) && $_GET['page'] == $this->hook) {
				$options = get_option($this->optionname);
				
				print "<!--";
				print_r($options);
				print "-->";

				# config page

			}
		}

		function load_cache($category, $ttl = 60, $dir = "./cache")
		{
			if( isset($this->cache) && $this->cache->category == $category )
				return $this->cache;

			if( !class_exists('CachedQuotes'))
				require plugin_dir_path(__FILE__) . "class.quotes.php";
			
			$this->cache = new CachedQuotes($category, $ttl, $dir);

			return $this->cache;
		}
		
		function config_page() {
			$options = get_option($this->optionname);
			echo $options['msg'];
			$options['msg'] = '';
			update_option($this->optionname, $options);

			$this->load_cache($options['categories'], $ttl = 60, "../cache");
			?>
			<div class="wrap">
				<h2><?=$this->name_str;?> Configuration</h2>
				<div class="postbox-container" style="width:65%;">
					<div class="metabox-holder">	
						<div class="meta-box-sortables">
							<form action="<?php echo $this->plugin_options_url(); ?>" method="post" id="omgquotes-conf">
								<input type="hidden" name="plugin" value="omgquotes"/>
								<?php

									$rows[] = array(
										'id' => 'title',
										'label' => 'Widget Title',
										'content' => $this->textinput('title'),
									);

									$rows[] = array(
										'id' => 'ttl',
										'label' => 'Cache Time in minutes',
										'content' => $this->textinput('ttl'),
									);

									$rows[] = array(
										'id' => 'categories',
										'label' => 'Comma-seperated category list',
										'content' => $this->textinput('categories'),
									);

									$rows[] = array(
										'id' => 'mode',
										'label' => 'Quote Pool',
										'content' => $this->select('mode', array(
											'latest' => 'Latest quote only',
											'manual' => 'Only those entered manually',
											'articles' => 'Only those scraped from articles',
											'all' => 'All available quotes' ))
									);

									$this->postbox('omqsettings', $this->name_str . ' Settings', $this->form_table($rows).$this->save_button() );
								
									$auto_rows = array();
									$man_rows = array();
									foreach( $this->cache->quotes as $i=>$quote )
									{
										// $quote->inc = $i;
										// $quote->del = $i;

										$quote = (array) $quote;
										$row =  "<td class='column-title'>" . $this->textinput('text',$quote, 90, true) . "</td>";
										$row .= "<td class='column-author'>" . $this->textinput('name',$quote, 20, true) . "</td>";
										$row .= "<td class='column-url'>" . $this->textinput('url',$quote,50, true) . "</td>";
										$row .= "<td style='text-align:center;padding-top:9px;' class='check-column'>" . $this->checkbox('inc',$quote,true,$i) . "</td>";
										$row .= "<td style='text-align:center;padding-top:9px;' class='check-column'>" . $this->checkbox('del',$quote,true,$i) . "</td>";
										$row .= "<input type='hidden' name='qids[]' value='$i' />";

										if( $quote['auto'] )
											$auto_rows[] = "<tr>$row</tr>";
										else
											$man_rows[] = "<tr>$row</tr>";
									}

									$content = "<table class='wp-list-table widefat' cellspacing='0'>";
									$content .= "<thead><tr><th scope='col' id='text' class='manage-column column-title sortable desc'>&nbsp;Quote</th>";
									$content .= "<th scope='col' id='name' class='manage-column column-author sortable desc'>Attribution</th>";
									$content .= "<th scope='col' id='url' class='manage-column column-url sortable desc'>Source URL</th>";
									$content .= "<th scope='col' id='inc' class='manage-column column-cb'>Included</th>";
									$content .= "<th scope='col' id='del' class='manage-column column-cb check-column'>Delete?&nbsp;</th>";
									$content .= "</tr></thead>";
									$auto = $content . implode( "\n", $auto_rows ) . "</table>";
									$manu = $content . implode( "\n", $man_rows ) . "</table>";

									print $manu.$this->save_button();
									print "<br/>";
									print $auto.$this->save_button();


									// $this->postbox('omgquotes', 'Manual Quotes', $manu.$this->save_button() );
									// $this->postbox('omgquotes', 'Scraped Quotes', $auto.$this->save_button() );


									$rows = array();
									$rows[] = array(
										'id' => 'ignore_userlevel',
										'label' => 'Ignore users',
										'desc' => 'Users of the role you select and higher will be ignored, so if you select Editor, all Editors and Administrators will be ignored.',
										'content' => $this->select('ignore_userlevel', array(
											'11' => 'Ignore no-one',
											'8' => 'Administrator',
											'5' => 'Editor',
											'2' => 'Author', 
											'1' => 'Contributor', 
											'0' => 'Subscriber (ignores all logged in users)', 
										)),
									);
									$rows[] = array(
										'id' => 'trackprefix',
										'label' => 'Prefix to use in Analytics before the tracked pageviews',
										'desc' => 'This prefix is used before all pageviews, they are then segmented automatically after that. If nothing is entered here, <code>/yoast-ga/</code> is used.',
										'content' => $this->textinput('trackprefix'),
									);
									?>
					</form>
					<form action="<?php echo $this->plugin_options_url(); ?>" method="post" onsubmit="javascript:return(confirm('Do you really want to reset all settings?'));">
						<input type="hidden" name="reset" value="true"/>
						<input type="hidden" name="plugin" value="omgquotes"/>
						<div class="submit"><input type="submit" value="Reset All Settings &raquo;" /></div>
					</form>
				</div>
			</div>
		</div>
		<div class="postbox-container side" style="width:20%;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">
					<?php
						$this->plugin_like();
					?>
				</div>
				<br/><br/><br/>
			</div>
		</div>
	</div>
			<?php
		} 
		
		function set_defaults() {
			$options = array(
				'title' => "Quote of the week",
				'ttl' => 1440,
				'mode' => 'all',
				'quotes' => array()
			);
			update_option($this->optionname,$options);
			return $options;
		}

	} // end class OhMyQuotes
	$ohmy = new OhMyQuotes();
} //endif





?>