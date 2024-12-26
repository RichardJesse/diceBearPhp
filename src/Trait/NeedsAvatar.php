<?php

namespace JesseRichard\DiceBearPhp\Trait;


trait NeedsAvatar
{

    protected string $apiUrl = 'https://api.dicebear.com';
    protected string $version = '9.x';
    protected string $filePath = '';

    protected array $styles = [
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
        'bottts-neutrals',
        'big-smile',
        'glass',
        'identicon',
    ];

    protected string $url = '';
    protected string $name = '';
    protected string $size = '';
    protected bool $flip;
    protected array $formats = ['png', 'jpg', 'svg'];
    protected array $sizes = [32, 48, 64, 80, 96];
    protected string $style = '';
    protected string $format = 'png';

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
     * @param $style - valid dice bear style
     * 
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
            throw new \InvalidArgumentException("Style '{$format}' is not valid.");
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

    // public function flip($value = true){
    //     $this->flip = $value;
    //     $this->generate();
    //     return $this;
    // }

    /**
     * Build query parameters for the avatar URL.
     *
     * @return string|null
     */
    protected function buildQueryParameters(): ?string
    {

        $queryParams = [
            'seed' => !empty($this->name) ? $this->name : null,
            'size' => !empty($this->size) ? $this->size : null,
            'flip' => !empty($this->flip) ? $this->flip : null
        ];


        $queryParams = array_filter($queryParams);

        return !empty($queryParams) ? http_build_query($queryParams) : null;
    }

    /**
     * Generate the avatar URL.
     *
     * @return void
     */
    protected function generate(): void
    {
        $this->url = "{$this->apiUrl}/{$this->version}/{$this->style}/{$this->format}";
        $queryString = $this->buildQueryParameters();

        if ($queryString) {
            $this->url .= '?' . $queryString;
        }
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

    public function savedPath()
    {

        return $this->filePath;
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

    public function botttsNeutrals($format = '')
    {
        return $format == '' ? $this->style('bottts-neutrals')->format($this->format)
        : $this->style('bottts-neutrals')->format($format);
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
}
