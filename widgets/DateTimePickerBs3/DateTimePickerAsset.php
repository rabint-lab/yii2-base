<?php

namespace rabint\widgets\DateTimePicker;

use yii\web\AssetBundle;

/**
 * @author Mohammad Mahdi Gholomian.
 * @copyright 2014 mm.gholamian@yahoo.com
 */
class DateTimePickerAsset extends AssetBundle
{
	public $sourcePath = '@rabint/widgets/DateTimePicker/assets';
	public $js = [
		'jalaliHelper.js',
		'PersianDateTimePicker.js',
	];
	public $css = [
		'PersianDateTimePicker.css',
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
