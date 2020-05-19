<?php

if (\rabint\helpers\user::isGuest()) {
    ?>

    <!-- User Dropdown -->
    <div class="btn-header float-left">
        <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user d-sm-none"></i>
            <span class="d-none d-sm-inline-block"><?= \Yii::t('app', 'کاربر میهمان'); ?></span>
            <i class="fas fa-angle-down"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
            <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase"><?= \Yii::t('rabint', 'منوی کاربری'); ?></h5>
            <a class="dropdown-item" href="<?= \rabint\helpers\uri::to(['/user/sign-in/login']); ?>">
                <i class="si si-login ml-2"></i> <?= \Yii::t('rabint', 'ورود به پروفایل کاربری'); ?>
            </a>
            <div class="dropdown-divider"></div>

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="dropdown-item" href="<?= \rabint\helpers\uri::to(['/user/sign-in/signup']); ?>">
                <i class="si si-user ml-2"></i> <?= \Yii::t('rabint', 'ثبت نام'); ?>
            </a>
        </div>
    </div>
    <!-- END User Dropdown -->
<?php
} else {

    ?>
    <!-- User Dropdown -->
    <div class="btn-header float-left">
        <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user d-sm-none"></i>
            <span class="d-none d-sm-inline-block"><?= \rabint\helpers\user::name(); ?></span>
            <i class="fas fa-angle-down"></i>
        </button>
        <div class="dropdown-menu dropdown-menu-right min-width-200" aria-labelledby="page-header-user-dropdown">
            <h5 class="h6 text-center py-10 mb-5 border-b text-uppercase"><?= \Yii::t('rabint', 'منوی کاربری'); ?></h5>
            <a class="dropdown-item" href="<?= \rabint\helpers\uri::to(['/user/default/profile']); ?>">
                <i class="si si-user ml-2"></i> <?= \Yii::t('rabint', 'Profile'); ?>
            </a>
            <!-- <a class="dropdown-item d-flex align-items-center justify-content-between" href="be_pages_generic_inbox.html">
             <span><i class="si si-envelope-open ml-2"></i> Inbox</span>
             <span class="badge badge-primary">3</span>
         </a> -->
            <!-- <a class="dropdown-item" href="be_pages_generic_invoice.html">
             <i class="si si-note ml-2"></i> Invoices
         </a> -->
            <div class="dropdown-divider"></div>

            <!-- Toggle Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <a class="dropdown-item" href="<?= \rabint\helpers\uri::to(['/user/default/index']); ?>">
                <i class="si si-wrench ml-2"></i> <?= \Yii::t('rabint', 'Account'); ?>
            </a>
            <!-- END Side Overlay -->

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="<?= \rabint\helpers\uri::to(['/user/sign-in/logout']); ?>">
                <i class="si si-logout ml-2"></i> <?= \Yii::t('rabint', 'Logout'); ?>
            </a>
        </div>
    </div>
    <!-- END User Dropdown -->


<?php } ?>