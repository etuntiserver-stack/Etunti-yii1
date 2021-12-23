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
        // get rid of seconds for start and end times
        $startTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->starting_time);
        if($startTime === false) {
            throw new Exception("starting_time isn't defined!");
        }
        $startTime = DateTime::createFromFormat("Y-m-d H:i", $startTime->format("Y-m-d H:i"));
        $endTime = DateTime::createFromFormat("Y-m-d H:i:s", $this->ending_time);
        if($endTime === false) {
            throw new Exception("starting_time isn't defined!");
        }
        $endTime = DateTime::createFromFormat("Y-m-d H:i", $endTime->format("Y-m-d H:i"));

        // assign starting_time & ending_time back with 00 seconds
        // because we might be recalculating the durations
        $this->starting_time = $startTime->format("Y-m-d H:i:s");
        $this->ending_time = $endTime->format("Y-m-d H:i:s");
        // if this->hours is 0 or null, calculate durations
        // the 0 is no problem, but the durations being null is a problem.
        if(!$this->hours) {
            $this->calculateDurations();
        }

        $hours = $this->hours;
        // 0.25 = 15 minutes of 60 minutes
        // figure out how many minutes we're missing
        // from the next "full 15 minutes" (0.25)
        $fmod = fmod($hours, 0.25);
        // remove the missing minutes from a full 15 minutes, and add the original
        // duration back in, which should result in a round number
        $newDuration = (0.25 - $fmod) + $hours;
        // convert back to human readable minutes
        $minutes = $newDuration * 60;
        // clone start time and modify it 
        $this->ending_time = (clone $startTime)->modify("+$minutes minutes")->format("Y-m-d H:i:s");
        // calculate new durations
        $this->calculateDurations();
        // save model if the caller wants that
        if($save) {
            $this->save();
        }
    }
}
