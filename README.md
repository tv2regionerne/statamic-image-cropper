# Image Cropper

<!-- statamic:hide -->
[![Packagist](https://img.shields.io/packagist/v/tv2regionerne/statamic-image-cropper.svg?style=flat-square)](https://packagist.org/packages/tv2regionerne/statamic-image-cropper)
[![Downloads](https://img.shields.io/packagist/dt/tv2regionerne/statamic-image-cropper.svg?style=flat-square)](https://packagist.org/packages/tv2regionerne/statamic-image-cropper)
[![License](https://img.shields.io/github/license/tv2regionerne/statamic-image-cropper.svg?style=flat-square)](LICENSE)
[![Supported Statamic version](https://img.shields.io/badge/Statamic-4.0%2B-FF269E)](https://github.com/statamic/cms/releases)
<!-- /statamic:hide -->

This addon adds an image cropping fieldtype, for those cases where focal point cropping just isn't enough.

## Features

This fieldtype can be found under the "Media" category, and requires you to set `Image Source Field` and `Dimensions`.

<img src="images/fieldtype-setup.png" />

If you rather want to do it YAML style, this is it:
```yaml
handle: crops
field:
  type: image_cropper
  display: Crops
  show_details: true
  source: image
  dimensions:
    '16_9': 16/9
    '1_1': 1/1
    freeform: Freeform
```

## How to Install

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require tv2regionerne/statamic-image-cropper
```

## How to Use

After setting up the fieldtype and have chosen an image, you get the option to select a dimension to crop:
<img src="images/fieldtype-buttons.png" />

Selecting a dimension will trigger the cropping overlay, and once you're happy with your crop you press `Save`:
<img src="images/fieldtype-cropping.png" />

In a template you can then use the crop as so:
```antlers
<img src="{{ glide:image :crop="crops:16_9:crop" }}" />
```
This line of code means that you're applying a cropping action to your image. The part :crop="crops:16_9:crop" tells the system two main things:
* `crops` is a reference (or "handle") to your specific Image Cropper setting. Remember, you should replace crops with whatever name you've given to your Image Cropper field.
* `16_9` indicates the aspect ratio for the crop. In this case, it's set to 16:9, which is a common widescreen format. You can change this to any other ratio you need for your image.

Enjoy cropping away!
