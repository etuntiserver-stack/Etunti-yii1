<?php
/* @var $this VuosilomatController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Vuosilomat'),
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

<style>
#verkko { 
    border-collapse: collapse;
    border-spacing: 0;
}
#verkko TH,#verkko TD {
    text-align: center;
    min-width: 23px;
    border:1px #999 solid;
}

#verkko  th:first-child,#verkko td:first-child {
    width: 150px;
    text-align: left;
    white-space: nowrap;
    font-size: 9pt;
    padding: 2px 5px;
}
#valikot span{
    color: white; 
    padding: 2px 7px;
    -moz-border-radius: 6px;
    -webkit-border-radius: 6px;
    border-radius: 6px;
    border:1px #999 solid;
}
.muokka{
	cursor: pointer;
}
#lisattyTyovuoroon{
    display: none;
    color: red;
    position: absolute;
}
.viikkonloppu{
	color: #333;
	//text-shadow: 1px 1px 0px #fff;
background: rgb(255,224,123);
background: -moz-linear-gradient(top,  rgba(255,224,123,1) 0%, rgba(255,215,83,1) 58%, rgba(255,215,83,1) 58%, rgba(255,202,89,1) 59%, rgba(255,202,87,1) 100%); 
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(255,224,123,1)), color-stop(58%,rgba(255,215,83,1)), color-stop(58%,rgba(255,215,83,1)), color-stop(59%,rgba(255,202,89,1)), color-stop(100%,rgba(255,202,87,1)));
background: -webkit-linear-gradient(top,  rgba(255,224,123,1) 0%,rgba(255,215,83,1) 58%,rgba(255,215,83,1) 58%,rgba(255,202,89,1) 59%,rgba(255,202,87,1) 100%); 
background: -o-linear-gradient(top,  rgba(255,224,123,1) 0%,rgba(255,215,83,1) 58%,rgba(255,215,83,1) 58%,rgba(255,202,89,1) 59%,rgba(255,202,87,1) 100%); 
background: -ms-linear-gradient(top,  rgba(255,224,123,1) 0%,rgba(255,215,83,1) 58%,rgba(255,215,83,1) 58%,rgba(255,202,89,1) 59%,rgba(255,202,87,1) 100%); 
background: linear-gradient(to bottom,  rgba(255,224,123,1) 0%,rgba(255,215,83,1) 58%,rgba(255,215,83,1) 58%,rgba(255,202,89,1) 59%,rgba(255,202,87,1) 100%); 
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffe07b', endColorstr='#ffca57',GradientType=0 );
}
</style>

<h1><?php echo Yii::t('main', 'Vuosilomat'); ?></h1>


<div class="row">
  <div class="col-sm-12">


  <H4><a href="index?pvm=<?php echo $previous; ?>"><<</a> <?php echo $months[$month].' '.$year; ?> <a href="index?pvm=<?php echo $next; ?>">>></a></H4>

  </div>
</div>
<br>

<div class="row">
 <div class="col-sm-10">
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

     $vl = Vuosilomat::model()->find(" pvm = '".$thisDate."' AND tid = '".$v->id."' ");
     if(isset($vl['id'])){
      	$status = explode("//",$vl['status']);
	if(isset($status[1]))
      	$style = " style='background: $status[1]; color: white' ";
	$st0 = $status[0];
	$id = $vl['id'];
     } else {
      	$status = '';
      	$style = '';
	$st0 = '';
	$id = 'new';
     }

     if(pyhat($thisDate,$date))
     {
       echo '<TD id="riv_'.$thisDate.$v->id.'" class="muokka viikkonloppu" method="'.$id.'" thisDate='.$thisDate.' thisTid='.$v->id.'>'.$st0.'</TD>';
     } else {
       echo '<TD id="riv_'.$thisDate.$v->id.'" '.$style.' class="muokka" method="'.$id.'" thisDate='.$thisDate.' thisTid='.$v->id.'>'.$st0.'</TD>';
     }
   }

  echo '<TR>';
  }
  ?>
  </TABLE>

 </div>
 <div class="col-sm-2">
 <?php
  $valikkoot = Valikkoot::model()->findAll("select_type = 'vuosilomat'");
  foreach($valikkoot as $vl){
    $expl = explode("/",$vl->value);
    $back = " style='background:".$expl[2].";color: white;'";
    if($expl[0] == 'VL')
    $checked = 'checked';
    else
    $checked = '';

    echo '<p><input type=radio name="valikko" class="valikko" value="'.$expl[0].'//'.$expl[2].'//'.$expl[1].'" '.$checked.'> 
	<span '.$back.' class="btn">'.$expl[0].'</span>  '.$expl[1].'</p>';
  }
 ?>
 </div>
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
