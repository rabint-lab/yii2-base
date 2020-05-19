<?php

use yii\helpers\Html;
use yii\helpers\Url;

// Register asset bundle
\rabint\assets\MapInputAsset::register($this);

// [BEGIN] - Map input widget container
echo Html::beginTag(
        'div', [
    'class' => 'rabint-map-input-widget',
    'style' => "width: $width; height: $height;",
    'id' => $id,
    'data' =>
        [
        'latitude' => $latitude,
        'longitude' => $longitude,
        'zoom' => $zoom,
        'pattern' => $pattern,
        'map-type' => $mapType,
        'animate-marker' => $animateMarker,
        'align-map-center' => $alignMapCenter,
        'enable-search-bar' => $enableSearchBar,
    ],
        ]
);

if ($hasModel) {

// The actual hidden input
    echo Html::activeHiddenInput(
            $model, $attribute, [
        'class' => 'rabint-map-input-widget-input',
            ]
    );
} else {
    echo Html::label(\Yii::t('rabint', 'شورت کد:'));
    echo Html::textInput($name, $value, [
        'class' => 'rabint-map-input-widget-input',
    ]);
}
// Search bar input
echo Html::input(
        'text', null, null, [
    'class' => 'rabint-map-input-widget-search-bar',
        ]
);

// Map canvas
echo Html::tag(
        'div', '', [
    'class' => 'rabint-map-input-widget-canvas',
        ]
);

// [END] - Map input widget container
echo Html::endTag('div');
