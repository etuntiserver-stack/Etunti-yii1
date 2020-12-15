<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Tunnit'),
);

?>



        <!-- begin: .tray-center -->
        <div class="tray-center">
<?php /*
   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-primary btn-sm myBgColors" value="PDF">
     </form>
   </div>
   <!-- tulostus -->
*/ ?>
        <h2 class="myBgColors p10"> 
	<div class="form-inline">
	 <div class="form-group">
		<i class="glyphicon glyphicon-th-list"></i> <?php echo Yii::t('main', 'Tuntiyhteenveto työntekijät'); ?>
	 </div><div class="form-group col-sm-offset-1">
		<?php
		( Yii::app()->request->getParam('aktiivinen') ) ? $selectedAktiivinen = Yii::app()->request->getParam('aktiivinen') : $selectedAktiivinen = '';

		$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
		   $tal = array();
		foreach($a as $v){
		$exV = explode("/",$v->value);
		   if(isset($exV[0]) and isset($exV[1]))
		   $tal[$exV[1]] = $exV[0];
		}
		//ksort($tal);
		echo CHtml::dropDownList('aktiivinen','aktiivinen', $tal, array('class'=>'form-control', 'options' => array( $selectedAktiivinen => array('selected'=>true))));
		?>
	 </div>
	</div>
	</h2>

<script>
$(document).ready(function(){
  $('#aktiivinen').change(function(){
	var thisVal = $(this).val();
	window.location.href="yhteenveto?aktiivinen=" + thisVal;
  });
});
</script>


   	    <form id="yhtveto" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
					'Tekija', // name
					'mult', // class
					'tyontekijat', // id
					(isset($_GET['Tekija']))?$_GET['Tekija']:'', //selected
					1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field">
   <?php
    $lounas = '';
    $matka = '';
    if( isset($_GET['ilman']) ){
	foreach($_GET['ilman'] as $t)
		if($t == 'MATKA')
			$matka = 'selected';
		if($t == 'Lounastauko')
			$lounas = 'selected';
    }
    echo '<select name="ilman[]" class="ilman"  multiple="multiple">';
    echo '<option value="Lounastauko" '.$lounas.'>'.Yii::t('main', 'Lounastauko').'</option>';
    echo '<option value="MATKA" '.$matka.'>'.Yii::t('main', 'Matka').'</option>';
    echo '</select>';
   ?>

                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 admin-form">
                        <div class="section">
                          <label class="field prepend-icon">
	   			<input type="text" name="from" id="from" class="gui-input datepickerFI" value="<?php echo $from; ?>">
                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 admin-form">
                        <div class="section">
                          <label class="field prepend-icon">
   	   			<input type="text" name="to" id="to" class="gui-input datepickerFI" value="<?php echo $to; ?>">
                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>
                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>






<div class="admin-form">
  <div class="panel-header">
      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
         <div class="form-inline">
    	  <!--<button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>-->
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="header" value="<?=$from?>-<?=$to?>">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
	  </form>
         </div>
        </div>
       </div>
      </div>
      <br>
  </div>
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div id="tableContent">
  <table class="table table-striped">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?> <button class="pull-right btn btn-sm btn-primary ava_kaikki"><i class="fa fa-plus"></i> Ava kaikki</button></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  </tr>
  </thead>

  <?php 
  $tids = array();
  $total_lu 	= 0;
  $totalTp	= 0;
  $total_sunniteltu = 0;
  $tot_sun	=0;
  $tp		= 0;

  $yht[0] = 0;
  $yht[1] = 0;
  $yht[2] = 0;
  $yht[3] = 0;

  $tids = [];
  foreach($model as $data)
 	$tids[$data->tid] = $data->tid;

  $tyovuorot = Yii::app()->createController('Tyovuoroot');
  $getAll = $tyovuorot[0]->TidfromtoTyovuoroWithVirtual(date("Y-m-d", strtotime($from)), date("Y-m-d", strtotime($to)), $tids, false, false, null);
/*
  echo '<pre>';
  print_r($getAll);
  echo '<pre>';
  exit;
*/

  foreach($model as $data)
  {
	$tids[] = $data->tid;
        $total_lu += $data->l_tunnit;
	$tp = $this->Tp($data->tid,$from,$to);
	$totalTp += $tp;
	
	// Suunnittellut
	$tyotunnit	= (isset($getAll[$data->tid]['tyotunnit']['kaikki']))? $getAll[$data->tid]['tyotunnit']['kaikki'] : 0;
	$matkatunnit	= (isset($getAll[$data->tid]['matkatunnit']['kaikki']))? $getAll[$data->tid]['matkatunnit']['kaikki'] : 0;
	$loun		= (isset($getAll[$data->tid]['lounaat']['kaikki']))? $getAll[$data->tid]['lounaat']['kaikki'] : 0;

	$suunn = $tyotunnit;
	if($matka != 'selected')
		$suunn += $matkatunnit;
	if($lounas != 'selected')
		$suunn += $loun;

	$tot_sun = $suunn;
	$total_sunniteltu += $tot_sun;

	$return = $this->toteutu($data->tid,"yhteenveto",$from,$to);
	$this->renderPartial('_yhteenveto',array('data'=>$data,'tp'=>$tp,'tot_sun'=>$tot_sun,'return'=>$return));

	$yht[0] += $return[0];
	$yht[1] += $return[1];
	$yht[2] += $return[2];
	$yht[3] += $return[3];
  }
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<?php
	$tas = explode(",",Yii::app()->user->adminPaketti);
	if(in_array('2',$tas)) {
	echo '<td>'.$this->sprint($total_sunniteltu).'</td>';
	}
	?>

	<td><?php echo $this->sprint($total_lu); ?></td>
	<td><?php echo $this->sprint($yht[0]); ?></td>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $this->sprint($yht[1]); ?></td>
	<td><?php echo $this->sprint($yht[2]); ?></td>
	<td><?php echo $this->sprint($yht[3]); ?></td>
  </tr>
  </tfoot>
  </table>
 </div>
</div>

   </div>
  </div>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){

$(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val($('#tableContent').html());
	$(this).closest('form').submit();
	e.preventDefault();
});
$(".ava_kaikki").click(function(){
	$(this).text('Odota..');
	$(".showKuka").click();
});
$(".haemob").click(function(){
	$("#yhtveto").submit();
});
$('.selectpicker').selectpicker({
      style: 'btn-default btn-sm',
      //size: 4
});
$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});
$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
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

$(".showKuka").click(function(){
	
	var thisID = $(this).attr("id").split("_");
	var k = $(this).attr("for").split("_");
	var from = $("#from").val();
	var to = $("#to").val();
	$.ajax({
		url: location.protocol + "//" + location.host + '/index.php/mobile/kohdebytekija',
		type: "GET",
		data: { tid : k[1], from : from, to : to },
		success: function(data){
			$(".ava_kaikki").remove();
			//console.log(data);
			$("#showtyo_"+thisID[1]).html(data);
		}
	});
});
$(document).delegate(".showKukaSub","click",function(){
	var thisButton = $(this).text('odota..');
	var row_id = $(this).attr("row_id");
	var kohdenID = $(this).attr("kohde");
	var kohde_kannasta = $(this).attr("kohde_kannasta");
	var status = $(this).attr("status");
	if($('.pvms_' + row_id).hasClass('in')){
		$('.pvms_' + row_id).hide('slow').removeClass('in');
		setTimeout(function(){ $('.pvms_' + row_id).remove(); }, 1000);
		thisButton.html('<i class="fa fa-2x fa-caret-square-o-down"></i>');
		return false;
	}
	
	var closestTR = $(this).closest('tr');
	var tid = $(this).attr("tid");
	var from = $("#from").val();
	var to = $("#to").val();
	if( parseInt(row_id) > 0 ){
		$.ajax({
			url: location.protocol + "//" + location.host + '/index.php/mobile/kohdebytekija',
			type: "GET",
			data: { tid : tid, from : from, to : to, kohde_kannasta : kohde_kannasta, status : status, row_id : row_id },
			success: function(data){
				$(".ava_kaikki").remove();
				console.log(data);
				closestTR.after('<td colspan="4" class="pvms in pvms_' + row_id + '"><table class="table table-striped">' +
					'<tr><th width="50%"></th><th>Pvm</th><th>Alku</th><th>Loppu</th><th>Kesto</th></tr>' +
					data +
					'</table></td>'
				);
				thisButton.html('<i class="fa fa-2x fa-caret-square-o-down"></i>');
			}
		});
	}
});

/*
$('.mult').selectpicker({
      style: 'gui-input',
      //size: 4
  });
*/


$('.ilman').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Ei lasketa"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});


$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
});


});
</script>
