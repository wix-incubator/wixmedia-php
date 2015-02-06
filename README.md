
Wix Media PHP SDK
====================

Wix Media Platform is a collection of APIs for image files, and services for storing, serving, uploading, and managing those files.

In addition to this basic functionality, Wix Media Platform includes the following components: 
* **Image Services** give you powerful REST APIs that enable you to manipulate images hosted on Wix Media Platform on the fly.
* **SDKs** include client- and server-side libraries.

The Wix Media PHP SDK is a PHP wrapper, which provides easier access to the Wix Media Plaform APIs and services.

## Setup ##

Installing wixmedia-PHP package is as simple as adding it to your project's include path.  

If you're using git, you can just clone down the repo like this:

```
git clone git@github.com:wix/wixmedia-php.git
```

__Note__: If you don't have git or would rather install by unpacking a Zip or Tarball, you can always grab the latest version of the package from [the downloads page](https://github.com/wix/wixmedia-php/archive/master.zip). 

## Usage ##

### Uploading files ###

The following example shows server-to-server image upload:

```PHP
require($sdk_wix_midea_dir.'WixClient.php');

$client = new WixClient('YOUR_API_KEY', 'YOUR_API_SECRET');

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
```

### Rendering Media ###

#### Images ####
Images can be uploaded to the Wix Media Platform using this package.
Once an image was uploaded, you can apply various manipulations on it. These manipulations include, but are not limited to, resizing, cropping, rotating, sharpening, watermarking, face-detection and applying numerous filters. 

For more information about image manipulation in your apps, please read the [Images SDK documentation](https://github.com/wix/wixmedia-php/blob/master/docs/images.md).
