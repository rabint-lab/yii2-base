<?php


\rabint\assets\AjaxCrudAsset::register($this);
\yii\widgets\PjaxAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@themeLayouts/base.php');
?>



   <!-- Page Container -->
        <!--
            Available classes for #page-container:

        GENERIC

            'enable-cookies'                            Remembers active color theme between pages (when set through color theme helper Template._uiHandleTheme())

        SIDEBAR & SIDE OVERLAY

            'sidebar-r'                                 Right Sidebar and left Side Overlay (default is left Sidebar and right Side Overlay)
            'sidebar-mini'                              Mini hoverable Sidebar (screen width > 991px)
            'sidebar-o'                                 Visible Sidebar by default (screen width > 991px)
            'sidebar-o-xs'                              Visible Sidebar by default (screen width < 992px)
            'sidebar-inverse'                           Dark themed sidebar

            'side-overlay-hover'                        Hoverable Side Overlay (screen width > 991px)
            'side-overlay-o'                            Visible Side Overlay by default

            'enable-page-overlay'                       Enables a visible clickable Page Overlay (closes Side Overlay on click) when Side Overlay opens

            'side-scroll'                               Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (screen width > 991px)

        HEADER

            ''                                          Static Header if no class is added
            'page-header-fixed'                         Fixed Header

        HEADER STYLE

            ''                                          Classic Header style if no class is added
            'page-header-modern'                        Modern Header style
            'page-header-inverse'                       Dark themed Header (works only with classic Header style)
            'page-header-glass'                         Light themed Header with transparency by default
                                                        (absolute position, perfect for light images underneath - solid light background on scroll if the Header is also set as fixed)
            'page-header-glass page-header-inverse'     Dark themed Header with transparency by default
                                                        (absolute position, perfect for dark images underneath - solid dark background on scroll if the Header is also set as fixed)

        MAIN CONTENT LAYOUT

            ''                                          Full width Main Content if no class is added
            'main-content-boxed'                        Full width Main Content with a specific maximum width (screen width > 1200px)
            'main-content-narrow'                       Full width Main Content with a percentage width (screen width > 1200px)
        -->
        <div id="page-container">
            
            <!-- Main Container -->
            <main id="main-container">


                <!-- Page Content -->
                <div class="messages">
                    <?=$this->render('_element/_flashes');?>
                </div>
                
                <!-- Page Content -->
                <div class="content">
                    <?=$content;?>
                </div>
                <!-- END Page Content -->

            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="opacity-0">
                <div class="content py-20 font-size-xs clearfix">
                    <div class="float-right">
                        <?= \Yii::t('rabint', 'تمامی حقوق این نرم افزار محفوظ می باشد ');?>
                    </div>
                    <div class="float-left">

                        <a class="font-w600" href="#" target="_blank"><i class="fas fa-atom text-pulse"></i>
                        <?= \Yii::t('rabint', 'رابینت');?>
                    </a>
                    </div>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!--
            Codebase JS Core

            Vital libraries and plugins used in all pages. You can choose to not include this file if you would like
            to handle those dependencies through webpack. Please check out assets/_es6/main/bootstrap.js for more info.

            If you like, you could also include them separately directly from the assets/js/core folder in the following
            order. That can come in handy if you would like to include a few of them (eg jQuery) from a CDN.

            assets/js/core/jquery.min.js
            assets/js/core/bootstrap.bundle.min.js
            assets/js/core/simplebar.min.js
            assets/js/core/jquery-scrollLock.min.js
            assets/js/core/jquery.appear.min.js
            assets/js/core/jquery.countTo.min.js
            assets/js/core/js.cookie.min.js
        -->
        <!-- <script src="assets/js/codebase.core.min.js"></script> -->

        <!--
            Codebase JS

            Custom functionality including Blocks/Layout API as well as other vital and optional helpers
            webpack is putting everything together at assets/_es6/main/app.js
        -->
        <!-- <script src="assets/js/codebase.app.min.js"></script> -->

        <!-- Page JS Plugins -->
        <!-- <script src="assets/js/plugins/chartjs/Chart.bundle.min.js"></script> -->

        <!-- Page JS Code -->
        <!-- <script src="assets/js/pages/be_pages_dashboard.min.js"></script> -->



<?php \yii\bootstrap4\Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",
    "size" => "modal-lg",
]) ?>
<?php \yii\bootstrap4\Modal::end(); ?>

<?php $this->endContent() ?>
