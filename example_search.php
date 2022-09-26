<?php  

require "src/RaqSoundcloud.php";

$sc = new RaqSoundcloud;
$sc->getClientId();
$sc->search("martin garrix");

var_dump($sc->search_result);

?>


