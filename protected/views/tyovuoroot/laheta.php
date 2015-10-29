<?php


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

<style>
table{
	width: 290px;
	font-size: 80%;
}
td,th{
	padding:3px 7px;
	border:1px #333 solid;
}
</style>

<?php if(!$tulosta) : ?>
<legend>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT'). ' '.$tt->tekijan_nimi.', '.Yii::t('main', 'VIIKKO').'-'.$week; ?></h1>
</legend>

<div class="form-inline">
  <form action="#" class="form-group" target="_blank" method="POST">
    <input type="submit" class="btn btn-success btn-sm" name="pdf" value="PDF">
  </form>

  <?php if(!empty($tt->tekijan_email)): ?>
  <form action="#" id="pdf_email" class="form-group" target="_blank" method="POST">
    <input type="hidden" name="pdf_email" value="true">
    <button class="btn btn-success btn-sm laheta"><?php echo Yii::t('main','Lähetä').': '.$tt->tekijan_email; ?></button>
  </form>
  <?php
	$file = $week.'_'.$year.'_'.$tid.'.pdf';
	$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
	if (file_exists($path.'/'.$file))
	echo CHtml::link(Yii::t('main', ' Lähetetty'),'../../emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file,array('target'=>'_blank','class'=>'btn btn-danger glyphicon glyphicon-file'));

  ?>
  <?php endif; ?>
</div>
<?php endif; ?>


<?php if($tulosta) : ?>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT'). ' '.Yii::t('main', 'VIIKKO').'-'.$week; ?></h1>
<h3><?php echo $tt->tekijan_nimi.', ',date('d.m.Y',strtotime($year ."W". $week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?></h3>
<?php endif; ?>

<?php
  $criteria = new CDbCriteria();
  $criteria->order = " alku ASC "; 
  $criteria->condition = " tid='".$tid."' AND 
  DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d')  
  BETWEEN  '".date('Y-m-d',strtotime($year ."W". $week .'1'))."' AND '".date('Y-m-d',strtotime($year ."W". $week .'7'))."' 
  AND pvm!='' ";
  $tv = Tyovuoroot::model()->findAll($criteria);
?>

<br>
<table class="table">
<tr>
<th><?php echo Yii::t('main', 'Viikonpäivä'); ?></th>
<th><?php echo Yii::t('main', 'Aika/Kohde'); ?></th>
<th><?php echo Yii::t('main', 'Tietoja'); ?></th>
</tr>
<?php
for($day= 1; $day <= 7; $day++) {

  $d = strtotime($year ."W". $week . $day);
  $date = date('d.m.Y',$d);

  echo '<tr>';
  echo '<td width="50">'.$paivat[date('N',$d)].'<br>'.$date.'</td>';
  echo '<td width="300">';
  foreach($tv as $t)
  {
    if($t->pvm == $date)
    {
	$k = Kohteet::model()->findbypk($t->kohde,array("select"=>"osoite"));

	if($t->alku > 0 and $t->loppu > 0)
	  $al = $t->alku.'-'.$t->loppu;
	else
	  $al = '';

	$expl = explode("/",$t->tyoajanlaatu);
	if(empty($k['osoite']) and isset($expl[0]))
		$k['osoite'] = $expl[0];

	$expl2 = explode("/",$t->tyoajanmerkinta);
		$cl = '';
	if(isset($expl2[1]) and !empty($expl2[1]))
		$cl = 'style="color:'.$expl2[1].'"';
	
	echo '<span '.$cl.'>'.$al.' '.$k['osoite'].'</span><br>';
    }
  }
  echo '</td>';

  echo '<td width="300">';
  foreach($tv as $t)
  {
	$k = Kohteet::model()->findbypk($t->kohde,array("select"=>"osoite,avain"));

    if($t->pvm == $date and (!empty($t->tietoja) or !empty($k['avain'])))
    {

	echo '<b>'.$k['osoite'].':</b> <br>'.$t->tietoja;
	if(!empty($k['avain']))
	{
	echo '
	<p><b>'.Yii::t('main', 'Avain: ').'</b> '.$k['avain'].'</p>';
	}
	echo '<hr>';
    }
  }
  echo '</td>';

  echo '</tr>';
}
?>
</table>


<?php if(!$tulosta) : ?>
<script type="text/javascript">
$(document).ready(function(){

$(".laheta").click(function(){
        var r=confirm("Oletko varmaa?")
        if (!r){
	   return false;
	} else {
	   $("#pdf_email").submit();
	}
});


});
</script>
<?php endif; ?>


