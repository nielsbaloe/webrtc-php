# webrtc-php

WebRTC is nice, but impossible to test out if you just have a normal shared-osting PHP server (which means no websockets), and no time/money/energy to hire a webserver with commandline access to use node.js or Java or a PHP websocket framework. So, here is an example of webRtc with just using plain old cheaply-available PHP. And it works great!

My intention is to keep it as simple as possible, so that you can use it as a startpoint for your own application or just to try out some webRtc stuff. Maybe I will even simplify it some more if I have the time. It is just one file divided in three parts: the client (browser), the server for ajax posts which writes received data to a file, and the server for sending EventSource messages which are read from a file.

Currently this works when two users load the same URL in their browser. I use this to communicate with my girlfriend, we both know an unique URL. As far as I know there is no restriction that this can work for a groupchat, however this handshake should be between every pair, and you have to add javascript to show all the streams on screen.

I intentionally left out any "room" functionality, but if you search for 'room' you can see that it is as easy as extending the file with a room name (be sure to heavily secure the room name if the room name comes directly as url parameter from the browser, it will be a very huge security leak if you don't).

## No Websocket?

The websocket in webRTC is only for handshaking, but webRTC does not need a websocket. You can also do a handshake by e-mail if you want.

I am using EventSource (SSE, Server Side Events) instead of a websocket, which actually works in all browsers including mobile browsers (except IE/Edge but I use a polyfill for that). With EventSource, the browser automaticly request the server for new data with a normal HTTP request (or a HTTP2 socket) every few seconds.

Because a "few seconds" is "long", I shortened it to 1 second (that is the "retry: ..." line in the code), and I wrote the eventsource.onmessage in a way that it can receive multiple messages in one call. It works perfectly, but due to the "few seconds" the handshake takes a about two seconds instead of "as soon as possible" - you can fill in a lower number at the retry, but it will upscale the load for your server (unless you stop the eventsource when you're done handshaking).

EventSource is syntacticly equal to WebSocket (so it is easy to move to websockets later!), except that there is no send() method to send data back to the server - for that I use a plain AJAX xmlHttpRequest. On the serverside, plain files are used for storage of data that is received on the server side but not yet sended to other users.

## Usage

To install, just place the file in a folder, and you might need to "chmod" the directory to writable (two files will be created) too. Be sure to hit F12 to look at the javascript console and see what is going on.

You also need to empty the folder once in a while, it will be stuffed with empty files. The reason for this is that I can not delete the file just after I have read it out, since the PHP locking mechanism works only when you have a file opened. If you'd use a database instead of a file, this problem would not arise, however for demonstration purposes I want to keep the code as simple as possible. 

