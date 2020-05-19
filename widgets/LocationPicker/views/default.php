<?php

/**
 * var int $id;
 */
?>
<div class="container">
    <br>
    <div class="row">
        <label for="latitude"><?= Yii::t('app','طول جغرافیایی ') ?></label><input name="lat" id="latitude">
        <label for="longitude"><?= Yii::t('app','عرض جغرافیایی ') ?></label><input name="lon" id="longitude">
    </div>
    <br>
    <div id="locationPicker" style="width: auto; height: 300px;"></div>
    <?= \yii\helpers\Html::activeHiddenInput($model,$attribute,[
        "id"=>"location-container".$id
    ]) ?>
</div>