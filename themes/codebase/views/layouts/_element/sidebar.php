<?php

use rabint\helpers\option;
use rabint\helpers\uri;
use rabint\helpers\user;

if (\rabint\helpers\user::isGuest()) {
    $userAvatar = Yii::$app->Attachment->baseUrl . '/example/avatars/avatar15.jpg';
    $userDisplayName = \Yii::t('app', 'کاربر میهمان');
} else {
    $userAvatar = Yii::$app->user->identity->userProfile->getAvatar(Yii::$app->Attachment->baseUrl . '/example/avatars/avatar15.jpg');
    $userDisplayName = \rabint\helpers\user::name();
}
?>



<!-- Sidebar -->
<!--
                Helper classes

                Adding .sidebar-mini-hide to an element will make it invisible (opacity: 0) when the sidebar is in mini mode
                Adding .sidebar-mini-show to an element will make it visible (opacity: 1) when the sidebar is in mini mode
                    If you would like to disable the transition, just add the .sidebar-mini-notrans along with one of the previous 2 classes

                Adding .sidebar-mini-hidden to an element will hide it when the sidebar is in mini mode
                Adding .sidebar-mini-visible to an element will show it only when the sidebar is in mini mode
                    - use .sidebar-mini-visible-b if you would like to be a block when visible (display: block)
            -->
<nav id="sidebar">
    <!-- Sidebar Content -->
    <div class="sidebar-content">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow px-15">
            <!-- Mini Mode -->
            <div class="content-header-section sidebar-mini-visible-b">
                <!-- Logo -->
                <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                    <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                </span>
                <!-- END Logo -->
            </div>
            <!-- END Mini Mode -->

            <!-- Normal Mode -->
            <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                <!-- Close Sidebar, Visible only on mobile screens -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                    <i class="fas fa-times text-danger"></i>
                </button>
                <!-- END Close Sidebar -->

                <!-- Logo -->
                <div class="content-header-item">
                    <a class="link-effect font-w700" href="<?= uri::home(true); ?>">
                        <i class="fas fa-atom text-primary"></i>
                        <span class="font-size-xl text-dual-primary-dark">
                            <?= option::get('general', 'subject', true, config('app_name')); ?>
                        </span>
                        <!-- <span class="font-size-xl text-primary"></span> -->
                    </a>
                </div>
                <!-- END Logo -->
            </div>
            <!-- END Normal Mode -->
        </div>
        <!-- END Side Header -->

        <!-- Side User -->
        <div class="content-side content-side-full content-side-user px-10 align-parent">
            <!-- Visible only in mini mode -->
            <div class="sidebar-mini-visible-b align-v animated fadeIn">
                <img class="img-avatar img-avatar32" src="<?= $userAvatar; ?>" alt="">
            </div>
            <!-- END Visible only in mini mode -->

            <!-- Visible only in normal mode -->
            <div class="sidebar-mini-hidden-b text-center">
                <a class="img-link" href="<?= \rabint\helpers\uri::to(['/user/default/profile']); ?>">
                    <img class="img-avatar" src="<?= $userAvatar; ?>" alt="">
                </a>
                <ul class="list-inline mt-10">
                    <li class="list-inline-item">
                        <a href="<?= \rabint\helpers\uri::to(['/user/default/profile']); ?>" class="link-effect text-dual-primary-dark font-size-xs font-w600 text-uppercase">
                            <?= $userDisplayName; ?>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                        <a class="link-effect text-dual-primary-dark" data-toggle="layout" data-action="sidebar_style_inverse_toggle" href="javascript:void(0)">
                            <i class="si si-drop"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <?php if (user::isGuest()) { ?>
                            <a class="link-effect text-dual-primary-dark" href="<?= \rabint\helpers\uri::to(['/user/sign-in/login']); ?>">
                                <i class="si si-login"></i>
                            </a>
                        <?php } else { ?>
                            <a class="link-effect text-dual-primary-dark" href="<?= \rabint\helpers\uri::to(['/user/sign-in/logout']); ?>">
                                <i class="si si-logout"></i>
                            </a>
                        <?php } ?>
                    </li>
                </ul>
            </div>
            <!-- END Visible only in normal mode -->
        </div>
        <!-- END Side User -->

        <!-- Side Navigation -->
        <div class="content-side content-side-full">

            <?= $this->render('_menu.php') ?>

        </div>
        <!-- END Side Navigation -->
    </div>
    <!-- Sidebar Content -->
</nav>
<!-- END Sidebar -->