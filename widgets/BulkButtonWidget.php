<?php
/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 2/4/19
 * Time: 3:56 PM
 */

namespace rabint\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class BulkButtonWidget extends Widget
{

    public $buttons;

    public function init()
    {
        parent::init();

    }

    public function run()
    {
        $content = '<div class="pull-right float-right">' .
            '
                &nbsp;' . Yii::t('rabint','Do With selected') . '&nbsp;
                <span class="fas fa-arrow-left"></span>
                &nbsp;
                ' .
            $this->buttons .
            '</div>';
        return $content;
    }
}
