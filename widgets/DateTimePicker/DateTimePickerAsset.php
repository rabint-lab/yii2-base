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
		'mds.bs.datetimepicker.js',
	];
	public $css = [
		'mds.bs.datetimepicker.style.css',
	];
	public $depends = [
        'rabint\assets\CommonAsset',
        'yii\bootstrap5\BootstrapAsset',
        'yii\bootstrap5\BootstrapPluginAsset',
	];
}
