<?php
$header = <<<'EOF'
##################################################################################################
# ------------Oooo---
# -----------(----)---
# ------------)--/----
# ------------(_/-
# ----oooO----
# ----(---)----
# -----\--(--
# ------\_)-
# ----
#     Yprisoner <yyprisoner@gmail.com>
#
#                            ------
#    「 涙の雨が頬をたたくたびに美しく 」
##################################################################################################
EOF;
return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'header_comment' => [
            'commentType' => 'PHPDoc',
            'header' => $header,
            'separate' => 'none'
        ],
        'array_syntax' => [
            'syntax' => 'short'
        ],
        'single_quote' => true,
        'class_attributes_separation' => true,
        'self_accessor'  => true,
        'no_empty_statement' => true,
        'no_unused_imports' => true,
        'standardize_not_equals' => true,
        'no_leading_namespace_whitespace' => true,
        'no_extra_consecutive_blank_lines' => true,
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            ->in(__DIR__)
    )
    ->setUsingCache(false);
