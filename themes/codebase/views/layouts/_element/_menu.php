<?php

use rabint\themes\codebase\widgets\Menu;
use rabint\option\models\Option;

$menusConf = include Yii::getAlias("@app/config/menus.php");

$premenusConf = [];//\rabint\helpers\collection::getValue($menusConf, 'preadmin', []);
$menusConf = \rabint\helpers\collection::getValue($menusConf, 'admin', []);


$ModuleMenu = [];
$modules = include(Yii::getAlias('@config/modules.php'));
foreach ((array) $modules as $item) {
    $moduleClass = $item['class'];

    if (method_exists($moduleClass, 'adminMenu')) {
        $menu = call_user_func([$moduleClass, 'adminMenu']);
        if (isset($menu['label'])) {
            $ModuleMenu[] = $menu;
        } else {
            $ModuleMenu = array_merge($ModuleMenu, $menu);
        }
    }
    //    $config = substr($moduleClass, 0, strrpos($moduleClass, '\\'));
    //    $config .= '\Config';
    //    if (class_exists($config)) {
    //        $C = new $config;
    //        if (method_exists($C, 'adminMenu')) {
    //            $ModuleMenu[] = $C->adminMenu();
    //        }
    //    }
}
/* =================================================================== */
// <li class="nav-main-heading"><span class="sidebar-mini-visible">UI</span><span class="sidebar-mini-hidden">User Interface</span></li>
$TopMenu = [
    [
        'label' => \Yii::t('rabint', 'پنل مدیریت'),
        'options' => ['class' => 'nav-main-heading'],
    ],
    [
        'label' => Yii::t('rabint', 'پیشخوان مدیریتی'),
        'icon' => '<i class="fas fa-tachometer-alt"></i>',
        'visible' => !\rabint\helpers\user::isGuest(),
        'url' => ['/admin/index'],
    ],
    [
        'label' => Yii::t('rabint', 'ورود به بخش مدیریت'),
        'visible' => \rabint\helpers\user::isGuest(),
        'icon' => '<i class="fas fa-sign-in-alt"></i>',
        'url' => ['/admin/index'],
    ],
    //     [
    //     'label' => Yii::t('rabint', 'محتوای ایستا'),
    //     'icon' => '<i class="fas fa-file-alt"></i>',
    //     'url' => ['/admin-page/index'],
    // ],
];

$optionItems = [];
$BottmMenu = [];
// [
//         [
//         'label' => Yii::t('rabint', 'سیستم'),
//         'options' => ['class' => 'nav-main-heading'],
//         'visible' => Yii::$app->user->can('manager')
//     ],
//         [
//         'label' => Yii::t('rabint', 'اختیارات'),
//         'icon' => '<i class="fas fa-check-square-o"></i>',
//         'options' => ['class' => 'treeview'],
//         'url' => '#',
//         'visible' => Yii::$app->user->can('manager'),
//         'items' => $optionItems,
//     ],
//         [
//         'label' => Yii::t('rabint', 'خط زمانی'),
//         'icon' => '<i class="far fa-chart-bar"></i>',
//         'url' => ['/timeline-event/index'],
//         'badgeBgClass' => 'label-success',
//     ],
//         [
//         'label' => Yii::t('rabint', 'کاربران'),
//         'icon' => '<i class="fas fa-users"></i>',
//         'url' => ['/user/admin/index'],
//         'visible' => Yii::$app->user->can('administrator')
//     ],
//         [
//         'label' => Yii::t('rabint', 'پیکربندی'),
//         'url' => '#',
//         'icon' => '<i class="fas fa-cogs"></i>',
//         'options' => ['class' => 'treeview'],
//         'visible' => Yii::$app->user->can('administrator'),
//         'items' => [
//                 ['label' => Yii::t('rabint', 'ذخیره کلید و مقدار'), 'url' => ['/key-storage/index'], 'icon' => '<i class="far fa-circle"></i>'],
// //            ['label' => Yii::t('rabint', 'File Storage'), 'url' => ['/file-storage/index'], 'icon' => '<i class="far fa-circle"></i>'],
//             ['label' => Yii::t('rabint', 'کش'), 'url' => ['/cache/index'], 'icon' => '<i class="far fa-circle"></i>'],
//                 [
//                 'label' => Yii::t('rabint', 'اطلاعات سیستم'),
//                 'url' => ['/system-information/index'],
//                 'icon' => '<i class="far fa-circle"></i>'
//             ],
//                 [
//                 'label' => Yii::t('rabint', 'رخداد ها'),
//                 'url' => ['/log/index'],
//                 'icon' => '<i class="far fa-circle"></i>',
//                 //'badge' => \app\models\SystemLog::find()->count(),
//                 'badgeBgClass' => 'label-danger',
//             ],
//                 [
//                 'label' => Yii::t('rabint', 'زمانبدی کارها'),
//                 'url' => ['/cronjob/index'],
//                 'icon' => '<i class="far fa-circle"></i>',
//             ],
//             [
//                 'label' => Yii::t('rabint', 'وقایع کارهای دارای زمانبندی '),
//                 'url' => ['/cronjob/logs'],
//                 'icon' => '<i class="far fa-circle"></i>',
//             ],
//              [
//                 'label' => Yii::t('rabint', 'ویرایشگر'),
//                 'url' => ['/admin/editor'],
//                 'icon' => '<i class="far fa-circle"></i>',
//             ],
//         ]
//     ]
// ];

$AllItems = array_merge($premenusConf,$TopMenu, $menusConf, $ModuleMenu, $BottmMenu);

$r = Yii::$app->request->url;
$b = Yii::$app->request->baseUrl;
$i = 1;
$res = str_replace($b, '', $r, $i);
while (!empty($res)) {
    $find = \rabint\helpers\collection::arraySearchDeep($AllItems, $res);
    if (!empty($find)) {
        $keys = explode('.', $find);
        $AllItems = tmp_add_class_to_menu($AllItems, $keys);
        break;
    }
    if (strpos($res, '&')) {
        $res = substr($res, 0, strrpos($res, '&'));
    } elseif (strpos($res, '?')) {
        $res = substr($res, 0, strrpos($res, '?'));
    } else {
        $res = substr($res, 0, strrpos($res, '/'));
    }
}
/* =================================================================== */

echo Menu::widget([
    'options' => ['class' => 'nav-main'],
    'linkTemplate' => '<a href="{url}">{icon}<span class="sidebar-mini-hide">{label}</span>{badge}</a>',
    'parentLinkTemplate' => '<a href="{url}" class="nav-submenu" data-toggle="nav-submenu">{icon}<span class="sidebar-mini-hide">{label}</span>{badge}</a>',
    'submenuTemplate' => "\n<ul>\n{items}\n</ul>\n",
    'activateParents' => true,
    'items' => $AllItems
]);

function tmp_add_class_to_menu($array, $keys)
{
    $k = array_shift($keys);
    if ($k === false or $k === null or $k === 'url') {
        return $array;
    }
    if (!isset($array[$k]))
        return $array;
    if (isset($array[$k]['options']['class'])) {
        $array[$k]['options']['class'] .= ' open ';
    } elseif (is_array($array[$k])) {
        $array[$k] = array_merge($array[$k], ['options' => ['class' => ' open ']]);
    }
    $array[$k] = tmp_add_class_to_menu($array[$k], $keys);
    return $array;
}
