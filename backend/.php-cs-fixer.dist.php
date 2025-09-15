<?php

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ])
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,               // PSR-12 コーディング規約に準拠
        'array_syntax' => ['syntax' => 'short'], // [] 配列記法を使う
        'single_quote' => true,         // 文字列はシングルクォート
        'no_unused_imports' => true,    // 使ってない use を削除
    ])
    ->setFinder($finder);
