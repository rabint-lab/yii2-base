<?php

namespace rabint\components\grid;

use yii\grid\DataColumn;

class NumberColumn extends DataColumn
{
    public $format = 'html';

    public function getDataCellValue($model, $key, $index)
    {

        $value = parent::getDataCellValue($model, $key, $index);
        return '<span class="rabintNumberView" style="text-align:center;direction: ltr;display: inline-block;">' . $value . '</span>';
    }

}
