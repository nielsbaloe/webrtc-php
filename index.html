<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
</head>
<body>
<div>
    <video id="localVideo" autoplay="true" muted="muted"></video>
    <video id="remoteVideo" autoplay="true" style="display:none"></video>
</div>
<script type="text/javascript">

    var answer = 0;
    var pc=null
	var localStream=null;
	var ws=null;
    
    // Not necessary with websockets, but here I need it to distinguish calls
    var unique = Math.floor(100000 + Math.random() * 900000);

    var localVideo = document.getElementById('localVideo');
    var remoteVideo = document.getElementById('remoteVideo');
    var configuration  = {
        'iceServers': [
			{ 'urls': 'stun:stun.stunprotocol.org:3478' },
			{ 'urls': 'stun:stun.l.google.com:19302' },
			//{'urls': 'stun:stun1.l.google.com:19302' },
			//{'urls': 'stun:stun2.l.google.com:19302' }
        ]
    };

	// Start
    navigator.mediaDevices.getUserMedia({
           //  audio: true, // audio is off here, enable this line to get audio too
            video: true
        }).then(function (stream) {
            localVideo.srcObject = stream;
            localStream = stream;

            try {
                ws = new EventSource('serverGet.php?unique='+unique);
            } catch(e) {
                console.error("Could not create eventSource ",e);
            }

            // Websocket-hack: EventSource does not have a 'send()'
            // so I use an ajax-xmlHttpRequest for posting data.
            // Now the eventsource-functions are equal to websocket.
			ws.send = function send(message) {
				 var xhttp = new XMLHttpRequest();
				 xhttp.onreadystatechange = function() {
					 if (this.readyState!=4) {
					   return;
					 }
					 if (this.status != 200) {
					   console.log("Error sending to server with message: " +message);
					 }
				 };
				 xhttp.open('POST', 'serverPost.php?unique='+unique, true);
				 xhttp.setRequestHeader("Content-Type","Application/X-Www-Form-Urlencoded");
				 xhttp.send(message);
			}

            // Websocket-hack: onmessage is extended for receiving 
            // multiple events at once for speed, because the polling 
            // frequency of EventSource is low.
			ws.onmessage = function(e) {
				if (e.data.includes("_MULTIPLEVENTS_")) {
					multiple = e.data.split("_MULTIPLEVENTS_");
					for (x=0; x<multiple.length; x++) {
						onsinglemessage(multiple[x]);
					}
				} else {
					onsinglemessage(e.data);
				}
			}

            // Go show myself
            localVideo.addEventListener('loadedmetadata', 
                function () {
                    publish('client-call', null)
                }
            );
			
        }).catch(function (e) {
            console.log("Problem while getting audio/video stuff ",e);
        });
		
    
    function onsinglemessage(data) {
        var package = JSON.parse(data);
        var data = package.data;
        
        console.log("received single message: " + package.event);
        switch (package.event) {
            case 'client-call':
                icecandidate(localStream);
                pc.createOffer({
                    offerToReceiveAudio: 1,
                    offerToReceiveVideo: 1
                }).then(function (desc) {
                    pc.setLocalDescription(desc).then(
                        function () {
                            publish('client-offer', pc.localDescription);
                        }
                    ).catch(function (e) {
                        console.log("Problem with publishing client offer"+e);
                    });
                }).catch(function (e) {
                    console.log("Problem while doing client-call: "+e);
                });
                break;
            case 'client-answer':
                if (pc==null) {
                    console.error('Before processing the client-answer, I need a client-offer');
                    break;
                }
                pc.setRemoteDescription(new RTCSessionDescription(data),function(){}, 
                    function(e) { console.log("Problem while doing client-answer: ",e);
                });
                break;
            case 'client-offer':
                icecandidate(localStream);
                pc.setRemoteDescription(new RTCSessionDescription(data), function(){
                    if (!answer) {
                        pc.createAnswer(function (desc) {
                                pc.setLocalDescription(desc, function () {
                                    publish('client-answer', pc.localDescription);
                                }, function(e){
                                    console.log("Problem getting client answer: ",e);
                                });
                            }
                        ,function(e){
                            console.log("Problem while doing client-offer: ",e);
                        });
                        answer = 1;
                    }
                }, function(e){
                    console.log("Problem while doing client-offer2: ",e);
                });
                break;
            case 'client-candidate':
               if (pc==null) {
                    console.error('Before processing the client-answer, I need a client-offer');
                    break;
                }
                pc.addIceCandidate(new RTCIceCandidate(data), function(){}, 
                    function(e) { console.log("Problem adding ice candidate: "+e);});
                break;
        }
    };

    function icecandidate(localStream) {
        pc = new RTCPeerConnection(configuration);
        pc.onicecandidate = function (event) {
            if (event.candidate) {
                publish('client-candidate', event.candidate);
            }
        };
        try {
            pc.addStream(localStream);
        }catch(e){
            var tracks = localStream.getTracks();
            for(var i=0;i<tracks.length;i++){
                pc.addTrack(tracks[i], localStream);
            }
        }
        pc.ontrack = function (e) {
            document.getElementById('remoteVideo').style.display="block";
            document.getElementById('localVideo').style.display="none";
            remoteVideo.srcObject = e.streams[0];
        };
    }

    function publish(event, data) {
        console.log("sending ws.send: " + event);
        ws.send(JSON.stringify({
            event:event,
            data:data
        }));
    }

</script>
</body>
</html>