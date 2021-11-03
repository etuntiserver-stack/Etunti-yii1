<?php

/**
 * @deprecated use SalaryHours or InvoiceHours instead.
 * This is the model class for table "edited_hours".
 * It's almost the same as the hours model, with some extra fields.
 * {@inheritdoc}
 * 
 * @property int $version
 * @property int $hours_id
 * @property int $editor_id
 * @property int $type
 * @property int $previous_version_id
 */
class EditedHours extends Hours
{
    public static function model($className=__class__) {
        return parent::model($className);
    }

    public function tableName() {
        return "edited_hours";
    }

    public function rules() {
        $parentRules = parent::rules();
        return array_merge($parentRules, [
            ["version, hours_id, editor_id, type, previous_version_id", "numerical", "integerOnly" => true],
        ]);
    }
}