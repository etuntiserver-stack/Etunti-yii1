<?php

class SalaryHours extends VersionedHours
{
    public static function model($className = __class__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "salary_hours";
    }

    public function rules()
    {
        $parentRules = parent::rules();
        return array_merge($parentRules, []);
    }
}
