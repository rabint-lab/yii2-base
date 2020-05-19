<?php

/**
 * var int $id;
 */

?>


 <div class="chartWidgetBox chart_<?= $id; ?>">
    <div class="chart_header center">
        <h5 >
            <?= $title; ?>
        </h5>
    </div>
     <div class="chartWidgetBox chart_<?= $id; ?>">
         <canvas id="chart_wrapper_<?= $id; ?>"></canvas>
     </div>
     <div class="text-center">
         <?= $note; ?>
     </div>
</div>

