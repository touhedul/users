<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e00012b9a9a555bf527dab7d80f507d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Properos\\Users\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Properos\\Users\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1e00012b9a9a555bf527dab7d80f507d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1e00012b9a9a555bf527dab7d80f507d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
