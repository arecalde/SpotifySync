<?php 



 function arrayContains($arr, $item){
    $count = 0;
    foreach($arr as $i){ //go through
      //each element in the array
      if($item == $i){ //if the item is found return true
        return $count;
      }
      $count++;
    }
    return -1; //if we reach the end, return false
  }


  //gets the spotify id of a track
//title is the song | artist on first run
//title is the song on the second run
//ranBefore indicates if the function has 
//previously ran, prevents infinite recursion
  function findTrack($title, $ranBefore){
    $songArray = findSong($title); //this gets the array
    //from json
    if(isset($songArray[0])){
      //if the songArray is defined, then continue
     $song = $songArray[0]["name"]." | ".$songArray[0]["artists"][0]["name"];
     $id = $songArray[0]["id"];   //get the song result
      $song = removeParen($song); //remove brackets, dashes, and parenthesis
      $dist = levenshtein($song, removeParen($title)); //get
      //the levenshtien
      if($ranBefore){ //if the function has ran before, 
        //calculate the levenshtien between the individual
        //songs, not the full titles
        $songSplit = explode("|", $song);
        $song = $songSplit[0]; //get only the song
        $dist = levenshtein($song, removeParen($title));
      }
      if($dist >= 15){ //if the difference is greater than 15
        //then assume it did not work
        if(!$ranBefore){
          $titleSplit = explode("|", $title);
          return findTrack($titleSplit[0], true); 
          //call the function again, using only the song
        }else{ //if it has ran before,
          //then the song could not be found
          echo "$title not found\n";
        }
      }else{
        return $id;
      }
    }else{//if it returned nothing
          //then search again with only the song
        if(!$ranBefore){ //if the function has not run
          $titleSplit = explode("|", $title);

          findTrack($titleSplit[0], true);
        }else{ //if the function has run,
          //then the song could not be found
          echo "$title not found\n";
        }
    }//*/
  }





   function findSong($title){
      $cmd = file_get_contents('spotifyCommands/findSpotifyTrack.txt');
     //get the curl command to find the json data
      $title= str_replace(chr(34), "", $title); //remove quotation marks
      $title = urlencode($title); //replace spaces with plusses and
     //do other appropriate substitutions
      $title = str_replace("+", "%20", $title); //change + to %20
      $cmd = str_replace("*token*", file_get_contents("textData/token.txt"), $cmd);
      $cmd = str_replace("*q*", $title, $cmd);
      $cmd = str_replace("*type*", "track", $cmd);
     //put appropriate changes through the command
		 		 //echo $cmd."<hr />";

      $output = shell_exec($cmd);
     //get the raw json text
      $tmpJson = json_decode($output, true);
     //decode the json
      if(isset($tmpJson["tracks"])){
        //make sure that there were results
        $songArray = $tmpJson["tracks"]["items"];
        return $songArray; //if shows return the songs
      }else{//otherwise return an empty array
        return array();
      }

   }







  function removeParen($oldString){
  $oldString = str_replace("\"", "", $oldString);
    //get rid of all parenthesis in the song
  $newString = ""; //store the new string
  $write = true; //start writing everything
  $count = 0;
    $seenMark = false; //prevents erasing the artist
  while($count < strlen($oldString)){ //go throughout the whole string
    $char = $oldString[$count]; //store the char for easy access
    if($char == "|"){$seenMark = true;} //prevents artists 
    if($char == "(" || ($char == "-" && !$seenMark) || $char == "["){
      $write = false; //stop writing if there are any of these
    }
    if($write){
      $newString = $newString.$char; //append 
      //the char at the en of the new string
      //while write = true
    }
    if($char == ")" || $char == "|" || $char == "]"){
      $write = true; //start writing again if there are any of these
    }
    $count++; //onto next char
  }
  $newString = str_replace("  ", " ", $newString);
    //remove double spaces
  return $newString; //return the new string
  }


?>