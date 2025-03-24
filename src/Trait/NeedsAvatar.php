<?php

namespace JesseRichard\DiceBearPhp\Trait;


trait NeedsAvatar
{
    private string $apiUrl = 'https://api.dicebear.com';

    private string $version = '9.x';

    private string $filePath = '';

    private array $styles = [
        'adventurer',
        'adventurer-neutral',
        'avataaars',
        'avataaars-neutral',
        'big-ears',
        'big-ears-neutral',
        'croodles',
        'croodles-neutral',
        'fun-emoji',
        'identicon',
        'initials',
        'lorelei',
        'lorelei-neutral',
        'notionists',
        'notionists-neutral',
        'pixel-art',
        'pixel-art-neutral',
        'thumbs',
        'shapes',
        'icons',
        'initials',
        'rings',
        'miniavs',
        'open-peeps',
        'personas',
        'micah',
        'dylan',
        'bottts',
        'bottts-neutral',
        'big-smile',
        'glass',
        'identicon',
    ];

    private string $url = '';

    private string $name = '';

    private string $size = '';

    private bool $flip = false;

    private bool $clip = false;

    private array $formats = ['png', 'jpg', 'svg' , 'webp', 'avif', 'json'];

    private array $sizes = [32, 48, 64, 80, 96];

    private string $style = '';

    private string $format = 'png';

    private int $rotate = 0;

    private int $radius;

    private int $scale;

    private int $translateX;

    private int $translateY;

    private string $backgroundColor;

    private string $backgroundType;

    private string $backgroundRotation;

    /**
     * Set the name for the avatar seed.
     *
     * @param string $name
     * @return $this
     */
    public function seed(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Specify the style that the user can use
     * 
     * @param $style - valid dice bear style
     */
    public function style(string $style)
    {
        if (in_array($style, $this->styles)) {
            $this->style = $style;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Style '{$style}' is not valid.");
        }
        return $this;
    }

    /**
     * Sets the scale for the image that is produced
     * 
     * @param int $scale - the scale for the image that is produced
     * @return $this
     * 
     */
    public function scale(int $scale)
    {
        if ((0 <= $scale) && ($scale <= 200)) {
            $this->scale = $scale;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Scale '{$scale}' is not supported");
        }

        return $this;
    }

    /**
     * sets the radius for the image that is produced
     * 
     * @param int $radius - The radius for the image that is produced
     * @return $this
     * 
     */
    public function radius(int $radius)
    {
        if ((0 <= $radius) && ($radius <= 50)) {
            $this->radius = $radius;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Radius '{$radius}' is not supported");
        }

        return $this;
    }


    /**
     * Defines the translate for the image that is added
     * 
     * @param string $axis - This is the axis for which the translation takes place either X or Y
     * @param string $translate - The value for the translation between -100 to 100 on both axes
     * 
     * @return $this
     */
    public function translate(string $axis, int $translate)
    {
        if ((-100 <= $translate) && ($translate <= 100)) {
            if ($axis == 'X') {

                $this->translateX = $translate;
                $this->generate();
            } elseif ($axis == 'Y') {
                $this->translateY = $translate;
                $this->generate();
            } else {
                throw new \InvalidArgumentException("No such axis exists");
            }
        } else {
            throw new \InvalidArgumentException("Translate is out of range");
        }


        return $this;
    }

    /**
     * set the angle for rotation
     * 
     * @param int $angle - angle for which the image should be rotated
     * @return $this
     * 
     */
    public function rotate(int $angle)
    {
        if ($angle <= 360) {
            $this->rotate = $angle;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Angle '{$angle}' is past 360 degress");
        }
        return $this;
    }

    /**
     * Set the size of the image
     * 
     * @param int $size - ra valid size
     * @return $this
     * 
     */
    public function size(int $size)
    {
        if (in_array($size, $this->sizes)) {
            $this->size = $size;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Size '{$size}' is not supported.");
        }
        return $this;
    }

    /**
     * Sets the format 
     * 
     * @param $format - valid dice bear format
     * 
     */
    public function format(string $format)
    {
        if (in_array($format, $this->formats)) {
            $this->format = $format;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Format '{$format}' is not supported.");
        }
        return $this;
    }

    /**
     * Pick a random style for the avatar.
     *
     * @return $this
     */
    public function randomStyle(): self
    {
        $randomKey = array_rand($this->styles);
        $this->style = $this->styles[$randomKey];
        $this->generate();
        return $this;
    }

    /**
     * allows the user to set the flip option
     * 
     * @param bool $value - the value for the flip option
     * @return $this
     * 
     */
    public function flip(bool $value = true)
    {
        $this->flip = $value;
        $this->generate();
        return $this;
    }

    /**
     * setting the clip level of the image that is produced
     * 
     * @param bool $value - the value for the flip option
     * @return $this
     * 
     */
    public function clip(bool $value = true)
    {
        $this->clip = $value;
        $this->generate();
        return $this;
    }

    /**
     * Build query parameters for the avatar URL.
     *
     * @return string|null
     */
    protected function buildQueryParameters(array $additionalParams = []): ?string {
        
        $queryParams = [
            'seed' => !empty($this->name) ? $this->name : null,
            'size' => !empty($this->size) ? $this->size : null,
            'flip' => $this->flip === true ? 'true' : ($this->flip === false ? 'false' : null),
            'clip' => $this->clip === true ? 'true' : ($this->clip === false ? 'false' : null),
            'rotate' => !empty($this->rotate) ? $this->rotate : null,
            'radius' => !empty($this->radius) ? $this->radius : null,
            'scale' => !empty($this->scale) ? $this->scale : null,
            'translateX' => !empty($this->translateX) ? $this->translateX : null,
            'translateY' => !empty($this->translateY) ? $this->translateY : null,
            'backgroundColor' => !empty($this->backgroundColor) ? $this->backgroundColor : null,
            'backgroundType' => !empty($this->backgroundType) ? $this->backgroundType : null,
        ];
    
        
        $queryParams = array_merge($queryParams, array_filter($additionalParams));
    
        return !empty(array_filter($queryParams)) ? http_build_query(array_filter($queryParams)) : null;
    }

    /**
     * Generate the avatar URL.
     * @param array $additionalParams - optional additional parameters that need to be passed to the
     *
     * @return void
     */
    protected function generate($additionalParams = []): void
    {
        $this->url = "{$this->apiUrl}/{$this->version}/{$this->style}/{$this->format}";
        $queryString = $this->buildQueryParameters($additionalParams);

        if ($queryString) {
            $this->url .= '?' . $queryString;
        }
    }

    /**
     * Sets the background color of the image that is produced
     * 
     * @param String $backgroundColor - a valid color name
     * @return $this
     */
    public function backgroundColor(String $backgroundColor)
    {
        $colorHex = $this->colorNameToHex($backgroundColor);
        $this->backgroundColor = $colorHex;
        $this->generate();
        return $this;
    }

    /**
     * Sets the type of background that the image that is produced will have
     * @param String $backgroundType - a valid background type
     * 
     * @return $this
     * 
     */
    public function backgroundType(String $backgroundType)
    {
        $this->backgroundType = $backgroundType;
        $this->generate();
        return $this;
    }

    /**
     * Set the rotation for the background of the image
     * 
     * @param int angle - angle between 0 and 360 degrees
     * @return $this
     * 
     */
    public function backgroundRotation(int $angle)
    {

        if ($angle <= 360) {
            $this->backgroundRotation = $angle;
            $this->generate();
        } else {
            throw new \InvalidArgumentException("Angle '{$angle}' is past 360 degress");
        }
        return $this;
       
    }

    /**
     * Get the generated avatar URL.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Check if an image with the same content already exists.
     *
     * @param string $directory The directory where images are stored.
     * @param string $imageContent The content of the new image.
     * @return bool True if the file exists, false otherwise.
     */
    protected function findExistingImage(string $directory, string $imageContent): bool
    {

        $newImageHash = md5($imageContent);
        $existingFiles = glob($directory . '/*');

        foreach ($existingFiles as $file) {

            if (md5_file($file) === $newImageHash) {
                $this->filePath = $file;
                return true;
            }
        }

        return false;
    }


    /**
     * Save the avatar image to the user's file system.
     *
     * @param string $directory The directory where the image will be saved.
     * @return bool True if the image was saved successfully, false otherwise.
     */
    public function saveImage(string $directory): bool
    {

        $imageContent = $this->getContent();

        if ($imageContent === false) {
            throw new \RuntimeException("Failed to download image from URL: {$this->url}");
        }


        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }


        if ($this->findExistingImage($directory, $imageContent)) {
            return $this;
        }


        $uniqueFilename = uniqid('avatar_', true) . '.' . $this->format;


        $this->filePath = rtrim($directory, '/') . '/' . $uniqueFilename;


        if (file_put_contents($this->filePath, $imageContent) === false) {
            throw new \RuntimeException("Failed to save image to: {$this->filePath}");
        }

        return $this;
    }


    /**
     * Returns the image contents directly
     * 
     */
    public function getContent()
    {

        if (empty($this->url)) {
            $this->generate();
        }

        $imageContent = file_get_contents($this->url);

        return $imageContent;
    }

    /**
     * Returns the path that the image produced was saved in
     * @return $filePath
     * 
     */
    public function savedPath()
    {

        return $this->filePath;
    }

    /**
     * Binds options to the url
     * 
     * @param array $styleOptions - array of the style options to be added
     * @return $this
     * 
     */
    public function options(array $styleOptions)
    {
        
        $this->generate($styleOptions);
        return $this;

    }


    public function adventurer($format = '')
    {

        return $format == '' ?
            $this->style('adventurer')->format($this->format) :
            $this->style('adventurer')->format($format);
    }

    public function adventurerNeutral($format = '')
    {
        return $format == '' ? $this->style('adventurer-neutral')->format($this->format)
            : $this->style('adventurer-neutral')->format($format);
    }

    public function avataaars($format = '')
    {
        return $format == '' ? $this->style('avataaars')->format($this->format)
            : $this->style('avataaars')->format($format);
    }

    public function avataaarsNeutral($format = '')
    {
        return $format == '' ? $this->style('avataaars-neutral')->format($this->format)
            : $this->style('avataaars-neutral')->format($format);
    }

    public function bigEars($format = '')
    {
        return $format == '' ? $this->style('big-ears')->format($this->format)
            : $this->style('big-ears')->format($format);
    }

    public function bigEarsNeutral($format = '')
    {
        return $format == '' ? $this->style('big-ears-neutral')->format($this->format)
            : $this->style('big-ears-neutral')->format($format);
    }

    public function croodles($format = '')
    {
        return $format == '' ? $this->style('croodles')->format($this->format)
            : $this->style('croodles')->format($format);
    }

    public function croodlesNeutral($format = '')
    {
        return $format == '' ? $this->style('croodles-neutral')->format($this->format)
            : $this->style('croodles-neutral')->format($format);
    }

    public function funEmoji($format = '')
    {
        return $format == '' ? $this->style('fun-emoji')->format($this->format)
            : $this->style('fun-emoji')->format($format);
    }

    public function identicon($format = '')
    {
        return $format == '' ? $this->style('identicon')->format($this->format)
            : $this->style('identicon')->format($format);
    }

    public function initials($format = '')
    {
        return $format == '' ? $this->style('initials')->format($this->format)
            : $this->style('initials')->format($format);
    }

    public function lorelei($format = '')
    {
        return $format == '' ? $this->style('lorelei')->format($this->format)
            : $this->style('lorelei')->format($format);
    }

    public function loreleiNeutral($format = '')
    {
        return $format == '' ? $this->style('lorelei-neutral')->format($this->format)
            : $this->style('lorelei-neutral')->format($format);
    }

    public function notionists($format = '')
    {
        return $format == '' ? $this->style('notionists')->format($this->format)
            : $this->style('notionists')->format($format);
    }

    public function notionistsNeutral($format = '')
    {
        return $format == '' ? $this->style('notionists-neutral')->format($this->format)
            : $this->style('notionists-neutral')->format($format);
    }

    public function pixelArt($format = '')
    {
        return $format == '' ? $this->style('pixel-art')->format($this->format)
            : $this->style('pixel-art')->format($format);
    }

    public function pixelArtNeutral($format = '')
    {
        return $format == '' ? $this->style('pixel-art-neutral')->format($this->format)
            : $this->style('pixel-art-neutral')->format($format);
    }

    public function thumbs($format = '')
    {
        return $format == '' ? $this->style('thumbs')->format($this->format)
            : $this->style('thumbs')->format($format);
    }

    public function shapes($format = '')
    {
        return $format == '' ? $this->style('shapes')->format($this->format)
            : $this->style('shapes')->format($format);
    }

    public function icons($format = '')
    {
        return $format == '' ? $this->style('icons')->format($this->format)
            : $this->style('icons')->format($format);
    }

    public function rings($format = '')
    {
        return $format == '' ? $this->style('rings')->format($this->format)
            : $this->style('rings')->format($format);
    }

    public function miniavs($format = '')
    {
        return $format == '' ? $this->style('miniavs')->format($this->format)
            : $this->style('miniavs')->format($format);
    }

    public function openPeeps($format = '')
    {
        return $format == '' ? $this->style('open-peeps')->format($this->format)
            : $this->style('open-peeps')->format($format);
    }

    public function personas($format = '')
    {
        return $format == '' ? $this->style('personas')->format($this->format)
            : $this->style('personas')->format($format);
    }

    public function micah($format = '')
    {
        return $format == '' ? $this->style('micah')->format($this->format)
            : $this->style('micah')->format($format);
    }

    public function dylan($format = '')
    {
        return $format == '' ? $this->style('dylan')->format($this->format)
            : $this->style('dylan')->format($format);
    }

    public function bottts($format = '')
    {
        return $format == '' ? $this->style('bottts')->format($this->format)
            : $this->style('bottts')->format($format);
    }

    public function botttsNeutral($format = '')
    {
        return $format == '' ? $this->style('bottts-neutral')->format($this->format)
            : $this->style('bottts-neutral')->format($format);
    }

    public function bigSmile($format = '')
    {
        return $format == '' ? $this->style('big-smile')->format($this->format)
            : $this->style('big-smile')->format($format);
    }

    public function glass($format = '')
    {
        return $format == '' ? $this->style('glass')->format($this->format)
            : $this->style('glass')->format($format);
    }

    /**
     * Converts a color name to its hex equivalent
     * 
     * @param String $colorName - valid color name
     * @return $this
     * 
     */
    protected function colorNameToHex(String $colorName)
    {

        $colors  =  array(
            'aliceblue' => 'F0F8FF',
            'antiquewhite' => 'FAEBD7',
            'aqua' => '00FFFF',
            'aquamarine' => '7FFFD4',
            'azure' => 'F0FFFF',
            'beige' => 'F5F5DC',
            'bisque' => 'FFE4C4',
            'black' => '000000',
            'blanchedalmond ' => 'FFEBCD',
            'blue' => '0000FF',
            'blueviolet' => '8A2BE2',
            'brown' => 'A52A2A',
            'burlywood' => 'DEB887',
            'cadetblue' => '5F9EA0',
            'chartreuse' => '7FFF00',
            'chocolate' => 'D2691E',
            'coral' => 'FF7F50',
            'cornflowerblue' => '6495ED',
            'cornsilk' => 'FFF8DC',
            'crimson' => 'DC143C',
            'cyan' => '00FFFF',
            'darkblue' => '00008B',
            'darkcyan' => '008B8B',
            'darkgoldenrod' => 'B8860B',
            'darkgray' => 'A9A9A9',
            'darkgreen' => '006400',
            'darkgrey' => 'A9A9A9',
            'darkkhaki' => 'BDB76B',
            'darkmagenta' => '8B008B',
            'darkolivegreen' => '556B2F',
            'darkorange' => 'FF8C00',
            'darkorchid' => '9932CC',
            'darkred' => '8B0000',
            'darksalmon' => 'E9967A',
            'darkseagreen' => '8FBC8F',
            'darkslateblue' => '483D8B',
            'darkslategray' => '2F4F4F',
            'darkslategrey' => '2F4F4F',
            'darkturquoise' => '00CED1',
            'darkviolet' => '9400D3',
            'deeppink' => 'FF1493',
            'deepskyblue' => '00BFFF',
            'dimgray' => '696969',
            'dimgrey' => '696969',
            'dodgerblue' => '1E90FF',
            'firebrick' => 'B22222',
            'floralwhite' => 'FFFAF0',
            'forestgreen' => '228B22',
            'fuchsia' => 'FF00FF',
            'gainsboro' => 'DCDCDC',
            'ghostwhite' => 'F8F8FF',
            'gold' => 'FFD700',
            'goldenrod' => 'DAA520',
            'gray' => '808080',
            'green' => '008000',
            'greenyellow' => 'ADFF2F',
            'grey' => '808080',
            'honeydew' => 'F0FFF0',
            'hotpink' => 'FF69B4',
            'indianred' => 'CD5C5C',
            'indigo' => '4B0082',
            'ivory' => 'FFFFF0',
            'khaki' => 'F0E68C',
            'lavender' => 'E6E6FA',
            'lavenderblush' => 'FFF0F5',
            'lawngreen' => '7CFC00',
            'lemonchiffon' => 'FFFACD',
            'lightblue' => 'ADD8E6',
            'lightcoral' => 'F08080',
            'lightcyan' => 'E0FFFF',
            'lightgoldenrodyellow' => 'FAFAD2',
            'lightgray' => 'D3D3D3',
            'lightgreen' => '90EE90',
            'lightgrey' => 'D3D3D3',
            'lightpink' => 'FFB6C1',
            'lightsalmon' => 'FFA07A',
            'lightseagreen' => '20B2AA',
            'lightskyblue' => '87CEFA',
            'lightslategray' => '778899',
            'lightslategrey' => '778899',
            'lightsteelblue' => 'B0C4DE',
            'lightyellow' => 'FFFFE0',
            'lime' => '00FF00',
            'limegreen' => '32CD32',
            'linen' => 'FAF0E6',
            'magenta' => 'FF00FF',
            'maroon' => '800000',
            'mediumaquamarine' => '66CDAA',
            'mediumblue' => '0000CD',
            'mediumorchid' => 'BA55D3',
            'mediumpurple' => '9370D0',
            'mediumseagreen' => '3CB371',
            'mediumslateblue' => '7B68EE',
            'mediumspringgreen' => '00FA9A',
            'mediumturquoise' => '48D1CC',
            'mediumvioletred' => 'C71585',
            'midnightblue' => '191970',
            'mintcream' => 'F5FFFA',
            'mistyrose' => 'FFE4E1',
            'moccasin' => 'FFE4B5',
            'navajowhite' => 'FFDEAD',
            'navy' => '000080',
            'oldlace' => 'FDF5E6',
            'olive' => '808000',
            'olivedrab' => '6B8E23',
            'orange' => 'FFA500',
            'orangered' => 'FF4500',
            'orchid' => 'DA70D6',
            'palegoldenrod' => 'EEE8AA',
            'palegreen' => '98FB98',
            'paleturquoise' => 'AFEEEE',
            'palevioletred' => 'DB7093',
            'papayawhip' => 'FFEFD5',
            'peachpuff' => 'FFDAB9',
            'peru' => 'CD853F',
            'pink' => 'FFC0CB',
            'plum' => 'DDA0DD',
            'powderblue' => 'B0E0E6',
            'purple' => '800080',
            'red' => 'FF0000',
            'rosybrown' => 'BC8F8F',
            'royalblue' => '4169E1',
            'saddlebrown' => '8B4513',
            'salmon' => 'FA8072',
            'sandybrown' => 'F4A460',
            'seagreen' => '2E8B57',
            'seashell' => 'FFF5EE',
            'sienna' => 'A0522D',
            'silver' => 'C0C0C0',
            'skyblue' => '87CEEB',
            'slateblue' => '6A5ACD',
            'slategray' => '708090',
            'slategrey' => '708090',
            'snow' => 'FFFAFA',
            'springgreen' => '00FF7F',
            'steelblue' => '4682B4',
            'tan' => 'D2B48C',
            'teal' => '008080',
            'thistle' => 'D8BFD8',
            'tomato' => 'FF6347',
            'turquoise' => '40E0D0',
            'violet' => 'EE82EE',
            'wheat' => 'F5DEB3',
            'white' => 'FFFFFF',
            'whitesmoke' => 'F5F5F5',
            'yellow' => 'FFFF00',
            'yellowgreen' => '9ACD32'
        );

        $colorName = strtolower($colorName);
        if (isset($colors[$colorName])) {
            return ($colors[$colorName]);
        } else {
            return "not a valid color";
        }
    }

}
