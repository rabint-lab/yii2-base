<?php

namespace rabint\helpers;

use Yii;
use common\models\User as UserClass;

/**
 * Author: Mojtaba Akbarzadeh
 * Author Email: akbarzadeh.mojtaba@gmail.com
 */
class user
{

    public static $userClass = "\common\models\User";
    public static $profileClass = "\common\models\UserProfile";

    public static function isGuest()
    {
        return Yii::$app->user->isGuest;
    }

    /**
     *
     * @param type $userId
     * @return \common\models\User
     */
    public static function object($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->identity;
        }
        return \common\models\User::findOne($userId);
    }

    /**
     * @return \common\models\UserProfile;
     */
    public static function profile($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->identity->userProfile;
        }
        $profileClass = static::$profileClass;
        return $profileClass::findOne(['user_id' => $userId]);
    }

    public static function realIP()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return Yii::$app->request->getUserIP();
    }

    public static function id()
    {
        return Yii::$app->user->getId();
    }

    public static function ip()
    {
        return Yii::$app->getRequest()->getUserIP();
    }

    public static function agent()
    {
        return Yii::$app->getRequest()->getUserAgent();
    }

    public static function roles($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        return Yii::$app->authManager->getRolesByUser($userId);
    }

    public static function role($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        $roles = Yii::$app->authManager->getRolesByUser($userId);
        if (is_array($roles) AND count($roles)) {
            $role = array_shift($roles);
            return $role->name;
        }
        return null;
    }

    public static function roleTitle($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        $roles = Yii::$app->authManager->getRolesByUser($userId);
        if (is_array($roles) AND count($roles)) {
            $role = array_shift($roles);
            return $role->description ?: $role->name;
        }
        return null;
    }

    public static function hasRole($role, $userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        $userRoles = self::roles($userId);
        return (isset($userRoles[$role])) ? true : false;
    }

    public static function name($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->identity->displayName;
        }
        return static::object($userId)->displayName;
    }

    public static function username($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->username;
        }
        return static::object($userId)->username;
    }

    public static function email($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->identity->email;
        }
        return static::object($userId)->email;
    }

    public static function cell($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->identity->userProfile->cell;
        }
        return static::profile($userId)->cell;
    }

    public static function can($premision, $params = [], $allowCaching = true)
    {
        return Yii::$app->user->can($premision, $params, $allowCaching);
    }

    public static function userCan($userId = null, $premision, $params = [])
    {
        $accessChecker = Yii::$app->getAuthManager();
        if ($accessChecker === null) {
            return false;
        }
        $access = $accessChecker->checkAccess($userId, $premision, $params);
        return $access;
    }

    public static function officialName($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            return Yii::$app->user->identity->OfficialName;
        }
        $userClass = static::$userClass;
        $user = $userClass::findOne($userId);
        return $user->OfficialName;
    }

    public static function premisions($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        return Yii::$app->authManager->getPermissionsByUser($userId);
    }

    public static function accessRules($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        $rules = ['*'];
        if (!empty($userId)) {
            array_push($rules, '@');
            array_push($rules, Yii::$app->user->id);
        } else {
            array_push($rules, '?');
        }
        $prems = self::premisions($userId);
        foreach ((array)$prems as $prm) {
            array_push($rules, $prm->name);
        }
        $roles = self::roles($userId);
        foreach ((array)$roles as $rol) {
            array_push($rules, $rol->name);
        }
        return $rules;
    }

    public static function NotAccessRules($userId = null)
    {
        if (empty($userId)) {
            if (static::isGuest()) {
                return null;
            }
            $userId = self::id();
        }
        $notRules = [];
        foreach ((array)self::accessRules($userId) as $rule) {
            $notRules[] = '!' . $rule;
        }
        return $notRules;
    }

    public static function userClasses($userId = null)
    {
        if (!$userId && static::isGuest()) {
            return 'user-gender-unknown  user-maturity-unknown';
        }
        $profile = static::profile($userId);
        $return = "";
        /**
         * check user gender
         */
        if ($profile->gender == \common\models\UserProfile::GENDER_MALE) {
            $return .= " user-gender-male";
        } elseif ($profile->gender == \common\models\UserProfile::GENDER_FEMALE) {
            $return .= " user-gender-female";
        } else {
            $return .= " user-gender-unknown";
        }
        /* ------------------------------------------------------ */
        /**
         * check user maturity
         */
        $maturity = static::userMaturity($userId);

        switch ($maturity) {
            case UserClass::MATURITY_A:
                $return .= " user-maturity-a";
                break;
            case UserClass::MATURITY_B:
                $return .= " user-maturity-b";
                break;
            case UserClass::MATURITY_C:
                $return .= " user-maturity-c";
                break;
            case UserClass::MATURITY_D:
                $return .= " user-maturity-d";
                break;
            case UserClass::MATURITY_E:
                $return .= " user-maturity-e";
                break;
            case UserClass::MATURITY_F:
                $return .= " user-maturity-f";
                break;
//            case UserClass::MATURITY_G:
//                $return .= " user-maturity-g";
//                break;
            case UserClass::MATURITY_UNKNOWN:
            default:
                $return .= " user-maturity-unknown";
                break;
        }
        return $return;
    }

    public static function userMaturity($userId = null)
    {
        if (!$userId && static::isGuest()) {
            return \common\models\User::MATURITY_UNKNOWN;
        }
        $profile = static::profile($userId);
        if (empty($profile->brithdate)) {
            return \common\models\User::MATURITY_UNKNOWN;
        }
        $old = (time() - (int)$profile->brithdate) / \rabint\cheatsheet\Time::SECONDS_IN_A_YEAR;
        if ($old < 1) {
            return \common\models\User::MATURITY_UNKNOWN;
        } elseif ($old < 4) {
            return \common\models\User::MATURITY_A;
        } elseif ($old < 7) {
            return \common\models\User::MATURITY_B;
        } elseif ($old < 12) {
            return \common\models\User::MATURITY_C;
        } elseif ($old < 19) {
            return \common\models\User::MATURITY_D;
        } elseif ($old < 30) {
            return \common\models\User::MATURITY_E;
//        } elseif ($old < 14) {
//            return \common\models\User::MATURITY_F;
//        } elseif ($old < 18) {
//            return \common\models\User::MATURITY_G;
        }
        return \common\models\User::MATURITY_F;
    }

    public static function userGender($userId = null)
    {
        if (!$userId && static::isGuest()) {
            return 0;
        }
        return (int)static::profile($userId)->gender;
    }

    /**
     * use \rabint\helpers\user::object instead
     * @deprecated since version 3.0.0
     */
    public static function record($userId = null)
    {
        return static::object($userId);
    }

    /**
     * use \rabint\helpers\user::object instead
     * @deprecated since version 3.0.0
     */
    public static function data($userId = null)
    {
        return static::object($userId);
    }

}
