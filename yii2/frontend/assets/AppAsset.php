<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css',
        'css/clean-blog.min.css',
        'css/site.css',
    ];
    public $js = [
        'js/jquery.min.js',
        'js/bootstrap.bundle.min.js',
        'js/clean-blog.min.js',
    ];
    public $depends = [];
}
