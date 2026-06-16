<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);
$config = new PhpCsFixer\Config();
$config->setRules([
        '@Symfony' => true,
        'trailing_comma_in_multiline' => false, // Not compatible with PHP 7.2.
    ])
    ->setFinder($finder);

return $config;
