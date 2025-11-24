<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        'phpdoc_no_empty_return' => false,
        'phpdoc_summary' => false,
        'yoda_style' => false,
        'single_quote' => true,
        'line_length' => ['max' => 120],
    ])
    ->setFinder($finder);
