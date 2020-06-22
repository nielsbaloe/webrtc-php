# webrtc-php

WebRTC is nice, but impossible to test out if you just have a normal cheap shared-hosting PHP server (which means no websockets), and no time/money/energy to hire a webserver with commandline access to use node.js or Java or a PHP websocket framework. So, here is an example of webRtc with just using plain old cheaply-available PHP. 

#### You do need a https server with SSL, otherwise it will not work. It has to be on a real working certificate on a real server. Please please look carefully to all the errors and warnings in your Javascript console - do not ignore any of them.

My intention is to keep it as simple as possible, so that you can use it as a startpoint for your own application or just to try out some webRtc stuff. 

It works great, however the WebRTC specs change a bit over time, and not all browsers keep up. Please do not ask me any webRTC specific questions - not because I do not want to help you, but simply because I do not have much specific webRTC knowledge. Please test it first with two Chrome browsers, that should work fine, and then start with testing other browsers. I tested it with some Firefox browsers and some mobile phones too, it worked every time, although the handshakes were extremely difficult to follow.
#### So again: please do not ask me any webRTC questions, I simply do not have the answers...

Currently this works when two users load the same URL in their browser. I use this to communicate with my girlfriend, we both know an unique URL. As far as I know there is no restriction that this can work for more users too, although you have to figure that out yourself.

I intentionally left out any "room" functionality (meaning: pairs of people can connect in seperate rooms), but if you search for 'room' you can see that on the serverside it already has some hints. You still have to work out the details though, only enabling the room variable will not be enough. And it will still work for just 2 people, not more.

## No Websocket?

The "websocket" usually seen in webRTC is only for handshaking, but webRTC does not need a websocket at all. You can also do a handshake by e-mail if you want. Or by using a dove.

I am using EventSource (or SSE, Server Side Events) instead of a websocket, which actually works in all browsers including mobile browsers (not in old browsers like IE; you could use a polyfill for that, but I removed the polyfill because it is now legally accepted to ignore IE). With EventSource, the browser automaticly request the server for new data with a normal HTTP request (or a HTTP2 socket) every few seconds.

Because a "few seconds" might be up to 30 seconds, I shortened it to 1 second (that is the "retry: ..." line in the code), and I wrote the eventsource.onmessage in a way that it can receive multiple messages in one call. It works perfectly, but due to the "few seconds" the handshake takes a about two seconds instead of "as soon as possible" - you can fill in a lower number at the retry, but it will upscale the load for your server (unless you stop the eventsource when you're done handshaking). It is fine as it is.

EventSource is syntacticly equal to WebSocket (so it is easy to move to websockets later!), except that there is no send() method to send data back to the server - for that I extend the eventsource with a send() method with a plain AJAX xmlHttpRequest. On the serverside, plain files are used for storage.

## Usage

To install (on a https server!), just place the files in a folder, and you need to "chmod" the directory to writable (because two files will be created) too. Be sure to hit F12 to look at the javascript console and see what is going on.

