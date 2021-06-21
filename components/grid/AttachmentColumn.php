<?php

namespace rabint\components\grid;

use yii\grid\DataColumn;

class AttachmentColumn extends DataColumn
{

    public $format = 'html';
    public $size = [50, 50];
    public $enableSorting = false;
    public $filter = false;

    public function init()
    {
        parent::init();
//        $this->filterOptions = ['style' => 'max-width:'.$this->size[0].'px;'];
        $this->filterOptions = array_merge(['style' => 'max-width:'.$this->size[0].'px;'], $this->filterOptions);

    }

    public function getDataCellValue($model, $key, $index)
    {

        $this->filterOptions = ['style' => 'max-width:'.$this->size[0].'px;'];
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_numeric($value)) {
            $attachment = \rabint\attachment\models\Attachment::findOne($value);
        } else {
            $attachment = \rabint\attachment\models\Attachment::findByPath($value);
        }
        if ($this->format == "html") {
            return \rabint\helpers\html::attachmentTag($attachment, [], $this->size);
        }
        return $attachment->getUrl($this->size);
    }

}
