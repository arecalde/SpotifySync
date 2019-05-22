<?php 
$outerCount = 0; //keep track of playlists
$songsInRequest = 0; //keeps track of how many songs are added in a single
//api call
$songs = ""; //stores the songs to be added in the api call
$cmd = ""; //store the cmd
while($outerCount < count($gPlaylists)){
  $array = $jsonDecode[$outerCount]['tracks']; //this gets the 
  //tracks in the particular playlist
  $pName = $gPlaylists[$outerCount]; //this gets that playlist name
    $count = 0; //start at the first track each time  

  $cmd = "python createPlaylist.py *user* '*name*'";
  //stores the api curl call
  $cmd = str_replace("*user*", file_get_contents("textData/user.txt"), $cmd);
  $cmd = str_replace("*name*", $pName, $cmd); //make appropriate substitutions in the command
  $output = shell_exec($cmd); //store the raw json
  $innerDecode = json_decode($output, true); //get the decodedJson
  $newId = $innerDecode['id'];  //store the playlist id
  
  while($count < count($array)){ //go through the array
    //that stores the tracks
    $title = $array[$count]['track']['title']; //get the title

		
    //fix some issues with you
    $googleTitle = $title." | ".$array[$count]['track']['artist']; //store the artist and song
    
    $googleTitle = removeParen($googleTitle); //remove
    //parenthesis, brackets, and dashes from title
    
    $googleTitle = str_replace("\"", "", $googleTitle);
    //remove apostrophes 
    
    $id = findTrack($googleTitle, false); //this gets
    //the spotify id

    if($id != 0){ //if the song was found
      $cmd = file_get_contents('spotifyCommands/addSongSpotify.txt');
      $cmd = str_replace("\n", "", $cmd);
      $cmd = str_replace("*token*", file_get_contents("textData/token.txt"), $cmd);
      $cmd = str_replace("*user*", file_get_contents("textData/user.txt"), $cmd);
      $cmd = str_replace("*pid*", $newId, $cmd); //make
      //appropriate substituions in the curl command
      $songsInRequest++; //add to the list of songs in the api calls
      $songs = $songs."\\\"spotify:track:$id\\\","; //add the uri to the list
      //of songs
      if($songsInRequest > 30){ //when a reasonable
        //amount is hit for the api call, run the api call
        $songs = substr($songs, 0, (strlen($songs)-1));
        //get rid of the comma at the end of the songs
        
        $cmd = str_replace("*songs*", $songs, $cmd);
        //substitute the list of uris into the curl command

        shell_exec($cmd); //execute the cmd
        $songs = ""; //reset the songs
        $songsInRequest = 0; //reset the number of songs inside the song string
      }
    }
    $count++; //move onto the next song on the playlist
  }
  
  if($songsInRequest != 0){ //if we still have songs left in the string,
    //do one last api call to add the songs to the playlist
        $songs = substr($songs, 0, (strlen($songs)-1));
        $cmd = str_replace("*songs*", $songs, $cmd);

        shell_exec($cmd);
        $songs = "";
        $songsInRequest = 0;
      }
  
  
  
  
  echo "\n\n";
  $outerCount++; //go to the next playlist
}
?>