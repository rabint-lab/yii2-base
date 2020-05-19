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
class AdvanceEnumColumn extends DataColumn
{
    /**
     * @var array List of value => name pairs
     */
    public $enum = [];
    public $titleAttr = 'title';
    public $classAttr = 'class';
    public $format = 'html';
    /**
     * @var bool
     */
    public $loadFilterDefaultValues = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $enums = ArrayHelper::getColumn($this->enum, $this->titleAttr);
        if ($this->loadFilterDefaultValues && $this->filter === null) {
            $this->filter = $enums;
        }
    }

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     * @return mixed
     */
    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        $data = isset($this->enum[$value][$this->titleAttr]) ? $this->enum[$value][$this->titleAttr] : $value;
        $class = isset($this->enum[$value][$this->classAttr]) ? $this->enum[$value][$this->classAttr] : 'default';
        return '<span class="badge badge-' . $class . '">' . $data . '</span>';
    }


}
