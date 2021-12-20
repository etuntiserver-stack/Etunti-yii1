<?php
/* @var $this LaskuController */
/* @var $results Array */
/* @var $workers Array of workers, mapped by their ID */
/*
    example of results
    [
        "2021-12-01" => 
            [
                "read" => [
                    // mobile objects
                ], "planned" => [
                    // shift objects
                ], "approved" => [
                    // mobile objects
                ]
            ]
    ]
*/

function parseDate($formats, $timestamp) {
    if(is_array($formats)) {
        foreach($formats as $format) {
            $date = DateTime::createFromFormat($format, $timestamp);
            if($date) {
                return $date;
            }
        }
    } else {
        return DateTime::createFromFormat($formats, $timestamp);
    }
}

function printShiftDuration($shiftObj) {
    
    $start_ts = $shiftObj->pvm . " " . $shiftObj->alku;
    $end_ts = $shiftObj->pvm . " " . $shiftObj->loppu;

    $format = "d.m.Y H:i";

    $start = parseDate($format, $start_ts);
    $end = parseDate($format, $end_ts);

    $duration = $end->getTimestamp() - $start->getTimestamp();
    $dur_hours = $duration / 60 / 60;
    $rounded_hours = round($dur_hours, 2);
    
    return $shiftObj->alku . " - " . $shiftObj->loppu . " <strong>($rounded_hours h)</strong>";
}

function printMobileDuration($mobileObj) {
    $format1 = "d.m.Y H:i:s";
    $format2 = "d.m.Y H:i";
    $start_time = $mobileObj->aloitan;
    $end_time = $mobileObj->loppui;

    $start = parseDate([$format1, $format2], $start_time);
    $end = parseDate([$format1, $format2], $end_time);

    $output_format = "H:i";

    $duration = $end->getTimestamp() - $start->getTimestamp();
    $dur_hours = $duration / 60 / 60;
    $rounded_hours = round($dur_hours, 2);

    return $start->format($output_format) . " - " . $end->format($output_format) . " <strong>($rounded_hours h)</strong>";
}

function printMessage($mobileObj) {
    return $mobileObj->viesti ?? "";
}

function printName($employeeId, $workers) {
    $emp = $workers[$employeeId];
    if($emp) {
        $f_name = $emp->tekijan_nimi;
        $l_name = $emp->sukunimi;
        return $l_name . " " . $f_name;
    }
    return "???";
}

function printPlanned($employeeId, $resultArr) {
    $planned = isset($resultArr["planned"]) ? $resultArr["planned"] : [];
    // merge all results into a single line
    $resultStr = "";
    foreach($planned as $result) {
        if($result->tid == $employeeId) {
            $resultStr .=  printShiftDuration($result) 
                . '<span style="font-size: 10px; line-height: 1;">'
                . "<br>Muistiinpanot:<br>"
                . parseNotes($result)
                . "<br>"
                . "</span>";
        }
    }
    return $resultStr;
}

function parseNotes($shiftObj) {
    $notes = "";
    $parsed_notes = json_decode($shiftObj->muistiinpano, true) ?? [];
    foreach($parsed_notes as $note) {
        $notes .= $note . "<br>";
    }
    return $notes;
}

function printTrackedDuration($employeeId, $resultArr) {
    $tracked = isset($resultArr["read"]) ? $resultArr["read"] : [];
    // merge all results into a single line
    $resultStr = "";
    foreach($tracked as $result) {
        if($result->tid == $employeeId) {
            $resultStr .= printMobileDuration($result) . " [viesti: " . printMessage($result) . "]";
            $resultStr .= "<br>";
        }
    }
    return $resultStr;
}

function printApprovedDuration($employeeId, $resultArr) {
    $approved = isset($resultArr["approved"]) ? $resultArr["approved"] : [];
    // merge all results into a single line
    $resultStr = "";
    foreach($approved as $result) {
        if($result->tid == $employeeId) {
            $resultStr .= printMobileDuration($result) . "<br>";
        }
    }
    return $resultStr;
}

function printApprovedTotalColumn($employeeId, $resultArr) {
    $approved = isset($resultArr["approved"]) ? $resultArr["approved"] : [];
    $totalHours = 0;
    foreach($approved as $result) {
        if($result->tid == $employeeId) {
            $format1 = "d.m.Y H:i:s";
            $format2 = "d.m.Y H:i";
            $start_time = $result->aloitan;
            $end_time = $result->loppui;

            $start = parseDate([$format1, $format2], $start_time);
            $end = parseDate([$format1, $format2], $end_time);

            $duration = $end->getTimestamp() - $start->getTimestamp();
            $dur_hours = $duration / 60 / 60;
            $rounded_hours = round($dur_hours, 2);

            $totalHours += $rounded_hours;

        }
    }
    return "Yhteensä: <strong>$totalHours h</strong>";
}



?>

<div class="hover-track-container">
    <?php foreach($results as $dateKey => $resultMap) : ?>
    <div class="row">
        <div class="col">
            <table class="table" style="margin-bottom: 10px">
                <thead>
                    <tr>
                        <th><?= $dateKey ?></th>
                        <th>Suunnitellut</th>
                        <th>Luetut</th>
                        <th>Hyväksytyt</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($workers as $worker) : ?>
                        <tr>
                        <td><?= printName($worker->id, $workers) ?></td>
                        <td><?= printPlanned($worker->id, $resultMap);?></td>
                        <td><?= printTrackedDuration($worker->id, $resultMap); ?></td>
                        <td><?= printApprovedDuration($worker->id, $resultMap); ?> <?= printApprovedTotalColumn($worker->id, $resultMap) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
        </div>
    </div>
    <?php endforeach; ?>
</div>