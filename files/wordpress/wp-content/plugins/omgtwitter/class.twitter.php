<?php

class CachedTwitter
{
	function CachedTwitter( $url, $ttl, $cache_dir = "./cache" )
	{
		$this->url = $url;
		$this->cache_ttl = $ttl;
		$this->cache_dir = $cache_dir;
		@mkdir($cache_dir);
		if( $this->cache_load() === false || $this->cache->expires < time() )
		{
			$this->feed_load();
		}
	}
	
	function cache_load()
	{
		if( !file_exists($this->cache_dir . "/twitter.cache") )
		{
			print "file doesn't exist - ".$this->cache_dir . "/twitter.cache";
			return false;
		}
				
		$file = file_get_contents( $this->cache_dir . "/twitter.cache" );
		
		$this->cache = @json_decode($file);
		$this->tweets =& $this->cache->tweets;
		$this->scale =& $this->cache->retweet_max;
		$this->owner =& $this->cache->owner;
				
		if( !isset($this->cache) || count($this->tweets) == 0 )
			return false;
		
		return true;
	}
	
	function feed_load()
	{
		// load feed
		$feed = file_get_contents( $this->url );

		// replace unicode characters with HTML equivalents
		$feed = preg_replace("/\\\u([A-Za-z0-9]{4})/", "&#x$1;", $feed);

		// decode feed
		$tweets = json_decode( $feed );

		// get max retweet-count, remove reply-tweets
		$max = 0;
		$min = 0;
		foreach( $tweets as $i=>$tweet )
		{
			if( $tweet->in_reply_to_screen_name )
				unset( $tweets[$i] );
			else
			{
				$tweet->ocount = $tweet->retweet_count;
				$tweet->age = floor((time() - strtotime( $tweet->created_at ))/3600);
				$tweet->retweet_count -= $tweet->age;
				$max = max( $tweet->retweet_count, $max );
				$min = min( $tweet->retweet_count, $min );
			}
		}
		$max -= $min;
		
		foreach( $tweets as $i=>$tweet ) $tweets[$i]->retweet_count -= $min;
		
		function _tw_cmp($a,$b) { return $a->retweet_count < $b->retweet_count; }
		usort( $tweets, '_tw_cmp' );
		
		// save important info to cache
		$tw = array();
		foreach( $tweets as $tweet )
		{
			// get rid of hashags at the end - the $ means "only match at end of string, fyi
			$otext = $tweet->text;
			do $tweet->text = preg_replace( "/ ?#[a-z0-9]+$/i", "", $tweet->text, -1, $count ); while( $count );
	
			// is there an URL at the end of the tweet? grab it, use it... else, use the twitter link
			if( ! preg_match( "/(http:\/\/[^\s;]+)$/i", $tweet->text, $link ) )
			{
				continue;
			}
			else
			{
				$link = $link[0];
				$tweet->text = str_replace( $link, "", $tweet->text );
			}		

			$tw[] = (object) array( 'url'=>$link, 'text'=>$tweet->text, 'original_text'=>$otext, 'id'=>$tweet->id_str, 'age'=>$tweet->age, 'count'=>$tweet->retweet_count, 'retweets'=>$tweet->ocount, 'time'=>strtotime($tweet->created_at) );
		}
		
		$this->cache = new stdClass;
		$this->cache->expires = time() + ($this->cache_ttl * 60);
		$this->cache->retweet_max = $max;
		$this->cache->tweets = $tw;
		$this->tweets =& $this->cache->tweets;
		$this->scale = $max;
		
		// get some other metric whilst we are here
		$this->owner = new stdClass;
		$this->owner->screen_name = $tweets[0]->user->screen_name;
		$this->owner->followers = $tweets[0]->user->followers_count;
		$this->owner->name = $tweets[0]->user->name;
		$this->cache->owner = $this->owner;
		
		// write to cache file
		@file_put_contents( $this->cache_dir . "/twitter.cache", json_encode( $this->cache ) );
	}

}
/*
$tc = new CachedTwitter( 'http://api.twitter.com/1/statuses/user_timeline.json?include_entities=1&contributor_details=true&include_rts=true&user_id=72915446', 5 );

$width_ratio = 400 / ($tc->scale * 2);
foreach( $tc->tweets as $tweet )
{
	$width = ($width_ratio * ($tc->scale + $tweet->count))+"px";
	
	print "<div style='width: $width; height: 30px; display: block; margin; 2px; white-space: nowrap; background: #38b; font-size: 8pt; line-height: 30px; padding: 5px;'><a href='".$tweet->url."'>".$tweet->count." ".$tweet->retweets." ".$tweet->text."</a></div>";

}*/
