<?php
return;

if(\rabint\helpers\user::isGuest()){
    $userAvatar = Yii::$app->Attachment->baseUrl . '/example/avatars/avatar15.jpg';
    $userDisplayName = '';
}else{
    $userAvatar =Yii::$app->user->identity->userProfile->getAvatar(Yii::$app->Attachment->baseUrl . '/example/avatars/avatar15.jpg');
    $userDisplayName = \rabint\helpers\user::name();
}
?>

<!-- Side Overlay-->
<aside id="side-overlay">
    <!-- Side Header -->
    <div class="content-header content-header-fullrow">
        <div class="content-header-section align-parent">
            <!-- Close Side Overlay -->
            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
            <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout" data-action="side_overlay_close">
                <i class="fas fa-times text-danger"></i>
            </button>
            <!-- END Close Side Overlay -->

            <!-- User Info -->
            <div class="content-header-item">
                <a class="img-link mr-5" href="be_pages_generic_profile.html">
                    <img class="img-avatar img-avatar32" src="<?= $userAvatar; ?>" alt="">
                </a>
                <a class="align-middle link-effect text-primary-dark font-w600" href="be_pages_generic_profile.html"><?= $userDisplayName; ?></a>
            </div>
            <!-- END User Info -->
        </div>
    </div>
    <!-- END Side Header -->

    <!-- Side Content -->
    <div class="content-side">
        <!-- Search -->
        <div class="card block pull-t pull-r-l">
            <div class="card-body block-content block-content-full block-content-sm bg-body-light">
                <form action="be_pages_generic_search.html" method="post">
                    <div class="input-group">
                        <input type="text" class="form-control" id="side-overlay-search" name="side-overlay-search" placeholder="جستجو">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary px-10">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Search -->

        <!-- Mini Stats -->
        <div class="card block pull-r-l">
            <div class="card-body block-content block-content-full block-content-sm bg-body-light">
                <div class="row">
                    <div class="col-4">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Clients</div>
                        <div class="font-size-h4">460</div>
                    </div>
                    <div class="col-4">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Sales</div>
                        <div class="font-size-h4">728</div>
                    </div>
                    <div class="col-4">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Earnings</div>
                        <div class="font-size-h4">$7,860</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Mini Stats -->

        <!-- Friends -->
        <div class="card block pull-r-l">
            <div class="card-header block-header bg-body-light">
                <h3 class="block-title"><i class="fas fa-fw fa-users font-size-default mr-5"></i>Friends</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="card-body block-content">
                <ul class="nav-users push">
                    <li>
                        <a href="be_pages_generic_profile.html">
                            <img class="img-avatar" src="assets/media/avatars/avatar8.jpg" alt="">
                            <i class="fas fa-circle text-success"></i> Betty Kelley
                            <div class="font-w400 font-size-xs text-muted">Photographer</div>
                        </a>
                    </li>
                    <li>
                        <a href="be_pages_generic_profile.html">
                            <img class="img-avatar" src="assets/media/avatars/avatar14.jpg" alt="">
                            <i class="fas fa-circle text-success"></i> Wayne Garcia
                            <div class="font-w400 font-size-xs text-muted">Web Designer</div>
                        </a>
                    </li>
                    <li>
                        <a href="be_pages_generic_profile.html">
                            <img class="img-avatar" src="assets/media/avatars/avatar5.jpg" alt="">
                            <i class="fas fa-circle text-warning"></i> Melissa Rice
                            <div class="font-w400 font-size-xs text-muted">UI Designer</div>
                        </a>
                    </li>
                    <li>
                        <a href="be_pages_generic_profile.html">
                            <img class="img-avatar" src="assets/media/avatars/avatar10.jpg" alt="">
                            <i class="fas fa-circle text-danger"></i> Scott Young
                            <div class="font-w400 font-size-xs text-muted">Copywriter</div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END Friends -->

        <!-- Activity -->
        <div class="card block pull-r-l">
            <div class="card-header block-header bg-body-light">
                <h3 class="block-title">
                    <i class="far fa-fw fa-clock font-size-default mr-5"></i>Activity
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                        <i class="si si-refresh"></i>
                    </button>
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="card-body block-content">
                <ul class="list list-activity">
                    <li>
                        <i class="si si-wallet text-success"></i>
                        <div class="font-w600">+$29 New sale</div>
                        <div>
                            <a href="javascript:void(0)">Admin Template</a>
                        </div>
                        <div class="font-size-xs text-muted">5 min ago</div>
                    </li>
                    <li>
                        <i class="si si-close text-danger"></i>
                        <div class="font-w600">Project removed</div>
                        <div>
                            <a href="javascript:void(0)">Best Icon Set</a>
                        </div>
                        <div class="font-size-xs text-muted">26 min ago</div>
                    </li>
                    <li>
                        <i class="si si-pencil text-info"></i>
                        <div class="font-w600">You edited the file</div>
                        <div>
                            <a href="javascript:void(0)">
                                <i class="fas fa-file-alt-o"></i> Docs.doc
                            </a>
                        </div>
                        <div class="font-size-xs text-muted">3 hours ago</div>
                    </li>
                    <li>
                        <i class="si si-plus text-success"></i>
                        <div class="font-w600">New user</div>
                        <div>
                            <a href="javascript:void(0)">StudioWeb - View Profile</a>
                        </div>
                        <div class="font-size-xs text-muted">5 hours ago</div>
                    </li>
                    <li>
                        <i class="si si-wrench text-warning"></i>
                        <div class="font-w600">App v5.5 is available</div>
                        <div>
                            <a href="javascript:void(0)">Update now</a>
                        </div>
                        <div class="font-size-xs text-muted">8 hours ago</div>
                    </li>
                    <li>
                        <i class="si si-user-follow text-pulse"></i>
                        <div class="font-w600">+1 Friend Request</div>
                        <div>
                            <a href="javascript:void(0)">Accept</a>
                        </div>
                        <div class="font-size-xs text-muted">1 day ago</div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END Activity -->

        <!-- Profile -->
        <div class="card block pull-r-l">
            <div class="card-header block-header bg-body-light">
                <h3 class="block-title">
                    <i class="fas fa-fw fa-edit font-size-default mr-5"></i>Profile
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="card-body block-content">
                <form action="be_pages_dashboard.html" method="post" onsubmit="return false;">
                    <div class="form-group mb-15">
                        <label for="side-overlay-profile-name">Name</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="side-overlay-profile-name" name="side-overlay-profile-name" placeholder="Your name.." value="John Smith">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-15">
                        <label for="side-overlay-profile-email">Email</label>
                        <div class="input-group">
                            <input type="email" class="form-control" id="side-overlay-profile-email" name="side-overlay-profile-email" placeholder="Your email.." value="john.smith@example.com">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-envelope"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-15">
                        <label for="side-overlay-profile-password">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="side-overlay-profile-password" name="side-overlay-profile-password" placeholder="New Password..">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-asterisk"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-15">
                        <label for="side-overlay-profile-password-confirm">Confirm New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="side-overlay-profile-password-confirm" name="side-overlay-profile-password-confirm" placeholder="Confirm New Password..">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-asterisk"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-block btn-alt-primary">
                                <i class="fas fa-sync-alt mr-5"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Profile -->

        <!-- Settings -->
        <div class="card block pull-r-l">
            <div class="card-header block-header bg-body-light">
                <h3 class="block-title">
                    <i class="fas fa-fw fa-wrench font-size-default mr-5"></i>Settings
                </h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-toggle="block-option" data-action="content_toggle"></button>
                </div>
            </div>
            <div class="card-body block-content">
                <div class="row gutters-tiny">
                    <div class="col-6">
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="side-overlay-settings-status" name="side-overlay-settings-status" value="1" checked>
                            <label class="custom-control-label" for="side-overlay-settings-status">Online Status</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="side-overlay-settings-public-profile" name="side-overlay-settings-public-profile" value="1">
                            <label class="custom-control-label" for="side-overlay-settings-public-profile">Public Profile</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="side-overlay-settings-notifications" name="side-overlay-settings-notifications" value="1" checked>
                            <label class="custom-control-label" for="side-overlay-settings-notifications">Notifications</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="side-overlay-settings-updates" name="side-overlay-settings-updates" value="1">
                            <label class="custom-control-label" for="side-overlay-settings-updates">Auto updates</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="side-overlay-settings-api-access" name="side-overlay-settings-api-access" value="1" checked>
                            <label class="custom-control-label" for="side-overlay-settings-api-access">API Access</label>
                        </div>
                        <div class="custom-control custom-checkbox mb-5">
                            <input type="checkbox" class="custom-control-input" id="side-overlay-settings-limit-requests" name="side-overlay-settings-limit-requests" value="1">
                            <label class="custom-control-label" for="side-overlay-settings-limit-requests">API Requests</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Settings -->
    </div>
    <!-- END Side Content -->
</aside>
<!-- END Side Overlay -->