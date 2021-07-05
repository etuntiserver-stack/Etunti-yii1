<?php
header("Content-Type: text/html; charset=utf-8");

/**
 * Daily cron jobs for kotipuhtaaksi domain
 */


$db_host = "localhost";
$site = Yii::app()->createController('Site');
$conn = $site[0]->dbConnectArr();
if(isset($conn["host"])) {
    $db_host = $conn["host"];
}

try {
	$mysqli = new mysqli($conn['host'], $conn['username'], $conn['password']);
} catch (\Exception $e) {
	echo $e->getMessage(), PHP_EOL;
	exit;
}

$domain = "kotipuhtaaksi";
Yii::app()->db1->setActive(false);
Yii::app()->db1->connectionString = "mysql:host=" . $db_host . ";dbname=" . $domain;
Yii::app()->db1->setActive(true);
$mysqli->select_db($domain);
$_SESSION["domain"] = $domain;

// call jobs
autoPassiveClients();
autoPassiveWorkers();
checkPassiveableEmployees();

// unset domain
unset($_SESSION["domain"]);

// defined jobs


/**
 * Searches for clients that haven't had any workshifts last month (this date - 1 month) and don't have any
 * workshifts in the future,
 * marks those clients as non-active and passive by automation (it's a workgroup with ID 176)
 */
function autoPassiveClients() {
    print_r("<br><br>Asiakas passivointi:<br>");

    $criteria = new CDbCriteria();
    $criteria->condition = 'STR_TO_DATE(pto, "%d.%m.%Y") > (CURDATE() - INTERVAL 1 MONTH) AND kohde > 0';
    $toistuvat = ToistuvatTyovuorot::model()->findAll($criteria); 
    $propertyIds = [];
    foreach($toistuvat as $toistuva) {
        $propertyIds[] = $toistuva->kohde;
    }
    // free memory
    $toistuvat = null;
    gc_collect_cycles();

    $criteria = new CDbCriteria();
    $criteria->condition = 'STR_TO_DATE(pvm, "%d.%m.%Y") > (CURDATE() - INTERVAL 1 MONTH) AND kohde > 0';
    $shifts = Tyovuoroot::model()->findAll($criteria);
    foreach($shifts as $shift) {
        $propertyIds[] = $shift->kohde;
    }
    // free memory
    $shifts = null;
    gc_collect_cycles();


    $propertyIds = array_unique($propertyIds);

    print_r("<br>SHIFT COUNT:<br>");
    print_r(count($propertyIds));
    print_r("<br>");

    $impl = implode(",", $propertyIds);
    // free memory
    $propertyIds = null;
    gc_collect_cycles();

    $criteria = new CDbCriteria();
    $criteria->condition = "id IN(".$impl.")";
    $properties = Kohteet::model()->findAll($criteria);

    $clientIds = [];
    foreach($properties as $property) {
        $clientIds[] = $property->asiakas_id;
    }
    print_r("<br>KOHDE COUNT:<br>");
    print_r(count($clientIds));
    print_r("<br>");

    $impl = implode(",", $clientIds);

    $criteria = new CDbCriteria();
    // select clients that have existed for at least 2 weeks
    $criteria->condition = "id NOT IN(".$impl.") AND aktiivinen = 1 AND time < CURDATE() - INTERVAL 14 DAY";
    $clients = Asiakkaat::model()->findAll($criteria);
    print_r("<br>ASIAKAS COUNT:<br>");
    print_r(count($clients));
    print_r("<br>");
    // remove "jatkuva leasing", "vanha proaqua", 
    // "toimitila" and "vanha proaqua 0" work groups
    // add "passiivinen automaatio" group
    // IDs are from sivex_selects
    // jatkuva leasing 	= 94
    // vanha proaqua 	= 95
    // toimitila 		= 107
    // vanha proaqua 0 	= 129
    // passiivinen automaatio = 176
    foreach($clients as $client) {
        
        $groups = json_decode($client->ryhma, true) ?? [];
        // remove the groups we don't want, we'll also include 176 here,
        // if for some reason the same client is in this list twice, it won't have that work group
        // multiple times in it's data.
        $newGroups = array_diff($groups, ["94", "95", "107", "129", "176"]);
        // add passiivinen automaatio
        $newGroups[] = "176";
        $client->aktiivinen = 0;
        $client->ryhma = json_encode(array_values($newGroups));
        // TODO: disable freshdesk

        if(!$client->save()) {
            print_r($client->getErrors());
            exit;
        }
        
    }
}

/**
 * Searches for workers that haven't had any workshifts last month (this date - 1 month) and don't have any
 * workshifts in the future.
 * Marks those employees as passive by automation (aktiivinen = 4)
 */
function autoPassiveWorkers() {

    print_r("<br><br>Työntekijä passivointi:<br>");

    $criteria = new CDbCriteria();
    $criteria->condition = 'STR_TO_DATE(pto, "%d.%m.%Y") > (CURDATE() - INTERVAL 1 MONTH) AND (kohde > 0 OR status = 11)';
    $toistuvat = ToistuvatTyovuorot::model()->findAll($criteria); 

    $workerIds = [];
    // collect employee IDs from the shifts
    foreach($toistuvat as $toistuva) {
        // attempt to parse "tyopaari"
        $colleagueIds = json_decode($toistuva->tyopaari, true) ?? [];
        // if there's IDs in the array, add them to the workerIds array
        if(count($colleagueIds) > 0) {
            foreach($colleagueIds as $colleaugeId) {
                if(!in_array($colleaugeId, $workerIds)) {
                    $workerIds[] = $colleaugeId;
                }
            } 
        } 
        // no colleagues, add "tid" to workerIds
        else {
            if(isset($toistuva->tid) and !in_array($toistuva->tid, $workerIds)) {
                $workerIds[] = $toistuva->tid;
            }
        }
    }

    // free memory
    $toistuvat = null;
    gc_collect_cycles();

    $criteria = new CDbCriteria();
    $criteria->condition = 'STR_TO_DATE(pvm, "%d.%m.%Y") > (CURDATE() - INTERVAL 1 MONTH) AND (kohde > 0 OR status = 11)';
    $shifts = Tyovuoroot::model()->findAll($criteria);
    
    foreach($shifts as $shift) {
        if(isset($shift->tid) and !in_array($shift->tid, $workerIds)) {
            $workerIds[] = $shift->tid;
        }
    }
    // free memory
    $shifts = null;
    gc_collect_cycles();

    $impl = implode(",", $workerIds);
    $criteria = new CDbCriteria();
    // we'll search for LIKE 'Siistijä', which should include "siistijä" and "Siistijä"
    // aktiivinen = 1 status is "Töissä (aktiivinen)"
    $criteria->condition = "aktiivinen = 1 AND ammattinimike LIKE 'Siistijä' AND t.id NOT IN(".$impl.")";
    $criteria->with = "tyosuhteet";

    $workers = Tyontekijat::model()->findAll($criteria);
    print_r("<br>WORKER COUNT:<br>");
    print_r(count($workers));
    print_r("<br>");

  
    // temporarily disable netvisor, it requires 
    // "ammattinimike", "tekijan_pankkitili", "tekijan_konttori" and "tekijan_henkilotunnus",
    // which are not guaranteed to be defined.
    $asetukset = Asetukset::model()->findByPk(1);
    $asetukset->netvisor_kaytto = 0;
    if(!$asetukset->save()) {
        print_r("<br>Error while saving settings<br>");
        print_r($asetukset->getErrors());
    }

    foreach($workers as $worker) {
        $workGroups = json_decode($worker->tyoryhma, true) ?? [];
        // even if an employee has "Siistijä" as their title, it might still mean they're actually
        // working at the office, and we don't want to passive those people.
        // we'll check if "tyoryhma" array contains "Toimisto" to filter them out.
        if(!in_array("Toimisto", $workGroups)) {
            $contract = $worker["tyosuhteet"];
            // check if "tyosuhteet" is actually set
            if(isset($contract)) {
                // convert "alku" to a date
                $startDate = date("Y-m-d", strtotime($contract->alku));
                // get the date for this day - 3 days
                $threeDays = date("Y-m-d", strtotime("-3 day"));
                // if the contract is older than 3 days, change employees status
                if($threeDays > $startDate) {
                    // aktiivinen 4 means "Lopettanut (automaatio)"
                    $worker->aktiivinen = 4;
                    if(!$worker->save()) {
                        print_r($worker);
                        print_r("<br><br>ERRORS:");
                        print_r($worker->getErrors());
                        exit;
                    }
                }
            }
        }
    }

    // re-enable netvisor
    $asetukset->netvisor_kaytto = 1;
    if(!$asetukset->save()) {
        print_r("<br>Error while saving settings<br>");
        print_r($asetukset->getErrors());
    }

}

/**
 * Searches for employees that have a defined end date in their contract,
 * but are still marked as active. If their end date is larger than now + 7 days,
 * mark the employee as quit.
 */
function checkPassiveableEmployees() {
    print_r("<br>Passiveable employees:<br>");
    // tyosuhdet->loppu
    $criteria = new CDbCriteria();
    $criteria->condition = "aktiivinen = 1";
    // find all workers which are still active, but have a defined end date in their
    // contract, which is larger than this day + 7 days
    $workers = Tyontekijat::model()->with(array(
        "tyosuhteet" => array("condition" => '(loppu != "" OR loppu != null) AND 
            CURDATE() > STR_TO_DATE(loppu, "%d.%m.%Y") + INTERVAL 7 DAY')
    ))->findAll($criteria);

    print_r("<br>WORKER COUNT:<br>");
    print_r(count($workers));
    print_r("<br>");
    
    // temporarily disable netvisor, it requires 
    // "ammattinimike", "tekijan_pankkitili", "tekijan_konttori" and "tekijan_henkilotunnus",
    // which are not guaranteed to be defined.
    $asetukset = Asetukset::model()->findByPk(1);
    $asetukset->netvisor_kaytto = 0;
    if(!$asetukset->save()) {
        print_r("<br>Error while saving settings<br>");
        print_r($asetukset->getErrors());
    }

    // any workers the query returns should be marked as aktiivinen = 3 ("Lopettanut")
    foreach($workers as $worker) {
        $worker->aktiivinen = 3;
        if(!$worker->save()) {
            print_r($worker->getErrors());
            exit;
        }
    }

    // re-enable netvisor
    $asetukset->netvisor_kaytto = 1;
    if(!$asetukset->save()) {
        print_r("<br>Error while saving settings<br>");
        print_r($asetukset->getErrors());
    }

}