<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $queryClassName string query class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */

/* @var $relations array list of relations (name => relation declaration) */

use rabint\helpers\str;

if ($generator->standardizeCapitals) {
    $prefix = str::before($tableName, '_');
    if (strcmp($tableName, $prefix) !== 0) {
        $newRelations = [];
        $newRule = $rules;
        $ucPrefix = ucfirst($prefix);
        /**
         * fix classname
         */
        $className = str::replacePrefix($ucPrefix, '', $className);
        /**
         * fix relataions
         */
        foreach ($relations as $key => $relation) {
            $newKey = str::replacePrefix($ucPrefix, '', $key);

            $newRelName = str::replacePrefix($ucPrefix, '', $relation[1]);

            $newRelations[$newKey] = [
                str_replace($relation[1], $newRelName, $relation[0]),
                $newRelName,
                $relation[2],
            ];

            foreach ($newRule as &$rule) {
                if (strpos($rule, 'targetClass') && strpos($rule, $relation[1])) {
                    $rule = str_replace($relation[1], $newRelName, $rule);
                }
            }
        }
    }
    $relations = $newRelations;
    $rules = $newRule;
}

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;

use Yii;
use common\models\User;

/**
* This is the model class for table "<?= $generator->generateTableName($tableName) ?>".
*
<?php foreach ($tableSchema->columns as $column): ?>
    * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
    *
    <?php foreach ($relations as $name => $relation): ?>
        * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
    <?php endforeach; ?>
<?php endif; ?>
*/
class <?= $className ?> extends \common\models\base\ActiveRecord     /* <?= '\\' . ltrim($generator->baseClass, '\\') ?> */
{
const SCENARIO_CUSTOM = 'custom';
/* statuses */
const STATUS_DRAFT = 0;
const STATUS_PENDING = 1;
const STATUS_PUBLISH = 2;

/**
* @inheritdoc
*/
public static function tableName()
{
return '<?= $generator->generateTableName($tableName) ?>';
}
<?php if ($generator->db !== 'db'): ?>

    /**
    * @return \yii\db\Connection the database connection used by this AR class.
    */
    public static function getDb()
    {
    return Yii::$app->get('<?= $generator->db ?>');
    }
<?php endif; ?>


public function behaviors() {
return [
[
'class' => \yii\behaviors\TimestampBehavior::class,
'createdAtAttribute' => 'created_at',
'updatedAtAttribute' => 'updated_at',
'value' => time(),
],
[
'class' => \yii\behaviors\BlameableBehavior::class,
'createdByAttribute' => 'created_by',
'updatedByAttribute' => 'updated_by',
],
// [
//     'class' =>\rabint\behaviors\SoftDeleteBehavior::class,
//     'attribute' => 'deleted_at',
//     'attribute' => 'deleted_by',
// ],
/*[
'class' => \rabint\behaviors\Slug::class,
'sourceAttributeName' => 'title', // If you want to make a slug from another attribute, set it here
'slugAttributeName' => 'slug', // Name of the attribute containing a slug
],*/
];
}

public function scenarios() {
$scenarios = parent::scenarios();
// $scenarios[self::SCENARIO_CUSTOM] = ['status'];
return $scenarios;
}


/* ====================================================================== */

public static function statuses() {
return [
static::STATUS_DRAFT => ['title' => \Yii::t('rabint', 'draft')],
static::STATUS_PENDING => ['title' => \Yii::t('rabint', 'pending')],
static::STATUS_PUBLISH => ['title' => \Yii::t('rabint', 'publish')],
];
}

/* ====================================================================== */

/**
* @inheritdoc
*/
public function rules()
{
return [<?= "\n            " . implode(",\n            ", $rules) . ",\n        " ?>];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
<?php foreach ($labels as $name => $label): ?>
    <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
];
}

/**
* @inheritdoc
*/
public function beforeSave($insert)
{
//if(!empty($this->publish_at)){
//    $this->publish_at = \rabint\helpers\locality::anyToGregorian($this->publish_at);
//    $this->publish_at = strtotime($this->publish_at);// if timestamp needs
//}
return parent::beforeSave($insert);
}


<?php foreach ($relations as $name => $relation): ?>

    /**
    * @return \common\models\base\ActiveQuery
    */
    public function get<?= $name ?>()
    {
    <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
<?php if ($queryClassName): ?>
    <?php
    $queryClassFullName = ($generator->ns === $generator->queryNs) ? $queryClassName : '\\' . $generator->queryNs . '\\' . $queryClassName;
    echo "\n";
    ?>
    /**
    * @inheritdoc
    * @return <?= $queryClassFullName ?> the active query used by this AR class.
    */
    public static function find()
    {
    return new <?= $queryClassFullName ?>(get_called_class());
    }
<?php else: ?>
    /**
    * @inheritdoc
    * @return \rabint\models\query\PublishQuery the active query used by this AR class.
    */
    //public static function find()
    //{
    //    $publishQuery = new \rabint\models\query\PublishQuery(get_called_class());
    //    $publishQuery->statusField="status";
    //    $publishQuery->activeStatusValue=self::STATUS_PUBLISH;
    //    $publishQuery->ownerField="creator_id";
    //    $publishQuery->showNotActiveToOwners=true;
    //    return $publishQuery;
    //}
<?php endif; ?>

}
