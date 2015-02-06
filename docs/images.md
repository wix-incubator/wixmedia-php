Wix Media PHP SDK
--------------------
Image Manipulation
===========================
Wix Media Platform provides web developers a versatile infrastructure for image manipulations easily accessable through the [Wix Media Images RESTful API](http://media.wixapps.net/playground/docs/images_restfull_api.html). The Wix Media PHP library provides a wrapper over the API.

## Usage ##

### SDK Initialization ###

```PHP
require($sdk_wix_midea_dir.'WixClient.php');
$client = new WixClient('YOUR_API_KEY', 'YOUR_API_SECRET');
```

### Uploading Images ###

It’s easy to upload images using the Wix Media PHP library. For example:

```PHP
if ($image = $client->uploadImage('test.jpg')) echo $image->getId();
else echo "An error occurred, check your php log file for more information.\n";
```

The code snippet above gives the following image-id as output:
```
wix-a091529b-0151-4768-a83e-4cb899c90de2/images/b484152a31484c25b8f15aee760d786a/test.jpg
```

__Note__: Wix Media Services supports the following images file formats: JPEG, GIF and PNG.

### Rendering Images ###

After uploading an image, you can easily apply any manipulation as described later in the document.
For example:

```PHP
$image_id = 'wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/parrot.jpg';
$image  = $client->getImageById($image_id);
print $image->fit(420, 820)->
              unsharp()->
              oil()->
              adjust(10, -15)->
              getUrl();
```

The last code snippet applies image manipulation on a previously uploaded image and prints the URL for rendering the manipulated image The URL can be embedded in an HTML *img* tag:

```html
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_820,rf_22,usm_0.50_0.20_0.00,oil,br_10,con_-15/parrot.jpg
```
----------------
__Note__: 
All rendered URLs (as shown in the previous *img* tag) conform to the following structure:
```
http://host/user-id/media-type/file-id/version/operation/params(p_value, comma-separated),manipulations(p_value, comma-separated)/filename.ext
```
Using this PHP package eliminates the need to manually construct such urls. For more information about the URLs browse [Wix Media Images RESTful API](http://media.wixapps.net/playground/docs/images_restfull_api.html) documentation.

-----------------

#### Image Transformation Operations ####

The following image transformations are available (one per image maipulation request):
- Canvas
- Fill
- Fit
- Crop


##### Canvas #####

Resizes the image canvas, filling the width and height boundaries and crops any excess image data. The resulting image will match the width and height constraints without scaling the image

```PHP
canvas($width, $height, $alignment = 'center', $color = '000000')
```

Parameter | value | Description
----------|-------|------------
width *(mandatory)*|Integer|The width constraint (pixels).
height *(mandatory)*|Integer|The height constraint (pixels).
alignment *(optional)*|string|The position pointing the place from which to start cropping  the picture. See optional values in the table below.```default: center```
ext_color *(optional)*|string (RGB)| the extension color, in case the canvas size is larger than the image itself. Please note that the string expected is a 6 hexadecimal digits representing RRGGBB. 

alignment optional values:

Value | Description
------|------------
center|The vertical and horizontal center of the image.
top|The top of the image, horizontal center.
top-left|The top-left side of the image.
top-right|The top-right side of the image.
bottom|The bottom of the image, horizontal center.
bottom-left|The bottom-left side of the image.
bottom-right|The bottom-right side of the image.
left|The left side of the image, vertical center.
right|The right side of the image, vertical center.

*Sample Request:*
```PHP
print $image->canvas(480, 240, 'top', 'ffffff')->getUrl();
```
would generate the URL:
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/canvas/w_480,h_240,al_t,c_ffffff/parrot.jpg
```

##### Fill #####

Creates an image with the specified width and height while retaining original image proportion. If the requested proportion is different from the original proportion, only part of the original image may be used to fill the area specified by the width and height.

```PHP
fill($width, $height, $resize_filter = 'LanczosFilter', $alignment = 'center')
```

Parameter | value | Description
----------|-------|------------
width *(mandatory)*|Integer|The width constraint (pixels).
height *(mandatory)*|Integer|The height constraint (pixels).
resize_filter *(optional)*|string|The resize filter to be used. One of the values below. ```default: LanczosFilter```
alignment *(optional)*|string|The position pointing the place from which to start cropping  the picture. See optional values in the table below.```default: center```

alignment optional values:

Value | Description
------|------------
center|The vertical and horizontal center of the image.
top|The top of the image, horizontal center.
top-left|The top-left side of the image.
top-right|The top-right side of the image.
bottom|The bottom of the image, horizontal center.
bottom-left|The bottom-left side of the image.
bottom-right|The bottom-right side of the image.
left|The left side of the image, vertical center.
right|The right side of the image, vertical center.
face|A face in the image. Detects a face in the picture and centers on it. When multiple faces are detected in the picture, the focus will be on one of them. Note! This is not valid in the Canvas operation.
faces|All faces in the image. Detects multiple faces and centers on them. Will do a best effort to have all the faces in the new image, depending on the size of the new canvas. Note! This is not valid in the Canvas operation.

resize_filter optional values + descriptions (view links):

[PointFilter](http://www.imagemagick.org/Usage/filter/#point)|[BoxFilter](http://www.imagemagick.org/Usage/filter/#box)|[TriangleFilter](http://www.imagemagick.org/Usage/filter/#triangle)|[HermiteFilter](http://www.imagemagick.org/Usage/filter/#hermite)
--------|---------|---------|--------
[**HanningFilter**](http://www.imagemagick.org/Usage/filter/#hanning)|[**HammingFilter**](http://www.imagemagick.org/Usage/filter/#hamming)|[**BlackmanFilter**](http://www.imagemagick.org/Usage/filter/#balckman)|[**GaussianFilter**](http://www.imagemagick.org/Usage/filter/#gaussian)
[**QuadraticFilter**](http://www.imagemagick.org/Usage/filter/#quadratic)|[**CubicFilter**](http://www.imagemagick.org/Usage/filter/#cubics)|[**CatromFilter**](http://www.imagemagick.org/Usage/filter/#catrom)|[**MitchellFilter**](http://www.imagemagick.org/Usage/filter/#mitchell)
[**JincFilter**](http://www.imagemagick.org/Usage/filter/#jinc)|[**SincFilter**](http://www.imagemagick.org/Usage/filter/#sinc)|[**SincFastFilter**](http://www.imagemagick.org/Usage/filter/#sinc)|[**KaiserFilter**](http://www.imagemagick.org/Usage/filter/#kaiser)
[**WelchFilter**](http://www.imagemagick.org/Usage/filter/#welch)|[**ParzenFilter**](http://www.imagemagick.org/Usage/filter/#parzen)|[**BohmanFilter**](http://www.imagemagick.org/Usage/filter/#bohman)|[**BartlettFilter**](http://www.imagemagick.org/Usage/filter/#bartlett)
[**LagrangeFilter**](http://www.imagemagick.org/Usage/filter/#lagrange)|[**LanczosFilter**](http://www.imagemagick.org/Usage/filter/#lanczos)|[**LanczosSharpFilter**](http://www.imagemagick.org/Usage/filter/#lanczos_sharp)|[**Lanczos2Filter**](http://www.imagemagick.org/Usage/filter/#lanczos2)
[**Lanczos2SharpFilter**](http://www.imagemagick.org/Usage/filter/#lanczos2sharp)|[**RobidouxFilter**](http://www.imagemagick.org/Usage/filter/#robidoux)|[**RobidouxSharpFilter**](http://www.imagemagick.org/Usage/filter/#robidoux_sharp)|[**CosineFilter**](http://www.imagemagick.org/Usage/filter/#cosine)

*Sample Request:*

```PHP
print $image->fill(480, 240)->getUrl();
```
would generate the URL:
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fill/w_480,h_240,rf_22,al_c/parrot.jpg
```

##### Fit #####

Resizes the image to fit to the specified width and height while retaining original image proportion. The entire image will be visible but not necessarily fill the area specified by the width and height.

```PHP
fit($width, $height, $resize_filter = 'LanczosFilter')
```

Parameter | value | Description
----------|-------|------------
width *(mandatory)*|Integer|The width constraint (pixels).
height *(mandatory)*|Integer|The height constraint (pixels).
resize_filter *(optional)*|string|The resize filter to be used. One of the in the table below. ```default: LanczosFilter```

resize_filter optional values + descriptions (view links):

[PointFilter](http://www.imagemagick.org/Usage/filter/#point)|[BoxFilter](http://www.imagemagick.org/Usage/filter/#box)|[TriangleFilter](http://www.imagemagick.org/Usage/filter/#triangle)|[HermiteFilter](http://www.imagemagick.org/Usage/filter/#hermite)
--------|---------|---------|--------
[**HanningFilter**](http://www.imagemagick.org/Usage/filter/#hanning)|[**HammingFilter**](http://www.imagemagick.org/Usage/filter/#hamming)|[**BlackmanFilter**](http://www.imagemagick.org/Usage/filter/#balckman)|[**GaussianFilter**](http://www.imagemagick.org/Usage/filter/#gaussian)
[**QuadraticFilter**](http://www.imagemagick.org/Usage/filter/#quadratic)|[**CubicFilter**](http://www.imagemagick.org/Usage/filter/#cubics)|[**CatromFilter**](http://www.imagemagick.org/Usage/filter/#catrom)|[**MitchellFilter**](http://www.imagemagick.org/Usage/filter/#mitchell)
[**JincFilter**](http://www.imagemagick.org/Usage/filter/#jinc)|[**SincFilter**](http://www.imagemagick.org/Usage/filter/#sinc)|[**SincFastFilter**](http://www.imagemagick.org/Usage/filter/#sinc)|[**KaiserFilter**](http://www.imagemagick.org/Usage/filter/#kaiser)
[**WelchFilter**](http://www.imagemagick.org/Usage/filter/#welch)|[**ParzenFilter**](http://www.imagemagick.org/Usage/filter/#parzen)|[**BohmanFilter**](http://www.imagemagick.org/Usage/filter/#bohman)|[**BartlettFilter**](http://www.imagemagick.org/Usage/filter/#bartlett)
[**LagrangeFilter**](http://www.imagemagick.org/Usage/filter/#lagrange)|[**LanczosFilter**](http://www.imagemagick.org/Usage/filter/#lanczos)|[**LanczosSharpFilter**](http://www.imagemagick.org/Usage/filter/#lanczos_sharp)|[**Lanczos2Filter**](http://www.imagemagick.org/Usage/filter/#lanczos2)
[**Lanczos2SharpFilter**](http://www.imagemagick.org/Usage/filter/#lanczos2sharp)|[**RobidouxFilter**](http://www.imagemagick.org/Usage/filter/#robidoux)|[**RobidouxSharpFilter**](http://www.imagemagick.org/Usage/filter/#robidoux_sharp)|[**CosineFilter**](http://www.imagemagick.org/Usage/filter/#cosine)

*Sample Request:*

```PHP
print $image->fit(480, 240, 'Lanczos2SharpFilter')->getUrl();
```
would generate the URL:
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_480,h_240,rf_25/parrot.jpg
```

##### Crop #####

Crops the image based on the supplied coordinates, starting at the x, y coordinates along with the width and height parameters.

```PHP
crop($x, $y, $width, $height)
```

Parameter | Value | Description
----------|-------|------------
x *(mandatory)*|Integer|The x-pixel-coordinate to start cropping from. (represents the top-left corner point of the cropped area).
y *(mandatory)*|Integer|The y-pixel-coordinate to start cropping from. (represents the top-left corner point of the cropped area).
width *(mandatory)*|Integer|The width constraint (pixels).
height *(mandatory)*|Integer|The height constraint (pixels).

*Sample Request:*
```PHP
print $image->crop(1900, 800, 800, 900)->getUrl();
```
would generate the URL:
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/crop/x_1900,y_800,w_800,h_900/parrot.jpg
```

#### Image Adjustment Operation ####

##### Adjust #####

Applies an adjustment to an image

```PHP
adjust($brightness = 0, $contrast = 0, $saturation = 0, $hue = 0)
```
the parameters may be one or more of the following options:

function | parameter(s) | Description
---------|--------------|------------
brightness *(optional)*|Integer (%)|brightness. ```value between -100 and 100```
contrast *(optional)*|Integer (%)|contrast ```value between -100 and 100```
saturation *(optional)*|Integer (%)|saturation ```value between -100 and 100```
hue *(optional)*|Integer (%)|hue ```value between -100 and 100```

*Sample Request:*
```PHP
print $image->fit(120, 120)->
              adjust(10, -15)->
              getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_120,h_120,rf_22,br_10,con_-15/parrot.jpg
```

#### Oil Filter ####

Applies an oil paint effect on an image

```PHP
oil()
```

*Sample Request:*
```PHP
print $image->fit(420, 420)->
              oil()->
              getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,oil/parrot.jpg
```

#### Negative Filter ####

Negates the colors of the image

```PHP
negate()
```

*Sample Request:*
```PHP
print $image->fit(420, 420)->
              negate()->
              getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,neg/parrot.jpg
```


#### Pixelate Filter ####

Applies a pixelate effect to the image The parameter value is the width of pixelation squares (in pixels).

```PHP
pixelate($value)
```

*Sample Request:*
```PHP
print $image->fit(420, 420)->
             pixelate(5)->
             getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,pix_5/parrot.jpg
```

#### Blur Filter ####

Applies a blur effect to the image The parameter value indicates the blur in percents.

```PHP
blur(value)
```

*Sample Request:*
```PHP
print $image->fit(420, 420)->
              blur(5)->
              getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/h_420,w_420,blur_5/parrot.jpg
```

*** 

#### Sharpening Filter ####

Applies a sharpening filter on the image, using the radius parameter. please note that the radius’ value is a float number.

```PHP
sharp(radius)
```
parameters:

Value | Description | Valid values
------|-------------|-------------
radius|sharpening mask radius|0 to image size


*Sample Request:*
```PHP
print $image->fit(420, 420)->
              sharp(0.8)->
              getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,shrp_0.8/parrot.jpg
```

***

#### Unsharp Mask Filter ####

The Unsharp Mask, applies the filter using radius, amount & threshold parameters. (see table below)

```PHP
unsharp($radius = 0.5, $amount = 0.2, $threshold = 0.0)
```

optional parameters:

Value | Description | Valid values
------|-------------|-------------
radius|sharpening mask radius|0 to image size
amount|sharpening mask amount|0 to 100
threshold|shapening mask threshold|0 to 255

*Sample Request:*
```PHP
print $image->fit(420, 420)->
              unsharp(0.4, 0.2, 0.0)->
              getUrl();
```
would generate the URL: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,usm_0.40_0.20_0.00/parrot.jpg
```

#### JPEG Options ####

Extra options for JPEGs only:

option | parameter(s) | description
-------|------------|------------
baseline|-|An option for JPEGs only. Applies baseline encoding on the image, instead of progressive encoding.
quality|Integer (%)|Quality of the image, values between 0 and 100 

*Sample Requests:*
```PHP
print $image->fit(420, 420)->
              baseline()->
              getUrl();
```
would generate the URL:
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,bl/parrot.jpg
```
and:
```PHP
print $image->fit(420, 420)->
              quality(35)->
              getUrl();
```
would generate: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22,q_35/parrot.jpg
```

### Composite Image Manipulation ###

The Image API allows linking several manipulations one after the other. 

For example:

```PHP
print $image->fit(420, 420)->
              crop(60, 60, 300, 300)->
              unsharp()->
              getUrl();
```
would generate: 
```
http://media.wixapps.net/wixmedia-samples/images/cdf1ba9ec9554baca147db1cb6e011ec/v1/fit/w_420,h_420,rf_22/crop/x_60,y_60,w_300,h_300,usm_0.50_0.20_0.00/parrot.jpg
```

### Best Paractices ###

* When Fill or Fit are used it is recomended to apply Unsharp Mask filter on the result:
```PHP
print $image->fit(420, 420)->
              unsharp()->
              getUrl();
```

* If the image is in JPEG format, it is recomended to set qulity to 75:

```PHP
print $image->fit(420, 420)->
              unsharp()->
              quality(75)->
              getUrl();
```

The recomendations above, describe simple image optimization while maintaining good balance between output size and quality.