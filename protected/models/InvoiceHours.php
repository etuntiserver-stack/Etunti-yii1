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

    /**
     * Rounds the ending_time fields to the next 15 minutes.
     * Automatically calls calculateDurations() to update all the durations.
     * @param bool $save Flag to indicate if we should save the model after rounding. Defaults to false.
     */
    public function roundToNext15Minutes($save = false)
    {
        $endTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->ending_time);
        $date = $endTime->format("Y-m-d");
        $hours = $endTime->format("H");
        $minutes = $endTime->format("i");

        $intMinutes = intval($minutes);
        
        $newMinutes = (15 - ($intMinutes % 15)) + $intMinutes;
        // can't assign $newSeconds = 0, php seems to think it's a boolean
        $newSeconds = "00";
        // php doesn't care if 00 time is written as 0 or 00, it'll correctly parse the date.
        $newEndTime = DateTime::createFromFormat("Y-m-d H:i:s", $date . " " . $hours . ":" . $newMinutes . ":" . $newSeconds);
        $this->ending_time = $newEndTime->format("Y-m-d H:i:s");
        $this->calculateDurations();
        if($save) {
            $this->save();
        }
    }
}
