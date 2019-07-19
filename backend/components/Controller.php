<?php /** @noinspection ALL */
    
    namespace backend\components;

    use Yii;
    use yii\filters\AccessControl;
    use common\components\AccessRule;
    use yii\filters\VerbFilter;
    use yii\helpers\VarDumper;


    class Controller extends  \yii\web\Controller
    {
        public $ajax;
        
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'ruleConfig' => [
                        'class' => AccessRule::className(),
                    ],
                    'rules' => [
                        [
                            'actions' => ['login', 'error'],
                            'allow' => true,
                        ],
                        [
                            'actions' => ['logout'],
                            'allow' => true,
                            'roles' => [''],
                        ]
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                ],
            ];
        }
    
        public function init(){
            parent::init();
            $get = Yii::$app->request->queryParams;
    
            if(Yii::$app->request->isAjax){
                $this->ajax = true;
            } else{
                if(isset($get['ajax']) && $get['ajax'] == 1){
                    $this->ajax = true;
                }
            }
            if(isset($get['iframe']) && $get['iframe'] == 1){
                $this->iframe = 1;
            }
        }
    }
