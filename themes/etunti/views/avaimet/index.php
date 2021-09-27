<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

// <--Konvertointi pois käytöstä 1.04.2018 jälkeen
$k = Kohteet::model()->findAll("avain!=''");
foreach($k as $item){
	$tid = '';
	$kenella = explode("//", $item->kenella_on_avain);
	if(isset($kenella[0])) $tid = $kenella[0];
	//echo $item->id.' - '.$item->avain.' <b>'.$tid.'</b><br>';
	
	$a = new Avaimet;
	$a->avainnumero = $item->avain;
	$a->kohde = $item->id;
	$a->tid = $tid;
	$a->status = 0;
	if(!empty($tid)){
	$a->sijainti = 'Työntekijällä';
	}
	if(!$a->save()){
		print_r($a->getErrors());
	} else {
		Kohteet::model()->updateByPk($item->id, array("avain" => "", "kenella_on_avain" => ""));
	}
	
}
if(count($k) > 0){
	Yii::app()->user->setFlash('success', "Avaimet on konvertoitu");
}
// <--Konvertointi -->
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'AVAIMET'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/avaimet/create',array('class'=>'btn btn-default fa fa-plus')); ?>

	 <div class="pull-right montakoRiviaSivulle">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';
	   ($perSivu == 2000) ? $defcl2000 = 'btn-success' : $defcl2000 = 'btn-default';
	   ($perSivu == 4000) ? $defcl4000 = 'btn-success' : $defcl4000 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
	   echo '<button class="btn '.$defcl2000.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
	   echo '<button class="btn '.$defcl4000.' kpl" kpl="4000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 4000 '.Yii::t('main', 'asiakasta sivulla').'">4000</button>';
	   ?>
	 </div>
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

                      <div class="col-md-3">
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
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="avainnumero"  class="gui-input" value="<?php if(isset($_GET['avainnumero'])) echo $_GET['avainnumero']; ?>" placeholder="<?php echo Yii::t('main', 'Avain nro..'); ?>..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-file-text-o"></i>
                            </label>
                          </label>
                        </div>
                      </div>



                      <div class="col-md-2 col-sm-offset-1">
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
			<div style="min-width: 400px">
				<?php

					$site = Yii::app()->createController('Site');
					$tyontekiatLista = $site[0]->workerListSelect2(
						'tyontekija', // name
						'form-control', //class
						'tyontekija', // id
						'', // selected
						1, // active workers or not,
						false, // multiple select or not
					);
					echo $tyontekiatLista;
				?>
			</div>
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

 $(".select2").select2();

 $(".submitFormTwo").click(function(e){
	e.preventDefault();
	var checked = false;
	$( ".avainnumero" ).each(function( ) {
		if ($(this).is(':checked')){
	   	  $('form#mobForm2').append('<input type="hidden" name="avaimet[]" value="' + $(this).val() + '" />');
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
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
  <th><?php echo Yii::t('main', 'Avainnumero'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Kohde'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Ovikoodi'); ?></th>
  <th><?php echo Yii::t('main', 'Sijainti'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>
 </div>
</div>


   </div>
  </div>
</div>


<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.dataTables.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#mobileTable').DataTable({
        "bPaginate": false,
        "bFilter": false,
        "bInfo": false
    });
});

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
