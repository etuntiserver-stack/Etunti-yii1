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
    // find clients that are active (aktiivinen = 1)
    // and have no bills during a time period
    
    $criteria->condition = "
        aktiivinen = 1 AND asiakasnumero NOT IN (SELECT as_nro FROM laskut
        WHERE paivays BETWEEN '".date("Y-m-d", strtotime($fromFormat))."' 
        AND '".date("Y-m-d", strtotime($toFormat))."')
    ";
    
    
    $asiakkaat = Asiakkaat::model()->findAll($criteria);

    $headers = ["Yhteyshenkilö", "Postinumero", 
        "Postitoimipaikka", "Puh", "Email"];
?>

<h2>0 tunti asiakkaat (<?= count($asiakkaat); ?> kpl)</h2>
<h3><?= $fromFormat;?> - <?= $toFormat; ?></h3>
<table style="width: 100%;">
    <tr>
        <?= headers($headers); ?>
    </tr>
    <?php foreach($asiakkaat as $asiakas) : ?>
        <tr>
            <?= tableColumn($asiakas->Etusukunimi); ?>
            <?= tableColumn($asiakas->postinumero); ?>
            <?= tableColumn($asiakas->kaupunki); ?>
            <?= tableColumn($asiakas->puhelin); ?>
            <?= tableColumn($asiakas->sahkoposti); ?>
        </tr>
    <?php endforeach; ?>

</table>