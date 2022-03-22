<?php

/**
 * Super lazy error log table for Netvisor, only to be used for Yii1 purposes.
 * @property string $response
 * @property string $category
 */
class NetvisorLegacyLog extends DB2ActiveRecord
{
    public function tableName()
    {
        return "netvisor_legacy_log";
    }

    public function rules()
    {
        return [
            ["response, category", "safe"],
        ];
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return NetvisorLegacyLog the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}