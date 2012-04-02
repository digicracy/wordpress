<?php

$file = file("alog");

$ips = array();
$types = array();

foreach($file as $line)
{
	$bits = explode(" ", $line);
	$ip = $bits[0];
	$file = $bits[1];

	print $ip."\n\n".$file;


	die();
}