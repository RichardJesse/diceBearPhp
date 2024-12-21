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
        'thumbs'
    ];

    protected string $url = '';
    protected string $name = '';
    protected string $size = '';
    protected bool $flip;
    protected array $formats = ['png', 'jpg'];
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
     * Magic method to handle dynamic style methods.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        $style = $this->camelToHyphen($method);
        if (in_array($style, $this->styles)) {
            if (isset($args[0])) {
                return $this->style($style)->format($args[0]);
            }
            return $this->style($style);
        }

        throw new \BadMethodCallException("Method '{$method}' does not exist.");
    }

    /**
     * Convert a camelCase string to a hyphenated string.
     *
     * @param string $string
     * @return string
     */
    protected function camelToHyphen(string $string): string
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $string));
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
            return true;
        }


        $uniqueFilename = uniqid('avatar_', true) . '.' . $this->format;


        $this->filePath = rtrim($directory, '/') . '/' . $uniqueFilename;


        if (file_put_contents($this->filePath, $imageContent) === false) {
            throw new \RuntimeException("Failed to save image to: {$this->filePath}");
        }

        return true;
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
}
