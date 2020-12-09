<?php

namespace rabint\widgets;

use Yii;
use kartik\grid\GridView as BaseGridView;
use yii\bootstrap4\Html;
use yii\helpers\ArrayHelper;
use rabint\assets\AjaxCrudAsset;

class GridView extends  BaseGridView
{

    public $modelTitle = '';
    public $bulkActions = [];
    public $bulkActionDropDown = false;
    public $showJsExport = false;
    public $showToggleData = false;
    public $showAddBtn = true;
    public $showRefreshBtn = true;
    public $addUrl= ['create'];
    public $action_btns = '';
    public $toolbar = '';

    public function init()
    {
        if($this->toolbar == '')
            $this->toolbar = [
                [
                    'content' =>
                   ($this->showAddBtn? Html::a(
                        '<i class="fas fa-plus"></i> ایجاد',
                        $this->addUrl,
                        [
                            //'role' => 'modal-remote',
                            'title' => Yii::t(
                                'rabint',
                                'Create new {title}',
                                ['title' => $this->modelTitle]
                            ), 'class' => 'btn btn-info'
                        ]
                    ) :'').
                    ($this->showRefreshBtn? Html::a(
                            '<i class="fas fa-redo"></i>',
                            [''],
                            [
                                'data-pjax' => 1,
                                'class' => 'btn btn-success',
                                'title' => Yii::t('rabint', 'Reset Grid')
                            ])
                        :'')

                ],
            ];
        if ($this->showToggleData) {
            $this->toolbar[]['content'] = '{toggleData}';
        }
        if ($this->showJsExport) {
            $this->toolbar[]['content'] = '{export}';
        }

        $this->striped = true;
        $this->condensed = true;
        $this->pjax = false;
        $this->responsive = true;
        $this->panelPrefix = '';
        $this->panelHeadingTemplate = '{title}{summary}'.$this->action_btns;

        $this->pager = [
            //                        'firstPageLabel' => 'first',
            //                        'lastPageLabel' => 'last',
            //                        'prevPageLabel' => 'previous',
            //                        'nextPageLabel' => 'next',
            //                        'maxButtonCount' => 3,

            // Customzing options for pager container tag
            'options' => [
                'tag' => 'ul',
                'class' => 'col-sm-12 pagination justify-content-center',
                'id' => 'pager-container',
            ],
            // Customzing CSS class for pager link
            'linkOptions' => ['class' => 'pager-link'],
            'activePageCssClass' => 'page-item active',
            'disabledPageCssClass' => 'pager-link disabled',

            // Customzing CSS class for navigating link
            //                        'prevPageCssClass' => 'page-link',
            //                        'nextPageCssClass' => 'page-link',
            //                        'firstPageCssClass' => 'page-link',
            //                        'lastPageCssClass' => 'page-link',
        ];
        $this->panel = [
            'summaryOptions' => ['class' => 'float-left'],
            'type' => '',
            'heading' => Yii::t('rabint', '{title} listing', ['title' => $this->modelTitle]),
            'options' => ['class' => 'card block block-rounded'],
            'headingOptions' =>  ['class' => 'card-header block-header block-header-default'],
            'titleOptions' =>  ['class' => 'card-title block-title'],
            //'before' => '<em class="float-left">' . Yii::t('rabint', '* Resize table columns just like a spreadsheet by dragging the column edges.') . '</em>',
            'after' => $this->bulkactionHandler(),
        ];
        /**
         * end
         */
        return parent::init();
    }
    
    public function run(){
        
        $this->registerAssets();
        return parent::run();
    }
    
    public function registerAssets(){
        AjaxCrudAsset::register($this->getView());
    }
    
    public function bulkactionHandler()
    {
        if (empty($this->bulkActions)) {
            return false;
        }
        if (!$this->bulkActionDropDown) {
            $buttons = '';
            foreach ($this->bulkActions as $key => $row) {

                $buttons .= Html::a(
                    '<i class="' . $row['icon'] . '"></i>&nbsp; ' . $row['title'],
                    ["bulk", 'action' => $key],
                    [
                        "class" => "btn btn-" . $row['class'] . " btn-sm btn-noborder mr-1 ml-1",
                        'role' => 'modal-remote-bulk',
                        'data-confirm' => false, 'data-method' => false, // for overide yii data api
                        'data-request-method' => 'post',
                        'data-confirm-title' => Yii::t('rabint', 'Are you sure?'),
                        'data-confirm-message' => Yii::t('rabint', 'Are you sure want to {action} this item?', ['action' => $row['title']]),
                    ]
                );
            }
        } else {
            $buttons  =  '<div class="bulk-dropdown">' . Html::dropDownList('bulk-action', '', ArrayHelper::getColumn($this->bulkActions, 'title'), ['class' => 'form-control', 'prompt' => '']);
            $buttons .= Html::submitButton(\Yii::t('rabint', 'اعمال'), ['role' => 'modal-remote-bulk-do-button', 'class' => 'btn btn-danger btn-noborder']);
            $buttons .= '</div>';
        }

        $out = '<div class="float-right">&nbsp;' .
            Yii::t('rabint', 'Do With selected') .
            '&nbsp;<span class="fas fa-arrow-left"></span>&nbsp;' .
            $buttons .
            '</div>
            <div class="clearfix"></div>';
        return $out;
    }
}
