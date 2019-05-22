from gmusicapi import Mobileclient
import json

api = Mobileclient()

email = ""
password = ""

logged_in = api.login('', '', Mobileclient.FROM_MAC_ADDRESS)

if logged_in:
  library = api.get_all_user_playlist_contents()
	
	f = open("textData/googleData.txt", "w+")
	f.write(json.dumps(library))
	f.close()

  print("Success")
else:
  print "Fail"
