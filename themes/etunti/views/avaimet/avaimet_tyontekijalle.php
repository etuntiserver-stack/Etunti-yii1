<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'Avaimet työvuoroittain'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/avaimet/create',array('class'=>'btn btn-default fa fa-plus')); ?>
<?php /*
	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
	   ?>
	 </div>
virtual TV takia
*/ ?>
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?=date("d.m.Y", strtotime($from))?>" placeholder="<?php echo Yii::t('main', 'Mistä'); ?>...">
                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?=date("d.m.Y", strtotime($to))?>" placeholder="<?php echo Yii::t('main', 'Mihin'); ?>...">
                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				if(isset($_GET[$sarake])) 			
				$postvalue = $_GET[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod,array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Kohteet';
				$sarake = 'osoite';
				$placeholder = 'Kohde';
				if(isset($_GET[$sarake])) 			
				$postvalue = $_GET[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Tyontekijat';
				$sarake = 'tekijan_nimi';
				$placeholder = 'Työntekijät';
				if(isset(Yii::app()->session['tekijan_nimi']))  $postvalue = Yii::app()->session['tekijan_nimi']; 
				else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, array('tekijan_nimi', 'sukunimi'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>



                </div>
              </div>
            </div>
	    </form>

	    <h3>Avaimien siirto</h3>
   	    <form id="mobForm2" action="#" class="form-inline" method="POST">
	     <div class="form-group">
		<?php
		$criteria = new CDbCriteria();
		$criteria->condition = " aktiivinen=1 ";

			// <-- Return order etu ja sukunimella
			$criteria = $site[0]->etuSukunimiCriteria($criteria);
			//     Return order etu ja sukunimella -->

			// <-- Tyoryhmat
			$site = Yii::app()->createController('Site');
			$tt = Yii::app()->createController('Tyontekijat');
			$tt_arr = $tt[0]->TyoryhmatTyontekijatHelper(null);
			$ids = implode(",", $tt_arr);
			if( count($tt_arr) > 0 ){
	        		$criteria->addCondition (' id IN ('.$ids.') ');
			}
			//    Tyoryhmat -->

		$tt = Tyontekijat::model()->findAll($criteria);
		?>
		<?php echo CHtml::dropDownList('tyontekija', 'tyontekija', CHtml::listData($tt, 'id', 'FullName'), 
		array('empty'=>'Valitse työntekijä', 'class'=>'form-control')); 
		?>
	     </div>
	     <div class="form-group">
		<select class="form-control" name="sijainti_omatekstti" id="Avaimet_sijainti_omatekstti">
		<option value="1">Kirjoittamalla oma sijainti</option>
		<option value="0">Valikon mukaan</option>
		</select>
	     </div>
	     <div class="form-group">
		<div id="sijainti_rakenne"></div>
	     </div>
	     <div class="form-group">
		<input type="submit" class="btn btn-primary btn-block submitFormTwo myBgColors" value="<?php echo Yii::t('main', 'Tallenna'); ?>">
	     </div>

	    </form>
	    <br>
        <!-- loppu: .tray-center -->
        </div>


<script type="text/javascript">
$(document).ready(function(){
 function sijainti(){
	if( $('#Avaimet_sijainti_omatekstti').val() == '0' ){
		$('#sijainti_rakenne').html('' +
			'<select class="form-control" name="sijainti" id="Avaimet_sijainti">' +
			'<option value="1">Toimistolla</option>' +
			'<option value="2">Palautettu asiakkaalle</option>' +
			'<option value="3">Työntekijällä</option>' +
			'</select>' 
		);
	}
	if( $('#Avaimet_sijainti_omatekstti').val() == '1' ){
		$('#sijainti_rakenne').html('' +
			'<input size="30" maxlength="255" class="form-control" name="sijainti" id="Avaimet_sijainti" type="text" placeholder="Kirjoita sijainti.."/>' 
		);
	}
	if( $("#Avaimet_sijainti").val() == '2' ){
		$('.palautetu_asiakkaalle_pvm').show(370);
	} else {
		$('.palautetu_asiakkaalle_pvm').hide(370);
	}
 }
 sijainti();
 $('#Avaimet_sijainti_omatekstti').change(function(){
	sijainti();
 });
 $(document).delegate("#Avaimet_sijainti","change",function(){
	if( $(this).val() == '2' ){
		$('.palautetu_asiakkaalle_pvm').show(370);
	} else {
		$('.palautetu_asiakkaalle_pvm').hide(370);
	}
 });

 $(".submitFormTwo").click(function(e){
	e.preventDefault();
	var checked = false;
	$( ".avainnumero" ).each(function( ) {
		if ($(this).is(':checked')){
	   	  $('form#mobForm2').append('<input type="text" name="avaimet[]" value="' + $(this).val() + '" />');
		  checked = true;
		}
	});
	if(!checked){
		alert('Valitse avain');
		return false;
	}
	$('#mobForm2').submit();	
 });
});
</script>


<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Kohde'); ?></th>
  <th><?php echo Yii::t('main', 'Työvuoro'); ?></th>
  <th><?php echo Yii::t('main', 'Avain'); ?></th>
  <th><?php echo Yii::t('main', 'Avain työntekijällä'); ?></th>
  <th><?php echo Yii::t('main', 'Sijainti'); ?></th>
  </tr>
  </thead>
  <?php 
	foreach($dataAll as $arr){
		$data = $arr['data'];
		$tv = $this->renderPartial('_view_avaimet_tyontekijalle',array(
			'arr' => $arr,
			'data' => $data,
			'kohteet' => (isset($data->kohteet))?$data->kohteet:'',
			'avaimet' => (isset($data->avaimet))?$data->avaimet:'',
			'tt' => (isset($data->tt))?$data->tt:'',
			//'this_id' => $arr['this_id']
		), true);
		echo $tv;
	}
  ?>
  </table>
 </div>
</div>


   </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#mobForm").submit();
});

 $(".kpl").click(function(){
	var asiakkaatPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'index',
           type: "POST",
           data: { "asiakkaatPerSivu" : asiakkaatPerSivu },
           success: function(data){
		var d = JSON.parse(data);
		window.location.reload();

           }
        });
 });

});
</script>
