<?php
if (!class_exists('\rabint\notify\models\Notification')) {
    return;
}

$notifyModel = \rabint\notify\models\Notification::find()->where(['user_id' => NULL, 'seen' =>\rabint\notify\models\Notification::SEEN_STATUS_NO]);
$notify = $notifyModel->count('*');
?>
<!-- Notifications -->
<div class="btn-header  float-left">
    <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-flag"></i>
        <span class="badge badge-primary badge-pill"><?= $notify; ?></span>
    </button>
    <div class="dropdown-menu dropdown-menu-right min-width-300" aria-labelledby="page-header-notifications">
        <h5 class="h6 text-center py-10 mb-0 border-b text-uppercase"><?= \Yii::t('rabint', 'Notifications'); ?></h5>
        <ul class="list-unstyled my-20">

            <?php foreach ($notifyModel->limit(5)->orderby(['id' => SORT_DESC])->all() as $logEntry) : ?>
                <li>
                    <a class="text-body-color-dark media mb-15" href="<?php echo Yii::$app->urlManager->createUrl(['/notify/admin/view', 'id' => $logEntry->id]) ?>">
                        <div class="ml-5 mr-15">
                            <i class="fas fa-fw fa-check text-<?php echo $logEntry->priority > 1 ? 'text-warning' : 'text-success' ?>"></i>
                        </div>
                        <div class="media-body pr-10">
                            <p class="mb-0"><?php echo $logEntry->content; ?></p>
                            <div class="text-muted font-size-sm font-italic"><?= \rabint\helpers\locality::secToTime(time()-$logEntry->created_at)?></div>
                        </div>
                    </a>
                </li>

            <?php endforeach; ?>
        </ul>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center mb-0" href="<?php echo Yii::$app->urlManager->createUrl(['/notify/admin/index']) ?>">
            <i class="fas fa-flag mr-5"></i> <?= \Yii::t('rabint', 'View All');?>
        </a>
    </div>
</div>
<!-- END Notifications -->
