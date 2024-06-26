<?php

namespace rabint\helpers;

use ReflectionClass;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\ActiveField;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class widget
{
    public static function select2Static($data, $attribute, $value = null, $options = [], $pluginOptions = [])
    {
        $options = array_merge([
            'placeholder' => \Yii::t('rabint', 'عبارت های مورد نظر را بنویسید'),
            'dir' => 'rtl',
            'multiple' => false,
        ], $options);
        $pluginOptions = array_merge([
            'maximumInputLength' => 100,
            'allowClear' => true
        ], $pluginOptions);

        $selected = [];
        return \kartik\select2\Select2::widget([
            'name' => $attribute, // initial value
            'value' => $value, // initial value
            'data' => $data,
            'maintainOrder' => true,
            'options' => $options,
            'pluginOptions' => $pluginOptions,
        ]);
    }

    public static function select2($form, $model, $fieldName, $data, $options = [], $pluginOptions = [])
    {
        $options = array_merge([
            'placeholder' => \Yii::t('rabint', 'عبارت های مورد نظر را بنویسید'),
            'dir' => 'rtl',
            'multiple' => false
        ], $options);
        $pluginOptions = array_merge([
            'maximumInputLength' => 100,
            'allowClear' => true
        ], $pluginOptions);

        $selected = []; //$model->find()->with('tags');
        return $form->field($model, $fieldName)
            ->widget(
                \kartik\select2\Select2::className(),
                [
                    'value' => $selected, // initial value
                    'data' => $data,
                    'maintainOrder' => true,
                    'options' => $options,
                    'pluginOptions' => $pluginOptions,
                ]
            );
    }

    /**
     *
     * @param type $form
     * @param type $model
     * @param type $fieldName
     * @param type $url return json id:name
     * @param type $options
     * @param type $pluginOptions
     * @return type
     *
     * output url example input :  $q = Amersfoort
     * output url example output :  {"results":[{"id":"21","text":"Amersfoort"},{"id":"326","text":"Americana"},...]}
     */
    public static function select2Ajax($form, $model, $fieldName, $url, $data, $options = [], $pluginOptions = [], $selected = [])
    {

        $options = array_merge([
            'placeholder' => \Yii::t('rabint', 'عبارت های مورد نظر را بنویسید'),
            'dir' => 'rtl',
            'multiple' => false,
        ], $options);
        $pluginOptions = array_merge([
            'maximumInputLength' => 100,
            'allowClear' => true,
            'minimumInputLength' => 3,
//            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
//            'templateResult' => new JsExpression('formatRepo'),
//            'templateSelection' => new JsExpression('formatRepoSelection'),
            'ajax' => [
                'url' => $url,
                'delay' => 250,
                'dataType' => 'json',
                'data' => new JsExpression('function(params) { return {q:params.term}; }'),
//                'processResults' => new JsExpression($resultsJs),
                'cache' => true
            ]
        ], $pluginOptions);
        $selected = []; //$model->find()->with('tags');
        return $form->field($model, $fieldName)
            ->widget(
                \kartik\select2\Select2::className(),
                [
                    'value' => $selected, // initial value
                    'maintainOrder' => true,
                    'options' => $options,
                    'data' => $data,
                    'pluginOptions' => $pluginOptions,
                ]
            );
    }

    public static function select2Multiple(
        $form,
        $model,
        $fieldName,
        $data,
        $options = [],
        $pluginOptions = [],
        $selected = []
    )
    {
        $options = array_merge([
            'placeholder' => \Yii::t('rabint', 'عبارت های مورد نظر را بنویسید'),
            'dir' => 'rtl',
            'multiple' => true
        ], $options);
        $pluginOptions = array_merge([
            'tags' => false,
            'maximumInputLength' => 100
        ], $pluginOptions);
        //$selected = []; //$model->find()->with('tags');
        if (!empty($selected)) {
            $model->$fieldName = $selected;
        }
        /* =================================================================== */
        return $form->field($model, $fieldName)
            ->widget(
                \kartik\select2\Select2::className(),
                [
                    'value' => $selected, // initial value
                    'data' => $data,
                    'maintainOrder' => true,
                    'options' => $options,
                    'pluginOptions' => $pluginOptions,
                ]
            );
    }

    public static function select2Tag(
        $form,
        $model,
        $fieldName,
        $data,
        $options = [],
        $pluginOptions = [],
        $selected = []
    )
    {
        $options = array_merge([
            'placeholder' => \Yii::t('rabint', 'عبارت های مورد نظر را بنویسید'),
            'dir' => 'rtl',
            'multiple' => true
        ], $options);
        $pluginOptions = array_merge([
            'tags' => true,
            'maximumInputLength' => 100
        ], $pluginOptions);
        //$selected = []; //$model->find()->with('tags');
        if (!empty($selected)) {
            $model->$fieldName = $selected;
        }
        /* =================================================================== */
        return $form->field($model, $fieldName)
            ->widget(
                \kartik\select2\Select2::className(),
                [
                    'value' => $selected, // initial value
                    'data' => $data,
                    'maintainOrder' => true,
                    'options' => $options,
                    'pluginOptions' => $pluginOptions,
                ]
            );
    }

    /**
     * @param $form
     * @param $model
     * @param $fieldName
     * @param null $default
     * @return ActiveField the created ActiveField object.
     */
    public static function datePickerOld($form, $model, $fieldName, $default = null)
    {
        $model->$fieldName = (empty($model->$fieldName) && $model->isNewRecord) ? $default : $model->$fieldName;
        if (!is_numeric($model->$fieldName)) {
            $model->$fieldName = \rabint\helpers\locality::anyToTimeStamp($model->$fieldName);
        }
        if (!empty($model->$fieldName)) {
            $model->$fieldName = \rabint\helpers\locality::jdate('Y/m/d', $model->$fieldName);
        } else {
            $model->$fieldName = null;
        }

        return $form->field($model, $fieldName)
            ->textInput()
            ->widget(
                \rabint\widgets\DateTimePicker\DateTimePicker::className(),
                [
                    'clientOptions' => [
                        'EnableTimePicker' => false,
                    ],
                ]
            );
    }


    public static function datePickerBs4($form, $model, $fieldName, $default = null, $params = [])
    {
        return static::datePicker($form, $model, $fieldName, $default, $params);
    }

    public static function datePicker($form, $model, $fieldName, $default = null, $params = [])
    {


        $model->$fieldName = (empty($model->$fieldName) && $model->isNewRecord) ? $default : $model->$fieldName;
        if (!is_numeric($model->$fieldName)) {
            $model->$fieldName = \rabint\helpers\locality::anyToTimeStamp($model->$fieldName);
        }
        if (!empty($model->$fieldName)) {
            $model->$fieldName = \rabint\helpers\locality::jdate('Y/m/d', $model->$fieldName);
        } else {
            $model->$fieldName = null;
        }


        $params = array_merge(
            [
                'clientOptions' => [
                    //'EnableTimePicker' => false,
//                    'selectedDate' => $time_to_show,
//                    'selectedDateToShow' => $time_to_show,
                ],
            ],
            $params
        );
        if (\Yii::$app->params['bsVersion'] == "5.x") {
            if (empty(!$model->$fieldName)) {
                $time_to_show = \rabint\helpers\locality::anyToGregorian($model->$fieldName);
            } else {
                $time_to_show = date('Y-m-d');
            }
            $params = array_merge(
                [
                    'clientOptions' => [
                        'selectedDate' => $time_to_show,
                        'selectedDateToShow' => $time_to_show,
                    ],
                ],
                $params
            );

            return $form->field($model, $fieldName)
                ->textInput()
                ->widget(
                    \rabint\widgets\DateTimePickerBs5\DateTimePickerBs5::className(),
                    $params
                );
        } else {
            return $form->field($model, $fieldName)
                ->textInput()
                ->widget(
                    \rabint\widgets\DateTimePickerBs4\DateTimePickerBs4::className(),
                    $params
                );
        }
    }

    public static function locationPicker($form, $model, $fieldName, $default = null, $params = [])
    {

        return $form->field($model, $fieldName)
            ->textInput()
            ->widget(
                \rabint\widgets\LocationPicker\LocationPicker::className(),
                $params
            );
    }


    public static function datePickerStaticOld($attribute, $value = null)
    {
        return \rabint\widgets\DateTimePickerBs4\DateTimePickerBs4::widget(
            [
                "name" => $attribute,
                "value" => $value,
                'clientOptions' => [
                    'EnableTimePicker' => false,
                ],
            ]
        );
    }


    public static function datePickerStatic($attribute, $value = null, $params = [])
    {
        if (!empty($value)) {
            if (!is_numeric($value)) {
                $value = \rabint\helpers\locality::anyToTimeStamp($value);
            }
            $value = \rabint\helpers\locality::jdate('Y/m/d', $value);
        } else {
            $value = null;
        }


        $params = array_merge(
            [
                "name" => $attribute,
                "value" => $value,
                'clientOptions' => [
                    //'EnableTimePicker' => false,
//                    'selectedDate' => $time_to_show,
//                    'selectedDateToShow' => $time_to_show,
                ],
            ],
            $params
        );
        if (\Yii::$app->params['bsVersion'] == "5.x") {

            if (empty(!$value)) {
                $time_to_show = \rabint\helpers\locality::anyToGregorian($value);
            } else {
                $time_to_show = date('Y-m-d');
            }
            $params = array_merge(
                [
                    'clientOptions' => [
                        'selectedDate' => $time_to_show,
                        'selectedDateToShow' => $time_to_show,
                    ],
                ],
                $params
            );


            return \rabint\widgets\DateTimePickerBs5\DateTimePickerBs5::widget($params);
        } else {
            return \rabint\widgets\DateTimePickerBs4\DateTimePickerBs4::widget($params);
        }
    }


    public static function datePickerBs4Static($attribute, $value = null)
    {
        return self::datePickerStatic($attribute, $value);
    }

    public static function datePickerOldStatic($attribute, $value = null)
    {
        return \rabint\widgets\DateTimePicker\DateTimePicker::widget(
            [
                "name" => $attribute,
                "value" => $value,
                'clientOptions' => [
                    'EnableTimePicker' => false,
                ],
            ]
        );
    }

    public static function dateTimePicker($form, $model, $fieldName)
    {
        $model->$fieldName = (empty($model->$fieldName) && $model->isNewRecord) ? time() : $model->$fieldName;
        if (!is_numeric($model->$fieldName)) {
            $model->$fieldName = strtotime($model->$fieldName);
        }
        $model->$fieldName = \rabint\helpers\locality::jdate('Y/m/d H:i:s', $model->$fieldName);
        return $form->field($model, $fieldName)
            ->textInput()
            ->widget(\rabint\widgets\DateTimePicker\DateTimePicker::className(), []);
    }

    public static function dateTimePickerStatic($attribute, $value = null)
    {
        return \rabint\widgets\DateTimePicker\DateTimePicker::widget(
            [
                "name" => $attribute,
                "value" => $value,
                'clientOptions' => [
                    'EnableTimePicker' => true,
                ],
            ]
        );
    }

    public static function colorPicker($form, $model, $fieldName)
    {
    }

    public static function colorPickerStatic($attribute, $value = null)
    {
    }

    public static function wysiwyg($form, $model, $fieldName, $options = [], $widgetOptions = [])
    {
        $widgetOptions = ArrayHelper::merge([
            'plugins' => ['fullscreen', 'fontcolor', 'video'],
            'options' => [
                //                'buttons' => ['formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'indent', 'outdent', 'alignment', 'fullscreen', 'fontcolor', 'backcolor', 'horizontalrule'],
                'minHeight' => 300,
                'maxHeight' => 300,
                'direction' => 'rtl',
                'buttonSource' => true,
                'convertDivs' => false,
                'removeEmptyTags' => false,
                'allowUpload' => true,
                'toolbarLevel' => 'full', //basic,standard
                'imageUpload' => \Yii::$app->urlManager->createUrl(['/attachment/default/wysiwyg-upload']),
                'uploadImageFields' => [
                    Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken()
                ]
            ]
        ], $widgetOptions);

        $widgetOptions['options'] = ArrayHelper::merge($widgetOptions['options'], $options);
        return $form->field($model, $fieldName)->widget(
            \yii\imperavi\Widget::className(),
            $widgetOptions
        );
    }

    public static function tinymce($form, $model, $fieldName, $options = [], $widgetOptions = [])
    {
        //todo: add uploader and handle options
        $cssUrl = \Yii::getAlias('@web') . '/css/tinymce.css';
        $options = ArrayHelper::merge([
            'allowUpload' => true,
            'toolbarLevel' => 'full', //basic,standard
        ], $options);

        $jayParsedAry = array(
            'selector' => 'textarea#full-featured',
            'plugins' => ' preview  importcss  searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap pagebreak nonbreaking anchor  insertdatetime advlist lists  wordcount      help    charmap   quickbars  emoticons  ',
            '_token_provider' => 'URL_TO_YOUR_TOKEN_PROVIDER',
            '_dropbox_app_key' => 'YOUR_DROPBOX_APP_KEY',
            '_google_drive_key' => 'YOUR_GOOGLE_DRIVE_KEY',
            '_google_drive_client_id' => 'YOUR_GOOGLE_DRIVE_CLIENT_ID',
            'mobile' =>
                array(
                    'plugins' => ' preview  importcss  searchreplace autolink autosave save directionality  visualblocks visualchars fullscreen image link media  template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists  wordcount help   charmap  quickbars  emoticons ',
                ),
            'menu' =>
                array(
                    'tc' =>
                        array(
                            'title' => 'Comments',
                            'items' => 'addcomment showcomments deleteallconversations',
                        ),
                ),
            'menubar' => 'file edit view insert format tools table tc help',
            'toolbar' => 'fullscreen| undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignright aligncenter alignleft alignjustify | outdent indent | numlist bullist  | forecolor backcolor    removeformat | pagebreak | charmap emoticons | preview save  | insertfile image media  template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
            'autosave_ask_before_unload' => true,
            'autosave_interval' => '30s',
            'autosave_prefix' => '{path}{query}-{id}-',
            'autosave_restore_when_empty' => false,
            'autosave_retention' => '2m',
            'image_advtab' => true,
            'link_list' =>
                array(
                    0 =>
                        array(
                            'title' => 'My page 1',
                            'value' => 'https://www.tiny.cloud',
                        ),
                    1 =>
                        array(
                            'title' => 'My page 2',
                            'value' => 'http://www.moxiecode.com',
                        ),
                ),
            'image_list' =>
                array(
                    0 =>
                        array(
                            'title' => 'My page 1',
                            'value' => 'https://www.tiny.cloud',
                        ),
                    1 =>
                        array(
                            'title' => 'My page 2',
                            'value' => 'http://www.moxiecode.com',
                        ),
                ),
            'image_class_list' =>
                array(
                    0 =>
                        array(
                            'title' => 'None',
                            'value' => '',
                        ),
                    1 =>
                        array(
                            'title' => 'Some class',
                            'value' => 'class-name',
                        ),
                ),
            'importcss_append' => true,
            'templates' =>
                array(
                    0 =>
                        array(
                            'title' => 'New Table',
                            'description' => 'creates a new table',
                            'content' => '<div class="mceTmpl"><table width="98%%" border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>',
                        ),
                    1 =>
                        array(
                            'title' => 'Starting my story',
                            'description' => 'A cure for writers block',
                            'content' => 'Once upon a time...',
                        ),
                    2 =>
                        array(
                            'title' => 'New list with dates',
                            'description' => 'New List with dates',
                            'content' => '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>',
                        ),
                ),
            'template_cdate_format' => '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            'template_mdate_format' => '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            'height' => 600,
            'image_caption' => true,
            'quickbars_selection_toolbar' => 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            '__class' => 'mceNonEditable',
            'toolbar_mode' => 'sliding',
            'spellchecker_ignore_list' =>
                array(
                    0 => 'Ephox',
                    1 => 'Moxiecode',
                ),
            '_mode' => 'embedded',
            'content_style' => '.mymention{ color: gray; }',
            'contextmenu' => 'link image  table configure',
            'a11y_advanced_options' => true,
            'skin' => 'oxide',
            'content_css' => 'default',
            '_selector' => '.mymention',
            '_fetch' => '_fetch',
            '_menu_hover' => '_menu_hover',
            '_menu_complete' => '_menu_complete',
            '_select' => '_select',
            '_item_type' => 'profile',
        );


        $widgetOptions = ArrayHelper::merge([
            'options' => ['rows' => 18],
            'language' => 'fa',
            'clientOptions' => [
                'menubar' => true,
                'directionality' => 'rtl',
                'content_css' => $cssUrl,
                'plugins' => [
                    "advlist autolink lists link charmap print preview anchor directionality",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste ",
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ],
                'toolbar' => 'fullscreen | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignright aligncenter alignleft alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment | undo redo ',
                'content_style' => 'body { font-family:vazirmant,sahel, Helvetica,Arial,sans-serif; font-size:14px }'
            ],
            'clientOptions' => $jayParsedAry
        ], $widgetOptions);
        return $form->field($model, $fieldName)->widget(\dosamigos\tinymce\TinyMce::className(), $widgetOptions);
    }

    public static function wysiwygStatic($attribute, $value = null, $options = [], $widgetOptions = [])
    {
        $widgetOptions = ArrayHelper::merge([
            'attribute' => $attribute,
            'value' => $value,
            'plugins' => ['fullscreen', 'fontcolor', 'video'],
            'options' => [
                //                'buttons' => ['formatting', 'bold', 'italic', 'underline', 'deleted', 'unorderedlist', 'orderedlist', 'indent', 'outdent', 'alignment', 'fullscreen', 'fontcolor', 'backcolor', 'horizontalrule'],
                'minHeight' => 300,
                'maxHeight' => 300,
                'buttonSource' => true,
                'convertDivs' => false,
                'removeEmptyTags' => false,
                'allowUpload' => true,
                'toolbarLevel' => 'full', //basic,standard
                'imageUpload' => \Yii::$app->urlManager->createUrl(['/attachment/default/wysiwyg-upload']),
                'imageUploadParam' => 'file',
                'uploadImageFields' => [
                    Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken()
                ]
            ]
        ], $widgetOptions);

        $widgetOptions['options'] = ArrayHelper::merge($widgetOptions['options'], $options);
        return \yii\imperavi\Widget::widget($widgetOptions);
    }

    public static function uploaderProtected($form, $model, $fieldName, $options = [])
    {
        $url = ['/attachment/default/file-upload-protected'];
        $options = ArrayHelper::merge([
            'url' => $url,
        ], $options);
        return self::uploader($form, $model, $fieldName, $options);
    }

    public static function uploader($form, $model, $fieldName, $options = [])
    {
        if (!$model->isNewRecord) {
            $options['files'] = \rabint\attachment\models\Attachment::getUploaderFileAttribute($model->$fieldName);
        }
        $modelName = (new ReflectionClass($model))->getShortName();
        if (isset($options['url'])) {
            $url = $options['url'];
        } else {
            $url = ['/attachment/default/file-upload'];
        }
        if (isset($options['serverOption'])) {
            $url['opt'] = $options['serverOption'];
            unset($options['serverOption']);
        }
        //http://localhost/rtv/attachment/default/file-upload?opt[multipleOptions][type]=image&opt[multiple]=1&fileparam=_fileinput_w1
        $options = ArrayHelper::merge([
            //                    'model' => $model,
            //                    'attribute' => $fieldName,
            'name' => $modelName . '[' . $fieldName . ']',
            'url' => $url,
            'options' => [
                'id' => strtolower($modelName . '-' . $fieldName),
            ],
            'sortable' => true,
            'returnType' => 'id', //url,dir or path,full
            'maxFileSize' => 10 * 1024 * 1024, // 10Mb
            //                    'minFileSize' => 1 * 1024 * 1024, // 1Mb
            'maxNumberOfFiles' => 1, //3, // default 1,
            'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            'clientOptions' => [
                //                        'start' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'done' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'fail' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'always' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'formData' => ['example' => 'test']
            ]
        ], $options);
        return \rabint\attachment\widgets\upload\Upload::widget($options);
        /* ------------------------------------------------------ */
        //        return $form->field($model, $fieldName)->widget(
        //                        \rabint\attachment\widgets\upload\Upload::className(), [
        //                    'url' => ['/attachment/default/image-upload'],
        //                    'maxFileSize' => 5000000, // 5 MiB
        //                    'sortable' => true,
        //                    'maxNumberOfFiles' => 10
        //                ])->label(FALSE);
    }

    public static function uploaderStatic($attribute, $value = null, $options = [])
    {
        if ($value) {
            $options['files'] = \rabint\attachment\models\Attachment::getUploaderFileAttribute($value);
        }

        if (isset($options['url'])) {
            $url = $options['url'];
        } else {
            $url = ['/attachment/default/file-upload'];
        }

        if (isset($options['serverOption'])) {
            $url['opt'] = $options['serverOption'];
            unset($options['serverOption']);
        }
        $options = ArrayHelper::merge([
            'name' => $attribute,
            'url' => $url,
            'options' => [
                'id' => strtolower($attribute),
            ],
            'sortable' => true,
            'returnType' => 'id', //url,dir or path,full
            'maxFileSize' => 10 * 1024 * 1024, // 10Mb
            //                    'minFileSize' => 1 * 1024 * 1024, // 1Mb
            'maxNumberOfFiles' => 1, //3, // default 1,
            'acceptFileTypes' => new JsExpression('/(\.|\/)(gif|jpe?g|png)$/i'),
            'clientOptions' => [
                //                        'start' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'done' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'fail' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'always' => new JsExpression('function(e, data) { ... do something ... }'),
                //                        'formData' => ['example' => 'test']
            ]
        ], $options);
        return \rabint\attachment\widgets\upload\Upload::widget($options);
    }

    public static function wysiwygF($form, $model, $fieldName, $options = [], $widgetOptions = [])
    {

        $widgetOptions = ArrayHelper::merge(static::wysiwygDefaultOptions(), $widgetOptions);

        $widgetOptions['options'] = ArrayHelper::merge($widgetOptions['options'], $options);


        return $form->field($model, $fieldName)->widget(
            \froala\froalaeditor\FroalaEditorWidget::className(),
            [
                'model' => $model,
                'attribute' => $fieldName,
                'options' => $options,
                'clientOptions' => $widgetOptions,
            ]
        );
        //        'imageUpload' =>
        //        'uploadImageFields' => [
        //
        //        ]
    }

    public static function wysiwygFStatic($attribute, $value = null, $options = [], $widgetOptions = [])
    {
        $widgetOptions = ArrayHelper::merge(static::wysiwygDefaultOptions(), $widgetOptions);

        $widgetOptions['options'] = ArrayHelper::merge($widgetOptions['options'], $options);
        return \froala\froalaeditor\FroalaEditorWidget::widget([
            'name' => $attribute,
            'value' => $value,
            'options' => $options,
            'clientOptions' => $widgetOptions,
        ]);
    }

    public static function wysiwygDefaultOptions()
    {
        $widgetOptions = [
            'zIndex' => '1000',
            //                        'toolbarSticky' => TRUE,
            'toolbarStickyOffset' => 50,
            'height' => 300, //  heightMin: 100, heightMax: 200
            'toolbarButtons' => [ //paragraphFormat , paragraphStyle
                'fullscreen',
                'html',
                "paragraphFormat",
                'color', //'fontFamily', 'fontSize', '', 'inlineStyle','|',
                'bold',
                'italic',
                'underline',
                'strikeThrough',
                '|', //'subscript', 'superscript',
                'align',
                'formatOL',
                'formatUL',
                'quote', //'outdent', 'indent',
                //                            '-',
                'insertLink',
                'insertImage',
                'insertTable',
                '|', //insertFile 'insertVideo',
                'insertHR',
                'emoticons', //'specialCharacters', // '|', '|',
                //                            'clearFormatting', '|',// '|', 'undo', 'redo'//'print' 'selectAll','help',
            ],
            //                        'toolbarButtonsMD' => ['undo', 'redo', '-', 'bold', 'italic', 'underline'],
            //                        'toolbarButtonsSM' => ['undo', 'redo', '-', 'bold', 'italic', 'underline'],
            //                        'toolbarButtonsXS' => ['undo', 'redo', '-', 'bold', 'italic', 'underline'],
            'toolbarInline' => false,
            'imageUploadURL' => \Yii::$app->urlManager->createUrl(['/attachment/default/wysiwyg-upload']),
            'imageUploadParams' => [Yii::$app->request->csrfParam => Yii::$app->request->getCsrfToken()],
            'theme' => 'gray', //optional: dark, red, gray, royal
            'language' => substr(\Yii::$app->language, 0, 2)
        ];

        //        if(\rabint\helpers\user::can(\common\models\User::ROLE_MANAGER)){
        $widgetOptions['toolbarButtons'] += ['undo', 'redo'];
        //        }

        return $widgetOptions;
    }
}
