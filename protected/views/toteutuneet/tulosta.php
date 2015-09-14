   <?php

    if(Yii::app()->session['tekija'])
       $explTekija = explode("//",Yii::app()->session['tekija']);

   ?>

<style>
table{
	width: 290px;
}
td{

	border:1px #333 solid;
}
</style>

<?php if(Yii::app()->session['tekija']) : ?>
  <h1><?php echo $explTekija[1]; ?></h1>
  <table>
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunnitellut'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luettu'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  </tr>
  </thead>
  <tbody>
  <?php 
  foreach($model as $data)
  {
    echo '<tr>';
    echo '<td width="1">'.date("d.m",strtotime($data->aloitan)).'</td>';
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas))
    echo '<td width=240>'.$this->renderPartial('//tyovuoroot/did',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'tulosta'),true).'</td>';
    echo '<td width=240>'.$this->renderPartial('luetutpvmtid',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili'),true).'</td>';
    echo '<td width=240>'.$this->renderPartial('totpvmtid',array('pvm'=>$data->aloitan,'tid'=>$data->tid,'from'=>'mobiili'),true).'</td>';
    echo '<td>'.$this->renderPartial('yhteensapvm',array('pvm'=>date("Y-m-d",strtotime($data->aloitan)),'tid'=>$data->tid,'from'=>'mobiili'),true).'</td>';
    echo '</tr>';
  }
  ?>
  </tbody>

  <tfoot>
<?php
    echo '<tr>';
    echo '<td>'.Yii::t('main', 'Yhteensä').'</td>';
    $tas = explode(",",Yii::app()->user->adminPaketti);
    if(in_array('2',$tas))
    echo '<td>'.sprint($this->renderPartial('//mobile/suunniteltu',array('id'=>$explTekija[0],'kohde_tid'=>'tid'),true)).'</td>';
    echo '<td>'.sprint($this->renderPartial('//mobile/total_luettu',array('tid'=>$explTekija[0]),true)).'</td>';
    echo '<td>'.sprint($this->renderPartial('//mobile/total_toteutu',array('tid'=>$explTekija[0]),true)).'</td>';
    echo '<td></td>';
    echo '</tr>';
?>
  </tfoot>

  </table>
<?php endif; ?>

