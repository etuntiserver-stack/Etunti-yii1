<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

ini_set("max_execution_time", "60");

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

?>




        <!-- begin: .tray-center -->
        <div class="tray-center">

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="hidden" name="osoite" value="<?php if(isset($_POST['osoite'])) echo $_POST['osoite']; ?>" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>..">
      <input type="submit" name="tulosta" class="btn btn-primary btn-sm myBgColors" value="PDF">
     </form>
   </div>
   <!-- tulostus -->


              <h2 class="myBgColors p10"> <i class="fa fa-home"></i> <?php echo Yii::t('main', 'TUNTIYHTEENVETO KOHTEET'); ?> 

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

	   <input type="text" name="osoite" class="gui-input" value="<?php if(isset($_POST['osoite'])) echo $_POST['osoite']; ?>" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-home"></i>
                            </label>
                          </label>
                        </div>
                      </div>

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
  $sunYht = 0;
  $luetutYht = 0;
  $toteutuneetYht = 0;
  $kplyht = 0;
  foreach($lu as $key=>$val)
  {
	// <-- sunniteltu
	$sunniteltu = $this->renderPartial('//mobile/suunniteltu',array('id'=>$val,'kohde_tid'=>'kohde','from'=>$from,'to'=>$to),true);
        $sunYht += $sunniteltu;
	// sunniteltu -->

	// <-- luetut
	$cr1 = new CDbCriteria();
	$this->totLu($cr1,$val,$from,$to);
	$lu = Mobile::model()->find($cr1);

	$luetut = $lu->l_tunnit;
  	$luetutYht += $luetut;
	// luetut -->

	// <-- toteutuneet
	$cr2 = new CDbCriteria();
	$this->totLu($cr2,$val,$from,$to);
	$cr2->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$tot1 = Mobile::model()->find($cr2);

	$cr3 = new CDbCriteria();
	$this->totLu($cr3,$val,$from,$to);
	$tot2 = Toteutuneet::model()->find($cr3);

	$toteutuneet = $tot1->l_tunnit+$tot2->l_tunnit;
  	$toteutuneetYht += $toteutuneet;
	//  toteutuneet -->

	// <-- kpl
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,$val,$from,$to);
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->find($cr4);

	if(isset($k->count)) $kpl1 += $k->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,$val,$from,$to);
	$k = Toteutuneet::model()->find($cr5);

	if(isset($k->count)) $kpl2 += $k->count;

	$kpl = $kpl1+$kpl2;
	$kplyht += $kpl;
	// kpl -->

	$this->renderPartial('_kyhteenveto',array(
		'luetut'=>$luetut,
		'toteutuneet'=>$toteutuneet,
		'sunniteltu'=>$sunniteltu, 
		'kohde_kannasta'=>$key,
		'kohdenID'=>$val,
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
	   data: { kohdenID : kohde_kannasta[1], from : from, to : to, asiakkalle : 0 },
           success: function(data){
		console.log(data);
		$("#showtyo_"+thisID[1]).html(data);
           }
        });
	

});

});
</script>
