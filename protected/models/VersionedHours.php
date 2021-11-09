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

    /**
     * Copies any overlapping attributes from a Toteutuneet or Mobile model
     * into a SalaryHour or InvoiceHour model.
     * @return SalaryHours|InvoiceHours copied model
     */
    public function copyFromToteutuneetOrMobile($model)
    {
        // starting and ending times can be either in H:i format or
		// H:i:s format in $model. we'll figure out which one it is here.
		$HIFormat = "d.m.Y H:i";
		$HISFormat = "d.m.Y H:i:s";

		$dateTimeFormat = "Y-m-d H:i:s";
        // parse start and end dates
        $startDate = DateTime::createFromFormat($HISFormat, $model->aloitan);
		$endDate = DateTime::createFromFormat($HISFormat, $model->loppui);
		if($startDate === false) {
			$startDate = DateTime::createFromFormat($HIFormat, $model->aloitan);
		}
		if($endDate === false) {
			$endDate = DateTime::createFromFormat($HIFormat, $model->loppui);
		}
        // if both start and end dates are ok, create the new model
        if($startDate !== false && $endDate !== false) {

            $cls = get_class($this);
            $hourModel = new $cls();
            // copy over any attributes that might've already been set on the model
            $hourModel->attributes = $this->attributes;
            if(isset($hourModel->id)) {
                $hourModel->previous_version_id = $hourModel->id;
            }
            unset($hourModel->id);
            // copy over stuff from $model
            $hourModel->starting_time = $startDate->format($dateTimeFormat);
			$hourModel->ending_time = $endDate->format($dateTimeFormat);

            $hourModel->worker_id = $model->tid;
            $hourModel->status = $model->status;
            $hourModel->property_id = $model->kohdenID;
            if(isset($model->viesti) && strlen($model->viesti) > 0) {
                $hourModel->message = $model->viesti;
            }
            
            $hourModel->approved = empty($model->hyvaksytty) ? 0 : 1;
            if($hourModel->approved) {
                $hourModel->approver = Yii::app()->user->id;
            }
            $hourModel->editor_id = Yii::app()->user->id;
            $hourModel->shift_id = $model->tv_id;
            if(isset($this->version)) {
                $hourModel->version = $this->version + 1;
            } else {
                $hourModel->version = 1;
            }
            $hourModel->calculateDurations();

            return $hourModel;
        } else {
            throw new Exception("Failed to parse start and end dates");
        }
        throw new Exception("Failed to copy model");
    }
}
