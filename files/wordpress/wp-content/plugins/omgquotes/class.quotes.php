<?php

class CachedQuotes
{
	function CachedQuotes( $category = 'all', $ttl = 60, $cache_dir = "./cache" )
	{
		$this->category = str_replace(" ","",$category);
		$this->cache_ttl = $ttl;
		$this->cache_dir = $cache_dir;

		$last = $this->cache_load();
		
		if( ! $last || $this->cache->expires < time() )
			$this->cache_update($last);
			
		// process a question
		$this->process_question();
	}
	
	function cache_name()
	{
		return $this->cache_dir . "/quotes." . $this->category . ".cache";
	}
	
	function cache_load()
	{
		if( !file_exists($this->cache_name()) )
		{
			print "<!-- CACHE: doesn't exist - ".$this->cache_name() . "-->";
			return false;
		}
				
		$file = file_get_contents( $this->cache_name() );
		
		$this->cache = @json_decode($file);
		$this->posts =& $this->cache->posts;
		$this->quotes =& $this->cache->quotes;

		print "<!-- CACHE: subload -->";

		$this->cache->quotes = (array) $this->cache->quotes;

		foreach( $this->cache->quotes as $i=>$q )
		{
			if( !isset($q->text) || strlen(trim($q->text)) < 5 )
			{
				unset($this->cache->quotes[$i]);
			}
		}
				
		if( !isset($this->cache) || !$this->cache || ( count($this->posts) == 0 && count($this->quotes) == 0 ) )
		{
			print "<!-- CACHE: empty! -->";
			return false;
		}
		
		print "<!-- CACHE: loaded -->";				
		return $this->cache->expires;
	}
	
	function cache_update($last = false)
	{	
		global $wpdb;

		print "<!-- UPDATE -->";
				
		// removes duplicate quotes whilst we are here...
		$list = array();
		foreach( $this->cache->quotes as $i=>$q )
		{
			// skip manual quotes
			if( ! $q->auto ) continue;

			// if this is a duplicate OR if it has a category and the category is not in the allowed list OR the quote is empty
			if( in_array( $q->text, $list ) || (isset($q->cat) && stripos($this->category,$q->cat) === false) || strlen(trim($q->text)) < 5 )
				unset( $this->cache->quotes[$i] );
			// otherwise, not a duplicate and is of right cateogry
			else
				$list[] = $q->text;
		}

		// if category is defined
		if( $this->category != "all" )
		{
			$querystr = "SELECT post_content, post_date_gmt, post_title, guid, name FROM $wpdb->posts
				LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
				LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
				LEFT JOIN $wpdb->terms ON($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
				WHERE $wpdb->term_taxonomy.taxonomy = 'category'
				AND $wpdb->posts.post_status = 'publish'
				AND $wpdb->posts.post_type = 'post'
				AND $wpdb->terms.name ";
			
			// multiple categories
			if( strpos($this->category,",") !== false )
				$querystr .= "IN('" . str_replace(",","','",$this->category) ."')";
			// single category
			else
				$querystr .= "= '".$this->category."'";
		}
		else
			$querystr = "SELECT post_content, post_date_gmt, post_title, guid, 'all' as name FROM $wpdb->posts";
		
		// last post skip
		if( $last )
			$querystr .= " AND post_date_gmt > '".date( "Y-m-d H:i:s", $last - ($this->cache_ttl * 60) )."'";

		$pageposts = $wpdb->get_results($querystr, OBJECT);

		if( ! $last )
			$this->cache = (object) array('quotes'=>array(), 'posts'=>array() );
				
		foreach( $pageposts as $post )
		{		
			// scrape blockquotes
			preg_match_all('/<blockquote[^>]*>(.*?)<\/blockquote>/', $post->post_content, $quotes );
			
			if( count($quotes[1]) == 0 )
				continue;
				
			$quotes = $quotes[1];
			
			// remove tags from quotes (only strips the tags, not the contents)
			// also, remove them if they include ppa:
			foreach( $quotes as $i=>$str )
			{
				$str = preg_replace("/(<[^>]+>)/", "", $str );
				$str = htmlentities($str,ENT_QUOTES);
				$str = preg_replace("/&[^;]+;/","",$str);

				$quotes[$i] = trim($str);
				if( preg_match("/(sudo|ppa:|apt-get)/", $str) || strlen(trim($str)) < 5 ) unset($quotes[$i]);
			}
			
			$o = new stdClass;
			$o->quotes = $quotes;
			$o->date = $post->post_date_gmt;
			$o->title = $post->post_title;
			$o->url = $post->guid;
			$o->cat = $post->name;
			
			$this->cache->posts[] = $o;
		}
		
		$this->cache->expires = time() + ($this->cache_ttl * 60);
		$this->cache->ttl = $this->cache_ttl;
		
		// write to cache file
		$this->write_cache();
	}
		
	function write_cache()
	{
		$this->cache->quotes = (array) $this->cache->quotes;
		return file_put_contents( $this->cache_name(), json_encode( $this->cache ) );
	}

	function process_question()
	{
		if( count( $this->cache->posts ) )
		{	
			print "<!-- PROCESS -->";

			$post = array_pop($this->cache->posts);
			
			// remove dictionary words and misc chars 
			$dictionary = explode("\n", file_get_contents( plugin_dir_path(__FILE__) . "dictionary/combined.dic" ) );
			
			$name = $post->title;
			foreach( $dictionary as $word )
				$name = preg_replace( "/([^a-z]|^)$word([^a-z]|$)/i", "  ", $name, -1, $c );
			$name = trim(preg_replace("/([^a-z ])/i", "", $name));
						
			// try extract name
			preg_match_all('/([A-Z][a-z]+ [A-Z][a-z]+)/', $name, $names );			
			$name = count($names[1]) ? $names[1][0] : false;

			if( !is_array($this->cache->quotes) )
				$this->cache->quotes = (array) $this->cache->quotes;

			foreach( $post->quotes as $quote )
			{
				$quote = preg_replace("/&?[a-z]+;/i", "", $quote);
				$this->cache->quotes[] = (object) array( 'cat'=>(isset($post->cat) ? $post->cat : null),'text'=>$quote, 'name'=>$name, 'url'=>(isset($post->url) ? $post->url : false), 'inc'=>true, 'auto'=>true );
			}

			print "<!--DONE-->";

			$this->write_cache();		
		}
	}
}

?>
