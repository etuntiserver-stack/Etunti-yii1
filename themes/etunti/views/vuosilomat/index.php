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


        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-table"></i> <?php echo Yii::t('main', 'VUOSILOMAT'); ?> </h2>

        <!-- loppu: .tray-center -->
        </div>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">
		     <center>
  			<H4><a href="index?pvm=<?php echo $previous; ?>"><<</a> <?php echo $months[$month].' '.$year; ?> <a href="index?pvm=<?php echo $next; ?>">>></a></H4>
		     </center>
                    </div>
                </div>
              </div>
            </div>

<br>

<div class="row">
 <div class="col-sm-10">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<div class="table-responsive">
  <TABLE id="verkko" class="table table-bordered">
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

     if($this->pyhat($thisDate))
     {
       echo '<TD id="riv_'.$thisDate.$v->id.'" class="muokka myBgColors" method="'.$id.'" thisDate='.$thisDate.' thisTid='.$v->id.'>'.$st0.'</TD>';
     } else {
       echo '<TD id="riv_'.$thisDate.$v->id.'" '.$style.' class="muokka" method="'.$id.'" thisDate='.$thisDate.' thisTid='.$v->id.'>'.$st0.'</TD>';
     }
   }

  echo '<TR>';
  }
  ?>
  </TABLE>
</div>

                 </div>
                </div>
              </div>
            </div>

 </div>
 <div class="col-sm-2">
 <?php
  $valikkoot = Valikkoot::model()->findAll("select_type = 'vuosilomat'");
  foreach($valikkoot as $vl){
    $expl = explode("/",$vl->value);

    if(isset($expl[0]) and isset($expl[1]) and isset($expl[2]))
    {
    $back = " style='background:".$expl[2].";color: white;'";
    if($expl[0] == 'VL')
    $checked = 'checked';
    else
    $checked = '';

    echo '
    <div class="form-inline valikot">
	<input type=radio name="valikko" class="valikko" value="'.$expl[0].'//'.$expl[2].'//'.$expl[1].'" '.$checked.'> 
	<span class="col-sm-1 btn btn-xs btn-group btn-primary btn-group-justified" style="background: '.$expl[2].'">'.$expl[0].'</span>  '.$expl[1].'
    </div>';
    }
  }
 ?>
 <div class="row"></div>
 <br>
 <p><span class="btn btn-primary myBgColors muokaValiko" for="vuosilomat"><i class="fa fa-pencil-square-o"></i></span></p>

 </div>
</div>


	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

<script type="text/javascript">
$(document).ready(function(){

 
/* valikot */
$(".muokaValiko").click(function() {
    var thisFor = $(this).attr("for");
        $.ajax({
           url: location.protocol + "//" + location.host + "/index.php/site/valiko",
	   type:'POST',
	   data: { "select_type" : thisFor },
           success: function(data){
		//console.log(data);
		$('#showres').modal().html(JSON.parse(data));
           }
        });
});
/* valikot */


  $(".muokka").click(function(){

    $(this).css({"background" : "#ccc"});

    var thisID = $(this).attr("id");
    var thisDate = $(this).attr("thisDate");
    var thisTid = parseInt($(this).attr("thisTid"));
    var thisTXT = $(this).text();
    var id = $(this).attr("method");
    var thisStatus = $(".valikot input:radio:checked").val();
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
