<?php

namespace rabint\components\grid;

use yii\grid\DataColumn;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class JDateColumn extends DataColumn
{

    public $dateFormat = 'j F Y - H:i:s';
    public $format = 'html';

    public function init()
    {
        $this->filterOptions['style'] = ' width: 95px;';
        return parent::init();
    }

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (empty($value)) {
            return null;
        }
        return '<span class="bnsDateView">'.\rabint\helpers\locality::jdate($this->dateFormat, $value).'</span>';
    }

    protected function renderFilterCellContent()
    {
        if (is_string($this->filter)) {
            return $this->filter;
        }

        $model = $this->grid->filterModel;

        if (
            $this->filter !== false &&
            $model instanceof Model &&
            $this->attribute !== null &&
            $model->isAttributeActive($this->attribute)
        ) {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            return Html::activeTextInput($model, $this->attribute, $this->filterInputOptions)->widget(
                    \rabint\widgets\DateTimePicker\DateTimePicker::className()) . $error;
        } else {
            if ($model->hasErrors($this->attribute)) {
                Html::addCssClass($this->filterOptions, 'has-error');
                $error = ' ' . Html::error($model, $this->attribute, $this->grid->filterErrorOptions);
            } else {
                $error = '';
            }
            return \rabint\widgets\DateTimePicker\DateTimePicker::widget([
                    'model' => $model,
                    'attribute' => $this->attribute,
                ]) . $error;
        }
    }

}
