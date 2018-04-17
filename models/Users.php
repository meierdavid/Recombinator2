<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id_user
 * @property string $last_name
 * @property string $first_name
 * @property string $lab_name
 * @property string $password
 * @property string $mail
 *
 * @property Comment[] $comments
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['last_name', 'first_name', 'lab_name', 'password', 'mail'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_user' => Yii::t('app', 'Id User'),
            'last_name' => Yii::t('app', 'Last Name'),
            'first_name' => Yii::t('app', 'First Name'),
            'lab_name' => Yii::t('app', 'Lab Name'),
            'password' => Yii::t('app', 'Password'),
            'mail' => Yii::t('app', 'Mail'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['id_user' => 'id_user']);
    }
}
