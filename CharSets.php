<?php
    $lowercase  = 'abcdefghijklmnopqrstuvwxyz';
    $uppercase  = strtoupper($lowercase);
    $digits     = '01234567890-';
    $whitespace = " \n\x09\r";
    
    $charSets['UC'][0] = $uppercase;
    $charSets['UC'][1] = $uppercase.$digits.'_';
    $charSets['LC'][0] = $lowercase;
    $charSets['LC'][1] = $lowercase.$digits.'_';
    $charSets['VC'][0] = $lowercase;
    $charSets['VC'][1] = $lowercase.$uppercase.'_';
    $charSets['AC'][0] = $lowercase.$uppercase.$digits.'_';
    $charSets['AC'][1] = $charSets['AC'][0];
    $charSets['WS'][0] = $whitespace;
?>