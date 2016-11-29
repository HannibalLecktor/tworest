<?php
namespace app\models\security;

use app\models\Country;
use app\models\District;
use app\models\Language;
use app\models\Message;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\validators\EmailValidator;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $district_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property boolean $is_admin
 * @property string $password write-only password
 * @property District $district
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const SCENARIO_PROFILE = 'profile';

    public function scenarios() {
        return [
            self::SCENARIO_DEFAULT => [
                'username',
                'first_name',
                'last_name',
                'email',
                'status',
                'is_admin',
                'status',
                'district_id',
                'avatar'
            ],
            self::SCENARIO_PROFILE => [
                'username',
                'email',
                'first_name',
                'last_name',
                'district_id',
                'avatar'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
            [
                'class'         => \maxmirazh33\image\Behavior::className(),
                'savePathAlias' => 'img/upload/',
                'urlPrefix'     => '/img/upload/',
                'crop'          => false,
                'attributes'    => [
                    'avatar' => [
                        'width'  => 130,
                        'height' => 130,
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            [['username'], 'required'],
            [['district_id', 'created_at', 'updated_at'], 'integer'],
            [['is_admin'], 'boolean', 'except' => self::SCENARIO_PROFILE],
            [
                ['username', 'first_name', 'last_name', 'phone', 'password_hash', 'password_reset_token', 'email'],
                'string',
                'max' => 64
            ],
            [['auth_key'], 'string', 'max' => 32, 'except' => self::SCENARIO_PROFILE]
        ];
    }

    public function fields() {
        return [
            'id',
            'username',
            'avatar'   => function ($model) {
                return $model->avatar ? $model->getImageUrl('avatar') : '/img/default_avatar.jpg';
            },
            'district' => function ($model) {
                return $model->district ? $model->district->name : null;
            },
            'city'     => function ($model) {
                return $model->district ? $model->city->name : null;
            },
            'country'  => function ($model) {
                return $model->district ? $model->country->name : null;
            },
        ];
    }

    public function afterFind() {
        if(Yii::$app->language !== 'ru') {
            $lang = Yii::$app->cache->get('language');
            if($lang) {
                if(!$lang['translit']) {
                    $lang['translit'] = Language::findOne(['code' => 'en'])->translit;
                }
                try {
                    $this->username = strtr($this->username, Json::decode($lang['translit']));
                } catch(\Exception $e) {

                }
            }
        }
    }

    public function getCity() {
        return $this->district->city;
    }

    public function getCountry() {
        return $this->city->country;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username or email
     *
     * @param string $usernameOrEmail
     * @return static|null
     */
    public static function findByUsernameOrEmail($usernameOrEmail) {
        $attribute = (new EmailValidator)->validate($usernameOrEmail) ? 'email' : 'username';

        return static::findOne(
            [
                $attribute => $usernameOrEmail,
                'status'   => self::STATUS_ACTIVE
            ]
        );
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token) {
        if(!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status'               => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token) {
        if(empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int)end($parts);

        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken() {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken() {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict() {
        return $this->hasOne(District::className(), ['id' => 'district_id']);
    }

    public function getDistrictName() {
        return $this->district->name;
    }

    public function getDistrictList($city_id = false) {
        if(!$city_id) {
            $districts = District::find()->with('city')->all();
        } else {
            $districts = District::find()->asArray()->where(['city_id' => $city_id])->all();
        }

        $districts = ArrayHelper::toArray(
            $districts,
            [
                'app\models\District' => [
                    'id',
                    'name' => function ($district) {
                        return $district->name . " (" . $district->city->name . ')';
                    }
                ]
            ]
        );

        return ArrayHelper::map($districts, 'id', 'name');
    }

    private static function filterLocation($array) {
        if(!empty($array['districts'])) {
            return $array;
        }

        return false;
    }

    public function getLocationList() {
        $location = Country::find()->innerJoinWith('cities.districts')->all();

        $location = ArrayHelper::toArray(
            $location,
            [
                'app\models\District' => [
                    'id',
                    'name',
                ],
                'app\models\City'     => [
                    'id',
                    'name',
                    'districts'
                ],
                'app\models\Country'  => [
                    'id',
                    'name',
                    'cities'
                ]
            ]
        );

        foreach($location as &$arLocation) {
            $arLocation['cities'] = array_filter($arLocation['cities'], "self::filterLocation");
        };

        return $location;
    }

    public function getMessages() {
        return $this->hasMany(Message::className(), ['user_id', 'id']);
    }

    public function attributeLabels() {
        return [
            'id'          => Yii::t('app', 'ID'),
            'username'    => Yii::t('app', 'Username'),
            'first_name'  => Yii::t('app', 'First Name'),
            'last_name'   => Yii::t('app', 'Last Name'),
            'email'       => Yii::t('app', 'Email'),
            'avatar'      => Yii::t('app', 'Avatar'),
            'district_id' => Yii::t('app', 'District')
        ];
    }

    public static function find() {
        return parent::find()->with('district.city.country');
    }
}