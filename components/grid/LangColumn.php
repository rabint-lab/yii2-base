<?php
namespace rabint\components\grid;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;

/**
 * Class EnumColumn
 * [
 *      'class' => 'common\grid\EnumColumn',
 *      'attribute' => 'role',
 *      'enum' => User::getRoles()
 * ]
 * @package common\components\grid
 */
class LangColumn extends EnumColumn
{
    var $titleField = 'title';
    var $attribute = "lang";

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->enum = ArrayHelper::getColumn(\Yii::$app->params['availableLocales'],$this->titleField);
        return parent::init();
    }

}
