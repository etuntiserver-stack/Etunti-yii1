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

              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-time"></i> <?php echo Yii::t('main', 'TUNTIYHTEENVETO MATKAT'); ?> 

		</h2>



   	    <form id="yhtveto" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="yhtvetoform">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-1">
                        <div class="section">
                          <label class="field">


   <?php
    $criteria = new CDbCriteria();
    $criteria->order = " tekijan_nimi ";
    $criteria->condition = " aktiivinen=1 ";
    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');

    echo '<select name="Tekija[]" class="mult" id="tyontekijat" multiple title="Työntekijät">';
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


                        </div>
                      </div>
                      <div class="col-md-2 col-md-offset-1">
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

                      <div class="col-md-2 col-md-offset-3">
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


  <table class="table table-striped">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Luetut'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet'); ?></th>
<?php 
/*
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
*/
?>
  </tr>
  </thead>

  <?php
	$luetutYht 	= 0;
	$toteutuneetYht	= 0;

  	foreach($model as $data){

		$luetut		= 0;
		$toteutuneet	= 0;

		// <-- Luetut
		$luetut = $this->YhteensaLuMatka($data->id,$from,$to);
		// Luetut -->

		// <-- Toteutuneet
       		$criteria = new CDbCriteria();
		$this->luMatka($criteria,$data->id,$from,$to);

		$lu = Mobile::model()->find($criteria);

		$this->totMatka($criteria,$data->id,$from,$to);
		$tot = Toteutuneet::model()->find($criteria);
		if( isset($lu->l_tunnit) or isset($tot->l_tunnit) )
		$toteutuneet = $lu->l_tunnit+$tot->l_tunnit;
		// Toteutuneet -->


		$this->renderPartial('_yhteenveto_m',array(
					'luetut'=>$luetut,
					'toteutuneet'=>$toteutuneet,
					'tekijan_nimi'=>$data->tekijan_nimi
		));

		$luetutYht += $luetut;
		$toteutuneetYht += $toteutuneet;
  	}
  ?>
  <tfoot>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo $this->sprint($luetutYht); ?></th>
  <th><?php echo $this->sprint($toteutuneetYht); ?></th>
  </tr>
  </tfoot>
  </table>

   </div>
  </div>

<?php endif; ?>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#yhtveto").submit();
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
