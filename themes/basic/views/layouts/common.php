<?php

use app\widgets\Alert;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\widgets\Breadcrumbs;
use yii\bootstrap4\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$this->beginContent('@themeLayouts/base.php');



$menusConf = include Yii::getAlias("@app/config/menus.php");

$ModuleMenu = [];
$modules = include(Yii::getAlias('@app/config/modules.php'));
foreach ((array)$modules as $item) {
    $moduleClass = $item['class'];
    if (method_exists($moduleClass, 'DashboardMenu')) {
        $ModuleMenu[] = call_user_func([$moduleClass, 'DashboardMenu']);
    }
}

$menus = \yii\helpers\ArrayHelper::merge(
#first menu
    [['label' => Yii::t('rabint', 'Home'), 'url' => ['/main/index']],],
#main site menu
    \rabint\helpers\collection::getValue($menusConf, 'dashboard', []),
#module menu
//    $ModuleMenu,
#last menu
    [
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

        Yii::$app->user->isGuest ? (['label' => 'Login', 'url' => ['/user/sign-in/login']]) : (

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
        ]
        ),
    ]);

?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => $menus,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left float-left">&copy; rabint <?= date('Y') ?></p>
    </div>
</footer>
<?php $this->endContent() ?>