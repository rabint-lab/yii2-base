<?php

namespace rabint\filters;

use Yii;
use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;

/**
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'environment' => [
 *             'class' => \rabint\filters\EnvironmentFilter::className(),
 *             'actions' => [
 *                 'index'  => 'admin',
 *                 'view'   => 'panel',
 *                 'create' => 'frontend',
 *                 '*' => 'frontend',
 *             ],
 *         ],
 *     ];
 * }
 * ```
 */
class EnvironmentFilter extends Behavior
{

    const ENV_ADMIN = "admin";
    const ENV_PANEL = "panel";
    const ENV_FRONTEND = "frontend";

    public $actions = [];

    public function init()
    {
        self::setEnv(self::ENV_FRONTEND);
        return parent::init();
    }

    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'beforeAction'];
    }

    /**
     * @param ActionEvent $event
     * @return boolean
     * @throws MethodNotAllowedHttpException when the request method is not allowed.
     */
    public function beforeAction($event)
    {
        $action = $event->action->id;
        if (isset($this->actions[$action])) {
            $env = $this->actions[$action];
        } elseif (isset($this->actions['*'])) {
            $env = $this->actions['*'];
        } else {
            return $event->isValid;
        }

        /* ------------------------------------------------------ */
        switch ($env) {
            case self::ENV_ADMIN:
                $this->switchToAdmin();
                break;
            case self::ENV_PANEL:
                $this->switchToPanel();
                break;
            default:
                $this->switchToFrontend();
                break;
        }
        /* ------------------------------------------------------ */

        return $event->isValid;
    }

    /* =================================================================== */

    public function switchToAdmin()
    {
        if (\Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', \Yii::t('rabint', 'لطفا ابتدا وارد حساب کاربری خود شوید.'));
            \yii\helpers\Url::remember();
            return redirect(array('/user/sign-in/login','redirect'=>\yii\helpers\Url::current()));
        }
        if (!\Yii::$app->user->can('loginToBackend')) {
            \Yii::$app->session->setFlash('warning', \Yii::t('rabint', 'شما حق دسترسی به این صفحه را ندارید.'));
            return redirect(array('/user/default/index'));
        }
        $adminTheme = config('backendThemePath', '@rabint/themes/admin');
        if (!empty($adminTheme)) {
            Yii::setAlias('@theme', $adminTheme);
            Yii::setAlias('@themeLayouts', $adminTheme . '/views/layouts');
            \Yii::$app->view->theme = new \yii\base\Theme([
                'pathMap' => ['@app/views' => $adminTheme . '/views'],
            ]);
        }
        self::setEnv(self::ENV_ADMIN);
    }

    /* =================================================================== */

    public function switchToPanel()
    {
        if (\Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', \Yii::t('rabint', 'لطفا ابتدا وارد حساب کاربری خود گردید.'));
            \yii\helpers\Url::remember();
            return redirect(array('/user/sign-in/login','redirect'=>\yii\helpers\Url::current()));

        }
        $panelTheme = config('panelThemePath', '@rabint/themes/basic');
        if (!empty($panelTheme)) {
            Yii::setAlias('@theme', $panelTheme);
            Yii::setAlias('@themeLayouts', $panelTheme . '/views/layouts');

            \Yii::$app->view->theme = new \yii\base\Theme([
                'pathMap' => ['@app/views' => $panelTheme . '/views'],
            ]);
        }
        self::setEnv(self::ENV_PANEL);
    }


    public function switchToFrontend()
    {
        self::setEnv(self::ENV_FRONTEND);
    }


    public static function getEnv()
    {
        return Yii::$app->params['rabint_enviroment'];
    }

    public static function setEnv($env)
    {
        Yii::$app->params['rabint_enviroment'] = $env;
    }

}
