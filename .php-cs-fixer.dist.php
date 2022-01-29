<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);
$config = new PhpCsFixer\Config();
$config->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder);

return $config;
