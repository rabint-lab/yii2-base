<?php

namespace rabint\behaviors\nestedset;

use yii\base\Behavior;
use yii\db\Expression;

/**
 * NestedSetsQueryBehavior
 *
 * @property \common\models\base\ActiveQuery $owner
 *
 */
class NestedSetsQueryBehavior extends Behavior {

    /**
     * Gets the root nodes.
     * @return \common\models\base\ActiveQuery the owner
     */
    public function roots() {
        $model = new $this->owner->modelClass();

        $this->owner
                ->andWhere([$model->leftAttribute => 1])
                ->addOrderBy([$model->primaryKey()[0] => SORT_ASC]);

        return $this->owner;
    }

    /**
     * Gets the leaf nodes.
     * @return \common\models\base\ActiveQuery the owner
     */
    public function leaves() {
        $model = new $this->owner->modelClass();
        $db = $model->getDb();

        $columns = [$model->leftAttribute => SORT_ASC];

        if ($model->treeAttribute !== false) {
            $columns = [$model->treeAttribute => SORT_ASC] + $columns;
        }

        $this->owner
                ->andWhere([$model->rightAttribute => new Expression($db->quoteColumnName($model->leftAttribute) . '+ 1')])
                ->addOrderBy($columns);

        return $this->owner;
    }

    public function treeSelect($show_field = 'title') {
//         $this->owner->andWhere(['status' => \app\modules\post\models\Post::STATUS_PUBLISH]);
//        return $this->owner;
        $cats = $this->owner->orderBy('root ASC ,lft ASC')->asArray()->all();
        foreach ($cats as &$cat) {
            $cat[$show_field] = str_repeat('-', $cat['level']) . $cat[$show_field];
        }
        return \yii\helpers\ArrayHelper::map($cats, 'id', $show_field);
    }

    public function nodeSelect($show_field = 'title') {
        $cats = $this->owner->andWhere(['lft' => new Expression('rgt-1')])->orderBy('root ASC ,lft ASC')->all();
//        foreach ($cats as &$cat) {
//            $cat[$show_field] = str_repeat('-', $cat['level']) . $cat[$show_field];
//        }
        return \yii\helpers\ArrayHelper::map($cats, 'id', $show_field);
        ;
    }

}
