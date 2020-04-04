<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

class Apple extends ActiveRecord
{
    const ON_TREE = 0;
    const ON_GROUND = 1;
    
    public static function tableName()
    {
        return 'apple';
    }
    
    public function rules()
    {
        return [
            ['fallen_at', 'hasFallen'],
            ['percent_eaten', 'required'],
            ['percent_eaten', 'integer', 'min' => 0, 'max' => 100],
            ['percent_eaten', 'isGood'],
        ];
    }
    
    /**
     * Проверить, если яблоко упал с дерева
     */
    public function hasFallen($attribute)
    {
        if (!$this->status) {
            $this->addError($attribute, 'Яблоко нельзя есть, потому что оно все еще на дереве.');
        }
    }
    
    /**
     * Проверить, не испортилось ли яблоко (5 часов)
     *
     * @return mixed
     */
    public function isGood($attribute = false)
    {
        if ($this->fallen_at - time() > 18000) {
            if (!$attribute && $this->status) return false;
            $this->addError($attribute, 'Яблоко нельзя есть, потому что оно испортилось.');
        }
        return true;
    }
}
