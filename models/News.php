<?php

namespace app\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "news".
 *
 * @property string $id
 * @property string $title
 * @property string $about
 * @property string $text
 * @property string $user_id
 * @property string $date_creation
 * @property string $date_modification
 *
 * @property User $user
 */
class News extends \yii\db\ActiveRecord {
    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
	        [['title', 'about', 'text'], 'required'],
	        [['title'], 'string', 'max' => 255],
	        [['about', 'text'], 'string'],
            [['user_id'], 'default', 'value'=>Yii::$app->user->id],
	        ['date_creation', 'default', 'value'=>new Expression('NOW()'), 'when'=>function($model) { return $model->isNewRecord; }],
	        ['date_modification', 'default', 'value'=>new Expression('NOW()'), 'when'=>function($model) { return !$model->isNewRecord; }],
	        [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '#',
            'title' => 'Название',
	        'about' => 'Краткое описание',
            'text' => 'Текст новости',
            'user_id' => 'Автор',
	        'date_creation' => 'Дата добавления',
	        'date_modification' => 'Дата изменения'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
