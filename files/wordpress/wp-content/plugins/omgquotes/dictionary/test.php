<?php

$function = $argv[1];
$phrase = $argv[2];

$dictionary = explode("\n", file_get_contents("combined.dic"));

function test( $phrase, $dictionary )
{
	foreach( $dictionary as $word )
	{
		$np = preg_replace( "/([^a-z]|^)$word([^a-z]|$)/i", " ", $phrase, -1, $c );
		
		if( $c )
		{
			print "R: $c $word\n\t$phrase\n\t$np\n";
			$phrase = $np;
		}
	}
	$phrase = trim(preg_replace("/([^a-z ])/i", "", $phrase ));	
	print "\n\nCOMPLETE\n$phrase\n";
}

if( $function == "test" )
	test( $phrase, $dictionary );

if( $function == "search" )
	foreach( $dictionary as $word )
		if( strtolower($word) == strtolower($phrase) )
			print "$phrase exists in dictionary";

if( $function == "remove" )
{
	foreach( $dictionary as $i=>$word )
		if( strtolower($word) == strtolower($phrase) )
			unset($dictionary[$i]);
	
	file_put_contents("combined.dic", implode("\n",$dictionary));
}
