
<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <meta name="csrf-param" content="_csrf-frontend">
    <meta name="csrf-token" content="nh6yXRjaHO5UJm-8p7m8-jnNNul1oSa7feSgNvatjcz1UucFfZ5_pGZOI4vq7MqDTqZCrxqZENctvecGp-68qQ==">

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/clean-blog.css" rel="stylesheet">    <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
<body>



<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="/" title="Lyrics">Lyrics</a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/random-lyrics">Random Lyrics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/singers/hit">Singers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/songs/hit">Songs</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Header -->
<header class="masthead" style="background-image: url('/img/bg-<?=rand(1, 7)?>.jpg')">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                <div class="site-heading">
                    <h1><?= $title ?></h1>
                    <?php if (isset($description)) { ?>
                        <span class="subheading"><?= $description ?></span>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</header>


