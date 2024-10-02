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
            $workers = $this->getActiveEmployeeList($refPeriodEnd);
            
            $recipients = [];
            $recipientVars = [];
            foreach($workers as $worker) {
                $email = $worker["tekijan_email"];
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                $hourString = $worker["vktyoaika"];
                if($hourString !== "00:00") {
                    $recipients[] = $email;
                    $hours = $this->parseWorkhours($hourString);
                    if($hours) {
                        $recipientVars[$email] = ["hours" => $hours];
                    } else {
                        $recipientVars[$email] = ["hours" => $worker["vktyoaika"]];
                    }
                } 
            }
            
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
                print_r($recipientVars);
                $sizeMatch = sizeof($recipients) === sizeof($recipientVars);
                if(!$sizeMatch) {
                    echo "Recipients and recipient vars don't match!" . PHP_EOL;
                }
            }
        } else {
            echo "Email subject, body or length isn't defined for domain $domain" . PHP_EOL;
        }
    }

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
            $from = ucfirst($domain) . " <no-reply@etunti.fi>";
            $subject = $settings["reference_period_end_email_subject"];
            $preset_body = $settings["reference_period_end_email_body"];
            $length = $settings["reference_period_length"];

            $format = "Y-m-d";
            $ref_period_end_date = $settings["reference_period_start_date"];
            $refEndDate = DateTime::createFromFormat($format, $ref_period_end_date);
            $refStartDate = (clone $refEndDate)->modify("-$length weeks");
            $workers = $this->getActiveEmployeeList($refEndDate);
            $workerIds = [];
            foreach($workers as $worker) {
                $workerIds[] = $worker["id"];
            }
            $workerHours = $this->getWorkHoursForPeriod($refStartDate, $refEndDate, $workerIds);
            $workerWeekHourList = [];
            $dateFormat = "d.m.Y H:i:s";
            $dateFormatNoSeconds = "d.m.Y H:i";
            $weekNumbers = [];
            for($i = 0; $i < $length; ++$i) 
            {
                $weekN = (clone $refStartDate)->modify("+$i weeks");
                $weekNumbers[] = $weekN->format("W");
            }
            foreach($workerHours as $workerId => $hourList)
            {
                foreach($weekNumbers as $n) 
                {
                    $workerWeekHourList[$workerId][$n] = 0;
                }
                foreach($hourList as $hourArray)
                {
                    $startDate = DateTime::createFromFormat($dateFormat, $hourArray["aloitan"]);
                    if($startDate === false) {
                        $startDate = DateTime::createFromFormat($dateFormatNoSeconds, $hourArray["aloitan"]);
                    }
                    $endDate = DateTime::createFromFormat($dateFormat, $hourArray["loppui"]);
                    if($endDate === false) {
                        $endDate = DateTime::createFromFormat($dateFormatNoSeconds, $hourArray["loppui"]);
                    }
                    if($startDate === false || $endDate === false) continue;

                    $startWeekNumber = $startDate->format("W");
                    if(!in_array($startWeekNumber,$weekNumbers)) continue;
  
                    $weekSum = $workerWeekHourList[$workerId][$startWeekNumber] ?? 0;
                    $duration = $startDate->diff($endDate);
                    $whDur = $duration->format("%H:%i");
                    $dur = $this->parseWorkhours($whDur);
                    if($dur) {
                        $weekSum += round($dur, 2);
                    }
                    $workerWeekHourList[$workerId][$startWeekNumber] = $weekSum;
                }
            }

            $recipients = [];
            $recipientVars = [];
            foreach($workers as $worker)
            {
                $email = $worker["tekijan_email"];
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }
                if(isset($workerWeekHourList[$worker["id"]])) {
                    $hourList = $workerWeekHourList[$worker["id"]];
                    $recipients[] = $email;
                    $varList = [];
                    $i = 0;
                    foreach($hourList as $hourValue)
                    {
                        $key = "hours$i";
                        $varList[$key] = $hourValue; 
                        $i++;
                    }
                    $recipientVars[$email] = $varList;
                }
            }

            $body = $this->endEmailBody($preset_body, $refStartDate, $length);

            if($this->dryRun === false) {
                $result = parent::batchSendMail($from, $recipients, $recipientVars, $subject, $body);

                if($result["responseCode"] !== 200) {
                    echo "something went wrong" . PHP_EOL;
                } else {
                    echo "Mails successfully sent" . PHP_EOL;
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
                print_r($recipientVars);
                $sizeMatch = sizeof($recipients) === sizeof($recipientVars);
                if(!$sizeMatch) {
                    echo "Recipients and recipient vars don't match!" . PHP_EOL;
                }
            }
        } else {
            echo "Email subject, body, length or start date isn't defined for domain $domain" . PHP_EOL;
        }
    }

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
            ->andWhere("contract.vktyoaika NOT LIKE '0%'")
            ->andWhere("worker.tekijan_email != ''");
        if($employeeOverrideList !== null)
        {
            $command->andWhere(["in", "worker.id", $employeeOverrideList]);
        }
        return $command->queryAll();
    }

    private function getWorkHoursForPeriod(DateTime $startDate, DateTime $endDate, Array $employeeIds)
    {
        $format = "Y-m-d";
        $start_date = $startDate->format($format);
        $end_date = $endDate->format($format);
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

    private function changeDbConnectionTo(string $domain)
    {
        Yii::app()->db1->setActive(false);
        Yii::app()->db1->connectionString = 'mysql:host=localhost;dbname=' . $domain;
        Yii::app()->db1->setActive(true);
    }

    private function parseWorkhours(string $workHours): ?float
    {
        $split = explode(":", $workHours);
        if(sizeof($split) > 1) {
            $hours = $split[0];
            $minutes = $split[1];
            $minutes = floatval($minutes);
            $minutes = $minutes / 60;
            return floatval(intval($hours) + $minutes);
        }
        return null;
    }
}

