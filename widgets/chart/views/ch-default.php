<?php

/**
 * var int $id;
 */

?>

<div class="card">
    <div class="card-header chart-chad">
        <div class="float-right ">
            <i class="fas fa-arrows-alt chartAction chartZoom chartZoom<?= $id; ?>" data-toggle="modal"
               data-target="#chartModal<?= $id; ?>"></i>
        </div>
        <div class="float-right">
            <?= $title; ?>
        </div>
        <div class="float-left form-inline">
            <div class="form-group">
                <label for="budgetIndexCh" class="mr-2 ml-2 "><?= $filterTitle; ?>:</label>
                <select class="form-control form-control-sm chart_select_filter" id="<?= $id; ?>_select_filter">
                    <?php
                    $k = 0;
                    foreach ($options as $key => $opt) {
                        ?>
                        <option value="<?= $key; ?>" <?= $k == 0 ? ' selected="selected" ' : ''; ?> ><?= $opt; ?></option>
                        <?php
                        $k++;
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="chartWidgetBox chart_<?= $id; ?>">
            <canvas id="cnx_<?= $id; ?>"></canvas>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="chartModal<?= $id; ?>" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"> <?= $title; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="chartWidgetBox chart_<?= $id; ?>">
                                        <canvas id="chart_wrapper_<?= $id; ?>"></canvas>
                                    </div>
                                    <div class="text-center">
                                        <?= $note; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>