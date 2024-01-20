<?php

// autoload_psr4.php @generated by Composer

$vendorDir = dirname(__DIR__);
$baseDir = dirname($vendorDir);

return array(
    'services\\' => array($baseDir . '/src/services'),
    'routes\\' => array($baseDir . '/src/routes'),
    'repositoris\\' => array($baseDir . '/src/repositoris'),
    'models\\' => array($baseDir . '/src/models'),
    'lib\\' => array($baseDir . '/src/lib'),
    'controllers\\' => array($baseDir . '/src/controllers'),
    'Symfony\\Polyfill\\Php80\\' => array($vendorDir . '/symfony/polyfill-php80'),
    'Symfony\\Polyfill\\Mbstring\\' => array($vendorDir . '/symfony/polyfill-mbstring'),
    'Symfony\\Polyfill\\Ctype\\' => array($vendorDir . '/symfony/polyfill-ctype'),
    'PhpOption\\' => array($vendorDir . '/phpoption/phpoption/src/PhpOption'),
    'GrahamCampbell\\ResultType\\' => array($vendorDir . '/graham-campbell/result-type/src'),
    'Firebase\\JWT\\' => array($vendorDir . '/firebase/php-jwt/src'),
    'Dotenv\\' => array($vendorDir . '/vlucas/phpdotenv/src'),
    'Curso\\' => array($baseDir . '/src'),
);