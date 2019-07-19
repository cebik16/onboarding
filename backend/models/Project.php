<?php

namespace common\models;

use Yii;
use yii\helpers\Html;
use kartik\mpdf\Pdf;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "project".
 *
 * @property string  $id
 * @property string  $vp_tree_id
 * @property string  $name
 * @property string  $progress
 * @property string  $image
 * @property string  $image_extension
 * @property string  $image_mime_type
 * @property string  $image_size
 * @property string  $created_by
 * @property integer $created_at
 * @property string  $scoreBar
 * @property mixed   $imageFilepath
 * @property mixed   $blocks
 * @property mixed   $permissions
 * @property mixed   $createdBy
 * @property mixed   $documents
 * @property mixed   $businessUnit
 * @property null    $score
 * @property string  $imageFilename
 * @property integer $updated_at
 */
class Project extends ActiveRecord
{

    public static $stages = [
        0 => '',
        1 => 'Stage 1',
        2 => 'Stage 2'
    ];


    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => 'yii\behaviors\TimestampBehavior',
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
            ],
        ];
        $behaviors['encryption'] = [
            'class' => '\common\components\EncryptionBehavior',
            'attributes' => [
                'elevator_pitch'
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vp_tree_id', 'name', 'created_by'], 'required'],
            [['vp_tree_id', 'progress', 'stage', 'image_size', 'created_by'], 'integer'],
            [['name', 'image','image_mime_type'], 'string', 'max' => 50],
            ['elevator_pitch', 'string', 'max' => 500],
            ['elevator_pitch', 'trim'],
            ['elevator_pitch', 'default', 'value' => null],
            [['image_extension'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'vp_tree_id' => 'Business Unit',
            'name' => 'Name',
            'progress' => 'Progress',
            'stage' => 'Project Stage',
            'image' => 'Image',
            'image_extension' => 'Image Extension',
            'image_mime_type' => 'Image Mime Type',
            'image_size' => 'Image Size',
            'created_by' => 'Created By',
            'created_at' => 'Created On',
            'updated_at' => 'Updated On',
        ];
    }

    public function getImageFilepath()
    {
        return Yii::getAlias('@storage/projects/images/'.$this->imageFilename);
    }

    public function getImageFilename()
    {
//        if ($this->image_extension) {
//            return $this->id . '.' . $this->image_extension;
//        } else {
//            return $this->id;
//        }
        return $this->id.'.enc';
    }

    public function getImageUrl($timestamp = false, $scheme = false)
    {
        if($this->image) {
            return \yii\helpers\Url::to(['/open/project-image', 'id' => $this->id, 'v' => $timestamp?time():null ], $scheme);
        }
        return null;
    }
    
    public function getProgressPercentage($totalBlockNo)
    {
        return intval( $this->progress / $totalBlockNo *100 );
    }

    public function progressBar($totalBlockNo = 6)
    {
        return '
            <div class="progress" title="'.$this->progress.' / '.$totalBlockNo.'">
            <div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$this->getProgressPercentage($totalBlockNo).'%">
              <span class="sr-only">70% Complete</span>
            </div>
            </div>
        ';
    }
    
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getBusinessUnit()
    {
        return $this->hasOne(VpTree::className(), ['id' => 'vp_tree_id']);
    }

    public function getBlocks()
    {
        return $this->hasMany(ProjectBlock::className(), ['project_id' => 'id']);
    }

    public function getPermissions()
    {
        return $this->hasMany(ProjectPermission::className(), ['project_id' => 'id']);
    }

    public function getDocuments()
    {
        return $this->hasMany(ProjectDocument::className(), ['project_id' => 'id']);
    }

    public function getBalloon(User $user){
        $permission = $this->getUserPermission($user);

        switch ($permission){
            case 1:
                return 'view';
            case 3:
                return 'edit';
            case 4:
                return 'mine';
            default:
                return null;
        }
    }

    public function getUserPermission(User $user) {
        if($user->isAdmin()){
            return 5;
        }
        if($this->created_by == $user->id){
            return 4;
        }
        $permission = 0;
        if($this->permissions){
            foreach($this->permissions as $userPermission){
                if($userPermission->user_id == $user->id){
                    if($userPermission->type == 'view'){
                        $permission = 1;
                    }else{
                        $permission = 3;
                    }
                }
            }
        }
        if($permission <= 1) {
            if($user->isCoach()){
                return 2;
            }
        }
        return $permission;
    }

    public function getScore(){
        $scores = [];
        foreach($this->blocks as $projectBlock){
            if($projectBlock->score) {
                $scores[] = $projectBlock->score;
            }
        }
        if($scores) {
            return round(array_sum($scores) / count($scores), 2);
        }else{
            return null;
        }
    }

    public function getScorePercentage($maxScore = 10)
    {
        return intval( $this->score / $maxScore *100 );
    }

    public function getScoreBar()
    {
        return '
            <div class="progress" title="'.($this->score?$this->score:0).' / 10">
            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:'.$this->getScorePercentage().'%">
            </div>
            </div>
        ';
    }

    public function getPdf($destination = 'I', $blocks, $projectBlocks){
        // get your HTML raw content without any layouts or scripts
        $content = Yii::$app->controller->renderPartial('@app/modules/projects/views/default/export',[
            'model' => $this,
            'blocks' => $blocks,
            'projectBlocks' => $projectBlocks
        ]);

        //I: send the file inline to the browser. The plug-in is used if available. The name given by filename is used when one selects the "Save as" option on the link generating the PDF.
        //D: send to the browser and force a file download with the name given by filename.
        //F: save to a local file with the name given by filename (may include a path).
        //S: return the document as a string. filename is ignored.
        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format'=>'A4-L',
            'options' => ['title' => Html::encode($this->name)],
            'cssFile' => '@webroot/css/export.css',
            'content' => $content,
            'filename' =>  preg_replace('/[^A-Za-z0-9\-_]/', '', str_replace(' ', '_', $this->name)).'-'.date('Y-m-d').'.pdf',
            'destination' => $destination
        ]);
        $pdf->methods = [
//                'setHeader' =>'Document Title|Center Text|{PAGENO}',
//                'setFooter'=>'|{PAGENO}|',
        ];
        $pdf->options = [
//                'defaultfooterline' => 0,
//                'defaultheaderline' => 0
        ];
        return $pdf;
    }

    public function delete($removeImage = true)
    {
        $delete = true;
        foreach($this->documents as $document){
            $delete = $delete && $document->delete();
        }
        if ($delete && parent::delete()) {
            if ($removeImage) {
                $this->removeImage();
            }
            return true;
        }
        return false;
    }

    protected function removeImage()
    {
        if (file_exists($this->imageFilepath))
            return unlink($this->imageFilepath);
    }

    public function save( $runValidation = true, $attributeNames = null ) {
        if(!$this->elevator_pitch) {
            $this->elevator_pitch = null;
        }
        return parent::save($runValidation, $attributeNames);
    }
}
