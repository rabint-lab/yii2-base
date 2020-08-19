<?php

/**
 * var int $id;
 */
?>
        <div class="form-group">
                <?= \yii\helpers\Html::activeTextInput($model,$attribute,[
                        "id"=>"location-container".$id,
                        "class"=>'form-control'

                    ]) ?>
            <div class="invalid-feedback"></div>
        </div>
        
        
        <?php // echo \yii\helpers\Html::activeTextInput($model, $attribute,['id'=>$id])  ?>
        
 
    <div id="locationPicker" style="width: auto; height: 300px;"></div>
    
</div>