<?php

function tableColumn($data) {
    return '<td>'.$data.'</td>';
}

function headers($headers) {
    $str = '';
    foreach($headers as $header) {
        $str .= '<th>'.$header.'</th>';
    }
    return $str;
}
if($_GET["from"] ?? false) {
    $fromFormat = $_GET["from"];
} else {
    // default to this month - 1 month
    $from = new \DateTime();
    $from->modify("-1 months");
    $fromFormat = $from->format("Y-m-d");
}
if($_GET["to"] ?? false) {
    $toFormat = $_GET["to"];
} else {
    $to = new \DateTime();
    $toFormat = $to->format("Y-m-d");
}


$criteria = new CdbCriteria();

$criteria->addCondition (" 
			paivays BETWEEN '".date("Y-m-d", strtotime($fromFormat))."' AND '".date("Y-m-d", strtotime($toFormat))."'
		");
$laskut = Lasku::model()->findAll($criteria);


$headers = ["Yhteyshenkilö", "Postinumero",
     "Postitoimipaikka", "Puh", "Email", "Laskut yht."];

$summa = [];
foreach($laskut as $lasku) {
    $s = $summa[$lasku->as_nro] ?? 0;
    if(is_numeric($lasku->yhteensa_total)) {
        $summa[$lasku->as_nro] = $s += $lasku->yhteensa_total; 
    }
    
}

?>
<h2>Laskutetut asiakkaat viime kuulta (<?= count($laskut) ?> kpl)</h2>
<h3><?= $fromFormat;?> - <?= $toFormat; ?></h3>
<table style="width: 100%;">
    <tr>
        <?= headers($headers); ?>
    </tr>
    <?php foreach($laskut as $lasku): ?>
        <tr>
            <?= tableColumn($lasku->yhteyshenkilo); ?>
            <?= tableColumn($lasku->postinumero); ?>
            <?= tableColumn($lasku->toimipaikka); ?>
            <?= tableColumn($lasku->puhelin); ?>
            <?= tableColumn($lasku->sahkoposti); ?>
            <?= tableColumn(round($summa[$lasku->as_nro], 2) . " €"); ?>
        </tr>
    <?php endforeach; ?>
</table>