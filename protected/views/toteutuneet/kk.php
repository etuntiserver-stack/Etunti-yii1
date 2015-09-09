<?php
/* @var $this VuosilomatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Toteutuneet (kk)'),
);
/*
$this->menu=array(
	array('label'=>'Create Vuosilomat', 'url'=>array('create')),
	array('label'=>'Manage Vuosilomat', 'url'=>array('admin')),
);
*/

$months=array(
	1=>'Tammikuu',
	2=>'Helmikuu',
	3=>'Maaliskuu',
	4=>'Huhtikuu',
	5=>'Toukokuu',
	6=>'Kesäkuu',
	7=>'Heinäkuu',
	8=>'Elokuu',
	9=>'Syyskuu',
	10=>'Lokakuu',
	11=>'Marraskuu',
	12=>'Joulukuu'
	);

$pvm = '';
if(!isset($_GET['pvm'])){
$pvm = date("Y-m");
$month 	= date("n");
$year 	= date("Y");
} else {
$pvm = $_GET['pvm'];
$month 	= date("n",strtotime($pvm));
$year 	= date("Y",strtotime($pvm));
}


$number = cal_days_in_month(CAL_GREGORIAN, $month, $year); 

$next	= date("Y-n",strtotime("+1 month ".$pvm));
$previous = date("Y-n",strtotime("-1 month ".$pvm));

echo	'<input type=hidden id=month value='.$month.'>';
echo	'<input type=hidden id=Year value='.$year.'>';
echo	'<input type=hidden id=number value='.cal_days_in_month(CAL_GREGORIAN, $month, $year).'>'; 
?>

<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/verkko.css" />

<h1><?php echo Yii::t('main', 'Toteutuneet (kk)'); ?></h1>


<div class="row">
  <div class="col-sm-12">


  <H4><a href="kk?pvm=<?php echo $previous; ?>"><<</a> <?php echo $months[$month].' '.$year; ?> <a href="kk?pvm=<?php echo $next; ?>">>></a></H4>

   <?php
    $lounas = '';
    $lounas = ( isset(Yii::app()->session['Lounastauko']))  ? 'selected' : '';
    $matka = '';
    $matka = ( isset(Yii::app()->session['MATKA']))  ? 'selected' : '';

    echo '<select name="ilman[]" class="selectpicker ilman"  multiple="multiple"  title="EI LASKE...">';
    echo '<option value="Lounastauko" '.$lounas.'>Lounastauko</option>';
    echo '<option value="MATKA" '.$matka.'>MATKA</option>';
    echo '</select>';
   ?>

  </div>
</div>
<br>



  <TABLE id="verkko" class="">
  <?php 

  echo '<TR>';
  echo '<TH>Nimi</TH>';

   for ($i = 1; $i <= $number; $i++) 
   {
     echo '<TH>'.$i.'</TH>';
   }
     echo '<TH>Yht.</TH>';
  echo '</TR>';
  $t = Tyontekijat::model()->findAll(" aktiivinen = '1' ");
  foreach($t as $v)
  {
  $yht = 0;
  echo '<TR>';
  echo '<TD>'.$v->tekijan_nimi.'</TD>';

   for ($i = 1; $i <= $number; $i++) 
   {
     $thisDate = $year.'-'.$month.'-'.$i;
     $date = $i.'.'.$month;

	$tot[$i] = $this->renderPartial('totpvmtid',array('pvm'=>$thisDate,'tid'=>$v->id,'from'=>'kk'),true);
	$explT = explode("//",$tot[$i]);
	if(isset($explT[1]))
	$yht += $explT[1];
	echo '<TD class="text-small" style="font-size:90%">'.$explT[0].'</TD>';

   }
  echo '<TD class="text-small"><b>'.sprint($yht).'</b></TD>';
  echo '<TR>';
  }
  ?>
  </TABLE>




<script type="text/javascript">
$(document).ready(function(){

  $("ilman").blur(function(){

	var thisVal = $(this).val();

	alert(thisVal)
/*
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/toteutuneet/kk',
	   type:'POST',
	   data: { "id" : tvID, "cut" : "true", "newPvm" : newPvm, "newTid" : newTid },
           success: function(data){
        	console.log(data);

	  	$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/tyovuoroot/did',
			type:'GET',
			data: { "pvm" : newPvm, "tid" : newTid, "from" : "ajax" },
			  success:function(data){
			  console.log(data);
			  $('#'+newPvm+"_"+newTid).html(data);
			  clearKaikki();
			  //return false;
			  },
			  error:function(data){
			  console.log(data);
			  }
	 	});

    	   },
    	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	    	console.log(XMLHttpRequest);
 	   }
        });
*/

  });

});
</script>
