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

    /**
     * Recursively finds and deletes old versions of itself.
     */
    public function deleteOldVersions($model)
    {
        if(isset($model->previous_version_id)) {
            $oldModel = self::model()->findByPk($model->previous_version_id);
            if($oldModel) {
                $oldModel->delete();
                $oldModel->deleteOldVersions($oldModel);
            }
        }
    }
}
