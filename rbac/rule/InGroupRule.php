<?php
/**
 * Eugine Terentev <eugine@terentev.net>
 */

namespace rabint\rbac\rule;

use Yii;
use app\modules\group\models\Grade;
use app\modules\group\models\GroupUser;
use app\modules\group\models\GroupPackage;
use app\modules\webinar\models\Room;
use app\modules\webinar\models\Meeting;
use yii\base\InvalidConfigException;
use yii\rbac\Item;
use yii\rbac\Rule;

class InGroupRule extends Rule
{
    /** @var string */
    public $name = 'InGroup';
    public $package;

    /**
     * @param int $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return bool a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $package = GroupPackage::GetActivePackage($params['group_id']);
        if($package == null){
            Yii::$app->session->setFlash('danger 1',Yii::t('rabint','پلن دسترسی فعالی برای شما یافت نشد .در صورت نیاز با مدیر مجموعه تماس حاصل فرمایید'));
            return false;
        }else{
            $this->package = $package;
        }
        
        if (!isset($params['group_id'])) {
            throw new InvalidConfigException("Please set group_id in `can` action params");
        }
        if (!$user) {
            return false;
        }
        switch($params['action']){
            case 'createRoom':
                return $this->canCreateRoom($user,$params);
            break;
            
            case 'joinRoom':
                return $this->canJoinRoom($user,$params);
            break;
            default:
                return false;
            break;
        }
        
    }
    
    
    public function getFutureClass($service){
        $list = \app\modules\group\components\ServiceAbstract::availableServices();
        $serviceClass = $list[$service]['class'];
        $class = new $serviceClass;
        return $class;
    }
    
    public function canCreateRoom($user,$params){

//        $package = GroupPackage::GetActivePackage($params['group_id']);
        $roomCount = Room::find()->where(['organization_id'=>$params['group_id']])->count();
        $featureValues = json_decode($this->package->package->feature, 1);
        
        $grade_level = $item->data['grade_level'] = 40; # minimum level for access to manage room
        $grades = Grade::find()
            ->andWhere(['>=', 'level', 40])
            ->andWhere([
                    'OR',
                    ['group_id' => null],
                    ['group_id' => $params['group_id']],
                ]
            )
            ->select('id')
            ->column();

        $hasInGroup = GroupUser::find()
            ->andWhere([
                'group_id' => $params['group_id'],
                'user_id' => $user,
                'grade_id' => $grades,
            ])
            ->count();
        if($featureValues['rooms']>=0 and $featureValues['rooms'] < $roomCount or !$hasInGroup){
            return false;
            // room limit reached or user not access
        }
        return true;
    }
    
    public function canJoinRoom($user,$params){
        
        $featureValues = json_decode($this->package->package->feature, 1);
        $activeMembers = Meeting::getActiveMembers($params['group_id']);
        if($featureValues['user'] >= 0 and $activeMembers['total'] >= $featureValues['user'] ){
            return false;
        }
        return true;
    }
}
