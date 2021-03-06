<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit32e99d71a66985f9be28f7e02c589e56
{
    public static $prefixLengthsPsr4 = array (
        'd' => 
        array (
            'dgr\\nohup\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'dgr\\nohup\\' => 
        array (
            0 => __DIR__ . '/..' . '/dgr/nohup/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit32e99d71a66985f9be28f7e02c589e56::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit32e99d71a66985f9be28f7e02c589e56::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
