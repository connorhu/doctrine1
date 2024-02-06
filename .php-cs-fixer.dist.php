<?php

$finder = PhpCsFixer\Finder::create()
    ->ignoreVCSIgnored(true)
    ->in(__DIR__.'/lib')
    ->in(__DIR__.'/tests')
    ->append(array(__FILE__))
    // Exclude generated files (single files)
    ->notPath('should-be-ignored.php')
;

$config = new PhpCsFixer\Config();
$config->setRules(array(
    '@PhpCsFixer' => true,
    '@Symfony' => true,
    'array_syntax' => array(
        'syntax' => 'short',
    ),
    'method_argument_space' => array(
        'on_multiline' => 'ensure_fully_multiline',
    ),
    'general_phpdoc_annotation_remove' => array(
        'annotations' => array('version', 'license', 'since'),
    ),
    'phpdoc_no_package' => true,
    'phpdoc_separation' => array(
        'groups' => array(
            array(
                'author',
            ),
            array(
                'deprecated',
                'see',
            ),
            array(
                'param',
                'return',
                'throws',
            ),
        ),
    ),
))
    ->setCacheFile('.php-cs-fixer.cache')
    ->setFinder($finder)
;

return $config;
