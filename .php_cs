<?php

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setFinder(
            PhpCsFixer\Finder::create()
                ->in(__DIR__)
                ->append([__FILE__])
    )
;
