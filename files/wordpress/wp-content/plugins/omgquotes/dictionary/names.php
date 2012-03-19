<?php
$names = explode(",",file_get_contents("names.txt"));
$dictionary = explode("\n", file_get_contents("combined.dic"));

foreach( $names as $i=>$name )
	$names[$i] = strtolower( substr($name,1,-1) );
$dictionary = array_diff( $dictionary, $names );

//foreach( $names as $i=>$name )
//{
//	$name = strtolower( substr($name,1,-1) );
	
//	$p = round( 100 * $i / count($names), 2 ) . "%";
	
//	foreach( $dictionary as $j=>$word )
//		if( strtolower($word) == strtolower($name) )
//		{
//			print "Removed $name - $p\n";
//			unset($dictionary[$j]);
//			break;
//		}
//}

//file_put_contents("names.txt", implode("\n",$names));
file_put_contents("combined.dic", implode("\n",$dictionary));
