<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?=Yii::t('main', 'Lista työvuoroista')?> </h2>



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
				$placeholder = 'Yritys';
				if(isset($_GET[$sarake])) $postvalue = $_GET[$sarake]; else $postvalue='';
		 	        $site[0]->autocompleteFor($mod, array('yrityksen_nimi','yhteyshenkilo'), $placeholder, $postvalue);
			    ?>
			    <!-- Autocomplete -->


                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field prepend-icon">

			    <!-- Autocomplete -->
			    <?php
	   			$site = Yii::app()->createController('Site');
				$mod = 'Kohteet';
				$sarake = 'osoite';
				$placeholder = 'Osoite';
				if(isset($_GET[$sarake])) $postvalue = $_GET[$sarake]; else $postvalue='';
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
                          <label class="field select">

				<?php 
				$a = Valikkoot::model()->findAll(" select_type='aktiivinen' ");
		        	$tal = '';
				foreach($a as $v){
				$exV = explode("/",$v->value);
				   if(isset($exV[0]) and isset($exV[1]))
				   $tal[$exV[1]] = $exV[0];
				}
				$selectedValues = 1;
				if(isset($_GET['aktiivinen']))
				$selectedValues = array($_GET['aktiivinen']=> Array('selected' => 'selected'));
		
				echo CHtml::dropDownList('aktiivinen','aktiivinen', $tal, 
				array('class'=>'gui-input aktiivinen','options' => $selectedValues)) 
				?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field">
                            <div id="tekijat_result"> 
				<?php
				$name_tyontekijat = 'tekijaPaaSivulla';
				(isset($_GET['aktiivinen']))? $aktiivinen = $_GET['aktiivinen'] : $aktiivinen = 1;
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
						$name_tyontekijat, // name
						'null', //class
						'tyontekijat', // id
						(isset($_GET['tekijaPaaSivulla']))? $_GET['tekijaPaaSivulla']: array(), //selected
						$aktiivinen// aktiivinen
				);
				echo $tyontekiatLista;
				?>
                            </div> 
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                      </div>


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php 
		   		$tilanteet = $this->tilanteet();
		   		$tilanteet[0] = 'Ei määritetty';
				echo '<select class="" id="status" name="status[]" multiple>';
		   		foreach($tilanteet as $k=>$v)
				echo '<option value="'.$k.'" '.((isset(Yii::app()->session['status']) and is_array(Yii::app()->session['status']) and in_array($k, Yii::app()->session['status']))?'selected':'').'>'.$v.'</option>';

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

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?=$from?>" placeholder="<?php echo Yii::t('main', 'Mistä'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?=$to?>" placeholder="<?php echo Yii::t('main', 'Mihin'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
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


        <!-- loppu: .tray-center -->
        </div>

	<div id="odota"></div>

<div class="admin-form">
  <div class="panel-header">
      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
         <div class="form-inline">
    	  <!--<button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>-->
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="GET">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Tyovuorot">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="GET">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Tyovuorot">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="GET">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Tyovuorot">
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
 <div class="table-responsive raporti_taulu" id="tableContent">

  <table class="table table-striped" id="mobileTable">
  <thead>
  <tr>
  <th><?=Yii::t('main', 'Työntekijä')?></th>
  <th><?=Yii::t('main', 'Päivämäärä')?></th>
  <th><?=Yii::t('main', 'Kohde')?></th>
  <th><?=Yii::t('main', 'Tilanne')?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  foreach($model as $data)
  {
	echo $this->renderPartial('_lista', array( 
			'data' => $data
	), true);
  }
  ?>
  </tbody>
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


  $(".submitForm").on('click', function(e){
	$('.mobileTable').addClass('table-bordered');
	$(this).prev('textarea').val(JSON.stringify($('#tableContent').html()));
	$(this).closest('form').submit();
	e.preventDefault();
  });


  $(".haemob").click(function(){
	$("#mobForm").submit();
  });

multi();
function multi(){
  $('#tyontekijat').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
  });
}

  $('#status').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Tyhjä"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Tilanteet"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
  });

  $(".aktiivinen").change(function(){
	var thisVal = parseInt($(this).val());
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyontekijat/is_aktiivinen_multiple',
           type: "GET",
	   data: { name_tyontekijat : '<?=$name_tyontekijat?>', value : thisVal, selected : null },
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		$('#tekijat_result').html(data);
		multi();
           }
        });
  });

});
</script>
