<?php
namespace App\Utils;

class SiteHelper
{
    /**
     * @param $lyrics
     *
     * @return string
     */
    public static function getPreviewLyrics($lyrics)
    {
        $lyrics     = str_ireplace(['<br/>', '<br>', '<br >'], '<br />', $lyrics);

        $lyrics_arr = explode('<br />', $lyrics);

        $lyrics_arr = array_filter(array_slice($lyrics_arr, 0, 3));

        return implode('<br />', $lyrics_arr).'...';
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public static function getPostedDate($timestamp)
    {
        return date('d/m/Y', $timestamp);
    }
}