<?php
namespace rabint\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * To use EmojiBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use \rabint\behaviors\EmojiBehavior;
 * public function behaviors(){
 *      return [
 *          EmojiBehavior::className(),
 *          'valAttribute'=>'content',
 *          'inFunc'=>'toShort'
 *      ]
 * }
 * ```
 *
 *
 *
 * @author abei <abei@nai8.me>
 * @since 1.1.0
 */
class EmojiBehavior extends AttributeBehavior {

    public $valAttribute = 'content';

    public $value;

    public $inFunc = 'toShort';

    /**
     * @inheritdoc
     */
    public function init(){
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->valAttribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->valAttribute,
            ];
        }
    }

    protected function getValue($event){
        $func = $this->inFunc;
        if(in_array($func,['shortnameToUnicode','toShort']) == false){
            return parent::getValue($event);
        }

        $attr = $this->valAttribute;
        return Emoji::{$func}($this->owner->{$attr});
    }
}