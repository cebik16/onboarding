<?php
    
    namespace common\components;
    
    
    use common\models\User;
    use Yii;
    use yii\helpers\VarDumper;

    class AccessRule extends \yii\filters\AccessRule {
        
        /**
         * @inheritdoc
         */
        protected function matchRole($user)
        {
            if (empty($this->roles)) {
                return true;
            }
            foreach ($this->roles as $role) {
                if ($role === '?') {
                    if ($user->getIsGuest()) {
                        return true;
                    }
                } elseif ($role === '@') {
                    if (!$user->getIsGuest()) {
                        return true;
                    }
                    VarDumper::dump(User::$roles[$user->identity->role], 10, true);
                    exit;
    
                } elseif (!$user->getIsGuest() && $role === User::$roles[$user->identity->role]) {
                    return true;
                } else {
                    return Yii::$app->response->redirect(Yii::$app->urlManagerFrontend->createUrl(''));
                }
            }
            
            return false;
        }
    }