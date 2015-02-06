<?php

$source_dir = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'wix'.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR;
require($source_dir.'WixClient.php');

$api_key = 'YOUR_API_KEY';
$secret = 'YOUR_API_SECRET';

$client = new WixClient($api_key, $secret);

if ($image = $client->uploadImage('test.jpg')) {
	echo "The image was successfully uploaded!\n";
	echo $image->getId();
} else {
	echo "An error occurred, check your php log file for more information.\n";
	echo "Trying to use stock image...\n";
	$image_id = 'wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/parrot.jpg';
	$image = $client->getImageById($image_id);
	echo $image->getId();
	echo "\n";
}
	
echo $image->canvas(400, 800, 'top', 'cccccc')->getUrl();
echo '<br>';

$image->reset();
echo $image->fill(480, 240)->getUrl();
echo '<br>';

$image->reset();
echo $image->fit(480, 240, 'Lanczos2SharpFilter')->getUrl();
echo '<br>';

$image->reset();
echo $image->crop(1900, 800, 800, 900)->getUrl();
echo '<br>';

$image->reset();
echo $image->fit(120, 120)->
		adjust(10, -15)->
		getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			oil()->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			negate()->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			pixelate(5)->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			blur(5)->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			sharp(0.8)->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			unsharp(0.4, 0.2, 0.0)->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			baseline()->
			getUrl();
echo '<br>';

$image->reset();
echo $image->fit(420, 420)->
			quality(35)->
			getUrl();
echo '<br>';