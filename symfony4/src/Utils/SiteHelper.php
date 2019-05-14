<?php
namespace App\Utils;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SiteHelper extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('getLyrics', array($this, 'getLyrics')),
            new TwigFunction('getPreviewLyrics', array($this, 'getPreviewLyrics')),
            new TwigFunction('getPostedDate', array($this, 'getPostedDate')),
        );
    }

    /**
     * @param $lyrics
     *
     * @return string
     */
    public function getPreviewLyrics($lyrics)
    {
        $lyrics     = nl2br($lyrics);

        $lyrics_arr = explode('<br />', $lyrics);

        $lyrics_arr = array_filter(array_slice($lyrics_arr, 0, 3));

        return implode('<br />', $lyrics_arr).'...';
    }

    /**
     * @param $lyrics
     *
     * @return string
     */
    public function getLyrics($lyrics)
    {
        return nl2br($lyrics);
    }

    /**
     * @param $timestamp
     *
     * @return string
     */
    public function getPostedDate($timestamp)
    {
        return date('d/m/Y', $timestamp);
    }
}