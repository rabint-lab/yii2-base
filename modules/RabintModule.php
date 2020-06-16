<?php

namespace rabint\modules;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RabintModule
 *
 * @author mojtaba
 */
abstract class RabintModule extends \yii\base\Module {
    
    
    /**
     * @example 
     * return [
            'label' => \Yii::t('rabint', 'مطالب'),
            'icon' => '<i class="fas fa-edit"></i>',
            'url' => '#',
            'options' => ['class' => 'treeview'],
            'items' => [
//                    [
//                    'label' => \Yii::t('rabint', 'مدیریت مطالب'), 'url' => '#', 'icon' => '<i class="far fa-circle"></i>',
//                    'options' => ['class' => 'treeview'],
//                    'items' => [
//                    ],
//                ],
                    ['label' => \Yii::t('rabint', 'همه مطالب'), 'url' => ['/post/admin/index'], 'icon' => '<i class="far fa-circle"></i>',],
                    [
                    'label' => \Yii::t('rabint', 'نظر سنجی'), 'url' => '#', 'icon' => '<i class="far fa-circle"></i>',
                    'options' => ['class' => 'treeview'],
                    'items' => [
                            ['label' => \Yii::t('rabint', 'نظرهای ثبت شده'), 'url' => ['/post/admin-poll/index'], 'icon' => '<i class="far fa-circle"></i>',],
                            ['label' => \Yii::t('rabint', 'الگوهای نظر سنجی'), 'url' => ['/post/admin-poll-template/index'], 'icon' => '<i class="far fa-circle"></i>'],
                    ],
                ],
                ['label' => \Yii::t('rabint', 'لیست پخش'), 'url' => ['/post/admin-playlist/index'], 'icon' => '<i class="far fa-circle"></i>'],
            ]
        ];
     */
    abstract public static function adminMenu();

    /**
     *  
     * @example
         return[ 'postCommentSend' => [
                        'description' => \Yii::t('rabint', 'ارسال نظر'),
                        'parents' => [User::ROLE_USER],
                    ],
                    'postTextCreate' => [
                        'description' => \Yii::t('rabint','ایجاد دلنوشته'),
                        'parents' => [User::ROLE_USER],
                    ],
                    'postCreate' => [
                        'description' => \Yii::t('rabint','ایجاد مطلب'),
                        'parents' => [User::ROLE_CONTRIBUTOR],
                    ],]
     */
    abstract public static function userPermisions($return = 'roles');

    abstract public static function registerEvent();


    public function install(){
        
    }
    
    public function activete(){
        
    }
    
    public function deactivate(){
        
    }
    
    public function modulesStatus(){
        
    }
    
}
