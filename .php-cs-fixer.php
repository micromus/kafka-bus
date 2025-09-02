<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__ . '/src/');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        '@PSR12' => true,
        '@PHP82Migration' => true,
        'control_structure_continuation_position' => [
            'position' => 'next_line',
        ],
        'native_function_invocation' => [
            'scope' => 'all',
            'strict' => true,
        ],
    ])
    ->setFinder($finder);
