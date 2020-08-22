<?php


namespace rabint\widgets;


use Yii;
use dosamigos\gallery\DosamigosAsset;
use dosamigos\gallery\Gallery;
use dosamigos\gallery\GalleryAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class GalleryButton extends Gallery
{
    public $buttonLabel = 'gallery';
    public $buttonClass = "btn btn-danger";
    public $buttonTitle = "gallery view";

    public function renderItems()
    {
        if (isset($this->items[0])) {
            $src = ArrayHelper::getValue($this->items[0], 'src');
            $url = ArrayHelper::getValue($this->items[0], 'url', $src);
            unset($this->items[0]);
        }
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $this->renderItem($item);
        }
        return Html::tag('div',
            implode("\n", array_filter($items)) .
            Html::a(Yii::t('app', $this->buttonLabel), $url, ['title' => $this->buttonTitle, 'class' => $this->buttonClass . ' gallery-item'])
            , $this->options);
    }

    /**
     * @param mixed $item
     * @return null|string the item to render
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return Html::a(Html::img($item), $item, ['class' => 'gallery-item']);
        }
        $src = ArrayHelper::getValue($item, 'src');
        if ($src === null) {
            return null;
        }
        $url = ArrayHelper::getValue($item, 'url', $src);
        $options = ArrayHelper::getValue($item, 'options', []);
        $imageOptions = ArrayHelper::getValue($item, 'imageOptions', []);
        Html::addCssClass($options, 'gallery-item d-none');

        return Html::a(Html::img($src, $imageOptions), $url, $options);
    }


    /**
     * Registers the client script required for the plugin
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        GalleryAsset::register($view);
        DosamigosAsset::register($view);

        $id = $this->options['id'];
        $options = Json::encode($this->clientOptions);
        $js = "dosamigos.gallery.registerLightBoxHandlers('#$id a', $options);";
        $view->registerJs($js);

        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('$id').on('$event', $handler);";
            }
            $view->registerJs(implode("\n", $js));
        }
    }
}