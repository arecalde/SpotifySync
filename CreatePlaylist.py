# Creates a playlist for a user

import pprint
import sys
import os
import subprocess

import spotipy
import spotipy.util as util
import json

if len(sys.argv) > 2:
    username = sys.argv[1]
    playlist_name = sys.argv[2]
else:
    print("Usage: %s username playlist-name" % (sys.argv[0],))
    sys.exit()
scope = 'playlist-modify-private,playlist-modify-public';

token = util.prompt_for_user_token(username,scope,
																	 client_id='',
																	 client_secret='',
																	 redirect_uri='')


if token:
    sp = spotipy.Spotify(auth=token)
    sp.trace = False
    playlists = sp.user_playlist_create(username, playlist_name)
    print(json.dumps(playlists))
else:
    print("Can't get token for", username)