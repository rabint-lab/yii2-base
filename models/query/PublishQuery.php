<?php

namespace rabint\models\query;

use rabint\filters\EnvironmentFilter;
use rabint\helpers\user;

/**
 * Created by PhpStorm.
 * User: mojtaba
 * Date: 3/16/19
 * Time: 6:36 PM
 */
class PublishQuery extends \yii\db\ActiveQuery
{

    public $statusField = 'status';
    public $ownerField = 'user_id';
    public $activeStatusValue = 0;
    public $showNotActiveToOwners = true;

    public function filters()
    {
        if ($this->showNotActiveToOwners) {
            $user_id = \rabint\helpers\user::id();
            $this->andWhere(
                [
                    'OR',
                    [$this->statusField => $this->activeStatusValue],
                    [$this->ownerField => $user_id]
                ]
            );
        } else {
            $this->andWhere([$this->statusField => $this->activeStatusValue]);
        }
    }
    public function doFilters()
    {
        if (EnvironmentFilter::getEnv() != EnvironmentFilter::ENV_ADMIN) {
            $this->filters();
        }
    }


    public function allNoFilter($db = null)
    {
        return parent::all($db);
    }
    public function all($db = null)
    {
        $this->doFilters();
        return parent::all($db);
    }
    public function hidden($db = null)
    {

        if (EnvironmentFilter::getEnv() != EnvironmentFilter::ENV_ADMIN) {
            $this->andWhere(['!=', $this->statusField, $this->activeStatusValue]);
        }
        return parent::one($db);
    }

    public function one($db = null)
    {
        $this->doFilters();
        return parent::one($db);
    }

    public function count($q = '*', $db = null)
    {
        $this->doFilters();
        return parent::count($q, $db);
    }

    public function published()
    {
        $this->filters();
        return $this;
    }
}
