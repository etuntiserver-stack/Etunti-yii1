<?php

$asetukset = Asetukset::model()->findByPk(1);
$paivat=array(
	1=>'Maanantai',
	2=>'Tiistai',
	3=>'Keskiviikko',
	4=>'Torstai',
	5=>'Perjantai',
	6=>'Lauantai',
	7=>'Suunnuntai',
	);

?>


<?php if($tulosta == 'lista') : ?>
<style>
.LahetettyTable table{
	width: 100%;
}
.LahetettyTable td, .LahetettyTable th{
	padding:3px 7px;
	border-top:1px #ccc solid;
}
</style>
<?php endif; ?>


<?php if(!$tulosta) : ?>
<legend>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT'). ', '.Yii::t('main', 'VIIKKO').' - '.$week; ?></h1>
</legend>

<div class="form-inline">

  <?php if(!empty($tt->tekijan_email)): ?>
  <form action="#" id="pdf_email" class="form-group" method="POST">
    <input type="hidden" name="pdf_email" value="true">
    <button class="btn btn-success btn-sm laheta">PDF >>> KAIKILLE</button>
  </form>
  <?php
	$file = $week.'_'.$year.'_'.$tid.'.pdf';
	$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
	if (file_exists($path.'/'.$file))
	echo CHtml::link(Yii::t('main', ' Lähetetty'),'../../emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file,array('class'=>'btn btn-danger btn-sm glyphicon glyphicon-file'));

  ?>
  <?php endif; ?>
</div>
<?php endif; ?>


<?php if($tulosta) : ?>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT'). ' '.Yii::t('main', 'VIIKKO').'-'.$week; ?></h1>
<h3><?php echo date('d.m.Y',strtotime($year ."W". $week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?></h3>
<?php endif; ?>

<?php
  $site = Yii::app()->createController('Site');

  $criteria = new CDbCriteria();
  $criteria->order = " alku ASC "; 
  $criteria->group = " tid "; 
  $criteria->condition = "  
  DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')  
  BETWEEN  '".date('Y-m-d',strtotime($year ."W". $week .'1'))."' AND '".date('Y-m-d',strtotime($year ."W". $week .'7'))."' 
  AND pvm!='' ";

  if(isset($_GET['check']) and !empty($_GET['check']))
  {
    $trimCheck = rtrim($_GET['check'], ",");
    $criteria->addCondition (" tid IN ($trimCheck) ");  	
  }

  $tv = Tyovuoroot::model()->findAll($criteria);
?>

<?php $ids = ''; ?>
<?php 
$tyosuhteet_checker = array();
$kenelleLahetetaan = array();
foreach ($tv as $t) { 
?>
<br>
<?php
$tt = Tyontekijat::model()->findbypk($t->tid);
$ts = Tyosuhdet::model()->find(" tid='".$t->tid."' ");


if(isset($tt->tekijan_email)){
$ids .= $tt->id.',';
array_push($kenelleLahetetaan, $this->etuSukunimi($tt->id));
}
?>
<h2><?php echo $this->etuSukunimi($tt->id); ?></h2>

<?php if($tulosta != 'lista') : ?>
  <form action="#" class="form-group" method="POST">
    <input type="hidden" name="kuka" value="<?php echo $t->tid; ?>">
    <input type="submit" class="btn btn-success btn-sm" name="pdf" value="PDF">
  </form>
<?php endif; ?>

<table class="table table-bordered LahetettyTable" cellspacing="0" cellpadding="0">
<?php
for($day= 1; $day <= 7; $day++) {

  $d = strtotime($year ."W". $week . $day);
  $date = date('d.m.Y',$d);

  if( !isset($tyosuhteet_checker[$tt->id]) and isset($ts->loppu) and !empty($ts->loppu) and strtotime($ts->loppu) < strtotime($date) )
  {
	$tyosuhteet_checker[$tt->id] = array('nimi' => $this->etuSukunimi($tt->id), 'tsloppu' => $ts->loppu );
  }

  echo '<tr><td colspan="2"><h2><b>'.$paivat[date('N',$d)].' '.$date.'</b></h2></td></tr>';
  echo '<tr><td valign="top" width="30%"><h5>Aika/Kohde</h5><br>';

  $criteria = new CDbCriteria();
  $criteria->order = " alku ASC "; 
  $criteria->condition = "  
  tid = '".$tt->id."'
  AND DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')  
  BETWEEN  '".date('Y-m-d',strtotime($year ."W". $week .'1'))."' AND '".date('Y-m-d',strtotime($year ."W". $week .'7'))."' 
  AND pvm!='' ";

  if(isset($_POST['P'])){
  $criteria->Addcondition ( " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%w') IN (".implode(",",$_POST['P']).") ");
  }
  $tv = Tyovuoroot::model()->findAll($criteria);

  $yht = 0;
  foreach($tv as $t)
  {
    if($t->pvm == $date)
    {
	$k = Kohteet::model()->findbypk($t->kohde);

	// <-- Asiakas Tiedot
	$asiakasTiedot = '';
	if(isset($k->asiakas_id)){

	$as = Asiakkaat::model()->findbypk($k->asiakas_id);
		
		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi) and $asetukset->tyovuorolahetys_naytetaanko_asiakas == 1){
			$asiakasTiedot = '<br><b>'.Yii::t('main', 'Asiakas').':</b> '.$as->yrityksen_nimi;
		} else if(isset($as->yhteyshenkilo) and empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo) and $asetukset->tyovuorolahetys_naytetaanko_asiakas == 1){
			$asiakasTiedot = '<br><b>'.Yii::t('main', 'Asiakas').':</b> '.$as->yhteyshenkilo;
		}

		if($asetukset->tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka == 1)
			$asiakasTiedot .= '<br><b>'.Yii::t('main', 'Kohteen postitoimipaikka').':</b> '.$k->kaupunki;

		if($asetukset->tyovuorolahetys_naytetaanko_asiakas == 1 or $asetukset->tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka == 1)
			$asiakasTiedot .= '<p style="padding:0;margin:0">------</p>';
	}
	// Asiakas Tiedot -->

	$al = '';
	if($t->alku > 0 and $t->loppu > 0){
	  $al = $t->alku.'-'.$t->loppu;
	  if($site[0]->eiLasketaSubStr($t->tyoajanmerkinta) === false){ $yht += strtotime($t->loppu)-strtotime($t->alku); }
	}

	// <-- osoite
	$osoite = '';
	if(!empty($t->osoite)){
		$osoite = $t->osoite;
	} elseif(isset($k->id) and empty($t->osoite)){
		$osoite = $k->osoite;
	} elseif(!isset($k->id) and $t->status != 0 and $t->status != 3){
		$osoite = $this->tilanteet()[$t->status];
	}

	$color = '#888';
	$bgcol = 'color:#333';
	if(!empty($t->tyoajanmerkinta)){
		$expl = explode("/",$t->tyoajanmerkinta);
		if(isset($expl[1]) and !empty($expl[1])){
			$color = $expl[1];
			$bgcol = 'color:'.$color;
		}
	}
	if(!empty($t->tyoajanlaatu) and empty($osoite)){
		$expl1 = explode("/",$t->tyoajanlaatu);
		if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
		$osoite = (isset($expl1[0])) ? '<div class="text-center tyoajanlaatu_laatiko" style="background:'.$color.';color:#fff">'.$expl1[0].'</div>' : '';
	}
	//     osoite -->

	echo '<span>'.$al.' '.$osoite.'</span>';
	if(isset($k->id) and !empty($k->avain))
	echo ' &nbsp;<b class="fa fa-key text-warning"></b>';
	elseif(isset($k->id) and !empty($k->avain) and $tulosta)
	echo ' &nbsp;(avain on)';
	echo $asiakasTiedot;
	echo '<br>';

    }
  }
  if($yht > 0)
  echo '<h4>'.Yii::t('main','Yhteensä: ').$this->sprint($yht).'</h4>';
  echo '</td><td valign="top" style="border-left: 1px #ccc solid"><h5>Tietoja</h5><br>';
  foreach($tv as $tvVal)
  {
	// <-- Osoite
	$osoite = '';
	if(!empty($tvVal->osoite)){
		$osoite = $tvVal->osoite;
	} elseif(empty($tvVal->osoite) and isset($tvVal->kohteet->osoite)){
		$osoite = $tvVal->kohteet->osoite;
	}
	// Osoite -->

	if( $tvVal->pvm == $date and !empty($tvVal->tietoja) ){
		echo '<div class="tietoja"><b>'.$osoite.':</b> <br>'.str_replace("\n", "<br>", $tvVal->tietoja).'</div>';
		echo '<p style="padding:0;margin:0">------</p>';
	}
  }
  echo '</td></tr>';
}
$totalWeek = '';
$totalWeek = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$tt->id,'viikko'=>$week,'year'=>$year),true);
?>
<tfoot>
 <tr>
 <th align="left"><?php echo Yii::t('','Yhteensä').' '. $totalWeek; ?></th>
 <th></th>
 </tr>
</tfoot>
</table>
<?php } ?>

<?php if($tulosta != 'lista') : ?>
<?php
$ids = json_encode(explode(",",$ids));
?>
<br>
<div class="row">
 <div class="col-sm-5">
  <form action="#" id="pdf_email" class="form-group" method="POST">
    <input type="hidden" name="pdf_email" value="true">
    <input type="hidden" name="year" value="<?php echo $year; ?>">
    <input type="hidden" name="week" value="<?php echo $week; ?>">
    <textarea name="kenelle" style="display:none"><?php echo $ids; ?></textarea>
    <label><?php echo Yii::t('main','Lähetettävän viestin sisältö'); ?></label>
    <textarea name="kirjenBody" class="form-control" rows="6"></textarea>
    <br>
    <label><?php echo Yii::t('main','Lähetettävät päivät'); ?></label>
<div class="row">
  <div class="col-sm-12">
  <label><?php echo Yii::t('main', 'Ma'); ?></label>
  <input type="checkbox" class="sw" name="P[1]" id="ma" value="1" checked>

  <label><?php echo Yii::t('main', 'Ti'); ?></label>
  <input type="checkbox" class="sw" name="P[2]" id="ti" value="2" checked>

  <label><?php echo Yii::t('main', 'Ke'); ?></label>
  <input type="checkbox" class="sw" name="P[3]" id="ke" value="3" checked>

  <label><?php echo Yii::t('main', 'To'); ?></label>
  <input type="checkbox" class="sw" name="P[4]" id="to" value="4" checked>

  <label><?php echo Yii::t('main', 'Pe'); ?></label>
  <input type="checkbox" class="sw" name="P[5]" id="pe" value="5" checked>

  <label><?php echo Yii::t('main', 'La'); ?></label>
  <input type="checkbox" class="sw" name="P[6]" id="la" value="6" checked>

  <label><?php echo Yii::t('main', 'Su'); ?></label>
  <input type="checkbox" class="sw" name="P[0]" id="su" value="0" checked>

  </div>
</div>

    <?php
	foreach($tyosuhteet_checker as $item)
	{
		echo '<div class="alert bg-danger">Huom! Työntekijä '.$item['nimi'].' työsuhde on päättynyt '.$item['tsloppu'].'</div>';
	}
    ?>
    <button class="btn btn-success btn-lg laheta"><?php echo Yii::t('main','Lähetä'); ?></button>
  </form>
 </div>
</div>
<?php endif; ?>

<textarea id="kenelleLahetetaan" class="form-control" style="display:none"><?php echo implode(", ", $kenelleLahetetaan); ?></textarea>

<?php if(!$tulosta) : ?>
<script type="text/javascript">
$(document).ready(function(){

$(".laheta").click(function(){

	var kenelleLahetetaan = $('#kenelleLahetetaan').val();

        var r=confirm("Haluatko varmasti lähettää viikon <?php echo $week; ?> työvuorot henkilöille: \n"+kenelleLahetetaan+"?")
        if (!r){
	   return false;
	} else {
	   $("#pdf_email").submit();
	}
});


});
</script>
<?php endif; ?>


