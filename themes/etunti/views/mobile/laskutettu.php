<?php

echo Yii::app()->request->getPost('laskutettu');
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p20"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Laskutettavat kohteet'); ?> 
	      </h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


				<?php
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tekijaPaaSivulla', // name
						'gui-input', //class
						null, // id
						Yii::app()->session['tekijaPaaSivulla'], //selected
						1 // aktiivinen
				);
				echo $tyontekiatLista;
				?>


                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Asiakkaat';
				$sarake = 'yrityksen_nimi';
				$placeholder = 'Asiakas';
				if(isset(Yii::app()->session[$sarake]))	$postvalue = Yii::app()->session[$sarake]; 
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

   			    <input type="text" class="gui-input" name="etsi_kohteet" value="<?php if(isset(Yii::app()->session['etsi_kohteet'])) echo Yii::app()->session['etsi_kohteet']; ?>" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">


   <?php
   $list = array();
   $l = Valikkoot::model()->findAll(" select_type='siivous' ",array('order' => "select_type"));

    echo '<select class="gui-input" name="siivousPaaSivulla">';
    if(isset(Yii::app()->session['siivousPaaSivulla']))
    {
       echo '<option value="'.Yii::app()->session['siivousPaaSivulla'].'">'.Yii::app()->session['siivousPaaSivulla'].'</option>';

    } else {
       echo '<option value="">'.Yii::t('main', 'Kohteen työnimike').'</option>';
    }

       echo '<option value="">Kaikki</option>';

    foreach($l as $key=>$val){
    echo '<option value="'.$val->value.'">'.$val->value.'</option>';

    }
    echo '</select>';
   ?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>


                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="fromP" id="from" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['fromP'])) echo date('d.m.Y', strtotime(Yii::app()->session['fromP'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="toP" id="to" class="gui-input datepickerFI" value="<?php if(isset(Yii::app()->session['toP'])) echo date('d.m.Y', strtotime(Yii::app()->session['toP'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="laskutettu" class="gui-input">
			     <?php 
			     if(isset(Yii::app()->session['laskutettu']) and Yii::app()->session['laskutettu'] == '1')
			     echo '<option value="1">Laskutettu</option>';
			     if(isset(Yii::app()->session['laskutettu']) and Yii::app()->session['laskutettu'] == '3')
			     echo '<option value="0">Laskuttamatta</option>';
			     ?>
			     <option value=""><?php echo Yii::t('main', 'Tilanne'); ?></option>
			     <option value="1"><?php echo Yii::t('main', 'Laskutettu'); ?></option>
			     <option value="3"><?php echo Yii::t('main', 'Laskuttamatta'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
        	        <input type="submit" name="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Hae'); ?>">
		      </div>

                    </div>
		    <div class="alert alert-default"><?php echo Yii::t('main', 'laskutettuAllaOlevaIlmoitus'); ?></div>
		    <div id="ilmoitusMerkkitysta"></div>
		

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
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'Versio'); ?></th>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Kartta'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'TAG'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th class="col-sm-4"><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th class="col-sm-2"><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th class="col-sm-2"><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Laskutettu'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
	'viewData' => array("sivu" => "laskutettu" ),
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


  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.mask.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mobile.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>

  <input type="hidden" id="dataChange" >

<script type="text/javascript">
$(document).ready(function(){


$(".haemob").click(function(){
	$("#mobForm").submit();
});

/*
  $(".chckbxHyvaksynta").bootstrapSwitch({
	size: "mini",
	//onColor: "success",
	//offColor: "warning",
	onText: "Kyllä",
	offText: "Ei"
  });
*/

$('.chckbxHyvaksynta').on('click', function(event, state) {
  $(this).each(function() {
      var label = $(this).prop("checked");
      var tot = $(this).attr("tot");
      var thisID = $(this).attr("id").split("_");

      if(label)
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/laskutettu',
           type: "POST",
	   data: { id : thisID[1], ajax : "true", las : "1", tot : tot },
           success: function(data){
		console.log(data);

		   $('#ilmoitusMerkkitysta').html('<div class="alert bg-success">Kohde merkitty laskutetuksi.</div>').show();
		setTimeout(function() { 
			$('#ilmoitusMerkkitysta').hide('slow');
		}, 5000);


           }
        });
      } else {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/laskutettu',
           type: "POST",
	   data: { id : thisID[1], ajax : "true", las : "0", tot : tot },
           success: function(data){
		console.log(data);

		   $('#ilmoitusMerkkitysta').html('<div class="alert bg-warning">Kohteen laskutusmerkintä poistettu.</div>').show();
		setTimeout(function() { 
			$('#ilmoitusMerkkitysta').hide('slow');
		}, 5000);

		
           }
        });
      }

  });
});





});
</script>


