Simple realtime chat
=================


Installation
------------

Prepare app files

	composer update
	bower install
	mkdir temp log 
	chmod -R a+rw temp log
	
Create database from chat_app.sql and set correct credentials in config.local.neon (copy config.local.example.neon)


WebSockets setup
----------------

After everything is prepared you can startup the websockets server. If you made changes like added new dependencies in websockets controller you must flush cache.

	php bin/WebSockets/server.php


Testing guide
----------------

There are several static accounts and you can activate them by choosing appropriate url.

Default is user with id 7. You can open new browser window and add userId parameter (eg. http://pathToChatApp/?userId=8) and then start chatting.

You can find all available users in user table.