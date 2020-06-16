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
    protected $enumClass = [];
    public $idAttr = null;
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
        $this->enumClass = $this->enum;
        if (!empty($this->idAttr)) {
            $this->enum = ArrayHelper::map($this->enum, $this->idAttr, $this->titleAttr);
            $this->enumClass = $this->classAttr ? (ArrayHelper::map($this->enumClass, $this->idAttr, $this->classAttr)) : [];
        } else {
            $this->enum = ArrayHelper::getColumn($this->enum, $this->titleAttr);
            $this->enumClass = $this->classAttr ? (ArrayHelper::getColumn($this->enumClass, $this->classAttr)) : [];
        }
        if ($this->loadFilterDefaultValues && $this->filter === null) {
            $this->filter = $this->enum;
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
        $data = $this->enum[$value] ?: $value;
        if ($this->classAttr) {
            $class = $this->enumClass[$value] ?: 'default';
            return '<span class="badge badge-' . $class . '">' . $data . '</span>';
        }
        return $data;
    }


}
