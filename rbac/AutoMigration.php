<?php

namespace rabint\rbac;


/**
 * @author Eugene Terentev <eugene@terentev.net>
 */
Abstract class AutoMigration extends Migration
{
    abstract public function roles();

    abstract public function permissions();

    abstract public function rules();

    abstract public function seeds();

    public function up()
    {

        /* create */
        foreach ($this->roles() as $roles => $options) {
            $newRoles = $this->auth->createRole($roles);
//            if (isset($options['rule_name']))
//                $newRoles->rule_name = $options['rule_name'];
            if (isset($options['description'])) {
                $newRoles->description = $options['description'];
            }
            if (isset($options['data'])) {
                $newRoles->data = $options['data'];
            }
            $this->auth->add($newRoles);
        }
        foreach ($this->permissions() as $permissions => $options) {
            $newPermissions = $this->auth->createPermission($permissions);
//            if (isset($options['rule_name']))
//                $newPermissions->rule_name = $options['rule_name'];
            if (isset($options['description'])) {
                $newPermissions->description = $options['description'];
            }
            if (isset($options['data'])) {
                $newPermissions->data = $options['data'];
            }
            $this->auth->add($newPermissions);
        }
        foreach ($this->rules() as $rule => $options) {
            $ruleClass = $options['class'];
            $ruleObj = new $ruleClass;
            if (isset($options['objConfig'])) {
                foreach ($options['objConfig'] as $key => $conf) {
                    $ruleObj->$key = $conf;
                }
            }
            $this->auth->add($ruleObj);

            $newRules = $this->auth->createPermission($rule);
            $newRules->ruleName = $ruleObj->name;
            if (isset($options['description'])) {
                $newRules->description = $options['description'];
            }
            if (isset($options['data'])) {
                $newRules->data = $options['data'];
            }

            $this->auth->add($newRules);
        }
        /* =================================================================== */

        foreach ($this->roles() as $roles => $options) {
            $newRoles = $this->auth->getRole($roles);
            if (isset($options['children'])) {
                foreach ($options['children'] as $item) {
                    $itemC = $this->auth->getRole($item);
                    if (empty($itemC)) {
                        $itemC = $this->auth->getPermission($item);
                    }
                    if (empty($itemC)) {
                        echo "\tAlert: `$item` item not Exists! \n";
                        continue;
                    }

                    if (!$this->auth->hasChild($newRoles, $itemC)) {
                        $this->auth->addChild($newRoles, $itemC);
                    }
                }
            }
            if (isset($options['parents'])) {
                foreach ($options['parents'] as $item) {
                    $itemC = $this->auth->getRole($item);
                    if (empty($itemC)) {
                        $itemC = $this->auth->getPermission($item);
                    }
                    if (empty($itemC)) {
                        echo "\tAlert: `$item` item not Exists! \n";
                        continue;
                    }

                    if (!$this->auth->hasChild($itemC, $newRoles)) {
                        $this->auth->addChild($itemC, $newRoles);
                    }
                }
            }
        }

        /* =================================================================== */


        foreach ($this->permissions() as $permissions => $options) {
            $newPermissions = $this->auth->getPermission($permissions);
            if (isset($options['children'])) {
                foreach ($options['children'] as $item) {
                    $itemC = $this->auth->getRole($item);
                    if (empty($itemC)) {
                        $itemC = $this->auth->getPermission($item);
                    }
                    if (empty($itemC)) {
                        echo "\tAlert: `$item` item not Exists! \n";
                        continue;
                    }
                    if (!$this->auth->hasChild($newPermissions, $itemC)) {
                        $this->auth->addChild($newPermissions, $itemC);
                    }
                }
            }
            if (isset($options['parents'])) {
                foreach ($options['parents'] as $item) {
                    $itemC = $this->auth->getRole($item);
                    if (empty($itemC)) {
                        $itemC = $this->auth->getPermission($item);
                    }
                    if (empty($itemC)) {
                        echo "\tAlert: `$item` item not Exists! \n";
                        continue;
                    }
                    if (!$this->auth->hasChild($itemC, $newPermissions)) {
                        $this->auth->addChild($itemC, $newPermissions);
                    }
                }
            }
        }
        /* =================================================================== */


        foreach ($this->rules() as $rule => $options) {
            $newRules = $this->auth->getPermission($rule);
            if (isset($options['children'])) {
                foreach ($options['children'] as $item) {
                    $itemC = $this->auth->getRole($item);
                    if (empty($itemC)) {
                        $itemC = $this->auth->getPermission($item);
                    }
                    if (empty($itemC)) {
                        echo "\tAlert: `$item` item not Exists! \n";
                        continue;
                    }
                    if (!$this->auth->hasChild($newRules, $itemC)) {
                        $this->auth->addChild($newRules, $itemC);
                    }
                }
            }
            if (isset($options['parents'])) {
                foreach ($options['parents'] as $item) {
                    $itemC = $this->auth->getRole($item);
                    if (empty($itemC)) {
                        $itemC = $this->auth->getPermission($item);
                    }
                    if (empty($itemC)) {
                        echo "\tAlert: `$item` item not Exists! \n";
                        continue;
                    }
                    if (!$this->auth->hasChild($itemC, $newRules)) {
                        $this->auth->addChild($itemC, $newRules);
                    }
                }
            }
        }

        /* =================================================================== */

        foreach ($this->seeds() as $Role) {
            $users = (!is_array($Role[1])) ? [$Role[1]] : $Role[1];
            switch ($Role[0]) {
                case 'role':
                    foreach ($users as $user) {
                        $aRole = $this->auth->getRole($Role[2]);
                        $this->auth->assign($aRole, $user);
                    }
                    break;
                case 'permission':
                    foreach ($users as $user) {
                        $aPrem = $this->auth->getPermission($Role[2]);
                        $this->auth->assign($aPrem, $user);
                    }
                    break;
                case 'rule':
                    foreach ($users as $user) {
                        $aRule = $this->auth->getRule($Role[2]);
                        $this->auth->addChild($aRule, $user);
                    }
                    break;
            }

        }
    }

    public function down()
    {
        foreach ($this->rules() as $rule => $options) {
            $this->auth->remove($this->auth->getPermission($rule));
            $this->auth->remove($this->auth->getRule($rule));

        }
        foreach ($this->permissions() as $permissions => $options) {
            $this->auth->remove($this->auth->getPermission($permissions));
        }
        foreach ($this->roles() as $roles => $options) {
            $this->auth->remove($this->auth->getRole($roles));
        }
    }


}
