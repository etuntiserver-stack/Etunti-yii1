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
.LahetettyTable table{
	width: 790px;
}
.LahetettyTable td, .LahetettyTable th{
	padding:3px 7px;
	border-top:1px #ccc solid;
}
</style>


<?php if(!$tulosta) : ?>
<legend>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT'). ' '.$this->etuSukunimi($tt->id).', '.Yii::t('main', 'VIIKKO').' - '.$week; ?></h1>
</legend>

<div class="form-inline">
  <form action="#" class="form-group" method="POST">
    <input type="submit" class="btn btn-success btn-sm" name="pdf" value="PDF">
  </form>

  <?php
	$file = $week.'_'.$year.'_'.$tid.'.pdf';
	$path = Yii::app()->request->baseUrl."emails/tyovuorot/".Yii::app()->user->domain;
	if (file_exists($path.'/'.$file))
	echo CHtml::link(Yii::t('main', 'Lähetetty'),'../../emails/tyovuorot/'.Yii::app()->user->domain.'/'.$file,array('class'=>'btn btn-sm btn-danger'));
  ?>
</div>


  <?php if(!empty($tt->tekijan_email)): ?>
<br>

  <form action="#" id="pdf_email" class="form-group" method="POST">
<div class="row">
 <div class="col-sm-5">

    <input type="hidden" name="pdf_email" value="true">
    <label><?php echo Yii::t('main','Lähetettävän viestin sisältö'); ?></label>
    <textarea name="kirjenBody" class="form-control" rows="6"></textarea>
    <br>

 </div>
</div>

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

<br>
    <button class="btn btn-success btn-sm laheta"><?php echo Yii::t('main','Lähetä').': '.$tt->tekijan_email; ?></button>
  </form>

<br>

  <?php endif; ?>

<?php endif; ?>


<?php if($tulosta) : ?>
<h1> <?php echo Yii::t('main', 'TYÖVUOROT'). ' '.Yii::t('main', 'VIIKKO').' - '.$week; ?></h1>
<?php echo Yii::t('main', 'Tulostettu '). ' '.date("d.m.Y H:i"); ?>
<h3><?php echo $this->etuSukunimi($tt->id).', ',date('d.m.Y',strtotime($year ."W". $week .'1')).' - '.date('d.m.Y',strtotime($year ."W". $week .'7')); ?></h3>
<?php endif; ?>

<?php
  $site = Yii::app()->createController('Site');
  $asetukset = Asetukset::model()->findByPk(1);
?>

<br>
<table class="table table-bordered LahetettyTable" cellspacing="0" cellpadding="0">
<?php
	$haku_criteria = [" peruutettu=0 OR peruutettu IS NULL "];
	$pvm_from = date("Y-m-d", strtotime($year ."W". $week .'1'));
	$pvm_to = date("Y-m-d", strtotime($year ."W". $week .'7'));
	$tv_arr = $this->tv_arr($pvm_from, $pvm_to, [$tt->id], $haku_criteria, false, ['this_id','data','tv_kesto']);
	$tids_after = [];
	foreach($tv_arr as $t => $arr)
		$tids_after[] = $t;

	$tv_kesto = 0;
	for($day= 1; $day <= 7; $day++) {
		$d = strtotime($year ."W". $week . $day);
		$date = date('d.m.Y',$d);
		if(isset($_POST['P']) and !in_array(date("N", strtotime($date)), $_POST['P'])){
			continue;
		}
		$date_arr = [];
		if(isset($tv_arr[$tid][$date])){
			ksort($tv_arr[$tid][$date]);
			$date_arr = $tv_arr[$tid][$date];
			foreach($date_arr as $arr){
				foreach($arr as $v2){
					if( isset($v2['tv_kesto']) ){
						$tv_kesto += $v2['tv_kesto'];
					}
				}
			}
			$this->renderPartial('_laheta_date', array(
				'site' => $site,
				'asetukset' => $asetukset,
				'tid' => $tt->id,
				'd' => $d,	
				'date' => $date,
				'paivat' => $paivat,
				'year' => $year,
				'week' => $week,
				'date_arr' => $date_arr
			));
		}
	}

	$totalWeek = $this->sprint($tv_kesto);
//$totalWeek = $this->renderPartial('//tyovuoroot/viikko',array('tid'=>$tid,'viikko'=>$week,'year'=>$year),true);
?>
 <tr>
 <th align="left"><?php echo Yii::t('','Yhteensä').' '. $totalWeek; ?></th>
 <th></th>
 </tr>
</table>


<?php if(!$tulosta) : ?>
<script type="text/javascript">
$(document).ready(function(){

/*
  $(".sw").bootstrapSwitch({
	size: "mini",
	onColor: "success",
	offColor: "danger",
	onText: "Kyllä",
	offText: "Ei"
  });
*/

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


