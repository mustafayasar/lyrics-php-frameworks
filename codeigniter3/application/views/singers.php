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
                        <li><a href="<?= base_url().'singers/'.$key ?>"><?= $val ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <ul class="list-group">
                <?php if (count($singers) > 0) { ?>
                    <?php foreach ($singers as $singer) { ?>
                        <li class="list-group-item">
                            <a href="<?= base_url().$singer->slug.'-songs' ?>"
                               title="<?= $singer->name ?> Songs"><?= $singer->name ?></a>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-danger">There is no singer.</p>
                <?php } ?>
            </ul>
            <div class="pagination">
                <?= $page_links ?>
            </div>
        </div>
    </div>
</div>


<?php $this->load->view('_footer') ?>
