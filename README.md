

![Dice Bear Image](Images/certificate%20of%20(1).png)
<div align="center">
 
![Packagist Downloads](https://img.shields.io/packagist/dt/jesse-richard/dice-bear-php.svg)
![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.2-brightgreen.svg)
[![Current Version](https://img.shields.io/packagist/v/jesse-richard/dice-bear-php.svg)](https://packagist.org/packages/your-vendor-name/your-package-name)
</div>


# Dice Bear for Php

This is a simple package that is aimed at making getting avatars from [Dice Bear](dicebear.com) easy in your php application.No official affiliation with the original dice bear


## Installation

To install the package all you need to do is run this command in your terminal.(assuming that you have composer installed)
```bash
 composer require jesse-richard/dice-bear-php
```


## How to use
Using diceBearPhp is pretty easy all use need to do is make use of the `NeedsAvatar` Trait and you'll be good to go.

```php
use JesseRichard\DiceBearPhp\Trait\NeedsAvatar;

class User {
    use NeedsAvatar;
}

```
## Utilities

This package provides some utilities to make the experience with the dice Bear easy. 

### Styles

Dice Bear offers a couple of styles that users can take advantage of and the package supports all the available styles. This package provides for three ways to make use of the Dice Bear Styles. You can have a look at all the styles [here](https://www.dicebear.com/styles/)

```php
// say the NeedsAvatar Trait is in use in the user class

$user = new User();

// You may choose to get a random style using the randomStyle() method like so
$user->randomStyle()

// You may choose to use the style as a method and optionally pass in the format you want it to be in using camelCase eg(big ears Neutral)
$user->bigEarsNeutral('svg')

// or 
$user->bigEarsNeutral()

```

### Format

The avatars that are provided by the Dice Bear Api are in different file formats. The package provides a method to allow you to get the file in the format that you find most convinient.
[Supported format](https://www.dicebear.com/how-to-use/http-api/#file-format)

```php
// getting the avatar in jpeg format
$user->format('jpeg')

// The alternative way of passing the format is while using the stlyle as a method as had been shown
$user->bigEarsNeutral('svg')

```

### Avatar alternatives

The avatars that are provided by Dice Bear are essentially image files. The package gives you the autonomy to choose what to do with them.

```php

// You may choose to get the image content and maybe do something with it
$user->getContent()


// You may choose to save the image locally and extract the path of the image
$user->saveImage('path/to/avatars_folders')->savedPath()


// You may only be intrested in the Url of the image
$user->url()

```
## Contributions

Contributions are highly welcomed. Feel free to fork the repo, play around to make it better.In case of any issues feel free to open an issue and i'll be right to it.A star wouldn't hurt.😉
