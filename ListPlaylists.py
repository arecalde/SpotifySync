# Creates a playlist for a user

import pprint
import sys
import os
import subprocess
import json
import spotipy
import spotipy.util as util


if len(sys.argv) > 1:
    username = sys.argv[1]
else:
    print("Usage: %s username" % (sys.argv[0],))
    sys.exit()
scope = 'playlist-modify-private,playlist-modify-public';

token = util.prompt_for_user_token(username,scope,
																	 client_id='',
																	 client_secret='',
																	 redirect_uri='')

if token:
    sp = spotipy.Spotify(auth=token)
    sp.trace = False
    playlists = sp.user_playlists(username)
    print(json.dumps(playlists))
else:
    print("Can't get token for", username)