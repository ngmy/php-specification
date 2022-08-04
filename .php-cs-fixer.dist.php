<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer' => true,
        // NOTE: To avoid the following issue.
        //       https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/4157
        'return_assignment' => false,
    ])
    ->setFinder($finder)
;
