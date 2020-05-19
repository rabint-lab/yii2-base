<?php

use rabint\attachment\models\Attachment;
use rabint\option\models\Option;

/* @var $this \yii\web\View */
/* @var $content string */

$opt = Option::get('general');
if (isset($opt[0])) {
    $opt = $opt[0];
}
$opt = array_merge(['subject' => '', 'title' => '', 'slogan' => '', 'page_img' => Yii::$app->Attachment->baseUrl . '/example/login@2x.jpg', 'desc' => ''], $opt);

$this->beginContent('@themeLayouts/base.php');
?>
<div id="page-container" class="main-content-boxed">

    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        <div class="bg-image" style="background-image: url('<?= Attachment::getUrlByPath($opt['page_img']); ?>');">
            <div class="row mx-0 bg-black-op">
                <div class="hero-static col-md-6 col-xl-8 d-none d-md-flex align-items-md-end">
                    <div class="p-30 invisible" data-toggle="appear">
                        <p class="font-size-h3 font-w600 text-white">
                            <?= $opt['subject']; ?>
                        </p>
                        <p class="font-italic text-white-op">
                            <?= $opt['slogan']; ?>
                        </p>
                    </div>
                </div>
                <div class="hero-static col-md-6 col-xl-4 d-flex align-items-center bg-white invisible" data-toggle="appear" data-class="animated fadeInRight">
                    <div class="content content-full">
                        <!-- Header -->
                        <div class="px-30 py-10">
                            <a class="link-effect font-w700" href="index.html">
                                <i class="fas fa-atom text-primary"></i>
                                <span class="font-size-xl text-dual-primary-dark">
                                    <?= $opt['title']; ?>
                                </span>
                            </a>
                            <br /><br /><br />
                            <h2 class="h5 font-w400 text-muted mb-0"><?= $this->title; ?></h2>
                        </div>
                        <!-- END Header -->

                        <div class="messages">
                            <?= $this->render('_element/_flashes'); ?>
                        </div>

                        <?= $content ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- END Page Content -->

    </main>
    <!-- END Main Container -->
</div>
<!-- END Page Container -->

<?php $this->endContent() ?>