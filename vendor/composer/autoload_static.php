<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit5ebf13c29a26ca2932c45c146a7b0ecf
{
    public static $prefixesPsr0 = array (
        'c' => 
        array (
            'cogpowered\\FineDiff' => 
            array (
                0 => __DIR__ . '/..' . '/cogpowered/finediff/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit5ebf13c29a26ca2932c45c146a7b0ecf::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}