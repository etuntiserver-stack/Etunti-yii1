<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

ini_set("max_execution_time", "900");

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

?>




        <!-- begin: .tray-center -->
        <div class="tray-center">
        <h2 class="myBgColors p15"> <i class="fa fa-home"></i> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet'); ?> 

   <!-- tulostus -->
   <div class="pull-right">
    <div class="form-inline">
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raportti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raportti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="header" value="<?=$from?>-<?=$to?>">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Raportti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
	  </form>
     	  <!-- en tieda<button class="btn btn-primary btn-sm btn-group myBgColors" data-toggle="collapse"  data-target="#haku"><?php echo Yii::t('main', 'Ekstrat'); ?> <b class="caret"></b></button>-->
    </div>
   </div>
   <!-- tulostus -->

	</h2>

   	    <form id="yhtveto" action="#" class="form-inline" method="GET">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="osoite" class="gui-input" value="<?php if(isset($_GET['osoite'])) echo $_GET['osoite']; ?>" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-home"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php echo $from; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php echo $to; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-4">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>




<?php if($from and $to) : ?>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive" id="tableContent">
  <table class="table table-striped small" id="this_table">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite (kohde)'); ?> <button class="pull-right btn btn-sm btn-primary ava_kaikki"><i class="fa fa-plus"></i> Ava kaikki</button></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltut tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Kpl'); ?></th>
  </tr>
  </thead>

  <?php 
  $sunYht 		= 0;
  $luetutYht 		= 0;
  $toteutuneetYht 	= 0;
  $kplyht 		= 0;
  $kpl			= 0;

	$suunniteltu 	= 0;
	$thisday	= date("Y-m-d");
	$tyovuorot 	= Yii::app()->createController('Tyovuoroot');
	$haku_criteria	= [];
	$haku_criteria[]= "(peruutettu=0 OR peruutettu IS NULL)";
	if( isset($_GET['osoite']) and !empty($_GET['osoite']) )
	$haku_criteria[]= "kohde IN(SELECT id FROM sivex_kohdet WHERE osoite LIKE '%".$_GET['osoite']."%')";
	$getAll 	= $tyovuorot[0]->tv_arr(date("Y-m-d", strtotime($from)), date("Y-m-d", strtotime($to)), [], $haku_criteria, false, ['tv_kesto']);
	
	$result = [];
	foreach($getAll as $k => $v)
		foreach($v as $unix => $dayarr)
			foreach($dayarr as $key => $arr)
				foreach($arr as $arr2)
					if(!isset($result[$arr2['kohde']]))
						$result[$arr2['kohde']] = $arr2['tv_kesto'];
					else
						$result[$arr2['kohde']] += $arr2['tv_kesto'];

	/*
	echo '<pre>';
	print_r($result);
	echo '<pre>';
	exit;
	*/

  foreach($lu as $key=>$val)
  {
	// <-- sunniteltu
	$sunniteltu = (isset($result[$val['kohdenID']]))?$result[$val['kohdenID']]:0;
        $sunYht += $sunniteltu;
	// sunniteltu -->

	// <-- luetut
	$cr1 = new CDbCriteria();
	$this->totLu($cr1,$val['kohdenID'],$from,$to);
	$lu = Mobile::model()->find($cr1);

	$luetut = $lu->l_tunnit;
  	$luetutYht += $luetut;
	// luetut -->

	// <-- toteutuneet
/*
	$cr2 = new CDbCriteria();
	$this->totLu($cr2,$val[0],$from,$to);
	$cr2->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$tot1 = Mobile::model()->find($cr2);

	$cr3 = new CDbCriteria();
	$this->totLu($cr3,$val[0],$from,$to);
	$tot2 = Toteutuneet::model()->find($cr3);
*/
	$toteutuneet = $val['l_tunnit'];
  	$toteutuneetYht += $toteutuneet;
	//  toteutuneet -->

	// <-- kpl
/*
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,$val[0],$from,$to);
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->find($cr4);

	if(isset($k->count)) $kpl1 += $k->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,$val[0],$from,$to);
	$kt = Toteutuneet::model()->find($cr5);

	if(isset($kt->count)) $kpl2 += $tot2->count;
*/
	$kpl = $val['count'];
	$kplyht += $kpl;
	// kpl -->

	$this->renderPartial('_kyhteenveto',array(
		'asiakas'=> $val['asiakas'],
		'luetut'=>$luetut,
		'toteutuneet'=>$toteutuneet,
		'sunniteltu'=>$sunniteltu, 
		'kohde_kannasta'=>$key,
		'kohdenID'=>$val['kohdenID'],
		'kpl'=>$kpl,
		'from'=>$from,
		'to'=>$to
	));
  }
  ?>

  <tfoot>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th></th>
  <th><?php echo $this->sprint($sunYht); ?></th>
  <?php endif; ?>

  <th><?php echo $this->sprint($luetutYht); ?></th>
  <th><?php echo $this->sprint($toteutuneetYht); ?></th>
  <th><?php echo $kplyht; ?></th>
  </tr>
  </tfoot>

  </table>
 </div>
</div>


   </div>
  </div>
</div>
<?php endif; ?>


	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

	<input type="hidden" id="from" value="<?php echo $from; ?>">
	<input type="hidden" id="to" value="<?php echo $to; ?>">


<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/moment.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-sortable.js"></script>

<script type="text/javascript">
$(document).ready(function(){

  /* Tulostus */
  $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
  });
  /* Tulostus */

$(".haemob").click(function(){
	$("#yhtveto").submit();
});

$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});
$(".ava_kaikki").click(function(){
	$(this).text('Odota..');
	$(".showKuka").click();
});
$(".showKuka").click(function(){
	var thisID = $(this).attr("id").split("_");
	var kohde_kannasta = $(this).attr("for").split("_");
	var from = $("#from").val();
	var to = $("#to").val();

	$.ajax({
		url: location.protocol + "//" + location.host + '/index.php/mobile/tyobykohde',
		type: "GET",
		data: { kohdenID : kohde_kannasta[1], from : from, to : to, asiakkalle : 0 },
		success: function(data){
			$(".ava_kaikki").remove();
			data = JSON.parse(data);
			//console.log(data);
			$("#showtyo_"+thisID[1]).html(data);
			$.bootstrapSortable({ applyLast: true }); //bootstrap-sortable.js
		}
	});
	
});

});
</script>
