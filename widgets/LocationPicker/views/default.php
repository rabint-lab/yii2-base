<?php

/**
 * var int $id;
 */
?>
<div class="form-group">
    <label for="">
        <?=$label;?>
    </label>
    <?php
    if (empty($model)) {
        echo \yii\helpers\Html::textInput($name, $value, [
            "id" => "location-container" . $id,
            "class" => 'form-control'

        ]);
    } else {

        echo \yii\helpers\Html::activeTextInput($model, $attribute, [
            "id" => "location-container" . $id,
            "class" => 'form-control'
        ]);
    } ?>
    <div class="hint-block"><?= $hint;?></div>
    <div class="invalid-feedback"></div>
</div>


<?php // echo \yii\helpers\Html::activeTextInput($model, $attribute,['id'=>$id])  ?>


<div id="locationPicker" style="width: auto; height: 300px;"></div>

</div>