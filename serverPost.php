<?

// A unique identifier (not necessary when working with websockets)
if (!isset($_GET['unique'])) {
    die('no identifier');
}
$unique=$_GET['unique'];
if (strlen($unique)==0 || ctype_digit($unique)===false) {
    die('not a correct identifier');
}

    
// A main lock to ensure save safe writing/reading
$mainlock = fopen('serverGet.php','r');
if ($mainlock===false) {
    die('could not create main lock');
}
flock($mainlock, LOCK_EX);
   
// Add the new message to file
$filename = '_file_' /*.$room*/ . $unique;
$file = fopen($filename,'ab');
if (filesize($filename)!=0) {
    fwrite($file,'_MULTIPLEVENTS_');
}
$posted = file_get_contents('php://input');
fwrite($file,$posted);
fclose($file);

// Unlock main lock
flock($mainlock,LOCK_UN);
fclose($mainlock);

?>