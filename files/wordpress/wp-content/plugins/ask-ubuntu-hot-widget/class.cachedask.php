<?php

class CachedASk
{
	function CachedAsk( $url, $key, $sort, $pages, $extra, $ttl = 10, $cache_dir = "./cache" )
	{
		$this->url = "http://$url?key=$key&sort=$sort&pagesize=$pages".$extra;
		$this->cache_unique = urlencode($url) . "-" . $sort . $pages . preg_replace( "/[^A-Za-z0-9]/", "", $extra );
		$this->cache_ttl = $ttl;
		$this->cache_dir = $cache_dir;
		
		if( $this->cache_load() === false || $this->cache->expires < time() )
		{
			$this->feed_load();
		}
	}
	
	function cache_name()
	{
		return $this->cache_dir . "/" . $this->cache_unique . "-".$this->cache_ttl . ".cache";
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
		$this->questions =& $this->cache->questions;
				
		if( !isset($this->cache) || count($this->questions) == 0 )
		{
			print "<!-- CACHE: empty! -->";
			return false;
		}
		print "<!-- CACHE: loaded -->";				
		return true;
	}
	
	function feed_load()
	{	
		// load feed
		//$feed = file_get_contents( $this->url );
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'gzip');  // Needed by API
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$data = curl_exec($ch);
		list($header, $body) = explode("\r\n\r\n", $data, 2);	
		
		// decode feed
		$questions = json_decode( $body );
		
		if( !isset($questions->questions) )
		{
			print "<!-- CURL: curl failed! -->";
			return false;
		}
		print "<!-- CURL: got {$this->cache_unique} -->";
		
		$this->cache = new stdClass;
		$this->cache->expires = time() + ($this->cache_ttl * 60);
		$this->cache->questions = $questions->questions;
		$this->questions  =& $this->cache->questions;
		
		// write to cache file
		@file_put_contents( $this->cache_name(), json_encode( $this->cache ) );
	}

}

/*
$API_KEY = "MhThP0gXyUSxQU0lLoc7qA";
$options = array( 'auh-sort'=>'hot', 'auh-cache'=>10, 'auh-items'=>5 );
$tc = new CachedAsk( 'api.askubuntu.com/1.1/questions', $API_KEY, $options['auh-sort'], 100, '&body=true&answers=true', $options['auh-cache'] );
$questions = $tc->questions;

$content = "<ul>";
for( $i = 0; $i < $options['auh-items']; $i++ )
{
	$question = (object) $questions[$i];
	$content   .= "<li><a title=\"{$question->title}\" href=\"http://www.askubuntu.com/questions/{$question->question_id}\" target='_blank'>{$question->title}<span class=\"post-rank\">".($i+1)."</span></a></li>";
}
$content .= "</ul>";

print $content; */
