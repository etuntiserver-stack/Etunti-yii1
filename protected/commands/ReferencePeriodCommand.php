<?php

class ReferencePeriodCommand extends BatchEmailCommand
{
    private $dryRun = false;

    public function actionIndex($dryrun = 0) 
    {
        if($dryrun == 1 || $dryrun === "true") {
            $this->dryRun = true;
        }
        if($this->dryRun === true) {
            echo "Dry run enabled" . PHP_EOL;
        }
        
        $this->changeDbConnectionTo("kotipuhtaaksi");
        $settings = Yii::app()->db1->createCommand()
            ->select("*")
            ->from("asetukset")
            ->where("id=1")
            ->queryRow();

        if($settings && isset($settings["reference_period_enabled"]) 
            // for some reason reference_period_enabled is returned as a string
            && $settings["reference_period_enabled"] == 1) {
            $this->handleReferencePeriodEmails($settings, "kotipuhtaaksi");
        } else {
            echo "Reference period not defined or enabled for domain kotipuhtaaksi [PH]" . PHP_EOL;
            return;
        }
    }

    /**
     * Looks at the domains settings and figures out if we should send out
     * start or end reference period emails.
     */
    private function handleReferencePeriodEmails(Array $settings, string $domain)
    {
        echo "Reference period enabled for domain $domain" . PHP_EOL;
        // make sure start_send_date is set
        if(isset($settings["reference_period_start_date"])) {
            $start_date_str = $settings["reference_period_start_date"];
            // make sure start_send_date is defined
            if($start_date_str) {
                // get DateTime object from start_send_date_str
                $startDate = DateTime::createFromFormat("Y-m-d", $start_date_str);
                $startDateTwoWeeks = (clone $startDate)->modify("-2 weeks");
                // compare startDate - 2 weeks to now, if now is larger or same than
                // startDate, we should send emails.
                if($startDateTwoWeeks <= new DateTime("now")) {
                    echo "Should send start emails" . PHP_EOL;
                    // check if emails are sent already
                    $emailsSent = $settings["reference_period_emails_sent"] == 1;
                    if($emailsSent === false) {
                        echo "Emails not sent yet" . PHP_EOL;
                        $this->sendStartEmails($settings, $startDate, $domain);
                    } else {
                        echo "Emails already sent." . PHP_EOL;
                    }
                } else {
                    echo "Should not send start emails" . PHP_EOL;
                }
                // compare startDate to now, if now is larger or same than
                // startDate, we should update the start date.
                if($startDate <= new DateTime("now")) {
                    echo "Should update start date". PHP_EOL;
                    if(isset($settings["reference_period_length"])) {
                        $length = $settings["reference_period_length"];
                        $format = "Y-m-d";
                        $newDate = $startDate->modify("+$length weeks");
                        $formatted = $newDate->format($format);
                        Yii::app()->db1->createCommand()
                            ->update("asetukset",
                            ["reference_period_start_date" => $formatted,
                            "reference_period_emails_sent" => 0],
                            "id=:id", 
                            [":id" => 1],
                        );
                        echo "Updated start date to $formatted" . PHP_EOL;
                    } else {
                        echo "Reference period length not defined" . PHP_EOL;
                    }
  
                } else {
                    echo "Should not update start date" . PHP_EOL;
                }
            } 
        } else {
            echo "Start send date not defined for domain $domain" . PHP_EOL;
        }
        // make sure end_send_date is set
        if(isset($settings["reference_period_end_send_date"])) {
            $end_send_date_str = $settings["reference_period_end_send_date"];
            // make sure end_send_date is defined
            if($end_send_date_str) {
                // get DateTime object from end_send_date_str
                $endDate = DateTime::createFromFormat("Y-m-d", $end_send_date_str);
                // compare endDate to now, if now is larger or same than
                // endDate, we should send emails.
                if($endDate <= new DateTime("now")) {
                    echo "Should send end emails" . PHP_EOL;
                    $this->sendEndEmails($settings, $endDate, $domain);
                   
                } else {
                    echo "Should not send end emails" . PHP_EOL;
                }
            }
        } else {
            echo "End send date not defined for domain $domain" . PHP_EOL;
        }
        
    }

    /**
     * Sends start email notifications and updates next start date.
     * @param array $settings settings object
     * @param DateTime $date reference period start date
     * @param string $domain current domain name
     */
    private function sendStartEmails(Array $settings, DateTime $date, string $domain)
    {
        echo "Sending start emails..." . PHP_EOL;
        // make sure email subject, body and length are defined
        if(isset($settings["reference_period_start_email_subject"]) 
            && isset($settings["reference_period_start_email_body"])
            && isset($settings["reference_period_length"])
        ) 
        {
            // sender address, make first char capital in $domain
            $from = ucfirst($domain) . " <no-reply@etunti.fi>";
            $subject = $settings["reference_period_start_email_subject"];
            $preset_body = $settings["reference_period_start_email_body"];
            $length = $settings["reference_period_length"];

            $body = $this->startEmailBody($preset_body, $date, $length);


            $refPeriodEnd = (clone $date)->modify("+$length weeks");
            // query active workers, with email address defined, no end date defined in contract
            // and vkotyoaika defined in contract
            $workers = $this->getActiveEmployeeList($refPeriodEnd);
            
            // test data
            /*
            $recipients = ["simo@kotipuhtaaksi.fi", "nekuin@gmail.com"];
            $recipientVars = 
            [
                "simo@kotipuhtaaksi.fi" => ["hours" => 37.5], 
                "nekuin@gmail.com" => ["hours" => 37]
            ];
            */
            
            //print_r($recipientVars);
             //uncomment this only if you want to send real emails
            $recipients = [];
            $recipientVars = [];
            foreach($workers as $worker) {
                // get and validate workers email
                $email = $worker["tekijan_email"];
                // skip this worker if email is invalid
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                // get workers hour string
                $hourString = $worker["vktyoaika"];
                // skip this worker if there's 0 hours scheduled
                // for the user.
                if($hourString !== "00:00") {
                    // add worker as a recipient for the email
                    $recipients[] = $email;
                    // parse hours string to a float
                    $hours = $this->parseWorkhours($hourString);
                    // if parsing is successful, use the float value
                    if($hours) {
                        $recipientVars[$email] = ["hours" => $hours];
                    } 
                    // if parsing fails, use the original value
                    else {
                        $recipientVars[$email] = ["hours" => $worker["vktyoaika"]];
                    }
                } 
            }
            
            //print_r($recipientVars);
            
            if($this->dryRun === false) {
                $result = parent::batchSendMail($from, $recipients, $recipientVars, $subject, $body);

                if($result["responseCode"] !== 200) {
                    echo "something went wrong" . PHP_EOL;
                } else {
                    echo "Mails successfully sent" . PHP_EOL;
                    Yii::app()->db1->createCommand()
                        ->update("asetukset",
                        ["reference_period_emails_sent" => 1],
                        "id=:id", 
                        [":id" => 1],
                    );
                    echo "Updated sent flag to true" . PHP_EOL;
                }
            } else {
                echo "Dry run, not actually sending emails" . PHP_EOL;
                print_r($recipients);
                echo PHP_EOL;
                print_r($recipientVars);
                echo PHP_EOL;
                print_r(sizeof($recipients));
                echo PHP_EOL;
                print_r(sizeof($recipientVars));
                echo PHP_EOL;
                $sizeMatch = sizeof($recipients) === sizeof($recipientVars);
                if(!$sizeMatch) {
                    echo "Recipients and recipient vars don't match!" . PHP_EOL;
                }
            }
            
            
        } else {
            echo "Email subject, body or length isn't defined for domain $domain" . PHP_EOL;
        }
    }

    /**
     * Sends end email notifications and updates next end date
     * 
     * @param array $settings Settings model as an array
     * @param DateTime $date *reference_period_end_send_date* from settings as DateTime
     * @param string $domain Current domain
     */
    private function sendEndEmails(Array $settings, DateTime $date, string $domain)
    {
        echo "Sending end emails..." . PHP_EOL;
        // make sure email subject, body and length are defined
        if(isset($settings["reference_period_end_email_subject"]) 
            && isset($settings["reference_period_end_email_body"])
            && isset($settings["reference_period_length"])
            && isset($settings["reference_period_start_date"])
        ) 
        {
            // sender address, make first char capital in $domain
            $from = ucfirst($domain) . " <no-reply@etunti.fi>";
            $subject = $settings["reference_period_end_email_subject"];
            $preset_body = $settings["reference_period_end_email_body"];
            $length = $settings["reference_period_length"];

            // we can get the last reference_periods starting date by taking the
            // new start_send_date (which should already be updated at this point), 
            // and reducing $length number of weeks from it
            $format = "Y-m-d";

            // start of the reference_period which will be used to query for done work hours
            // start date is updated by this time, so it's actually the end date of the last period
            $ref_period_end_date = $settings["reference_period_start_date"];
            $refEndDate = DateTime::createFromFormat($format, $ref_period_end_date);

            // we can't directly modify refEndDate, so we'll clone it.
            // end of the reference_period which will be used to query for done work hours.
            $refStartDate = (clone $refEndDate)->modify("-$length weeks");

            // get the same (unless their data changed) list of employees that 
            // we used for starting emails. in theory there can be a case
            // where an employee might've had their active status changed or something
            // which would result in them not being on the list, but most of those
            // changes usually mean that the employee is quitting... so I don't think
            // they'll mind. see getActiveEmployeeList for query params
            $workers = $this->getActiveEmployeeList($refEndDate);

            // build a list of just the worker IDs for the query
            $workerIds = [];
            foreach($workers as $worker) {
                $workerIds[] = $worker["id"];
            }
            // query done hours for employees in $workers
            $workerHours = $this->getWorkHoursForPeriod($refStartDate, $refEndDate, $workerIds);
            //  split every workers hours into week long sections
            $workerWeekHourList = [];
            $dateFormat = "d.m.Y H:i:s";
            $dateFormatNoSeconds = "d.m.Y H:i";
            // pre-generate week numbers based on period start date, end date and length
            $weekNumbers = [];
            for($i = 0; $i < $length; ++$i) 
            {
                $weekN = (clone $refStartDate)->modify("+$i weeks");
                $weekNumbers[] = $weekN->format("W");
            }
            // $hourList is an array of partial Mobile model objects
            // with id, aloitan, loppui, tid and status fields
            foreach($workerHours as $workerId => $hourList)
            {
                // pre-construct weekNumber array for worker
                foreach($weekNumbers as $n) 
                {
                    $workerWeekHourList[$workerId][$n] = 0;
                }
                // $hourArray is a partial Mobile model object
                // with id, aloitan, loppui, tid and status fields
                foreach($hourList as $hourArray)
                {
                    $startDate = DateTime::createFromFormat($dateFormat, $hourArray["aloitan"]);
                    // I've seen a couple entries with the seconds removed
                    // from "aloitan" for some reason, so we'll attempt to parse
                    // that with a format without the seconds.
                    if($startDate === false) {
                        $startDate = DateTime::createFromFormat($dateFormatNoSeconds, $hourArray["aloitan"]);
                    }
                    
                    $endDate = DateTime::createFromFormat($dateFormat, $hourArray["loppui"]);
                    // same as above, attempt to parse without seconds on failure
                    if($endDate === false) {
                        $endDate = DateTime::createFromFormat($dateFormatNoSeconds, $hourArray["loppui"]);
                    }
                    // skip this entry if we don't have valid $startDate and $endDate
                    if($startDate === false || $endDate === false) continue;

                    // get the week number of this hour entry
                    $startWeekNumber = $startDate->format("W");
                    // skip date if the week number isn't in the periods week numbers
                    // this can happen in "user related errors", where they define
                    // the sending date as some weird number
                    if(!in_array($startWeekNumber,$weekNumbers)) continue;
  
                    // find old sum from workerWeekHourList
                    $weekSum = $workerWeekHourList[$workerId][$startWeekNumber] ?? 0;
                    // add work duration to sum
                    $duration = $startDate->diff($endDate);
                    // format duration as HH:mm which we can parse with the work hours parser
                    $whDur = $duration->format("%H:%i");
                    $dur = $this->parseWorkhours($whDur);
                    // if parsing was succesful, add duration to weeks sum
                    if($dur) {
                        $weekSum += round($dur, 2);
                    }
                    
                    $workerWeekHourList[$workerId][$startWeekNumber] = $weekSum;
                }
            }
            //print_r($workerWeekHourList);

            // construct recipient and recipientVars list for batch email
            
            $recipients = [];
            $recipientVars = [];
            
            // loop through employee list, getting their hour data from
            // the constructed $workerWeekHourList
            foreach($workers as $worker)
            {
                // get and validate workers email 
                $email = $worker["tekijan_email"];
                // skip this worker if email is invalid
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                // there is a chance that there was no work done for
                // some employees, which is why we need to check for existance
                if(isset($workerWeekHourList[$worker["id"]])) {
                    $hourList = $workerWeekHourList[$worker["id"]];
                    // get workers email
                    //$email = $worker["tekijan_email"];
                    $recipients[] = $email;
                    // construct var list
                    $varList = [];
                    $i = 0;
                    foreach($hourList as $hourValue)
                    {
                        // mailgun %recipient.key% key
                        // which should be an indexed hours entry
                        // in the email body it'll look like this %recipient.hours0%
                        $key = "hours$i";
                        $varList[$key] = $hourValue; 
                        $i++;
                    }
                    $recipientVars[$email] = $varList;
                }
            }

            print_r(count($recipients));
            print_r("/");
            print_r(count($recipientVars) . PHP_EOL);
            
            //print_r($recipientVars);
            

            //print_r($recipients);
            //print_r($recipientVars);

            /*
            test data
            $recipients = ["simo@kotipuhtaaksi.fi", "nekuin@gmail.com"];
            $recipientVars = [
                "simo@kotipuhtaaksi.fi" => [
                    "hours0" => 35,
                    "hours1" => 35,
                    "hours2" => 35,
                    "hours3" => 35,
                    "hours4" => 35,
                    "hours5" => 35,
                    "hours6" => 35,
                    "hours7" => 35,
                ],
                "nekuin@gmail.com" => [
                    "hours0" => 35.5,
                    "hours1" => 35.5,
                    "hours2" => 35.5,
                    "hours3" => 35.5,
                    "hours4" => 35.5,
                    "hours5" => 35.5,
                    "hours6" => 35.5,
                    "hours7" => 35.5,
                ],
            ];
            
            */


            // construct email body, $date and $length are used to calculate the week numbers
            $body = $this->endEmailBody($preset_body, $refStartDate, $length);

            if($this->dryRun === false) {
                $result = parent::batchSendMail($from, $recipients, $recipientVars, $subject, $body);

                if($result["responseCode"] !== 200) {
                    echo "something went wrong" . PHP_EOL;
                } else {
                    echo "Mails successfully sent" . PHP_EOL;
                    
                    // update next send date to settings
                    $newDate = $date->modify("+$length weeks");
                    $formatted = $newDate->format("Y-m-d");
                    Yii::app()->db1->createCommand()
                        ->update("asetukset",
                        ["reference_period_end_send_date" => $formatted],
                        "id=:id", 
                        [":id" => 1],
                    );
                    echo "Updated next end send date to $formatted" . PHP_EOL;
                }
            } else {
                echo "Dry run, not actually sending emails" . PHP_EOL;
                print_r($recipients);
                echo PHP_EOL;
                print_r($recipientVars);
                echo PHP_EOL;
                print_r(sizeof($recipients));
                echo PHP_EOL;
                print_r(sizeof($recipientVars));
                echo PHP_EOL;
                $sizeMatch = sizeof($recipients) === sizeof($recipientVars);
                if(!$sizeMatch) {
                    echo "Recipients and recipient vars don't match!" . PHP_EOL;
                }
            }
            
        } else {
            echo "Email subject, body, length or start date isn't defined for domain $domain" . PHP_EOL;
        }
    }

    /**
     * Constructs an HTML email body. Pass the user defined email template from settings
     * along with reference_period_start_date and reference_period_length, which
     * will be used to construct a table with week numbers under the template.
     * 
     * The resulting email will look something like this:
     * 
     * --
     * Users text here,
     * this is something that the user defined in settings.
     * --------------------------
     * |  38  |   39   |   40   |
     * --------------------------
     * | 37.5 |  37.5  |  37.5  |
     * --------------------------
     * --
     * 
     * Top row is week numbers and bottom row is work hours.
     * 
     * @param string $preset_body User defined body from settings, *reference_period_start_email_body*
     * @param DateTime $date DateTime of start send date from settings *reference_period_start_date*
     * @param int $length Length of the reference period from settings *reference_period_length*
     */
    private function startEmailBody(string $preset_body, DateTime $date, int $length)
    {
        $styles = "
            table {
                border: 1px solid black;
            }
            td, th {
                border: 1px solid black;
                padding: 5px 10px;
            }
        ";
        $tableHead = [];
        $tableBody = [];
        for($i = 0; $i < $length; ++$i) {
            // clone $date instead of mutating the original one
            $_date = (clone $date)->modify("+$i weeks");
            
            $head = $_date->format("W");
            $tableHead[] = "<th>$head</th>";
            $tableBody[] = "<td>%recipient.hours%</td>";
        }
        
        $table = [
            "<table>",
                "<thead>",
                    "<tr>",
                        implode("", $tableHead),
                    "</tr>",
                "</thead>",
                "<tbody>",
                    "<tr>",
                        implode("", $tableBody),
                    "</tr>",
                "</tbody>",
            "</table>",
        ];
        $html = [
            "<!DOCTYPE html>",
            "<html lang=\"fi\">",
                "<head>",
                    "<style>",
                        trim($styles),
                    "</style>",
                    "<title>",
                        "Tasoittumisjakso",
                    "</title>",
                "</head>",
                "<body>",
                    "<p>",
                        $preset_body,
                    "</p>",
                    implode("", $table),
                "</body>",
            "</html>"
        ];
        return implode("", $html);
    }

    /**
     * Constructs an HTML email body. Pass the user defined email template from settings
     * along with table data, which
     * will be used to construct a table with week numbers under the template.
     * 
     * The resulting email will look something like this:
     * 
     * --
     * Users text here,
     * this is something that the user defined in settings.
     * --------------------------
     * |  38  |   39   |   40   |
     * --------------------------
     * | 37.5 |  37.5  |  37.5  |
     * --------------------------
     * --
     * 
     * Top row is week numbers and bottom row is work hours.
     * 
     * @param string $preset_body User defined body from settings, *reference_period_end_email_body*
     * @param DateTime $date DateTime of period start date
     * @param int $length Length of the reference period from settings *reference_period_length*
     */
    private function endEmailBody(string $preset_body, DateTime $date, int $length)
    {
        $styles = "
            table {
                border: 1px solid black;
            }
            td, th {
                border: 1px solid black;
                padding: 5px 10px;
            }
        ";
        $tableHead = [];
        $tableBody = [];
        
        for($i = 0; $i < $length; ++$i) {
            // clone $date instead of mutating the original one
            $_date = (clone $date)->modify("+$i weeks");
            
            $head = $_date->format("W");
            $tableHead[] = "<th>$head</th>";
            $tableBody[] = "<td>%recipient.hours$i%</td>";
        }
        
        
        $table = [
            "<table>",
                "<thead>",
                    "<tr>",
                        implode("", $tableHead),
                    "</tr>",
                "</thead>",
                "<tbody>",
                    "<tr>",
                        implode("", $tableBody),
                    "</tr>",
                "</tbody>",
            "</table>",
        ];
        $html = [
            "<!DOCTYPE html>",
            "<html lang=\"fi\">",
                "<head>",
                    "<style>",
                        trim($styles),
                    "</style>",
                    "<title>",
                        "Tasoittumisjakso",
                    "</title>",
                "</head>",
                "<body>",
                    "<p>",
                        $preset_body,
                    "</p>",
                    implode("", $table),
                "</body>",
            "</html>"
        ];
        return implode("", $html);
    }

    /**
     * Returns a list of active employees (aktiivinen = 1)
     * who have a defined email, and don't have ending date 
     * ("loppu") defined in their contract,
     * and have weekly work hours defined ("vktyoaika") which
     * does not start with a 0.
     * 
     * @param array|null $employeeOverrideList optional array of employee IDs to select only specific employees
     * @param DateTime $endDate reference period end date
     */
    private function getActiveEmployeeList(DateTime $endDate, Array $employeeOverrideList = null): Array
    {
        $endDateStr = $endDate->format("Y-m-d");
        $command = Yii::app()->db1->createCommand()
            ->select("worker.id, tekijan_nimi, tekijan_email, sukunimi, aktiivinen, 
                contract.loppu, contract.vktyoaika")
            ->from("sivex_ttekijat worker")
            ->join("sivex_tyosuhdet contract", "worker.id=contract.tid")
            ->where("worker.aktiivinen = 1")
            ->andWhere("contract.loppu = ''")
            ->andWhere("STR_TO_DATE(alku, '%d.%m.%Y') <= '$endDateStr'")
            ->andWhere("contract.vktyoaika != ''")
            // vktyoaika should not start with a 0
            ->andWhere("contract.vktyoaika NOT LIKE '0%'")
            ->andWhere("worker.tekijan_email != ''");
        if($employeeOverrideList !== null)
        {
            $command->andWhere(["in", "worker.id", $employeeOverrideList]);
        }
        return $command->queryAll();
    }

    /**
     * Get work hours between $startDate and $endDate for defined employees.
     * 
     * @param DateTime $startDate start date
     * @param DateTime $endDate end date
     * @param array $employeeIds Array of employee IDs
     */
    private function getWorkHoursForPeriod(DateTime $startDate, DateTime $endDate, Array $employeeIds)
    {
        $format = "Y-m-d";
        $start_date = $startDate->format($format);
        $end_date = $endDate->format($format);
        // 2 = travel
        // 3 = normal work end
        // 5 = trainee work end
        // 7 = help work end
        // 20 = coffee break
        $statuses = [2, 3, 5, 7, 20];
        
        $readHours = Yii::app()->db1->createCommand()
            ->select("id, aloitan, loppui, tid, status")
            ->from("sivexkuitti")
            ->where("id NOT IN (SELECT kid FROM sivexkuitti_repaired)")
            ->andWhere(["in", "sivexkuitti.status", $statuses])
            ->andWhere("STR_TO_DATE(sivexkuitti.aloitan, '%d.%m.%Y') >= STR_TO_DATE('$start_date', '%Y-%m-%d')")
            ->andWhere("STR_TO_DATE(sivexkuitti.loppui, '%d.%m.%Y') <= STR_TO_DATE('$end_date', '%Y-%m-%d')")
            ->andWhere(["in", "sivexkuitti.tid", $employeeIds])
            ->andWhere("hyvaksytty != ''")
            ->queryAll();

        $repairedHours = Yii::app()->db1->createCommand()
            ->select("id, aloitan, loppui, tid, status")
            ->from("sivexkuitti_repaired")
            ->where(["in", "status", $statuses])
            ->andWhere("STR_TO_DATE(aloitan, '%d.%m.%Y') >= STR_TO_DATE('$start_date', '%Y-%m-%d')")
            ->andWhere("STR_TO_DATE(loppui, '%d.%m.%Y') <= STR_TO_DATE('$end_date', '%Y-%m-%d')")
            ->andWhere(["in", "tid", $employeeIds])
            ->andWhere("hyvaksytty != ''")
            ->queryAll();
        
        // build result array where tid points to hours
        $results = [];
        foreach($readHours as $read)
        {
            $results[$read["tid"]][] = $read;
        }
        foreach($repairedHours as $repaired)
        {
            $results[$repaired["tid"]][] = $repaired;
        }
        return $results;
    }

    /**
     * Default db1 connection is always for "defdb", this function can
     * be used to change the current database.
     */
    private function changeDbConnectionTo(string $domain)
    {
        Yii::app()->db1->setActive(false);
        Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=' . $domain;
        Yii::app()->db1->setActive(true);
    }

    /**
     * Parses the "vktyoaika" field of a Tyosuhdet model,
     * which should be in XX:YY format i.e. (37:30).
     * Returns an float representation of that string, i.e. 37.5
     * @param string $workHours
     * 
     * @return ?float vkotyoaika as a float
     */
    private function parseWorkhours(string $workHours): ?float
    {
        $split = explode(":", $workHours);
        if(sizeof($split) > 1) {
            $hours = $split[0];
            $minutes = $split[1];
            $minutes = floatval($minutes);
            // convert minutes into a value between 0 and 1
            $minutes = $minutes / 60;
            // return hours and minutes as float
            return floatval(intval($hours) + $minutes);
        }
        // return null if parsing fails
        return null;
    }
}
