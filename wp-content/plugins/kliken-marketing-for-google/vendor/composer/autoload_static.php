<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitab4d7ed8498b17e5db5a30f4153069b4
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Kliken\\WcPlugin\\Helper' => __DIR__ . '/../..' . '/classes/class-helper.php',
        'Kliken\\WcPlugin\\Message' => __DIR__ . '/../..' . '/classes/class-message.php',
        'Kliken\\WcPlugin\\Plugin' => __DIR__ . '/../..' . '/classes/class-plugin.php',
        'Kliken\\WcPlugin\\REST_Misc_Controller' => __DIR__ . '/../..' . '/classes/class-rest-misc-controller.php',
        'Kliken\\WcPlugin\\REST_Orders_Controller' => __DIR__ . '/../..' . '/classes/class-rest-orders-controller.php',
        'Kliken\\WcPlugin\\REST_Products_Controller' => __DIR__ . '/../..' . '/classes/class-rest-products-controller.php',
        'Kliken\\WcPlugin\\WC_Integration' => __DIR__ . '/../..' . '/classes/class-wc-integration.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInitab4d7ed8498b17e5db5a30f4153069b4::$classMap;

        }, null, ClassLoader::class);
    }
}
