<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('site_helper');
?>
<?php $this->load->view('_header', ['title' => $title]) ?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div>
                <ul class="letters">
                    <?php foreach (getLetters() as $key => $val) { ?>
                        <li><a href="<?= base_url().'songs/'.$key ?>"><?= $val ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <ul class="list-group">
                <?php if (count($songs) > 0) { ?>
                    <?php foreach ($songs as $song) { ?>
                        <?php $song_url = base_url().$song->singer_slug.'/'.$song->slug.'-lyrics'; ?>
                        <li class="list-group-item">
                            <a href="<?= $song_url ?>"
                               title="<?= $song->title ?> Lyrics - <?= $song->singer_name ?>">
                                <?= $song->title ?> - <?= $song->singer_name ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-danger">There is no song.</p>
                <?php } ?>
            </ul>
            <div class="pagination">
                <?= $page_links ?>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('_footer') ?>
