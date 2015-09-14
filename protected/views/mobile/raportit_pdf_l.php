<style>
table{
	width: 290px;
}
td{
	padding:2px 5px;
	border:1px #333 solid;
}
</style>

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

  $strlen = strlen($data->kohde_kannasta);
  if($strlen > 20)
    $kohde = substr($data->kohde_kannasta,0,20).'..';
  else
    $kohde = $data->kohde_kannasta;
  
  $kesto = strtotime($data->loppui)-strtotime($data->aloitan);
  $kkesto += $kesto;
   echo  '<tr>';
   echo '<td>'.date("d.m",strtotime($data->aloitan)).'</td>';
   echo '<td>'.$data->tekijan_nimi.'</td>';
   echo '<td>'.$kohde.'</td>';
   echo '<td>'.date("H:i",strtotime($data->aloitan)).'</td>';
   echo '<td>'.date("H:i",strtotime($data->loppui)).'</td>';
   echo '<td>'.sprint($kesto).'</td>';
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









