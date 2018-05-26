<?php

namespace app\models;

// AJOUTER LA DATE

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id_comment
 * @property integer $content
 * @property integer $id_user
 * @property integer $id_dyck_functionnal_structure
 * @property integer $id_semantics
 *
 * @property Sequence $idDickFunctionnalStructure
 * @property User $idUser
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_dyck_functionnal_structure', 'id_semantics'], 'required'],
            [['id_dyck_functionnal_structure', 'id_semantics'], 'integer'],
            [['id_dyck_functionnal_structure', 'id_semantics'], 'exist', 'skipOnError' => true, 'targetClass' => Sequence::className(), 'targetAttribute' => ['id_dyck_functionnal_structure' => 'id_dyck_functionnal_structure', 'id_semantics' => 'id_semantics']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id_user']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_comment' => Yii::t('app', 'Id Comment'),
            'content' => Yii::t('app', 'Content'),
            'id_user' => Yii::t('app', 'Id User'),
            'id_dyck_functionnal_structure' => Yii::t('app', 'Id Dick Functionnal Structure'),
            'id_semantics' => Yii::t('app', 'Id Semantics'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdDickFunctionnalStructure()
    {
        return $this->hasOne(Sequence::className(), ['id_dyck_functionnal_structure' => 'id_dyck_functionnal_structure', 'id_semantics' => 'id_semantics']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUser()
    {
        return $this->hasOne(User::className(), ['id_user' => 'id_user']);
    }
}
