<?
namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public $errorsCode = [
        1 => 'UPLOAD_ERR_INI_SIZE',
        2 => 'UPLOAD_ERR_FORM_SIZE',
        3 => 'UPLOAD_ERR_PARTIAL',
        5 => 'UPLOAD_ERR_NO_FILE',
        6 => 'UPLOAD_ERR_NO_TMP_DIR',
        7 => 'UPLOAD_ERR_CANT_WRITE',
        8 => 'UPLOAD_ERR_EXTENSION'
    ];

    public $error;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function upload($image)
    {
        $this->imageFile = $image;
        $dir = \Yii::getAlias('@app/web') . \Yii::getAlias('@upload/');
        $file = $this->imageFile->baseName . '.' . $this->imageFile->extension;
        if ($this->validate() && $this->imageFile->saveAs($dir . $file)) {
            return $file;
        } else {
            if ($this->imageFile->error) {
                $this->error = $this->errorsCode[$image->error];
            }
            return false;
        }
    }

    public function getImagesPath($model, $propName)
    {
        $uploaded = new UploadedFile();
        if ($file = $uploaded->getInstance($model, $propName)) {
            $file = $this->upload($file);

            if ($file)
                $model->setAttribute($propName, $file);
            elseif ($err = $this->error)
                $model->addError($propName, $err);
        } else {
            $model->setAttribute($propName, $model->getOldAttribute($propName));
        }
    }
}