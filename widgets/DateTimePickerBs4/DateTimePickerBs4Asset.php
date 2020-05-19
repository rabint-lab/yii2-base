<?php

namespace rabint\widgets\DateTimePickerBs4;

use yii\web\AssetBundle;

/**
 * @author Mohammad Mahdi Gholomian.
 * @copyright 2014 mm.gholamian@yahoo.com
 */
class DateTimePickerBs4Asset extends AssetBundle
{
	public $sourcePath = '@rabint/widgets/DateTimePickerBs4/assets';
	public $js = [
		'jquery.md.bootstrap.datetimepicker.js',
	];
	public $css = [
		'jquery.md.bootstrap.datetimepicker.style.css',
	];
	public $depends = [
		'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapPluginAsset',
		'yii\web\JqueryAsset',
	];
}
