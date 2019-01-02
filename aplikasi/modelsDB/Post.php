<?php

namespace app\modelsDB;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $title_en
 * @property string $subtitle
 * @property string $subtitle_en
 * @property string $content
 * @property string $content_en
 * @property string $page
 * @property string $url
 * @property string $dateCreated
 * @property string $dateModified
 */
class Post extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'page'], 'required'],
            [['content', 'content_en'], 'string'],
            [['dateCreated', 'dateModified'], 'safe'],
            [['title', 'title_en', 'subtitle', 'subtitle_en'], 'string', 'max' => 100],
            [['page', 'url'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'title_en' => Yii::t('app', 'Title En'),
            'subtitle' => Yii::t('app', 'Subtitle'),
            'subtitle_en' => Yii::t('app', 'Subtitle En'),
            'content' => Yii::t('app', 'Content'),
            'content_en' => Yii::t('app', 'Content En'),
            'page' => Yii::t('app', 'Page'),
            'url' => Yii::t('app', 'Url'),
            'dateCreated' => Yii::t('app', 'Date Created'),
            'dateModified' => Yii::t('app', 'Date Modified'),
        ];
    }
}
