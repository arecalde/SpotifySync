<?php 

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	include "includes/functions.php";
	$googlePlaylistOutput = file_get_contents("textData/googleData.txt");
	$jsonDecode = json_decode($googlePlaylistOutput, true);
	//decode the raw json

	$outerCount = 0;
	$gPlaylists = array();

	while($outerCount < count($jsonDecode)){
		$name = $jsonDecode[$outerCount]["name"];
		$gPlaylists[$outerCount] = $name;
		$outerCount++;
	}

	include "RemovePlaylists.php";
	include "CreatePlaylists.php";

?>