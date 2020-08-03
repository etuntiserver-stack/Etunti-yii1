<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> 

	 <span class="pull-right montakoRiviaSivulle" style="margin-top:-7px">
	   <?php
	   ($perSivu == 10) ? $defcl10 = 'btn-success' : $defcl10 = 'btn-default';
	   ($perSivu == 50) ? $defcl50 = 'btn-success' : $defcl50 = 'btn-default';
	   ($perSivu == 100) ? $defcl00 = 'btn-success' : $defcl00 = 'btn-default';
	   ($perSivu == 2000) ? $defcl2000 = 'btn-success' : $defcl2000 = 'btn-default';
	   ($perSivu == 10000) ? $defcl10000 = 'btn-success' : $defcl10000 = 'btn-default';

	   echo '<button class="btn '.$defcl10.' kpl" kpl="10" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10 '.Yii::t('main', 'asiakasta sivulla').'">10</button>';
	   echo '<button class="btn '.$defcl50.' kpl" kpl="50" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 50 '.Yii::t('main', 'asiakasta sivulla').'">50</button>';
	   echo '<button class="btn '.$defcl00.' kpl" kpl="100" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 100 '.Yii::t('main', 'asiakasta sivulla').'">100</button>';
	   echo '<button class="btn '.$defcl2000.' kpl" kpl="2000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 2000 '.Yii::t('main', 'asiakasta sivulla').'">2000</button>';
	   echo '<button class="btn '.$defcl10000.' kpl" kpl="10000" data-toggle="tooltip" title="'.Yii::t('main', 'Näytä').' 10000 '.Yii::t('main', 'asiakasta sivulla').'">10000</button>';
	   ?>
	 </span>

	 <i class="glyphicon glyphicon-envelope"></i> <?=((isset($_GET['uusi_tilaus']) and $_GET['uusi_tilaus'] == 1)? Yii::t('main', 'Tilaukset'):Yii::t('main', 'Lista suunnitelluista työvuoroista'))?> 

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
				$placeholder = 'Yritys tai yhteyshenkilö';
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
		        	$tal = [];
				foreach($a as $v){
				$exV = explode("/",$v->value);
				   if(isset($exV[0]) and isset($exV[1]))
				   $tal[$exV[1]] = $exV[0];
				}
				$selectedValues = array(1 => Array('selected' => 'selected'));
				if(isset($_GET['aktiivinen']))
					$selectedValues = array($_GET['aktiivinen'] => Array('selected' => 'selected'));

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
				echo '<option value="'.$k.'" '.((isset($_GET['status']) and is_array($_GET['status']) and in_array($k, $_GET['status']))?'selected':'').'>'.$v.'</option>';

				echo '</select>';
				?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select" style="z-index:0">
				<select class="" id="laskutettu" name="laskutettu">
				<option value="0" <?=((isset($_GET['laskutettu']) and $_GET['laskutettu'] == 0)? 'selected':'') ?>>Laskuttamattomat</option>
				<option value="1" <?=((isset($_GET['laskutettu']) and $_GET['laskutettu'] == 1)? 'selected':'') ?>>Laskutettu</option>
				</select>
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
                        <div class="section">
                          <label class="field select">
				<select class="" id="uusi_tilaus" name="uusi_tilaus">
				<option value="0" <?=((isset($_GET['uusi_tilaus']) and $_GET['uusi_tilaus'] == 0)? 'selected':'') ?>><?=Yii::t('main', 'Työvuorot')?></option>
				<option value="1" <?=((isset($_GET['uusi_tilaus']) and $_GET['uusi_tilaus'] == 1)? 'selected':'') ?>><?=Yii::t('main', 'Tilaukset')?></option>
				</select>
                            <i class="arrow double"></i>
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
                        <div class="section">
                          <label class="field select" style="z-index:0">
				<select class="" id="peruutettu" name="peruutettu">
				<option value="0" <?=((isset($_GET['peruutettu']) and $_GET['peruutettu'] == 0)? 'selected':'') ?>>Voimassa olevat</option>
				<option value="1" <?=((isset($_GET['peruutettu']) and $_GET['peruutettu'] == 1)? 'selected':'') ?>>Peruutetut</option>
				</select>
                            <i class="arrow double"></i>
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
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Tyovuorot">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Tyovuorot">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="<?php echo Yii::app()->request->baseUrl; ?>/index.php/mobile/tulostus" class="form-group" target="_blank" method="POST">
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
  <th><?=Yii::t('main', 'Klo')?></th>
  <th><?=Yii::t('main', 'Asiakas')?></th>
  <th><?=Yii::t('main', 'Kohde')?></th>
  <th><?=Yii::t('main', 'Tilanne')?></th>
  </tr>
  </thead>
  <tbody>
  <?php
	foreach($dataAll as $arr){
		$tv = $this->renderPartial('_lista',array(
			'arr' => $arr
		), true);
		echo $tv;
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
	$(this).prev('textarea').val($('#tableContent').html());
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
	nonSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
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
	nonSelectedText: '<?php echo Yii::t("main", "Tilanteet"); ?>',
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


 $(".kpl").click(function(){
	var asiakkaatPerSivu = $(this).attr('kpl');
        $.ajax({
           url: 'lista',
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
