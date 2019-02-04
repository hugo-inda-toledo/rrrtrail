<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'Bake' => $baseDir . '/vendor/cakephp/bake/',
        'CakeExcel' => $baseDir . '/vendor/dakota/cake-excel/',
        'CakePdf' => $baseDir . '/vendor/friendsofcake/cakepdf/',
        'Cewi/Excel' => $baseDir . '/vendor/Cewi/Excel/',
        'DebugKit' => $baseDir . '/vendor/cakephp/debug_kit/',
        'Management' => $baseDir . '/plugins/Management/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/',
        'Retailers' => $baseDir . '/plugins/Retailers/',
        'Robotusers/Excel' => $baseDir . '/vendor/robotusers/cakephp-excel/',
        'SendgridEmail' => $baseDir . '/vendor/iandenh/cakephp-sendgrid/',
        'Suppliers' => $baseDir . '/plugins/Suppliers/',
        'WyriHaximus/TwigView' => $baseDir . '/vendor/wyrihaximus/twig-view/'
    ]
];