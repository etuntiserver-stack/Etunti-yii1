<style>
table{
	width: 100%;
	font-size:80%;
}
td,th{
	padding:3px 7px;
	border-right:1px #333 solid;
	border-bottom:1px #333 solid;
}
</style>

  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
  <br>

<h1><?php echo Yii::t('main', 'TOTEUTUNEET'); ?></h1>
<p><?php echo date("d.m.Y",strtotime(Yii::app()->session['from'])).'-'.date("d.m.Y",strtotime(Yii::app()->session['to'])); ?></p>
<br>
<table>
  <thead>
  <tr>
    <th><?php echo Yii::t('main', 'Pvm.'); ?></th>
    <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
    <th><?php echo Yii::t('main', 'Osoite'); ?></th>
    <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
    <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
    <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $kkesto = 0;
  foreach($model as $data){
  /*
  $strlen = strlen($data->kohde_kannasta);
  if($strlen > 20)
    $kohde = substr($data->kohde_kannasta,0,20).'..';
  else
    $kohde = $data->kohde_kannasta;
  */
  
  $kesto = strtotime($data->loppui)-strtotime($data->aloitan);
  $kkesto += $kesto;
   echo  '<tr>';
   echo '<td>'.date("d.m",strtotime($data->aloitan)).'</td>';
   echo '<td>'.$data->tekijan_nimi.'</td>';
   echo '<td>'.$data->kohde_kannasta.'</td>';
   echo '<td>'.date("H:i:s",strtotime($data->aloitan)).'</td>';
   echo '<td>'.date("H:i:s",strtotime($data->loppui)).'</td>';
   echo '<td>'.sprint($kesto).' <b>('.num($kesto).')</b></td>';
   echo '</tr>';
  }
  ?>
  </tbody>
  <tfoot>
  <tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th><?php echo sprint($kkesto); ?></th>
  </tr>
  </tfoot>
</table>
