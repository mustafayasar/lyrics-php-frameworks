<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->load->helper('site_helper');
?>
<?php $this->load->view('_header', ['title' => $song->singer_name.' - '.$song->title. ' Lyrics']) ?>

<!-- Main Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">

            <div class="post-preview">
                <p>
                    <?= $song->lyrics ?>
                </p>
                <p class="post-meta">Posted on <?= getPostedDate($song->created_at) ?></p>
                <p class="post-meta">Viewed <?= $song->hit ?> times</p>
            </div>

            <p>
                <a class="btn btn-primary" href="<?= base_url().$song->singer_slug.'-songs' ?>"><?= $song->singer_name ?> All Songs</a>
            </p>
        </div>
    </div>
</div>


<?php $this->load->view('_footer') ?>
