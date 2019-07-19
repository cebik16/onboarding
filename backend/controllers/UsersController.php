<?php
    
    namespace backend\controllers;
    
    use Yii;
    use yii\caching\DbDependency;
    use yii\db\Query;
    use yii\helpers\VarDumper;
    use yii\web\NotFoundHttpException;
    
    use common\models\User;
    use backend\models\UserForm;
    use backend\models\UserSearch;
    use common\models\Project;
    use backend\components\Controller;
    use yii\web\UploadedFile;
    
    /**
     * UsersController implements the CRUD actions for User model.
     */
    class UsersController extends Controller
    {
        /**
         * @inheritdoc
         */
        public function behaviors()
        {
            $behaviors = parent::behaviors();
            $behaviors['access']['rules'][] = [
                'actions' => [
                    'index',
                    'create',
                    'view',
                    'update',
                    'validate-delete',
                    'delete',
                    'change-status',
                    'avatar',
                ],
                'allow' => true,
                'roles' => [User::$roles[User::ROLE_ADMIN]],
            ];
            $behaviors['verbs']['actions'] = [
                'index' => ['GET'],
                'view' => ['GET'],
                'create' => ['GET', 'POST'],
                'update' => ['GET', 'PUT', 'POST'],
                'delete' => ['POST', 'DELETE'],
            ];
//            VarDumper::dump(User::$roles[User::ROLE_ADMIN], 10, true);
//            exit;
            
            return $behaviors;
        }
        
        /**
         * Lists all User models.
         * @return mixed
         */
        public function actionIndex()
        {
            $searchModel = new UserSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
        
        /**
         * Displays a single User model.
         * @param string $id
         * @return mixed
         */
        public function actionView($id)
        {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
        
        /**
         * Creates a new User model.
         * If creation is successful, the browser will be redirected to the 'view' page.
         * @return mixed
         */
        public function actionCreate()
        {
            $model = new UserForm([
                'user' => new User(['status' => User::STATUS_ACTIVE]),
            ]);
            
            $model->scenario = 'create';
            
            if (($postData = Yii::$app->request->post()) && $model->load($postData, 'UserForm')) {
                
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                
                if (!empty($model->avatar)) {
                    $postData['User']['avatar'] = $model->avatar->baseName . '.' . $model->avatar->extension;
                    $postData['User']['avatar_extension'] = $model->avatar->extension;
                    $postData['User']['avatar_mime_type'] = $model->avatar->type;
                    $postData['User']['avatar_size'] = $model->avatar->size;
                    
                }
                
                if ($model->user->load($postData, 'User') && $model->save() && $model->upload()) {
                    return $this->redirect(['view', 'id' => $model->user->id]);
                }
            }
            
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        
        /**
         * Updates an existing User model.
         * If update is successful, the browser will be redirected to the 'view' page.
         * @param string $id
         * @return mixed
         */
        public function actionUpdate($id)
        {
            $model = new UserForm(['user' => $this->findModel($id)]);
            
            if (Yii::$app->request->isPost && ($postData = Yii::$app->request->post()) && $model->load($postData, 'UserForm')) {
                
                $model->avatar = UploadedFile::getInstance($model, 'avatar');
                
                if (!empty($model->avatar)) {
                    $postData['User']['avatar'] = $model->avatar->baseName . '.' . $model->avatar->extension;
                    $postData['User']['avatar_extension'] = $model->avatar->extension;
                    $postData['User']['avatar_mime_type'] = $model->avatar->type;
                    $postData['User']['avatar_size'] = $model->avatar->size;
                }
                
                if ($model->user->load($postData, 'User') && $model->save() && $model->upload()) {
                    return $this->redirect(['view', 'id' => $model->user->id]);
                }
            }
            return $this->render('update', [
                'model' => $model,
            ]);
        }
        
        /**
         * Deletes an existing User model.
         * If deletion is successful, the browser will be redirected to the 'index' page.
         * @param string $id
         * @return mixed
         */
        public function actionDelete($id)
        {
            $this->findModel($id)->delete();
            
            return $this->redirect(['index']);
        }
        
        /**
         * Finds the User model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param string $id
         * @return User the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        protected function findModel($id)
        {
            if (($model = User::findOne($id)) !== NULL) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');
            }
        }
        
        public function actionResendActivationEmail($id)
        {
            $model = $this->findModel($id);
            if ($model->status == $model::STATUS_PENDING) {
                $mail = Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'activate-html', 'text' => 'activate-text'],
                        [
                            'user' => $model,
                            'activationUrl' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['site/activate', 'id' => $model->id, 'key' => $model->auth_key]),
                        ]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($model->email)
                    ->setSubject('Activate your account')
                    ->send();
                if ($mail) {
                    Yii::$app->session->setFlash('success', 'Activation email sent to: ' . $model->username);
                } else {
                    Yii::$app->session->setFlash('error', 'Activation email NOT sent to: ' . $model->username);
                }
            } else {
                Yii::$app->session->setFlash('error', 'User: ' . $model->username . ' is not allowed to receive activation emails');
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        public function actionChangeStatus($id)
        {
            $model = $this->findModel($id);
            
            if ($model->status === $model::STATUS_ACTIVE) {
                $model->status = $model::STATUS_INACTIVE;
            } else {
                $model->status = $model::STATUS_ACTIVE;
            }
            $model->save();
            return $this->redirect(Yii::$app->request->referrer);
            
        }
        
        public function actionApprove($id)
        {
            $model = $this->findModel($id);
            $model->status = $model::STATUS_ACTIVE;
            if ($model->save()) {
                $mail = Yii::$app
                    ->mailer
                    ->compose(
                        ['html' => 'approve-html', 'text' => 'approve-text'],
                        [
                            'user' => $model,
                            'loginUrl' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['site/login']),
                        ]
                    )
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($model->email)
                    ->setSubject('Your account has been approved')
                    ->send();
                if ($mail) {
                    Yii::$app->session->setFlash('success', 'Approve email sent to: ' . $model->username);
                } else {
                    Yii::$app->session->setFlash('error', 'Approve email NOT sent to: ' . $model->username);
                }
            } else {
                Yii::$app->session->setFlash('error', 'Unable to update user: ' . json_encode($model->errors));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        public function actionValidateDelete($id)
        {
            $model = $this->findModel($id);
            
            $query = new Query;
//            $query->from(Project::tableName())->where(['created_by' => $model->id]);
            $count = (int)$query->count();
            
            $output = [
                'id' => $model->id,
                'hasProjects' => (bool)$count,
            ];
            return $this->asJson($output);
        }
    }
