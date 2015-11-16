<?php

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

	$asiakas_id = '';
  if(!isset($a->id))
	echo Yii::t('main','Asiakas puutuu');
  else 
	$asiakas_id = $a->id; 

$body = '';

$body .= '

  <h1>'.$k->osoite.', '.Yii::app()->session['fromPosti'].'-'.Yii::app()->session['toPosti'].'</h1>
  <table cellspacing="0" cellpadding="10" border="1" style="color:#666;font:13px Arial;line-height:1.4em;width:100%;">
  <tr>
  <th style="padding: 3px 7px">'.Yii::t('main','Työntekijä ID').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Kohde').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Päivämäärä').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Ajaat').'</th>
  <th style="padding: 3px 7px">'.Yii::t('main','Kesto').'</th>
  </tr>';

	$yht = 0;

  foreach(Yii::app()->session['idsToSahkoposti'] as $v){

	$explV = explode("_",$v);
	if($explV[0] == 'mobile' and isset($explV[1]))
		$str = Mobile::model()->findbypk($explV[1]);
	if($explV[0] == 'toteutu' and isset($explV[1]))
		$str = Toteutuneet::model()->findbypk($explV[1]);

	$kesto = 0;

	  $str['loppui'] = date("d.m.Y H:i",strtotime($str['loppui']));
	  $str['aloitan'] = date("d.m.Y H:i",strtotime($str['aloitan'])

	$kesto = strtotime($str['loppui'])-strtotime($str['aloitan']);
	$yht += strtotime($str['loppui'])-strtotime($str['aloitan']);

	$body .= 
	'<tr>
		<td style="padding: 3px 7px">'.$str['tid'].'</td>
		<td style="padding: 3px 7px">'.$str['kohde_kannasta'].'</td>
		<td style="padding: 3px 7px">'.date("d.m",strtotime($str['aloitan'])).'</td>
		<td style="padding: 3px 7px">
			'.date("H:i",strtotime($str['aloitan'])).' -
			'.date("H:i",strtotime($str['loppui'])).'
						</td>
		<td style="padding: 3px 7px">'.$this->sprint($kesto).'</td>
	</tr>
	';

  }
  $body .= '
  <tfoot>
  <tr>
  <th style="padding: 3px 7px"></th>
  <th style="padding: 3px 7px"></th>
  <th style="padding: 3px 7px"></th>
  <th style="padding: 3px 7px">'.Yii::t('main','Yhteensä').'</th>
  <th style="padding: 3px 7px">'.$this->sprint($yht).'</th>
  </tr>
  </tfoot>';
  $body .= '</table>';


		if(isset($_GET['tulosta']))
		{

	          $html2pdf = Yii::app()->ePdf->HTML2PDF('P', 'A4', 'en');
		  $html2pdf->setDefaultFont('Arial');
	          $html2pdf->WriteHTML($body);
	          $html2pdf->Output();
		exit;
		}
	echo $body;

	$body = preg_replace('!(?:\xc2\xa0|[\pZ\s]++)++!', ' ', $body);
	$body = json_encode($body);
?>

   <!-- tulostus -->
   <br>
   <div class="pull-right">
     <form target="_blank" method="GET">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

<br>
<hr>
<div class="row form">
 <div class="col-sm-4">
   <form action="asiakas_hyvaksyminen" method="POST">
	<input type="hidden" name="laheta" value="true">
	<input type="hidden" name="asiakas_id" class="form-control" value="<?php echo $asiakas_id; ?>">
	<input type="hidden" name="status" class="form-control" value="1">
	<textarea name="ids" class="form-control" rows="4" style="display:none"><?php echo Yii::app()->request->getPost('ids'); ?></textarea>

	<label><?php echo Yii::t('main','Otsikko '); ?></label>
	<input type="text" name="otsikko" class="form-control" value="<?php echo Yii::t('main','Tuntien hyväksyntä'); ?>">
	<label><?php echo Yii::t('main','Saaja '); ?></label>
	<input type="text" name="sahkoposti" class="form-control" value="<?php echo $a->sahkoposti; ?>">
	<textarea name="kirjen_body" class="form-control" rows="4" style="display:none"><?php echo $body; ?></textarea>

	<input type="submit" class="btn btn-sm btn-success" value="<?php echo Yii::t('main','Lähetä '); ?>">
   </form>
 </div>
</div>


