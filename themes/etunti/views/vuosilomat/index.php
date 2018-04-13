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
	1=>Yii::t('main', 'Tammikuu'),
	2=>Yii::t('main', 'Helmikuu'),
	3=>Yii::t('main', 'Maaliskuu'),
	4=>Yii::t('main', 'Huhtikuu'),
	5=>Yii::t('main', 'Toukokuu'),
	6=>Yii::t('main', 'Kesäkuu'),
	7=>Yii::t('main', 'Heinäkuu'),
	8=>Yii::t('main', 'Elokuu'),
	9=>Yii::t('main', 'Syyskuu'),
	10=>Yii::t('main', 'Lokakuu'),
	11=>Yii::t('main', 'Marraskuu'),
	12=>Yii::t('main', 'Joulukuu')
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

	   <h2 class="myBgColors p10">

               <div class="form-inline">
		<i class="fa fa-table"></i> <?php echo Yii::t('main', 'Lomat'); ?> 

		<a href="index?pvm=<?php echo $previous; ?>" class="fa fa-arrow-left btn btn-default btn-group" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Edellinen kuukausi'); ?>"></a> 
		<span class="btn btn-default"><?php echo $months[$month].' '.$year; ?></span>
		<a href="index?pvm=<?php echo $next; ?>" class="fa fa-arrow-right btn btn-default btn-group" data-toggle="tooltip" data-placement="bottom" title="<?php echo Yii::t('main', 'Seuraava kuukausi'); ?>"></a>
               </div>

	   </h2>

	   <textarea class="form-control vapaateksti" rows="2" placeholder="<?php echo Yii::t('main', 'Tähän tulee teksti, joka lisätään selitteeksi työvuorosuunnittelussa tietoihin.'); ?>"></textarea>
        <!-- loppu: .tray-center -->
        </div>




		<!-- Fixed Table -->
		<!-- http://www.jqueryscript.net/table/jQuery-Plugin-For-Fixed-Table-Header-Footer-Columns-TableHeadFixer.html -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/tableHeadFixer.js"></script>

		<style>	
			#fixTable {
				width: 1800px !important;
			}
			.laatikot{
				min-width: 30px;
				text-align: center;
			}
		</style>

		<script>
			$(document).ready(function() {
				window.onload = function(event) { resizeDiv(); }
				//window.onresize = function(event) { resizeDiv(); }

				function resizeDiv() {
				    vpw = $(window).width()-100; 
				    vph = $(window).height()-310;

				    $('#parent').css({'height': vph + 'px', 'overflow-y' : 'hidden'});

				    $("#fixTable").tableHeadFixer({
					"left" : 1,
					'z-index': 0
				    }); 
				}

			});
		</script>
		<!-- Fixed Table -->

<br>


<div class="row">
 <div class="col-sm-10">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<div class="tvuoro table-responsive" id="parent">
  <table class="table table-striped table-condensed table-bordered" id="fixTable">
  <?php 
  echo '<thead><tr>';
  echo '<th>Nimi</th>';

   for ($i = 1; $i <= $number; $i++) 
   {
     echo '<th><div class="fixed-column laatikot">'.$i.'</div></th>';
   }

  echo '</tr></thead><tbody>';

  $criteria = new CDbCriteria();

  $asetukset = Asetukset::model()->findByPk(1);
  if($asetukset->tyontekijan_etunimi_sukunimi_jarjestys == 0)
	$criteria->order = " tekijan_nimi ";
  else
	$criteria->order = " sukunimi ";

		// <-- Tyoryhmat
		$checkOikeus = "tyoryhmat_4_".Yii::app()->user->adminStatus;
		$site = Yii::app()->createController('Site');
		if( $site[0]->checkOikeusFields($checkOikeus) == 0 ){
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper();
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
		       		$criteria->condition = " id IN ($ids) ";
			} else {
				$criteria->condition =" 1!=1 ";
			}
		} else {
			$criteria->condition =" aktiivinen=1  ";
		}
		//     Tyoryhmat -->

  $t = Tyontekijat::model()->findAll($criteria);
  foreach($t as $v)
  {
  echo '<tr>';
  echo '<td class="fixed-column">'.$this->etuSukunimi($v->id).'</td>';

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
	$myBgColors = '';
     } else {
      	$status = '';
      	$style = '';
	$st0 = '';
	$id = 'new';
	$myBgColors = 'myBgColors';
     }

     if($this->pyhat($thisDate))
     {
       echo '<td id="riv_'.date("ymd", strtotime($thisDate)).$v->id.'" class="muokaTaulunLatiko '.$myBgColors.'" '.$style.' method="'.$id.'" thisdate='.$thisDate.' thistid='.$v->id.'>
	<div class="link laatikot">'.$st0.'</div></td>';
     } else {
       echo '<td id="riv_'.date("ymd", strtotime($thisDate)).$v->id.'" '.$style.' class="muokaTaulunLatiko" method="'.$id.'" thisdate='.$thisDate.' thistid='.$v->id.'><div class="link laatikot">'.$st0.'</div></td>';
     }
   }

  echo '</tr>';
  }
  ?>
   </tbody>
  </table>
</div>

                 </div>
                </div>
              </div>
            </div>

 </div>
 <div class="col-sm-2">

    <div class="form-inline valikot" data-toggle="tooltip" data-placement="left" title="<?php echo Yii::t('main', 'Vuosilomat siirtyvät merkinnän jälkeen tuntien hyväksyntään, josta hyväksynnän jälkeen palkkatauluun.'); ?>">
	<input type=radio name="valikko" class="valikko" value="VL//green//Vuosiloma" checked> 
	<span class="col-sm-1 btn btn-xs btn-group btn-primary btn-group-justified" style="background: green">VL</span>  Vuosiloma
    </div>
    <div class="form-inline valikot" data-toggle="tooltip" data-placement="left" title="<?php echo Yii::t('main', 'Viikkolomapäivät siirtyvät merkinnän jälkeen tuntien hyväksyntään, josta hyväksynnän jälkeen palkkatauluun.'); ?>">
	<input type=radio name="valikko" class="valikko" value="VKL//blue//Viikkolomapäivä"> 
	<span class="col-sm-1 btn btn-xs btn-group btn-primary btn-group-justified" style="background: blue">VKL</span>  Viikkolomapäivä
    </div>

    <br><br>
    <legend><?php echo Yii::t('main', 'Ekstrat'); ?></legend>

 <?php
  $valikkoot = Valikkoot::model()->findAll("select_type = 'vuosilomat'");
  foreach($valikkoot as $vl){
    $expl = explode("/",$vl->value);

    if(isset($expl[0]) and isset($expl[1]) and isset($expl[2]))
    {
    $back = " style='background:".$expl[2].";color: white;'";

    echo '
    <div class="form-inline valikot">
	<input type=radio name="valikko" class="valikko" value="'.$expl[0].'//'.$expl[2].'//'.$expl[1].'"> 
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


$('input[type="radio"]').change(function() {
		$('.vapaateksti').val('');
});

/* valikot */

  $(document).delegate(".muokaTaulunLatiko","click",function(){

    $(this).css({"background" : "#ccc"});

    var thisID = $(this).attr("id");
    var thisDate = $(this).attr("thisdate");
    var thisTid = parseInt($(this).attr("thistid"));
    var thisTXT = $(this).text();
    var id = $(this).attr("method");
    var thisStatus = $(".valikot input:radio:checked").val();
    var lat1 = thisStatus.split("//");
    var lat = '('+lat1[0]+') '+lat1[2]+'/'+lat1[1];
    var vapaateksti = $('.vapaateksti').val();

//alert(thisID)
//return false;

    var postdata = {
	tid 	: thisTid,
	pvm 	: thisDate,
	status 	: thisStatus,
	tietoja	: vapaateksti,
	tyoajanlaatu : lat,
    }

        $.ajax({
           url: 'vlupdater?id='+id+'&txt='+thisTXT,
	   type: 'POST',
	   data: { Vuosilomat : postdata },
           success: function(data){
		console.log(data);

		var spData  = data.split("//");

		if(spData[3] != '' && data != 'removed'){
		   $("#"+thisID).attr("method",spData[0]);
		   $("#"+thisID).removeClass("myBgColors bg-info");
		   $("#"+thisID).attr("style","background:"+spData[4]+";color:white;");
		   $("#"+thisID).html('<div class="link laatikot">'+ spData[3] +'</div>');
		}

		if(data == 'removed')
		{
		   $("#"+thisID).attr("method", "new");
		   $("#"+thisID).html('<div class="link laatikot"></div>');
		}
    

           },
	   error:function(data){
		console.log(data);
		/*window.location.href=location.protocol + "//" + location.host + "/index.php/site/index";*/
	   }
        });


  });

});
</script>




