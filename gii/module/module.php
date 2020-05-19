<?php
/**
 * This is the template for generating a module class file.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\module\Generator */

$className = $generator->moduleClass;
$pos = strrpos($className, '\\');
$ns = ltrim(substr($className, 0, $pos), '\\');
$className = substr($className, $pos + 1);

echo "<?php\n";
?>

namespace <?= $ns ?>;

/**
 * <?= $generator->moduleID ?> module definition class
 */
class <?= $className ?> extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = '<?= $generator->getControllerNamespace() ?>';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
    
    
    public static function adminMenu() {
        return [
            'label' => \Yii::t('rabint', '<?= $generator->moduleID ?>'),
            'icon' => '<i class="fas fa-edit"></i>',
            'url' => '#',
            'options' => ['class' => 'treeview'],
            'items' => [
                [
                    'label' => \Yii::t('rabint', 'manage <?= $generator->moduleID ?>'), 'url' => '#', 'icon' => '<i class="far fa-circle"></i>',
                    'options' => ['class' => 'treeview'],
                    'items' => [
                        [
                            'label' => \Yii::t('rabint', 'all <?= $generator->moduleID ?>'), 'url' => ['/post/admin'], 'icon' => '<i class="far fa-circle"></i>',
                        ],
                        ['label' => \Yii::t('rabint', 'new <?= $generator->moduleID ?>'), 'url' => ['/post/admin-comment'], 'icon' => '<i class="far fa-circle"></i>'],
                    ],
                ],
                ['label' => \Yii::t('rabint', 'برچسب'), 'url' => ['/post/admin-tag'], 'icon' => '<i class="far fa-circle"></i>'],
                ['label' => \Yii::t('rabint', 'گروه ها'), 'url' => ['/post/admin-group'], 'icon' => '<i class="far fa-circle"></i>'],
            ]
        ];
    }
}
