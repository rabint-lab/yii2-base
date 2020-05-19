<?php

if (!class_exists('\common\models\SystemLog')) {
   return;
}

?>
<!-- Notifications -->
<div class="btn-header  float-left">
    <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-flag"></i>
        <span class="badge badge-primary badge-pill"> <?php echo \common\models\SystemLog::find()->count() ?></span>
    </button>
    <div class="dropdown-menu dropdown-menu-right min-width-300" aria-labelledby="page-header-notifications">
        <h5 class="h6 text-center py-10 mb-0 border-b text-uppercase"><?= \Yii::t('rabint', 'Notifications'); ?></h5>
        <ul class="list-unstyled my-20">

        <?php foreach (\app\models\SystemLog::find()->orderBy(['log_time' => SORT_DESC])->limit(5)->all() as $logEntry): ?>

                <li>
                    <a class="text-body-color-dark media mb-15" href="<?php echo Yii::$app->urlManager->createUrl(['/log/view', 'id' => $logEntry->id]) ?>">
                        <div class="ml-5 mr-15">
                            <i class="fas fa-fw fa-check text-<?php echo $logEntry->level == \yii\log\Logger::LEVEL_ERROR ? 'text-danger' : 'text-warning' ?>"></i>
                        </div>
                        <div class="media-body pr-10">
                            <p class="mb-0"><?php echo $logEntry->category ?></p>
                            <div class="text-muted font-size-sm font-italic"><?= \rabint\helpers\locality::secToTime(time()-$logEntry->created_at)?></div>
                        </div>
                    </a>
                </li>

            <?php endforeach; ?>
        </ul>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-center mb-0" href="<?php echo Yii::$app->urlManager->createUrl(['/log/index']) ?>">
            <i class="fas fa-flag mr-5"></i> <?= \Yii::t('rabint', 'View All');?>
        </a>
    </div>
</div>
<!-- END Notifications -->