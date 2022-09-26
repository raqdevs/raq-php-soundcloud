<?php  

require "src/RaqSoundcloud.php";

$progressive = 'https://api-v2.soundcloud.com/media/soundcloud:tracks:275869874/0412c3a6-e6a7-4399-b9bd-3a77f2c6be15/stream/progressive';

$sc = new RaqSoundcloud;
$sc->getClientId();
$sc->getStreamProgressive($progressive);

echo $sc->stream;


?>