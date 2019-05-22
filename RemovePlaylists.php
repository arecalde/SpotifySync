<?php
	$cmd = "curl -X GET \"https://api.spotify.com/v1/users/*user*/playlists\" -H \"Authorization: Bearer *token*\"";
	//this gets all user playlists
	$cmd = str_replace("*user*", file_get_contents("textData/user.txt"), $cmd);
	$cmd = str_replace("*token*", file_get_contents("textData/token.txt"), $cmd); //make appropriate substitutions
//into the command
	$output = shell_exec($cmd); //get the raw json

	$spotifyJson = json_decode($output, true); //decode the json
	foreach($spotifyJson["items"] as $playlist){
		//go through each playlist
		$name = $playlist["name"]; //get the name
		$id = $playlist["id"]; //get the id
		$index = arrayContains($gPlaylists, $name);
		//check if the name is among the google playlists
		if($index != -1){ //if so,
			$cmd = file_get_contents("spotifyCommands/deletePlaylist.txt");
			$cmd = str_replace("\n", "", $cmd);
			$cmd = str_replace("*user*", file_get_contents("textData/user.txt"), $cmd);
			$cmd = str_replace("*token*", file_get_contents("textData/token.txt"), $cmd);
			$cmd = str_replace("*pid*", $id, $cmd);
			//make appropriate substitutions in curl
			$output = shell_exec($cmd); //execute the curl command
			echo "$name deleted from Spotify, now create it and fill with tracks\n";
		}
	}
?>