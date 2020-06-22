<?php

/**
 * Piilotettu lista kohteen omasiistijöistä, joka voidaan sijoittaa kohteen
 * kortille ja asiakkaan/työvuoron kortille.
 */


//?   /*
//?    * Tässä laajennettuna SQL haku joka suoritetaan myöhemmin, jolla haetaan
//?    * työntekijät joilla on hyväksyttyjä tunteja kyseisessä kohteessa.
//?    *
//?    * Haetaan tiedot niiltä työntekijöiltä, jotka koskee hakua. Alempana
//?    * tehtävä ID rajaus rajaa työntekijät vain niihin, joilla on hyväksyttyjä
//?    * tunteja kyseisessä kohteessa.
//?    */
//=
//=   SET @kohde_id = 2654;
//=   SELECT id, tekijan_nimi, sukunimi
//=   FROM sivex_ttekijat
//=
//?   /* Vain aktiiviset työntekijät (1:aktiivinen, 2:passiivinen, 3:epäaktiivinen) */
//=   WHERE aktiivinen = 1
//=
//?   /* Rajataan työntekijät vain niihin, joilla on allaolevan haun perusteella hyväksyttyjä tunteja kohteessa. */
//=   AND id IN
//=   (
//?     /* Haetaan työntekijä ID lista kohteen hyväksytyistä tunneista. */
//=     SELECT tid FROM
//=     (
//=       SELECT tid FROM sivexkuitti
//=         WHERE kohdenID = @kohde_id AND hyvaksytty != ''
//=       UNION ALL
//=       SELECT tid FROM sivexkuitti_repaired
//=         WHERE kohdenID = @kohde_id AND hyvaksytty != ''
//=     ) t
//?     /* Groupataan, jotta päällekkäiset ID:t katoavat (jokainen tt vain kerran listalla) */
//=     GROUP BY tid
//=   );



// Require valid target ID.
if (empty($kohde_id))
  throw new \Exception("virhe: kohdetta ei ole määritetty. jos vika jatkuu, ota yhteys ylläpitoon.");

/** @var CDbConnection */
$connection = Yii::app()->db1;

// Get list of workers that have been to this target.
// See top of file for explanation.
$workers_query = $connection->createCommand("
  SELECT id, tekijan_nimi, sukunimi
    FROM sivex_ttekijat
    WHERE aktiivinen = 1
    AND id IN
    (
      SELECT tid FROM
      (
        SELECT tid
          FROM sivexkuitti
          WHERE kohdenID = :kohde_id
          AND hyvaksytty != ''
        UNION ALL
        SELECT tid
          FROM sivexkuitti_repaired
          WHERE kohdenID = :kohde_id
          AND hyvaksytty != ''
      ) t
      GROUP BY tid
    )")
  ->bindValue(':kohde_id', $kohde_id)
  ->queryAll(false);

// Specify ID for the div. This should be unique as there may be many locations on a page.
$div_id = "omasiistijat_{$kohde_id}";

?>

<style>
  .omasiistijat-painike {
    width: 100%;
    padding: 4px 6px;
    margin-top: 12px;
    border: 1px solid darkgray;
    border-radius: 3px;
    background-color: #f5f8fa;
    text-align: left;
    overflow-x: hidden;
  }

  .omasiistijat-painike:hover {
    background-color: #e6e9eb;
  }
</style>

<!-- Output collapse button with the formed text. -->
<?php if (empty($workers_query)): ?>
<button class="omasiistijat-painike" type="button" disabled="disabled"><b>Ei omasiistijöitä</b></button>
<?php else: ?>
<button class="omasiistijat-painike" type="button" data-toggle="collapse" data-target="#<?= $div_id; ?>"><b>Näytä omasiistijät</b></button>
<br><br>

<!-- Form the hidden box containing the workers that have been to this location. -->
<div id="<?= $div_id; ?>" class="collapse">
  <div class="well well-sm">
      <?php
      foreach ($workers_query as $result) {
        echo CHtml::link("{$result[1]} {$result[2]}", ['tyontekijat/update', 'id' => $result[0]]) . '<br>';
      }
      ?>
  </div>
</div>
<?php endif; ?>
