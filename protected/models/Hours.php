<?php

/**
 * This is the modal class for table "hours".
 * 
 * @property int $id
 * @property string $created_at
 * @property string $updated_at
 * @property int $worker_id
 * @property int $shift_id
 * @property int $property_id
 * @property int $client_id
 * @property string $starting_time YYYY-MM-DD HH:mm:ss (Y-m-d H:i:s)
 * @property string $ending_time YYYY-MM-DD HH:mm:ss (Y-m-d H:i:s)
 * @property int $status
 * @property string $gps_location
 * @property string $google_distance
 * @property bool $approved User ID of the approver
 * @property int $approver
 * @property float $evening_hours
 * @property float $night_hours
 * @property float $sunday_hours
 * @property float $special_saturday_hours
 * @property float $sick_leave_paid_hours
 * @property float $sick_leave_unpaid_hours
 * @property float $annual_leave_hours
 * @property float $public_holiday_hours
 * @property float $unpaid_hours
 */
class Hours extends DB2ActiveRecord
{

    private $DATE_TIME_FORMAT = "Y-m-d H:i:s";
    private $DATE_FORMAT = "Y-m-d";
    private $TIME_FORMAT = "H:i:s";

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return "hours";
    }


    public function rules()
    {
        return [
            ["id, worker_id, shift_id, property_id, client_id, status, approver", "numerical", "integerOnly" => true],
            ["starting_time, ending_time, gps_location, google_distance", "safe"],
            ["evening_hours, night_hours, sunday_hours, special_saturday_hours, sick_leave_paid_hours, sick_leave_unpaid_hours, annual_leave_hours, public_holiday_hours, unpaid_hours", "numerical"],
        ];
    }

    /**
     * Handles auto-accepting this hour-entry if possible.
     * @param int $workAcceptDeltaMinutes maximum time difference allowed between 
     * planned and done work duration
     * @param int $travelAcceptDeltaMinutes maximum time difference allowed between... probably
     * estimated travel time (by google) vs actual travel time (this isn't done/enabled yet)
     * Returns an array when auto-accepting is completed: ["salary" => editedHourModel, "invoice" => editedHourModel],
     * returns null when no auto-accepting happened.
     */
    public function handleAutoAccept(
        $acceptCriteria,
        int $workAcceptDeltaMinutes,
        int $travelAcceptDeltaMinutes = 0
    ) {
        // check if status is a work type
        if (in_array($this->status, [
            3, // normal work stop
            5, // trainee work stop
            7 // help work stop
        ])) {
            // TODO: app_auto_hyvaksyminen_tvmukaan
            if ($this->shift_id) {
                $shift = Tyovuoroot::model()->findByPk($this->shift_id);
                if ($shift) {
                    $shiftStart = DateTimeImmutable::createFromFormat("d.m.Y H:i", $shift->pvm . " " . $shift->alku);
                    $shiftEnd = DateTimeImmutable::createFromFormat("d.m.Y H:i", $shift->pvm . " " . $shift->loppu);
                    // return early if start or end are invalid dates
                    if (!$shiftStart || !$shiftEnd) {

                        return null;
                    }
                    // calculate shift duration
                    $plannedDuration = $this->calculateSecondsBetween($shiftStart, $shiftEnd);

                    $startDate = DateTimeImmutable::createFromFormat($this->DATE_TIME_FORMAT, $this->starting_time);
                    $endDate = DateTimeImmutable::createFromFormat($this->DATE_TIME_FORMAT, $this->ending_time);
                    if (!$startDate || !$endDate) {

                        return null;
                    }
                    $doneDuration = $this->calculateSecondsBetween($startDate, $endDate);

                    // convert cutoff minutes to seconds
                    $autoAcceptDelta = $workAcceptDeltaMinutes * 60;
                    // depending on the criteria, we need to handle auto-accepting differently
                    // accept by duration
                    if ($acceptCriteria == 0) {

                        return $this->handleDurationAutoAccept($autoAcceptDelta, $doneDuration, $plannedDuration);
                    } // accept by timeframe 
                    else if ($acceptCriteria == 1) {

                        return $this->handleTimeframeAutoAccept(
                            $startDate,
                            $endDate,
                            $shiftStart,
                            $shiftEnd,
                            $autoAcceptDelta
                        );
                    }
                }

                // shift not found, return null
                return null;
            }

            // shift ID not set (or 0), return null
            return null;
        }

        // not work or travel type, return null
        return null;
    }

    /**
     * Handles auto-accepting by comparing the actual duration
     * of planned and done workshift.
     */
    private function handleDurationAutoAccept(
        int $autoAcceptDelta,
        int $doneDuration,
        int $plannedDuration
    ) {
        // get the difference of done and done seconds (absolute value, always positive)
        $diffSeconds = abs($doneDuration - $plannedDuration);

        // check if difference is smaller than the allowed delta
        if ($diffSeconds <= $autoAcceptDelta) {
            $salary = new EditedHours();
            $salary->attributes = $this->attributes;
            unset($salary->id);
            $salary->hours_id = $this->id;
            $salary->approved = 1;
            // approver should be user id, but since this is
            // auto-accepted there's no real user id. set it to 0
            $salary->approver = 0;
            $salary->version = 1;
            // editor should be user id, but since this is
            // auto-accepted there's no real user id. set it to 0
            $salary->editor_id = 0;
            // salary hours type, yii2 constant
            $salary->type = 1;

            $invoice = new EditedHours();
            $invoice->attributes = $salary->attributes;
            // invoice hours type, yii2 constant
            $invoice->type = 2;
            // this doesn't seem to copy over for some reason
            $invoice->approved = 1;

            $salarySaved = $salary->save();
            $invoiceSaved = $invoice->save();

            if ($salarySaved && $invoiceSaved) {
                // return new models on auto-accept
                return ["salary" => $salary, "invoice" => $invoice];
            }
        }
        return null;
    }

    /**
     * Handles auto-accepting by comparing start and end
     * timestamps of planned and done workshift.
     */
    private function handleTimeframeAutoAccept(
        DateTimeInterface $doneStart,
        DateTimeInterface $doneEnd,
        DateTimeInterface $plannedStart,
        DateTimeInterface $plannedEnd,
        $autoAcceptDelta
    ) {
        $doneDiff = abs($doneStart->getTimestamp() - $plannedStart->getTimestamp());
        $endDiff = abs($doneEnd->getTimestamp() - $plannedEnd->getTimestamp());

        if ($doneDiff <= $autoAcceptDelta && $endDiff <= $autoAcceptDelta) {
            $salary = new EditedHours();
            $salary->attributes = $this->attributes;
            unset($salary->id);
            $salary->hours_id = $this->id;
            $salary->approved = 1;
            // approver should be user id, but since this is
            // auto-accepted there's no real user id. set it to 0
            $salary->approver = 0;
            $salary->version = 1;
            // editor should be user id, but since this is
            // auto-accepted there's no real user id. set it to 0
            $salary->editor_id = 0;
            // salary hours type, yii2 constant
            $salary->type = 1;

            $invoice = new EditedHours();
            $invoice->attributes = $salary->attributes;
            // invoice hours type, yii2 constant
            $invoice->type = 2;
            // this doesn't seem to copy over for some reason
            $invoice->approved = 1;

            $salarySaved = $salary->save();
            $invoiceSaved = $invoice->save();

            if ($salarySaved && $invoiceSaved) {
                // return new models on auto-accept
                return ["salary" => $salary, "invoice" => $invoice];
            }
        }
        return null;
    }

    /**
     * Calculates durations in hours for evening hours, night hours, ...
     * and updates those durations into the model.
     */
    public function calculateDurations()
    {
        // TODO: function which calculates evening, night, ..., unpaid hours based on the time
        // convert start and end times into datetime objects for easier comparison

        $startDate = DateTimeImmutable::createFromFormat($this->DATE_TIME_FORMAT, $this->starting_time);
        $endDate = DateTimeImmutable::createFromFormat($this->DATE_TIME_FORMAT, $this->ending_time);

        // TODO: read cutoffs from settings
        $evening_cutoff = "18:00:00";
        $night_cutoff = "22:00:00";
        // do calculations if startDate and endDate parsed successfully
        if ($startDate !== false && $endDate !== false) {
            $this->evening_hours = $this->calculateEveningHours($startDate, $endDate, $evening_cutoff, $night_cutoff);
            $this->night_hours = $this->calculateNightHours($startDate, $endDate, $night_cutoff);
            $this->sunday_hours = $this->calculateSundayHours($startDate, $endDate);
            $this->public_holiday_hours = $this->calculatePublicHoldayHours($startDate, $endDate);
            $this->special_saturday_hours = $this->calculateSpecialSaturdayHours($startDate, $endDate);
        }
    }

    /**
     * Calculates evening hours between start and end DateTime objects.
     * cutoffStart and cutoffEnd will be used to calculate hours between the
     * time when an evening shift should start (like 18:00:00) and when it should end
     * (like 22:00:00 when a night shift would start)
     * @param DateTimeInterface $start Starting DateTime
     * @param DateTimeInterface $end Ending DateTime
     * @param string $cutoffStart cutoff start time in H:i:s (HH:mm:ss) format
     * @param string $cutoffEnd cutoff end time in H:i:s (HH:mm:ss) format
     * @return float number of hours after cutoff
     */
    private function calculateEveningHours(
        DateTimeInterface $start,
        DateTimeInterface $end,
        string $cutoffStart,
        string $cutoffEnd
    ) {

        // create a DateTime object from the cutoffStart that has the DATE part of the starting time
        $startCutoffDate = DateTime::createFromFormat(
            $this->DATE_TIME_FORMAT,
            $start->format($this->DATE_FORMAT) . " " . $cutoffStart
        );
        // default the start time as the provided start time
        $cutoffStartDate = DateTime::createFromImmutable($start);

        // if the start cutoff time is larger than (lets say 18:00:00) than the provided
        // start time (let's say 17:00:00) we need to set cutoffStartDate as the created
        // startCutoffDate, to ignore the hours before the cutoffStart time.
        if ($startCutoffDate > $start) {
            $cutoffStartDate = $startCutoffDate;
        }

        // create a DateTime object that has the DATE part of the starting time
        $endCutoffDate = DateTime::createFromFormat(
            $this->DATE_TIME_FORMAT,
            $start->format($this->DATE_FORMAT) . " " . $cutoffEnd
        );

        // default the end time as the provided end time
        $cutoffEndDate = DateTime::createFromImmutable($end);
        // if the original end time (lets say 23:00:00)
        // is larger than the calculated end cutoff time (say 22:00:00)
        // we need to set cutoffEndDate as the calculated end date, to ignore
        // the hours after the ending cutoff.
        if ($end > $endCutoffDate) {
            $cutoffEndDate = $endCutoffDate;
        }
        $duration = $this->calculateHoursBetween($cutoffStartDate, $cutoffEndDate);
        // minus values can happen when end is before the cutoff date.
        // we can just return 0 in a case where the value is negative.
        if ($duration < 0) {
            $duration = 0;
        }
        return $duration;
    }

    /**
     * Calculates the night hours between start and end DateTime objects.
     * cutoffStart will be used to calculate hours between the time when a
     * night shift should start (like 22:00:00) and the end time.
     * @param DateTimeInterface $start Starting DateTime
     * @param DateTimeInterface $end Ending DateTime
     * @param string $cutoffStart cutoff start time in H:i:s (HH:mm:ss) format
     */
    private function calculateNightHours(
        DateTimeInterface $start,
        DateTimeInterface $end,
        string $cutoffStart
    ) {
        // create a DateTime object from the cutoffStart that has the DATE part of the starting time
        $startCutoffDate = DateTime::createFromFormat(
            $this->DATE_TIME_FORMAT,
            $start->format($this->DATE_FORMAT) . " " . $cutoffStart
        );

        // default the start time as the provded start time...
        $cutoffStartDate = DateTime::createFromImmutable($start);
        // ...however, if the start cutoff time is larger than (lets say 23:00:00) than the
        // provided start time (let's say 22:00:00) we need to set the cutoffStartDate as the created
        // startCutoffDate, to ignore the hours before the cutoffStart time.
        if ($startCutoffDate > $start) {
            $cutoffStartDate = $startCutoffDate;
        }

        $duration = $this->calculateHoursBetween($cutoffStartDate, $end);
        // minus values can happen when end is before the cutoff date.
        // we can just return 0 in a case where the value is negative.
        if ($duration < 0) {
            $duration = 0;
        }
        return $duration;
    }

    /**
     * Calculates the hours between start end end DateTime objects.
     * @param DateTimeInterface $start Starting date
     * @param DateTimeInterface $end Ending date
     * @return float number of hours
     */
    private function calculateHoursBetween(
        DateTimeInterface $start,
        DateTimeInterface $end
    ) {
        // calculates the seconds between the timestams, then
        // converts the seconds into hours
        return ($end->getTimestamp() - $start->getTimestamp()) / 3600;
    }

    /**
     * Calculates the seconds between start and end DateTime objects.
     * @param DateTimeInterface $start Starting date
     * @param DateTimeInterface $end Ending date
     * @return float Seconds between start and end timestamps
     */
    private function calculateSecondsBetween(
        DateTimeInterface $start,
        DateTimeInterface $end
    ) {
        return $end->getTimestamp() - $start->getTimestamp();
    }

    /**
     * Checks if two dates are the same (Y-m-d comparison)
     * @param DateTimeInterface $d1 First date to compare
     * @param DateTimeInterface $d2 Second date to compare
     * @return bool date equality boolean
     */
    private function isSameDate(DateTimeInterface $d1, DateTimeInterface $d2): bool
    {
        return $d1->format($this->DATE_FORMAT) === $d2->format($this->DATE_FORMAT);
    }

    /**
     * Calculates how many hours are on a sunday between start
     * and end DateTime objects.
     * @param DateTimeInterface $start Starting date
     * @param DateTimeInterface $end Ending date
     * @return float number of hours
     */
    private function calculateSundayHours(
        DateTimeInterface $start,
        DateTimeInterface $end
    ): float {
        // "w" = 0 (sunday) ... 6 (saturday) (not ISO-8601 standard
        // but in this case it doesn't matter)
        $weekDayFormat = "w";
        // check if start date is a sunday
        $isSunday = $start->format($weekDayFormat) === "0";
        if ($isSunday) {
            return $this->calculateHoursBetween($start, $end);
        }
        return 0;
    }

    /**
     * Calculates how many hours are on a special saturday between
     * start and end DateTime objects.
     * @param DateTimeInterface $start Starting date
     * @param DateTimeInterface $end Ending date
     * @return float number of hours
     */
    private function calculateSpecialSaturdayHours(
        DateTimeInterface $start,
        DateTimeInterface $end
    ): float {
        $year = $start->format("Y");
        $specialSaturdays = $this->specialSaturdaysForYear($year);
        $startDay = $start->format($this->DATE_FORMAT);
        if (in_array($startDay, $specialSaturdays)) {
            return $this->calculateHoursBetween($start, $end);
        }
        return 0;
    }

    /**
     * Calculates how many hours are on a public holday between
     * start and end DateTime objects.
     * @param DateTimeInterface $start Starting date
     * @param DateTimeInterface $end Ending date
     * @return float number of hours
     */
    private function calculatePublicHoldayHours(
        DateTimeInterface $start,
        DateTimeInterface $end
    ): float {
        // get the current year number in YYYY format
        $currentYear = $start->format("Y");
        // get public holday list for current year
        $holidays = $this->holidaysForYear($currentYear);
        // check if starting day is one of those holiday dates
        // if yes, calculate duration between start and end
        $startDay = $start->format($this->DATE_FORMAT);
        if (in_array($startDay, $holidays)) {
            return $this->calculateHoursBetween($start, $end);
        }
        return 0;
    }

    /**
     * Returns an array of public holiday dates in YYYY-MM-DD (Y-m-d php) 
     * format for a given year.
     * @param string $year
     */
    private function holidaysForYear(string $year): array
    {
        // Easter timestamp is in UTC time, so dates need to be adjusted +1 day.
        // (easter_date returns previous day 21:00).
        $easter_timestamp = easter_date($year);
        return [
            "$year-01-01", // uv
            "$year-01-06", // loppiainen
            date('Y-m-d', strtotime('-1day', $easter_timestamp)), // pitkäperjantai
            date('Y-m-d', strtotime('+1day', $easter_timestamp)), // pääsiäispäivä 1
            date('Y-m-d', strtotime('+2day', $easter_timestamp)), // pääsiäispäivä 2
            "$year-05-01", // vappu
            date('Y-m-d', strtotime("second sunday of may $year")), // äitienpäivä (ei virallinen)
            date('Y-m-d', strtotime('+40day', $easter_timestamp)), // helatorstai
            date('Y-m-d', strtotime('+50day', $easter_timestamp)), // helluntai
            date('Y-m-d', strtotime('next friday', strtotime("$year-06-18"))), // juhannusaatto (ei virallinen)
            date('Y-m-d', strtotime('next saturday', strtotime("$year-06-19"))), // juhannus
            date('Y-m-d', strtotime('next saturday', strtotime("$year-10-30"))), // pyhäinpäivä
            date('Y-m-d', strtotime("second sunday of november $year")), // isänpäivä
            "$year-12-06", // itsenäisyyspäivä
            "$year-12-24", // jouluaatto (ei virallinen)
            "$year-12-25", // joulupäivä
            "$year-12-26", // tapaninpäivä
        ];
    }

    /**
     * Returns an array of special saturday dates in YYYY-MM-DD (Y-m-d php)
     * format for a given year.
     * @param string $year
     */
    private function specialSaturdaysForYear(string $year): array
    {
        $easter_timestamp = easter_date($year);
        return [
            // new years weeks saturday
            date("Y-m-d", strtotime("saturday this week", strtotime("$year-01-01"))),
            // loppiainen weeks saturday
            date("Y-m-d", strtotime("saturday this week", strtotime("$year-01-06"))),
            // pääsiäislauantai
            date("Y-m-d", $easter_timestamp),
            // vappu weeks saturday
            date("Y-m-d", strtotime("saturday this week", strtotime("$year-05-01"))),
            // helatorstai weeks saturday
            date("Y-m-d", strtotime("saturday this week", strtotime("+40day", $easter_timestamp))),
            // itssenäisyyspäivä viikon lauantai
            date("Y-m-d", strtotime("saturday this week", strtotime("$year-12-06")))
        ];
    }
}
