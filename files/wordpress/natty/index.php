<?php

function video($id)
{
	include("./includes/_video.php");
}

// if(!isset($_GET['secret'])) die; 

$page = isset($_GET['page']) ? $_GET['page'] : "what";
if( !file_exists("./includes/$page.php") || !preg_match("^[a-z]+$^", $page))
	$page = "what";

$ajax = isset($_GET['ajax']);

$file = "./includes/".$page.".php";

preg_match("/class=.title.[^>]*>(.+?)</im", file_get_contents($file), $matches);
$title = isset($matches[1]) ? $matches[1] : "OMG! Ubuntu!'s guide to Unity in 11.04";


if(!$ajax)
	include "includes/_header.php";

include	$file;

if(!$ajax)
	include "includes/_footer.php";

?>