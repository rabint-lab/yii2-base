<?php

use yii\bootstrap4\Html;

\rabint\themes\admin\ThemeAsset::register($this);

use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

/* @var $this \yii\web\View */
/* @var $content string */

$menusConf = include Yii::getAlias("@app/config/menus.php");


$ModuleMenu = [];
$modules = include(Yii::getAlias('@app/config/modules.php'));
foreach ((array)$modules as $item) {
    $moduleClass = $item['class'];
    if (method_exists($moduleClass, 'AdminMenu')) {
        $ModuleMenu[] = call_user_func([$moduleClass, 'adminMenu']);
    }
}

$menus = \yii\helpers\ArrayHelper::merge(
#first menu
    [['label' => Yii::t('rabint', 'Home'), 'url' => ['/main/index']],],
#main site menu
    \rabint\helpers\collection::getValue($menusConf, 'admin', []),
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
                    'url' => ['/user/default/index']
                ],
                [
                    'label' => Yii::t('rabint', 'ویرایش پروفایل'),
                    'url' => ['/user/default/profile']
                ],
//                [
//                    'label' => Yii::t('rabint', 'پنل مدیریت'),
//                    'url' => ['/user/admin/index'],
//                    'visible' => rabint\helpers\user::can('loginToBackend')
//                ],
                [
                    'label' => Yii::t('rabint', 'خروج'),
                    'url' => ['/user/sign-in/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ]
            ]
        ],

    ]);

$opts = \rabint\option\models\Option::get('general');


?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language ?>" dir="rtl">
<head>
    <meta charset="<?php echo Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php //echo $this->renderMetaTags(); ?>
    <?php $this->head() ?>
    <link rel="stylesheet" href="//cdn.rawgit.com/morteza/bootstrap-rtl/v3.3.4/dist/css/bootstrap-rtl.min.css">
    <?php echo Html::csrfMetaTags() ?>
</head>
<body>
<?php $this->beginBody() ?>


<header id="header">

    <nav class="navbar navbar-expand-lg navbar-light bg-light  masterNav">
        <div class="container-fluid">
            <a class="master-brand navbar-brand" href="<?= \rabint\helpers\uri::home(); ?>">
                <div class="logoTitle">
                    <h1><?= $opts[0]['subject']; ?></h1>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">

                <?php
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav mr-auto'],
                    'items' => $menus,
                ]);
                ?>
                <form class="form-inline">
                    <input style="display: none" class="form-control mr-sm-2" type="search" placeholder="Search"
                           aria-label="Search">
                    <button class="btn btn-link" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>
</header>

<div class="clearfix"></div>

<section class="mainContent">
    <div class="container-fluid">
        <?php echo $content ?>
    </div>
</section>

<div class="clearfix"></div>
<footer class="footer">
    <div class="container-fluid">
        <p class="pull-left float-left">&copy; rabint <?php echo date('Y') ?></p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
