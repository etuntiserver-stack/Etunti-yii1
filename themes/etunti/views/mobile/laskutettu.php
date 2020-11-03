<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p20"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Laskutettavat kohteet'); ?> 
	      </h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">

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
						(isset($_GET['tekijaPaaSivulla']))? $_GET['tekijaPaaSivulla']: '', //selected
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
				if(isset($_GET[$sarake])) 			$postvalue = $_GET[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
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
				$placeholder = 'Osoite';
				if(isset($_GET[$sarake])) 			$postvalue = $_GET[$sarake]; 
				else if(isset(Yii::app()->session[$sarake])) 	$postvalue = Yii::app()->session[$sarake]; 
				else $postvalue='';				
		 	        $site[0]->autocompleteFor($mod, $sarake, $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->

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
    if(isset($_GET['siivousPaaSivulla']))
    {
       echo '<option value="'.$_GET['siivousPaaSivulla'].'">'.$_GET['siivousPaaSivulla'].'</option>';

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

   			    <input type="text" name="fromP" id="from" class="gui-input datepickerFI" value="<?php if(isset($_GET['fromP']) and !empty($_GET['fromP'])) echo date('d.m.Y', strtotime($_GET['fromP'])); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
		        <?php if(isset($_GET['asiakasryhma'])) echo '<input type="hidden" id="asiakasryhma" value="'.$_GET['asiakasryhma'].'">'; ?>
                        <div class="section">
                          <label class="field select">
			  <?php
					$list = array();
			      		$l = Valikkoot::model()->findAll(" select_type='asiakas_ryhma_real' ",array('order' => "select_type"));
					foreach($l as $v)
					$list[$v->id] = $v->value;
			
					if(count($list) > 0)
					{
			        	echo CHtml::dropDownList('asiakasryhma', 'asiakasryhma', $list,
					array('empty'=>'Valitse ryhmä','class'=>'form-control form-group', 'id'=>'ryhmaSelect'));
					}
			  ?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="toP" id="to" class="gui-input datepickerFI" value="<?php if(isset($_GET['toP']) and !empty($_GET['toP'])) echo date('d.m.Y', strtotime($_GET['toP'])); ?>">
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
			     if(isset($_GET['laskutettu']) and $_GET['laskutettu'] == '1')
			     echo '<option value="1">Laskutettu</option>';
			     if(isset($_GET['laskutettu']) and $_GET['laskutettu'] == '3')
			     echo '<option value="3">Laskuttamatta</option>';
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
  <table class="table table-bordered table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'T'); ?></th>
  <th><?php echo Yii::t('main', 'Versio / GPS'); ?></th>
  <th><?php echo Yii::t('main', 'Pvm'); ?></th>
  <th class="text-center" data-toggle="tooltip" title="Tästä näkyy aloitus ja lopetus etäisyydet kilometri tarkkuudella merkitystä työkohteesta."><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Er.'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Työvuorot'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <?php endif; ?>
  
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'NFC TAG'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Hyväksytty'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
  <th><center><?php echo Yii::t('main', 'Laskutettu'); ?> <input type="checkbox" id="valitsekaikki"></center></th>
  <th class="text-center" data-toggle="tooltip" title="<?php echo Yii::t('main', 'Poista'); ?>"><?php echo Yii::t('main', '<i class="fa  fa-question-circle"></i>'); ?></th>
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
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>

  <div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

  <input type="hidden" id="dataChange" >

<script type="text/javascript">
$(document).ready(function(){

$("#valitsekaikki").click(function(){
  if( $(this).prop("checked") == true ){
     $(".chckbxHyvaksynta").each(function() {
	if( $(this).prop("checked", false) ){ $(this).trigger('click'); }
     });
  }
  if( $(this).prop("checked") == false ){
     $(".chckbxHyvaksynta").each(function() {
	if( $(this).prop("checked", true) ){ $(this).trigger('click'); }
     });
  }
});

$(".haemob").click(function(){
	$("#mobForm").submit();
});

if($("#asiakasryhma").val()){ $("#ryhmaSelect").val($("#asiakasryhma").val()); }

$(document).delegate(".show_erittelyt", "click", function(){
    var mob_id = $(this).attr('mob_id');
    var tv_id = $(this).attr('tv_id');
    var modal_content = '';
	$.ajax({
	      url: 'index',
	      type: "POST",
	      data: { geterittelyt : "true", tv_id : tv_id, mob_id : mob_id },
	      async: false,
	      	success: function(data){
			modal_content = JSON.parse(data)
	  	  	//console.log(data);
	      	},
	  	error:function(data){
			window.location.href=location.protocol + "//" + location.host + '/index.php';
	  	}
	});

    $('#showres').modal().html(''+
    '<div class="modal-dialog modal-md" id="myModal">' +
    '<div class="modal-content">' +
      '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
        '<h4 class="modal-title">Työerittelyt</h4>' +
      '</div>' +
      '<div class="modal-body">'+ modal_content +'</div>' +
    '</div>' +
    '</div>');
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
      var this_tr = $(this).closest("tr");

      if(label)
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/laskutettu',
           type: "POST",
	   data: { id : thisID[1], ajax : "true", las : "1", tot : tot },
           success: function(data){
		console.log(data);
		this_tr.hide(370);
/*
		   $('#ilmoitusMerkkitysta').html('<div class="alert bg-success">Kohde merkitty laskutetuksi.</div>').show();
		setTimeout(function() { 
			$('#ilmoitusMerkkitysta').hide('slow');
		}, 5000);
*/

           }
        });
      } else {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/laskutettu',
           type: "POST",
	   data: { id : thisID[1], ajax : "true", las : "0", tot : tot },
           success: function(data){
		console.log(data);
/*
		   $('#ilmoitusMerkkitysta').html('<div class="alert bg-warning">Kohteen laskutusmerkintä poistettu.</div>').show();
		setTimeout(function() { 
			$('#ilmoitusMerkkitysta').hide('slow');
		}, 5000);
*/
		
           }
        });
      }

  });
});





});
</script>


