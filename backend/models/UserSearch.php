<?php
    
    
    namespace backend\models;
    
    
    use common\models\User;
    use yii\data\ActiveDataProvider;
    use yii\helpers\VarDumper;

    class UserSearch extends User
    {
        public $username;
    
        public function rules()
        {
            return [
                ['username', 'safe'],
                ['first_name', 'safe'],
                ['last_name', 'safe'],
                ['email', 'safe'],
                ['status', 'safe'],
                ['role', 'safe'],
            ];
        }
        
        public function search($params = [])
        {
            $query = User::find();
            $dataProvider = new ActiveDataProvider([
                'query' => $query
            ]);
            
            if (!($this->validate() && $this->load($params))) {
                return $dataProvider;
            }
    
            foreach($this->attributes() as $attribute){
                if ($this->$attribute !== '') {
                    $query->andFilterWhere([
                        $attribute => $this->$attribute
                    ]);
                }
            }
            return $dataProvider;
        }
    }