<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = (new Finder)
    ->exclude('bootstrap/cache')
    ->exclude('node_modules')
    ->exclude('storage')
    ->exclude('vendor')
    ->in(__DIR__)
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config)
    ->setRules([
        '@PSR12' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'no_unused_imports' => true,
    ])
    ->setFinder($finder);
