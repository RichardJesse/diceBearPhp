<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5dce98ddd435a729d7cb974805dbbf52
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'JesseRichard\\DiceBearPhp\\' => 25,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'JesseRichard\\DiceBearPhp\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit5dce98ddd435a729d7cb974805dbbf52::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit5dce98ddd435a729d7cb974805dbbf52::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit5dce98ddd435a729d7cb974805dbbf52::$classMap;

        }, null, ClassLoader::class);
    }
}
