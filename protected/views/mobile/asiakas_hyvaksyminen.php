<?php
print_r($_POST);
exit;

  if(Yii::app()->request->getPost('ids'))
  Yii::app()->session['idsToSahkoposti'] = explode(",",Yii::app()->request->getPost('ids'));

  if(Yii::app()->request->getPost('kohdenID'))
  Yii::app()->session['kohdenID'] = Yii::app()->request->getPost('kohdenID');

  if(Yii::app()->request->getPost('fromPosti'))
  Yii::app()->session['fromPosti'] = date("d.m.Y",Yii::app()->request->getPost('fromPosti'));

  if(Yii::app()->request->getPost('toPosti'))
  Yii::app()->session['toPosti'] = date("d.m.Y",Yii::app()->request->getPost('toPosti'));

  $k = Kohteet::model()->findbypk(Yii::app()->session['kohdenID']);
  $a = Asiakkaat::model()->findbypk($k->asiakas_id);
?>

  <h1><?php echo $k->osoite.', '.Yii::app()->session['fromPosti'].'-'.Yii::app()->session['toPosti']; ?></h1>
  <table cellspacing="0" cellpadding="10" border="1" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
  <tr>
  <th style="padding: 3px 7px"><?php echo Yii::t('main','Työntekijä ID'); ?></th>
  <th style="padding: 3px 7px"><?php echo Yii::t('main','Päivämäärä'); ?></th>
  <th style="padding: 3px 7px"><?php echo Yii::t('main','Kesto'); ?></th>
  </tr>
  <?php
  foreach(Yii::app()->session['idsToSahkoposti'] as $v){

	$explV = explode("_",$v);
	if($explV[0] == 'mobile' and isset($explV[1]))
		$str = Mobile::model()->findbypk($explV[1]);
	if($explV[0] == 'toteutu' and isset($explV[1]))
		$str = Toteutuneet::model()->findbypk($explV[1]);

	$kesto = 0;
	$kesto = strtotime($str['loppui'])-strtotime($str['aloitan']);

	echo 
	'<tr>
		<td style="padding: 3px 7px">'.$str['tid'].'</td>
		<td style="padding: 3px 7px">'.date("d.m",strtotime($str['aloitan'])).'</td>
		<td style="padding: 3px 7px">'.$this->sprint($kesto).'</td>
	</tr>
	';

  }
  ?>
  </table>

