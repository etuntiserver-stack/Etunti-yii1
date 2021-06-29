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

// unset domain
unset($_SESSION["domain"]);

// defined jobs


/**
 * Searches for clients that haven't had any workshifts last month (this date - 1 month) and don't have any
 * workshifts in the future,
 * marks those clients as non-active and passive by automation (it's a workgroup with ID 176)
 */
function autoPassiveClients() {
    print_r("<br><br>Passivointi:<br>");

    $criteria = new CDbCriteria();
    $criteria->condition = 'STR_TO_DATE(pfrom, "%d.%m.%Y") > (CURDATE() - INTERVAL 1 MONTH) AND kohde > 0';
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
    $criteria->condition = "id NOT IN(".$impl.") AND aktiivinen = 1 AND time < CURDATE() - INTERVAL 3 DAY";
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