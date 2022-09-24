<?php  

require "src/RaqSoundcloud.php";

$url = "https://soundcloud.com/chillplanetmusic/invisible-annick-bestia-stay-with-me-2";

$sc = new RaqSoundcloud;
$sc->request($url);
$sc->trackId();
$sc->clientId();
$sc->trackInformation();
$sc->getStream();

// var_dump($sc->track);

?>

<img src="<?= $sc->track->artwork_url_hd ?>">
<h2><?= $sc->track->title ?></h2>
<audio controls src="<?= $sc->stream_url ?>"></audio>
<hr>
<?= $sc->stream_url ?>