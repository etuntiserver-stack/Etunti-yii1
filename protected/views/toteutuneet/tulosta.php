   <?php

    if(Yii::app()->session['tekija'])
       $explTekija = explode("//",Yii::app()->session['tekija']);

   ?>

<style>
table{
	width: 280px;
	font-size:80%;	
}

td{
	padding: 2px 5px;
	border:1px #333 solid;
	white-space: nowrap;
	min-height:70px;
}
</style>

<?php if(Yii::app()->session['tekija']) : ?>

  <?php $asetukset=Asetukset::model()->find("id=1"); ?>
  <img src="<?php echo $asetukset->logon_polkku; ?>" height="<?php echo $asetukset->logon_korkeus; ?>">
  <br>

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
	$this->renderPartial('_view',array('data'=>$data));
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

