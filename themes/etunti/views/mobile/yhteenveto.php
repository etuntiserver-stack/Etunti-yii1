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

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-primary btn-sm myBgColors" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-time"></i> <?php echo Yii::t('main', 'Tuntiyhtenveto työntekijät'); ?> 

		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-4">
                        <div class="section">
                          <label class="field">


   <?php
    $list = Mobile::model()->findAll(array('order' => 'tid','group'=>'tid'));

    echo '<select name="Tekija[]" class="mult" id="tyontekijat" class="mult" multiple title="Työntekijät">';
    foreach($list as $data){
     $t = Tyontekijat::model()->findbypk($data->tid);
     if(isset($t->id))
     {
       if(isset(Yii::app()->session['Tekija']) and in_array($t->id,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$t->id.'" selected>'.$this->etuSukunimi($t->id).'</option>';
       else
       	 echo '<option value="'.$t->id.'">'.$this->etuSukunimi($t->id).'</option>';
     }
    }
    echo '</select>';
   ?>


   <?php
    $lounas = '';
    $lounas = ( isset(Yii::app()->session['Lounastauko']))  ? 'selected' : '';
    $matka = '';
    $matka = ( isset(Yii::app()->session['MATKA']))  ? 'selected' : '';

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
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>

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

  foreach($model as $data)
  {
	$tids[] = $data->tid;
        $total_lu += $data->l_tunnit;
	$tp = $this->Tp($data->tid,$from,$to);
	$totalTp += $tp;
	$tot_sun = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->tid,'kohde_tid'=>'tid','from'=>$from,'to'=>$to),true);
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
		console.log(data);
		$("#showtyo_"+thisID[1]).html(data);
           }
        });
	

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
});


});
</script>
