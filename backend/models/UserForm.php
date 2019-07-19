<?php
    
    namespace backend\models;
    
    use Yii;
    use common\models\User;
    use yii\base\Model;
    use yii\behaviors\TimestampBehavior;
    use yii\helpers\VarDumper;
    
    class UserForm extends Model
    {
        public $password;
        public $confirm_password;
        public $user;
        public $avatar;
        
        public function behaviors()
        {
            return [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created_at',
                    'updatedAtAttribute' => 'updated_at',
                ],
            ];
        }
        
        public function rules()
        {
            return [
                ['user', 'safe'],
                ['avatar', 'safe'],
                ['avatar', 'image', 'skipOnEmpty' => true, 'extensions' => 'jpg, gif, png'],
                [['password', 'confirm_password'], 'required', 'on' => 'create'],
                [['password', 'confirm_password'], 'string', 'min' => 8],
                ['password', 'match', 'pattern' => '/^(?=.*[0-9])(?=.*[A-Z])([a-zA-Z0-9]+)$/', 'message' => 'The {attribute} doesn\'t meet the necessary requirements.'],
                ['confirm_password', 'compare', 'compareAttribute' => 'password', /*'skipOnEmpty' => false*/],
            ];
        }
        
        public function attributeLabels()
        {
            return [
            
            ];
        }
        
        public function save()
        {
            
            if ($this->validate()) {
                $isNewRecord = $this->user->isNewRecord;
                
                if ($isNewRecord) {
                    $this->user->generateAuthKey();
                    $this->user->setPassword($this->password);
                } else {
                    if ($this->password) {
                        $this->user->setPassword($this->password);
                    }
                }
                
                if (!$this->user->save()) {
                    VarDumper::dump($this->user->getErrors(), 10, true);
                    exit;
                }
                return $this->user->save();
            } else {
                VarDumper::dump($this->getErrors(), 10, true);
                exit;
            }
            
            return NULL;
        }
        
        public function upload()
        {
            if (!empty($this->avatar)) {
                return $this->avatar->saveAs(Yii::$app->params['user_avatar_path'] . $this->user->id . '.' . $this->avatar->extension);
            }
            return true;
        }
    }
