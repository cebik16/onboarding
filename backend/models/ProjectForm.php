<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;

class ProjectForm extends Model
{
    public $image;
    public $delete_image= 0;
    public $userPermissions = [
        'view' => [],
        'edit' => []
    ];

    public $project;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        foreach ($this->project->permissions as $permission) {
            $this->userPermissions[$permission->type][] = $permission->user_id;
        }
    }

    public function load ( $data, $formName = null ) {
        if($result = parent::load($data) && $this->project->load($data)) {
            $this->image = \yii\web\UploadedFile::getInstance($this, 'image');
            return $result;
        }else{
            return false;
        }
    }

    public function rules()
    {
        $rules = [
            ['image', 'image', 'extensions' => Yii::$app->params['project']['image']['extensions']],
            ['delete_image', 'boolean'],
            ['userPermissions', 'safe']
        ];
        return $rules;
    }

    public function validate($attributeNames = NULL, $clearErrors = true){
        return parent::validate($attributeNames,$clearErrors) && $this->project->validate($attributeNames,$clearErrors);
    }

    public function save()
    {
        if ($this->validate()) {
            $isNewRecord = $this->project->isNewRecord;
            $transaction = Yii::$app->db->beginTransaction();
            if($this->project->save()){
                if($isNewRecord){
                    $this->project->id = Yii::$app->db->lastInsertID;
                }
                if(ProjectPermission::deleteAll(['project_id' => $this->project->id]) !== null){
                    foreach ($this->userPermissions as $type => $userIds) {
                        if($userIds) {
                            foreach ($userIds as $userId) {
                                if ($projectPermission = ProjectPermission::find()->where(['user_id' => $userId, 'project_id' => $this->project->id])->one()) {
                                    $projectPermission->type = $type;
                                }else{
                                    $projectPermission = new ProjectPermission([
                                        'user_id' => $userId,
                                        'project_id' => $this->project->id,
                                        'type' => $type
                                    ]);
                                }
                                if (!$projectPermission->save()) {
                                    return $this->rollback($transaction);
                                }
                            }
                        }
                    }
                }else{
                    return $this->rollback($transaction);
                }
                // Image
                if($this->image){
                    if (file_exists($this->project->imageFilepath)) unlink($this->project->imageFilepath);
                    $this->project->image = $this->image->name;
                    $this->project->image_extension = $this->image->extension;
                    $this->project->image_mime_type = $this->image->type;

                    $image = Image::thumbnail($this->image->tempName, Yii::$app->params['project']['image']['width'], Yii::$app->params['project']['image']['height']);
                    $imageContent = $image->get($this->project->image_extension);
                    $this->project->image_size = strlen($imageContent);
                    if (file_exists($this->project->imageFilepath)) unlink($this->project->imageFilepath);
                    if (file_put_contents($this->project->imageFilepath, Yii::$app->encrypter->encrypt($imageContent)) !== false) {
                        if ($this->project->save()) {
                            if (file_exists($this->image->tempName)) unlink($this->image->tempName);
                            $transaction->commit();
                            return true;
                        }else{
                            return $this->rollback($transaction);
                        }
                    } else {
                        return $this->rollback($transaction);
                    }
//                    if($image->save($this->project->imageFilepath)) {
//                        if($this->project->save()){
//                            $transaction->commit();
//                            return true;
//                        }else{
//                            return $this->rollback($transaction);
//                        }
//                    }else{
//                        return $this->rollback($transaction);
//                    }
                }else if($this->delete_image){
                    if (file_exists($this->project->imageFilepath)) unlink($this->project->imageFilepath);
                    $this->project->image = null;
                    $this->project->image_extension = null;
                    $this->project->image_mime_type = null;
                    $this->project->image_size  = null;
                    if($this->project->save()){
                        $transaction->commit();
                        return true;
                    }else{
//                        throw new \Exception('Delete Image Details not saved',1);
                        return $this->rollback($transaction);
                    }
                }

                $transaction->commit();
                return true;
            }else{
                return $this->rollback($transaction);
            }
        }else{
            return false;
        }

        return null;
    }

    private function rollback($transaction){
        $transaction->rollBack();
        return false;
    }

}
