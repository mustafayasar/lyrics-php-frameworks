<?php

function getPreviewLyrics($lyrics)
{
    $lyrics     = str_ireplace(['<br/>', '<br>', '<br >'], '<br />', $lyrics);

    $lyrics_arr = explode('<br />', $lyrics);

    $lyrics_arr = array_filter(array_slice($lyrics_arr, 0, 3));

    return implode('<br />', $lyrics_arr).'...';
}

function getPostedDate($timestamp)
{
    return date('d/m/Y', $timestamp);
}

function getLetters()
{
    return ['hit' => 'Hit', 'a'=>'A', 'b'=>'B', 'c'=>'C', 'd'=>'D', 'e'=>'E', 'f'=>'F', 'g'=>'G', 'h'=>'H', 'i'=>'I', 'j'=>'J', 'k'=>'K', 'l'=>'L', 'm'=>'M', 'n'=>'N', 'o'=>'O', 'p'=>'P', 'q'=>'Q', 'r'=>'R', 's'=>'S', 't'=>'T', 'u'=>'U', 'v'=>'V', 'w'=>'W', 'x'=>'X', 'y'=>'Y', 'z'=>'Z', '09'=>'0-9'];
}