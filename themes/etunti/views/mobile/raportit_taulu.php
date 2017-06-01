<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?=Yii::t('main', 'Raporti')?> (<?=$raporti_tyyppi?>)</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-3">
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
				if(isset($_POST['aktiivinen']))
				$selectedValues = array($_POST['aktiivinen']=> Array('selected' => 'selected'));
		
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
				(isset($_POST['aktiivinen']))? $aktiivinen = $_POST['aktiivinen'] : $aktiivinen = 1;
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatLista( 
						$name_tyontekijat, // name
						'null', //class
						'tyontekijat', // id
						Yii::app()->request->getPost('tekijaPaaSivulla'), //selected
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


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field select">
				<select name="raporti_tyyppi">
				 <?php if(Yii::app()->request->getPost('raporti_tyyppi')): ?>
				 <option value="<?=$raporti_tyyppi?>"><?=$raporti_tyyppi?></option>
				 <?php endif; ?>
				 <option value="Luetut"><?=Yii::t('main', 'Luetut')?></option>
				 <option value="Toteutuneet"><?=Yii::t('main', 'Toteutuneet')?></option>
				</select>
                            <i class="arrow double"></i>
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
				if(isset($_POST[$sarake])) $postvalue = $_POST[$sarake]; else $postvalue='';
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
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xlsx">
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="pdf">
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
 <div class="table-responsive raporti_taulu">
<?php
  $tb = '
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th>'.Yii::t('main', 'Työntekijä').'</th>
  <th>'.Yii::t('main', 'Kohde').'</th>
  <th>'.Yii::t('main', 'Pvm').'</th>
  <th>'.Yii::t('main', 'Aloitus').'</th>
  <th>'.Yii::t('main', 'Lopetus').'</th>
  <th>'.Yii::t('main', 'Kesto').'</th>
  <th>'.Yii::t('main', 'Viesti').'</th>
  </tr>
  </thead>';
  foreach($model as $data)
  {
	$tb .= $this->renderPartial('_raportit_taulu', array( 
			'data' => $data, 'from' => $from, 'to' => $to, 'raporti_tyyppi' => $raporti_tyyppi, 'osoite' => $osoite 
	), true);
  }
  $tb .= '</table>';
  echo $tb;
?>

<?php
  if (!file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain )) {
 	mkdir( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain, 0777, true );
  }

  			$tiedosto = 'temp_raporti_'.str_replace(" ", "_", Yii::app()->user->nimi);
  			$path = 'tiedostot/temp/'.Yii::app()->user->domain.'/';

			// <-- Poistetaan edelliset
			if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.html' ))
			{
			 	unlink($path.$tiedosto.'.html');
			}
			if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.xlsx' ))
			{
			 	unlink($path.$tiedosto.'.xlsx');
			}
			if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.doc' ))
			{
			 	unlink($path.$tiedosto.'.doc');
			}
			if (file_exists( Yii::app()->basePath.'/../tiedostot/temp/'.Yii::app()->user->domain.'/'.$tiedosto.'.pdf' ))
			{
				unlink($path.$tiedosto.'.pdf');
			}
			//     Poistetaan edelliset -->

$c = '<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
*
{
           margin:0px;
           padding:0;
           font-family:Arial;
           font-size:9pt;
           color:#000;
}
body
{
           width:100%;
           font-family:Arial;
           font-size:9pt;
           margin:0;
           padding:0;
}
.table {
	    width: 100%;
	    max-width: 100%;
	    border-collapse: 
	    collapse; border-spacing: 0; 
}
.table th,
.table td {
  padding: 3px 5px;
  vertical-align: top;
  border-top: 1px solid #333333;
}
</style>
</head>
<body>';
$c .= preg_replace("/(?=\>\s+\n|\n)+(\s+)/", '', $tb);
$c .= '</body></html>';


file_put_contents($path.$tiedosto.'.html', $c);
?>
 </div>
</div>


   </div>
  </div>
</div>



<script type="text/javascript">
$(document).ready(function(){


  $(".submitForm").on('click', function(e){
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
