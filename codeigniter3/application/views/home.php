<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('site_helper');
?>
<?php $this->load->view('_header', ['title' => 'Lyrics', 'description' => 'A Sample Project for Testing PHP Frameworks']) ?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <?php foreach ($songs as $song) { ?>
                <div class="post-preview">
                    <?php $song_url = base_url().$song->singer_slug.'/'.$song->slug.'-lyrics'; ?>

                    <a href="<?= $song_url ?>" title="<?= $song->title ?> Lyrics - <?= $song->singer_name ?>">
                        <h2 class="post-title">
                            <?= $song->title ?> - <?= $song->singer_name ?>
                        </h2>
                    </a>
                    <p>
                        <?= getPreviewLyrics($song->lyrics) ?>

                        <a class="more" href="<?= $song_url ?>" title="<?= $song->title ?> Lyrics - <?= $song->singer_name ?>">Read More <i class="fas fa-angle-double-right"></i></a>
                    </p>
                    <p class="post-meta">Posted on <?= getPostedDate($song->created_at) ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
</div>


<?php $this->load->view('_footer') ?>
