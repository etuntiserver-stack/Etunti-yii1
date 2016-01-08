<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
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


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'TUNTIYHTEENVETO KOHTEET'); ?> 

		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
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

                      <div class="col-md-2 col-md-offset-6">
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




<?php if($from and $to) : ?>

  <div class="panel heading-border">
   <div class="panel-body">

  <table class="table table-striped small">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Kpl'); ?></th>
  </tr>
  </thead>

  <?php 
  foreach($lu as $key=>$val)
	$this->renderPartial('_kyhteenveto',array('kohde_kannasta'=>$key,'kohdenID'=>$val));
  ?>

  <tfoot>
  <?php
	$lu = '0';
	$tot = '0';
	$suunn = '0';
	$kplyht = '0';

		$suunn = $this->yhtSUUNN();
		$lu = $this->yhtLU();
		$tot = $this->yhtTOT();


	//kpl
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,"kaikki");
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->findAll($cr4);
	foreach($k as $kk)
	$kpl1 += $kk->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,"kaikki");
	$k = Toteutuneet::model()->findAll($cr5);
	foreach($k as $kk)
	$kpl2 += $kk->count;

	$kplyht = $kpl1+$kpl2;


  ?>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th>/ <?php echo Yii::t('main', 'oikeasti:'); ?><?php echo $this->sprint($suunn); ?></th>
  <?php endif; ?>

  <th><?php echo $this->sprint($lu); ?></th>
  <th><?php echo $this->sprint($tot); ?></th>
  <th><?php echo $kplyht; ?></th>
  </tr>
  </tfoot>

  </table>

   </div>
  </div>

<?php endif; ?>


	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

	<input type="hidden" id="from" value="<?php echo $from; ?>">
	<input type="hidden" id="to" value="<?php echo $to; ?>">


<script type="text/javascript">
$(document).ready(function(){

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


$(".showKuka").click(function(){
	
	var thisID = $(this).attr("id").split("_");
	var kohde_kannasta = $(this).attr("for").split("_");
	var from = $("#from").val();
	var to = $("#to").val();

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/tyobykohde',
           type: "GET",
	   data: { kohdenID : kohde_kannasta[1], from : from, to : to },
           success: function(data){
		console.log(data);
		$("#showtyo_"+thisID[1]).html(data);
           }
        });
	

});

});
</script>
