<?php

/**
 * This is the base model class for SalaryHours and InvoiceHours.
 * It's almost the same as the hours model, with some extra fields.
 * {@inheritdoc}
 * 
 * @property int $version
 * @property int $hours_id
 * @property int $editor_id
 * @property int $type
 * @property int $previous_version_id
 * @property bool $approved 
 * @property int $approver user ID of the approver
 */
class VersionedHours extends Hours
{
    public function rules()
    {
        $parentBaseRules = parent::baseRules();
        return array_merge($parentBaseRules, [
            ["version, hours_id, editor_id, previous_version_id, approver", "numerical"],
            ["approved", "boolean"],
        ]);
    }
}
