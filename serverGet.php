<?

// TODO:
// - read all files from the folder, not just the first one
// - look at this one, might demonstrate slightly better: https://shanetully.com/2014/09/a-dead-simple-webrtc-example/


// A unique identifier (not necessary when working with websockets)
if (!isset($_GET['unique'])) {
    die('no identifier');
}
$unique=$_GET['unique'];
if (strlen($unique)==0 || ctype_digit($unique)===false) {
    die('not a correct identifier');
}


header('Content-Type: text/event-stream');
header('Cache-Control: no-cache'); // recommended

function startsWith($haystack, $needle) {
    return (substr($haystack, 0, strlen($needle) ) === $needle);
}

// Get a list of all files that start with '_file_' except the file containing
// messages that this client has sended itsself ('_file_'.$unique).
$all = array ();
$handle = opendir ( '../'.basename ( dirname ( __FILE__ ) ) );
if ($handle !== false) {
    while ( false !== ($filename = readdir ( $handle )) ) {
	if (startsWith($filename,'_file_' /* .$room */)  && !(startsWith($filename,'_file_' /*.$room*/ .$unique) )) {
	    $all [] .= $filename;
	}
    }
    closedir( $handle );
}

if (count($all)!=0) {

    // A main lock to ensure save safe writing/reading
    $mainlock = fopen('serverGet.php','r');
    if ($mainlock===false) {
	die('could not create main lock');
    }
    flock($mainlock, LOCK_EX);

    // show and empty the first file that is not empty
    for ($x=0; $x<count($all); $x++) {
	$filename=$all[$x];

	// prevent sending empty files
	if (filesize($filename)==0) {
	    unlink($filename);
	    continue;
	}

	$file = fopen($filename, 'c+b');
	flock($file, LOCK_SH);
	echo 'data: ', fread($file, filesize($filename)), PHP_EOL;
	fclose($file);
	unlink($filename);
	break;
    }

    // Unlock main lock
    flock($mainlock, LOCK_UN);
    fclose($mainlock);
}

echo 'retry: 1000', PHP_EOL, PHP_EOL; // shorten the 3 seconds to 1 sec

?>