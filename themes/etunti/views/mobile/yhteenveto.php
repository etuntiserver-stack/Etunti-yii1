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
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-time"></i> <?php echo Yii::t('main', 'TUNTIYHTEENVETO TYÖNTEKIJÄT'); ?> 

		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field">


   <?php
    $list = CHtml::listData(Mobile::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'tid', 'tekijan_nimi');

    echo '<select name="Tekija[]" class="mult" id="tyontekijat" multiple class="btn btn-default" title="Työntekijät">';
    foreach($list as $key=>$val){
     if(!empty($val))
     {
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
     }
    }
    echo '</select>';
   ?>


                          </label>

   <a href="#" id="deselAll" class="glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="glyphicon glyphicon-plus"></a>


                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">

   <?php
    $lounas = '';
    $lounas = ( isset(Yii::app()->session['Lounastauko']))  ? 'selected' : '';
    $matka = '';
    $matka = ( isset(Yii::app()->session['MATKA']))  ? 'selected' : '';

    echo '<select name="ilman[]" class="mult ilman"  multiple="multiple"  title="Ei lasketa...">';
    echo '<option value="Lounastauko" '.$lounas.'>Lounastauko</option>';
    echo '<option value="MATKA" '.$matka.'>MATKA</option>';
    echo '</select>';
   ?>



                          </label>
                        </div>
                      </div>
                      <div class="col-md-2 col-md-offset-1">
                        <div class="section">
                          <label class="field prepend-icon">

	   <input type="text" name="from" id="from" class="gui-input datepicker" value="<?php echo $from; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   	   <input type="text" name="to" id="to" class="gui-input datepicker" value="<?php echo $to; ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Hae</button>
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


<br>





  <div class="panel heading-border">
   <div class="panel-body">

  <table class="table table-striped small">
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
  foreach($model as $data)
  {
	$tids[] = $data->tid;
        $total_lu += $data->l_tunnit;
	$tp = $this->Tp($data->tid,$from,$to);
	$totalTp += $tp;
	$tot_sun = $this->renderPartial('//mobile/suunniteltu',array('id'=>$data->tid,'kohde_tid'=>'tid','from'=>$from,'to'=>$to),true);
	$total_sunniteltu += $tot_sun;

	$this->renderPartial('_yhteenveto',array('data'=>$data,'tp'=>$tp,'tot_sun'=>$tot_sun,'from'=>$from,'to'=>$to));
  }


	$yht[0] = 0;
	$yht[1] = 0;
	$yht[2] = 0;
	$yht[3] = 0;

  foreach($tids as $t)
  {
	$return = '';
	$return = $this->toteutu($t,"yhteenveto",$from,$to);
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



$('.mult').selectpicker({
      style: 'gui-input',
      //size: 4
  });

});
</script>
