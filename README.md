# Notes
## CreatePlaylist & ListPlaylist 
Both require spotify developer account
open the files and fill in your credentials
visit [spotipy docs](https://spotipy.readthedocs.io/en/latest/) for more info

## GetGoogleData 
Needs a username and password

It also gets all your playlists and puts it in googleData.txt
This must be called before running index.php
visit [gmusic api docs](https://unofficial-google-music-api.readthedocs.io/en/latest/) for more info

## user.txt
spotify username of whatever account you are accessing

## token.txt
A current spotify token with all scopes

### user.txt and token.txt can not have any whitespaces



# includes/functions.php
Contains some helper functions with two key functions
## Arr Contains & Remove Paren
`arrContains` checks if an array contains an object

`removeParen` is used to remove parenthesis from a string. If a song is
titled `Cool Song (ft. Cool Dude)` then this will return `Cool Song`

## findTrack & findSong
`findTrack` gets the spotify id of a track
on the first run the song is titled as `song | artist` on the second run it is
just `song`

ranBefore indicates if the function has previously ran, to prevent infinite recursion

`findSong` just does the actual api call


# RemovePlaylists
Removes matching spotify playlists, it uses `ListPlaylists.py` to get playlists

# CreatePlaylists
Creates the playlists and fills them with songs it uses `CreatePlaylists.py` to create playlists