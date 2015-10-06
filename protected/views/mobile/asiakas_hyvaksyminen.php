<?php
//print_r($_POST);
//exit;

  if(Yii::app()->request->getPost('ids'))
  Yii::app()->session['idsToSahkoposti'] = explode(",",Yii::app()->request->getPost('ids'));

  if(Yii::app()->request->getPost('kohdenID'))
  Yii::app()->session['kohdenID'] = Yii::app()->request->getPost('kohdenID');

  if(Yii::app()->request->getPost('fromPosti'))
  Yii::app()->session['fromPosti'] = date("d.m.Y",strtotime(Yii::app()->request->getPost('fromPosti')));

  if(Yii::app()->request->getPost('toPosti'))
  Yii::app()->session['toPosti'] = date("d.m.Y",strtotime(Yii::app()->request->getPost('toPosti')));

  $k = Kohteet::model()->findbypk(Yii::app()->session['kohdenID']);
  $a = Asiakkaat::model()->findbypk($k->asiakas_id);


$body = '

  <h1>'.$k->osoite.', '.Yii::app()->session['fromPosti'].'-'.Yii::app()->session['toPosti'].'</h1>
  <table cellspacing="0" cellpadding="10" border="1" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
  <tr>
  <th style="padding: 3px 7px">'.Yii::t('main','Työntekijä ID').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Päivämäärä').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Ajaat').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Kesto').'</th>
  </tr>';

  foreach(Yii::app()->session['idsToSahkoposti'] as $v){

	$explV = explode("_",$v);
	if($explV[0] == 'mobile' and isset($explV[1]))
		$str = Mobile::model()->findbypk($explV[1]);
	if($explV[0] == 'toteutu' and isset($explV[1]))
		$str = Toteutuneet::model()->findbypk($explV[1]);

	$kesto = 0;
	$kesto = strtotime($str['loppui'])-strtotime($str['aloitan']);

	$body .= 
	'<tr>
		<td style="padding: 3px 7px">'.$str['tid'].'</td>
		<td style="padding: 3px 7px">'.date("d.m",strtotime($str['aloitan'])).'</td>
		<td style="padding: 3px 7px">
			'.date("H:i",strtotime($str['aloitan'])).' -
			'.date("H:i",strtotime($str['loppui'])).'
						</td>
		<td style="padding: 3px 7px">'.$this->sprint($kesto).'</td>
	</tr>
	';

  }

  $body .= '</table>';

echo $body;

		$body = preg_replace('!(?:\xc2\xa0|[\pZ\s]++)++!', ' ', $body);
		$body = json_encode($body);

?>



<br>
<div class="row form">
 <div class="col-sm-4">
   <form action="asiakas_hyvaksyminen" method="POST" target="_blank">
	<label><?php echo Yii::t('main','Otsikko '); ?></label>
	<input type="text" name="otsikko" class="form-control" value="<?php echo Yii::t('main','Tuntien hyväksyntä. '); ?>">
	<label><?php echo Yii::t('main','Saaja '); ?></label>
	<input type="text" name="sahkoposti" class="form-control" value="<?php echo $a->sahkoposti; ?>">
	<textarea name="kirje" class="form-control" rows="4" style="display:none"><?php echo $body; ?></textarea>
	<input type="hidden" name="laheta" value="true">
	<input type="submit" class="btn btn-sm btn-success" value="<?php echo Yii::t('main','Lähetä '); ?>">
   </form>
 </div>
</div>


