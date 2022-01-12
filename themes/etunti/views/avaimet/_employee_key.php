<?php
/* @var $keys array of Avaimet objects, extracted from shifts, mapped by their ID */
/* @var $empId employee ID */
/* @var $shiftKeys array of Avaimet objects from shifts that the employee needs */
/* @var $employees Array of Tyontekija objects mapped by their ID */
/* @var $employeeKeys Array of Avaimet objects that the employee has right now. */
/* @var $showTransferOnly 1 or 0 indicating if we're only showing transfers */
// since this view is used in a loop, the function can already be declared
// and we'll get an error if we try to redeclare it.
if(!function_exists("keyState")) {
    function keyState($key, $employees) {
        $keyEmployeeId = $key->tid;
        $empData = $employees[$keyEmployeeId] ?? null;
        // by set state
        if($key->sijainti_omatekstti == 0) {
            if($key->sijainti == 1) {
                return "Toimistolla";
            } else if($key->sijainti == 2) {
                return "Asiakkaalla";
            } else if($key->sijainti == 3) {
                if($empData) {
                    $empName = $empData->tekijan_nimi . " " . $empData->sukunimi;
                    return "Työntekijällä " . $empName;
                }
                return "Työntekijällä " . $key->tid;
            }
            return "??? ($key->sijainti)";
        } 
        // by custom location
        else if($key->sijainti_omatekstti == 1) {
            if($empData) {
                $empName = $empData->tekijan_nimi . " " . $empData->sukunimi;
                return "\"$key->sijainti\"" . " ja merkattu työntekijälle " . $empName;
            }
            return "\"$key->sijainti\"" . " ja ei merkattu työntekijälle";
        }
        return "???";
    }
}

if(!function_exists("employeeName")) {
    function employeeName($employeeId, $employees) {
        if(isset($employees[$employeeId])) {
            $empData = $employees[$employeeId];
            return $empData->tekijan_nimi . " " . $empData->sukunimi;
        }
        return "?";
    }
}

if(!function_exists("shiftDate")) {
    function shiftDate($shift) {
        $pvm = $shift->pvm;
        $start = $shift->alku;
        $stop = $shift->loppu;
        return $pvm . " (" .$start.  "-" . $stop . ")";
    }
}

?>
<thead>
    <tr>
        <th>Työntekijä <?= employeeName($empId, $employees) ?> tarvitsee...</th>
    </tr>
</thead>
<tbody>
    <?php foreach($shiftKeys as $shiftKey) : ?>
    <?php
            $shift = $shiftKey["shift"];
            $is_repeating_shift = isset($shift->pfrom);
            $key = $shiftKey["key"];
        ?>
    <tr>
        <td>Avaimen <a target="_blank" rel="noreferrer noopener" href="/index.php/avaimet/update?id=<?=$key->id?>"><?= $key->avainnumero ?></a>
            <?= $is_repeating_shift ? "toistuvalle työvuorolle" : "työvuorolle" ?> <?= shiftDate($shift) ?>
            joka on <strong><?= keyState($key, $employees) ?></strong></td>
    </tr>
    <?php endforeach; ?>
    <?php if(count($shiftKeys) === 0) : ?>
        <tr>
            <td>
                Ei työvuoroja tai kaikki tarvittavat avaimet on merkattu työntekijälle
            </td>
        </tr>
    <?php endif; ?>

</tbody>
<?php if(!$showTransferOnly) : ?>
<thead>
    <tr>
        <th>Työntekijällä <?= employeeName($empId, $employees) ?> on...</th>
    </tr>
</thead>
<tbody>
    <?php foreach($employeeKeys as $key) : ?>
    <tr>
        <td>
            Avain <a taret="_blank" rel="noopener noreferrer" href="/index.php/avaimet/update?id=<?= $key->id ?>"><?=$key->avainnumero ?></a>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php if(count($employeeKeys) === 0) : ?>
    <tr>
        <td>Työntekijälle ei ole merkattu yhtään avainta</td>
    </tr>
    <?php endif; ?>
</tbody>
<?php endif; ?>
<tr>
    <td rowspan="1"></td>
</tr>