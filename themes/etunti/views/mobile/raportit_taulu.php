<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?=Yii::t('main', 'Tuntiraportti')?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="GET">
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
		        	$tal = array();
				foreach($a as $v){
				$exV = explode("/",$v->value);
				   if(isset($exV[0]) and isset($exV[1]))
				   $tal[$exV[1]] = $exV[0];
				}
				$selectedValues = 1;
				if(isset($_GET['aktiivinen'])){
					$selectedValues = array($_GET['aktiivinen']=> array('selected' => 'selected'));
				}
				echo CHtml::dropDownList('aktiivinen','aktiivinen', $tal, 
				array('class'=>'gui-input aktiivinen'));
				?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field select">

				<?php 
				$a = Valikkoot::model()->findAll(" select_type='tyoryhma' ");
		        	$tal = array(''=>'Valitse');
				foreach($a as $v){
				   $tal[$v->value] = $v->value;
				}
				$selectedValues = 1;
/*
				if(isset($_GET['aktiivinen'])){
					$selectedValues = array($_GET['aktiivinen']=> array('selected' => 'selected'));
				}
*/		
				echo CHtml::dropDownList('tyoryhma','tyoryhma', $tal, 
				array('empty' => 'Valitse työryhmä', 'class'=>'gui-input tyoryhma'));
				?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
			<p class="text-danger">Työryhmässä näytetään siihen kuuluvat työntekijät</p>
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
						(isset($_GET['tekijaPaaSivulla']))?$_GET['tekijaPaaSivulla']:'', //selected
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
				 <option value="Suunnitellut" <?=(isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Suunnitellut')?'selected':''?>><?=Yii::t('main', 'Suunnitellut')?></option>
				 <option value="Luetut" <?=(isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Luetut')?'selected':''?>><?=Yii::t('main', 'Luetut')?></option>
				 <option value="Hyvaksynta" <?=(isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksynta')?'selected':''?>><?=Yii::t('main', 'Hyväksyntä')?></option>
				 <option value="Hyvaksytyt" <?=(isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksytyt')?'selected':''?>><?=Yii::t('main', 'Hyväksytyt')?></option>
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
				if(isset($_GET[$sarake])){ $postvalue = $_GET[$sarake]; } else { $postvalue=''; }
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

<?php if(isset($_GET['aktiivinen'])): ?>
<div class="admin-form">
  <div class="panel-header">
      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
         <div class="form-inline">
    	  <!--<button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>-->
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="doc">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-word-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="ext" value="xls">
	    <input type="hidden" name="fileName" value="Raporti">
	    <input type="hidden" name="from" value="<?=$from?>">
	    <input type="hidden" name="to" value="<?=$to?>">
	    <textarea name="html_content" class="form-control" style="display:none"></textarea>
    	    <button type="submit" class="btn btn-primary myBgColors submitForm"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
	  </form>
	  <form action="tulostus" class="form-group" target="_blank" method="POST">
	    <input type="hidden" name="header" value="<?=$from?>-<?=$to?>">
	    <input type="hidden" name="ext" value="pdf">
	    <input type="hidden" name="fileName" value="Raporti">
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
  <th><?=Yii::t('main', 'Tilanne')?></th>
  <th><?=Yii::t('main', 'Kohde')?></th>
  <th><?=Yii::t('main', 'Pvm')?></th>
  <th><?=Yii::t('main', 'Aloitus')?></th>
  <th><?=Yii::t('main', 'Lopetus')?></th>
  <th><?=Yii::t('main', 'Kesto')?></th>
  <th><?=Yii::t('main', 'Lasku &euro;')?></th>
  </tr>
  </thead>
  <tbody>
  <?php
  $yhteensa = 0;
  $yhteensa_laskusumma = 0;
/*
  if( count($model) == 0 ){
	$data->id = 0;
	$m = $this->tidFromTo_suunnitellut($data->id, $from, $to, $kohde_id);
	echo $this->renderPartial('_raportit_taulu', array( 
			'data' => $data, 'from' => $from, 'to' => $to, 'm' => $m, 'mob_or_tv' => $mob_or_tv
	), true);
  }
*/
  if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Suunnitellut'){
	echo $this->renderPartial('_raportit_taulu', [
		'm' => $dataAll, 'mob_or_tv' => $mob_or_tv
	], true);
  } else {

	foreach($model as $data){
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Luetut')
			$m = $this->tidFromTo_luetut($data->id, $from, $to, $kohde_id);
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksynta')
			$m = $this->tidFromTo_toteutuneet($data->id, $from, $to, $kohde_id, 'Hyvaksynta');
		if( isset($_GET['raporti_tyyppi']) and $_GET['raporti_tyyppi'] == 'Hyvaksytyt')
			$m = $this->tidFromTo_toteutuneet($data->id, $from, $to, $kohde_id, 'Hyvaksytyt');

		echo $this->renderPartial('_raportit_taulu', [
				'data' => $data, 'm' => $m, 'mob_or_tv' => $mob_or_tv
		], true);
		foreach($m as $val){
			if( $mob_or_tv == 'mob' ){
				$aloitus = $val->aloitan;
				$lopetus = $val->loppui;
			}
			if( $mob_or_tv == 'tv' ){
				$data = $val['data'];
				$val = $data;
				$aloitus = $val->alku;
				$lopetus = $val->loppu;
			}
			$yhteensa_laskusumma += (isset($val->laskurivi->hinta))? $val->laskurivi->hinta : 0;
			$yhteensa += strtotime($lopetus)-strtotime($aloitus);
		}
	}
  }
  ?>
  </tbody>
  <tfoot>
   <tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	<td><b><?=Yii::t('main', 'Yhteensä')?></b></td>
	<td><?=($yhteensa > 0)?$this->sprint($yhteensa).'&nbsp;|&nbsp'.$this->num($yhteensa):''?></td>
	<td><?=($yhteensa_laskusumma > 0)?$this->num($yhteensa_laskusumma):''?></td>
   </tr>
  </tfoot>
  </table>


 </div>
</div>


   </div>
  </div>
</div>
<?php endif; ?>

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

$('#mobileTable tfoot tr').prependTo('#mobileTable thead');

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
	nonSelectedText: '<?php echo Yii::t("main", "Valitse työntekijät"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Työntekijät"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
        maxHeight: 300,
  });
}


  $(".aktiivinen, .tyoryhma").change(function(){
	checker();
  });

  function checker(){
    	var aktiivinen = parseInt($('.aktiivinen').val());
    	var tyoryhma = $('.tyoryhma').val();
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/tyontekijat/is_aktiivinen_multiple',
           type: "GET",
	   data: { name_tyontekijat : '<?=$name_tyontekijat?>', aktiivinen : aktiivinen, tyoryhma : tyoryhma, selected : null },
           success: function(data){
		data = JSON.parse(data);
		console.log(data);
		$('#tekijat_result').html(data);
		multi();
           }
        });
  }

});
</script>
