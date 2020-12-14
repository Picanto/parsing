<?php

namespace app\models;

use yii\db\ActiveRecord;

class Parser extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    public static function stripTags($string)
    {
        return strip_tags(preg_replace('/<[^>]*>/', '', str_replace(array("&nbsp;", "\n", "\r"), "", html_entity_decode($string, ENT_QUOTES, 'UTF-8'))));
    }

    /**
     * @return string название таблицы, сопоставленной с этим ActiveRecord-классом.
     */
    public static function tableName()
    {
        return '{{topic}}';
    }
}