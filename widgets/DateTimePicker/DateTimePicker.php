<?php

namespace rabint\widgets\DateTimePicker;

use yii\helpers\Json;
use yii\helpers\Html;
use yii\widgets\InputWidget;

/**
 * Jalali date & time.
 * @author Mohammad Mahdi Gholomian.
 * @copyright 2014 mm.gholamian@yahoo.com
 * Supported format
  yyyy: سال چهار رقمی
  yy: سال دو رقمی
  MMMM: نام فارسی ماه
  MM: عدد دو رقمی ماه
  M: عدد یک رقمی ماه
  dddd: نام فارسی روز هفته
  dd: عدد دو رقمی روز ماه
  d: عدد یک رقمی روز ماه
  HH: ساعت دو رقمی با فرمت 00 تا 24
  H: ساعت یک رقمی با فرمت 0 تا 24
  hh: ساعت دو رقمی با فرمت 00 تا 12
  h: ساعت یک رقمی با فرمت 0 تا 12
  mm: عدد دو رقمی دقیقه
  m: عدد یک رقمی دقیقه
  ss: ثانیه دو رقمی
  s: ثانیه یک رقمی
  fff: میلی ثانیه 3 رقمی
  ff: میلی ثانیه 2 رقمی
  f: میلی ثانیه یک رقمی
  tt: ب.ظ یا ق.ظ
  t: حرف اول از ب.ظ یا ق.ظ
 */
class DateTimePicker extends InputWidget {

    /**
     * @var array Date picker options.
     */
    public $clientOptions = [
        'Placement' => 'bottom',
        'Trigger' => 'click',
        'EnableTimePicker' => true,
        'TargetSelector' => '',
        'GroupId' => '',
        'ToDate' => false,
        'FromDate' => false,
        'Disabled' => false,
        'EnglishNumber' => true,
        'DisableBeforeToday' => false,
        'Disabled' => false,
        'Format' => 'yyyy/MM/dd',
        'IsGregorian' => false,
    ];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        Html::addCssClass($this->options, 'form-control md_datetimepicker');
    }

    /**
     * Executes the widget.
     */
    public function run() {
        $input = $this->hasModel() ? Html::activeTextInput($this->model, $this->attribute, $this->options) : Html::textInput($this->name, $this->value, $this->options);
        echo $input;
        $this->registerClientScript();
    }

    /**
     * Register datepicker default asset into view.
     */
    function registerAssets() {
        DateTimePickerAsset::register($this->getView());
    }

    /**
     * Render Js code.
     */
    public function registerClientScript() {
        $this->registerAssets();
        $js = [];
        $id = $this->options['id'];
        $selector = ";jQuery('#$id')";
        $this->clientOptions = array_merge([
            'Placement' => 'bottom',
            'Trigger' => 'click',
            'EnableTimePicker' => true,
            'TargetSelector' => '',
            'GroupId' => '',
            'ToDate' => false,
            'FromDate' => false,
            'Disabled' => false,
            'EnglishNumber' => true,
            'DisableBeforeToday' => false,
            'Disabled' => false,
            'Format' => 'yyyy/MM/dd',
            'IsGregorian' => false,
                ], $this->clientOptions);
        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';

        $js[] = "$selector.MdPersianDateTimePicker($options);";

//        if (!empty($this->clientEvents)) {
//            foreach ($this->clientEvents as $event => $handler) {
//                $js[] = "$selector.on('$event', $handler);";
//            }
//        }
        $this->getView()->registerJs(implode("\n", $js));
    }

}
