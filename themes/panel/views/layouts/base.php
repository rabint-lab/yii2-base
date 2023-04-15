<?php

use yii\helpers\Html;

\rabint\themes\panel\ThemeAsset::register($this);

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

$menusConf = include Yii::getAlias("@app/config/menus.php");


$ModuleMenu = [];
if (!\rabint\helpers\user::isGuest()) {
    $modules = include(Yii::getAlias('@app/config/modules.php'));
    foreach ((array) $modules as $item) {
        $moduleClass = $item['class'];
        if (method_exists($moduleClass, 'dashboardMenu')) {
            $ModuleMenu[] = call_user_func([$moduleClass, 'dashboardMenu']);
        }
    }
}

$menus = \yii\helpers\ArrayHelper::merge(
    #first menu
    [
        [
            'label' => Yii::t('rabint', 'Home'),
            'url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/site/index']),
        ]
    ],
    #main site menu
    (!\rabint\helpers\user::isGuest()?\rabint\helpers\collection::getValue($menusConf, 'admin', []):[]),
    #module menu
    $ModuleMenu,
    #last menu
    [
        [
            'label' => Yii::t('rabint', 'Login'),
            'url' => ['/user/sign-in/login'],
            'visible' => Yii::$app->user->isGuest
        ],
        [
            'label' => Yii::t('rabint', 'Language'),
            'items' => array_map(function ($code) {
                return [
                    'label' => Yii::$app->params['availableLocales'][$code]['title'],
                    'url' => ['/main/set-locale', 'locale' => $code],
                    'active' => Yii::$app->language === $code
                ];
            }, array_keys(Yii::$app->params['availableLocales']))
        ],
        [
            'label' => Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->getPublicIdentity(),
            'visible' => !Yii::$app->user->isGuest,
            'items' => [
                [
                    'label' => Yii::t('rabint', 'پیشخوان'),
                    'url' => \rabint\helpers\uri::dashboardRoute()
                ],
                [
                    'label' => Yii::t('rabint', 'ویرایش پروفایل'),
                    'url' => ['/user/default/profile']
                ],
                [
                    'label' => Yii::t('rabint', 'پنل مدیریت'),
                    'url' => ['/user/admin/index'],
                    'visible' => rabint\helpers\user::can('loginToBackend')
                ],
                [
                    'label' => Yii::t('rabint', 'خروج'),
                    'url' => ['/user/sign-in/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ]
            ]
        ],

    ]
);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>" dir="rtl">

<head>
    <meta charset="<?php echo Yii::$app->charset ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //echo $this->renderMetaTags(); 
    ?>
    <?php $this->head() ?>
    <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
    <?php echo Html::csrfMetaTags() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['/site/index']),
            'innerContainerOptions' => [
                'class' => 'container',
            ],
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        ?>
        <?php
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-left'],
            'items' => $menus
        ]);
        ?>
        <?php NavBar::end(); ?>
        <?php echo $content ?>

    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left float-left">&copy; rabint <?php echo date('Y') ?></p>
        </div>
    </footer>
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>