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
            ["version", "default", "value" => 1]
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
        if ($startDate === false) {
            $startDate = DateTime::createFromFormat($HIFormat, $model->aloitan);
        }
        if ($endDate === false) {
            $endDate = DateTime::createFromFormat($HIFormat, $model->loppui);
        }
        // if both start and end dates are ok, create the new model
        if ($startDate !== false && $endDate !== false) {

            $cls = get_class($this);
            $hourModel = new $cls();
            // copy over any attributes that might've already been set on the model
            $hourModel->attributes = $this->attributes;
            if (isset($hourModel->id)) {
                $hourModel->previous_version_id = $hourModel->id;
            }
            unset($hourModel->id);
            // copy over stuff from $model
            $hourModel->starting_time = $startDate->format($dateTimeFormat);
            $hourModel->ending_time = $endDate->format($dateTimeFormat);

            $hourModel->worker_id = $model->tid;
            $hourModel->status = $model->status;
            $hourModel->property_id = $model->kohdenID;
            if (isset($model->viesti) && strlen($model->viesti) > 0) {
                $hourModel->message = $model->viesti;
            }

            $hourModel->approved = empty($model->hyvaksytty) ? 0 : 1;
            if ($hourModel->approved) {
                $hourModel->approver = Yii::app()->user->id;
            }
            $hourModel->editor_id = Yii::app()->user->id;
            $hourModel->shift_id = $model->tv_id;
            if (isset($this->version)) {
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

    public function save(
        $runValidation = true,
        $attributeNames = null,
        $insertOthers = true
    ) {
        if ($runValidation && !$this->validate($attributeNames)) {
            return false;
        }
        if ($this->getIsNewRecord()) {
            if ($insertOthers) {
                $transaction = Yii::app()->db1->beginTransaction();
                $otherModel = ($this instanceof SalaryHours) ? new InvoiceHours() : new SalaryHours();
                $otherModelName = ($this instanceof SalaryHours) ? "Invoice" : "Salary";
                $hours = new Hours();

                $hours->attributes = $this->attributes;
                $otherModel->attributes = $this->attributes;
                $hours->automatic = 1;
                $otherModel->automatic = 1;

                $saved = $hours->save($runValidation, $attributeNames, false);

                $otherModel->hours_id = $hours->id;
                if (!$otherModel->save($runValidation, $attributeNames, false)) {
                    foreach ($otherModel->getErrors() as $attrName => $errorArr) {
                        foreach ($errorArr as $e) {
                            $this->addError($attrName, "Other: " . $e);
                        }
                    }
                    $transaction->rollBack();
                    return false;
                }

                $this->hours_id = $hours->id;
                if (!$this->insert($attributeNames)) {
                    $transaction->rollBack();
                    return false;
                }

                $hours->latest_salary_id = $otherModelName === "Salary"
                    ? $otherModel->id : $this->id;
                $hours->latest_invoice_id = $otherModelName === "Salary"
                    ? $this->id : $otherModel->id;
                // $hours is no longer new, so we don't need to
                // pass false as the last argument
                $saved = $hours->save($runValidation, $attributeNames);
                if ($saved) {
                    $transaction->commit();
                    return [
                        "hour" => $hours,
                        "salary" => $otherModelName === "Salary" ? $otherModel : $this,
                        "invoice" => $otherModelName === "Salary" ? $this : $otherModel,
                    ];
                }
            } else {
                return $this->insert($attributeNames);
            }
        }
        return $this->update($attributeNames);
    }

    /**
     * Saves this model and automatically sets previous_version_id and version
     * to the correct values (even if they were already set by say copyFrom).
     * Automatically updates Hours models latest_x_id field.
     * @return self|false
     */
    public function saveAsNewVersionOf(VersionedHours $previousVersion)
    {
        $transaction = Yii::app()->db1->beginTransaction();

        // these might already be set if the user called copyFrom
        // but just in case let's check
        if ($this->previous_version_id != $previousVersion->id) {
            $this->previous_version_id = $previousVersion->id;
        }
        if ($this->version != $previousVersion->version + 1) {
            $this->version = $previousVersion->version + 1;
        }

        if (!$this->save(true, null, false)) {
            Yii::log(
                "Failed to save hours: " . json_encode($this->getErrors()),
                CLogger::LEVEL_ERROR,
                __METHOD__
            );
            $transaction->rollBack();
            return false;
        }

        $hoursIdField = ($this instanceof SalaryHours)
            ? "latest_salary_id" : "latest_invoice_id";

        $baseHours = Hours::model()->find("$hoursIdField = {$previousVersion->id}");
        if ($baseHours === null) {
            Yii::log("Base hours can't be null, but it is", CLogger::LEVEL_ERROR, __METHOD__);
            throw new Error("Base hours was null, even though it can't be !");
        }

        $baseHours->$hoursIdField = $this->id;

        if (!$baseHours->save()) {
            Yii::log("Failed to update base hours $hoursIdField field", CLogger::LEVEL_ERROR, __METHOD__);
            $transaction->rollBack();
            return false;
        }
        $transaction->commit();
        return $this;
    }
}
