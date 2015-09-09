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

  </div>
</div>
<br>

<div class="row">

  <TABLE id="verkko" class="">
  <?php 

  echo '<TR>';
  echo '<TH>Nimi</TH>';

   for ($i = 1; $i <= $number; $i++) 
   {
     echo '<TH>'.$i.'</TH>';
   }

  echo '</TR>';
  $t = Tyontekijat::model()->findAll(" aktiivinen = '1' ");
  foreach($t as $v)
  {
  echo '<TR>';
  echo '<TD>'.$v->tekijan_nimi.'</TD>';

   for ($i = 1; $i <= $number; $i++) 
   {
     $thisDate = $year.'-'.$month.'-'.$i;
     $date = $i.'.'.$month;


	$tot = $this->renderPartial('totpvmtid',array('pvm'=>$thisDate,'tid'=>$v->id,'from'=>'kk'),true);
	echo '<TD class="text-warning">'.sprint($tot).'</TD>';

   }

  echo '<TR>';
  }
  ?>
  </TABLE>

</div>


<script type="text/javascript">
$(document).ready(function(){

  $(".muokka").click(function(){

    $(this).css({"background" : "#ccc"});

    var thisID = $(this).attr("id");
    var thisDate = $(this).attr("thisDate");
    var thisTid = parseInt($(this).attr("thisTid"));
    var thisTXT = $(this).text();
    var id = $(this).attr("method");
    var thisStatus = $("input:radio:checked").val();
    var lat1 = thisStatus.split("//");
    var lat = '('+lat1[0]+') '+lat1[2]+'/'+lat1[1];



    var postdata = {
	tid 	: thisTid,
	pvm 	: thisDate,
	status 	: thisStatus
    }

        $.ajax({
           url: 'vlupdater?id='+id+'&txt='+thisTXT+'&lat='+lat,
	   type: 'POST',
	   data: { Vuosilomat : postdata },
           success: function(data){
		console.log(data);

		var spData  = data.split("//");

		if(spData[3] != ''){
		   $("#"+thisID).attr("method",spData[0]);
		   $("#"+thisID).attr("style","background:"+spData[4]+";color:white;");
		   $("#"+thisID).html(spData[3]);
		}

		if(data == 'removed')
		   $("#"+thisID).html('');

    		if(thisTXT == ''){
		    $("#lisattyTyovuoroon").html("<h3>Lisätty työvuoroon</h3>").fadeToggle("fade", function() {
			$("#lisattyTyovuoroon").fadeOut(2000);
  		    });
		 
	   	} else {
		    $("#lisattyTyovuoroon").html("<h3>Poistettu</h3>").fadeToggle("fade", function() {
			$("#lisattyTyovuoroon").fadeOut(2000);
  		    });
		}
    

           },
	   error:function(data){
		console.log(data);
	   }
        });


  });

});
</script>
