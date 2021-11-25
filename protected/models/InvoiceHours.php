<?php

/**
 * This is the model class for table "invoice_hours".
 * It's almost the same as the hours model, with some extra fields.
 * {@inheritdoc}
 * 
 * @property bool $invoiced
 * 
 */
class InvoiceHours extends VersionedHours
{
    public static function model($className = __class__)
    {
        return parent::model($className);
    }

    public static function copyFromHours($hours)
    {
        $invoice = new InvoiceHours();
        $invoice->attributes = $hours->attributes;
        unset($invoice->id);
        $invoice->version = 1;
        $invoice->hours_id = $hours->id;
        return $invoice;
    }

    public function tableName()
    {
        return "invoice_hours";
    }

    public function rules()
    {
        $parentRules = parent::rules();
        return array_merge($parentRules, [
            ["invoiced", "boolean"]
        ]);
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
