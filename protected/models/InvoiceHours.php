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
}
