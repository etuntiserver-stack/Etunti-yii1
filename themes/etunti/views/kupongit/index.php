<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


        <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Alennuskoodit'); ?>
	<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/kupongit/create',array('class'=>'btn btn-default fa fa-plus','data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää kupongi') )); ?> 
	</h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

			      <input type="number" class="gui-input" name="kupongin_maara" placeholder="Alennuskoodi määrä..." required>
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>


                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="Kupongit[maara_tyyppi]" id="maara_tyyppi">
       				<option value="euro"><?php echo Yii::t('main', 'Euro'); ?></option>
       				<option value="prosentti"><?php echo Yii::t('main', 'Prosentti'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

		      </div>

                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

			      <input type="text" class="gui-input datepickerFI" name="Kupongit[voimassa]" placeholder="Voimassa päivämäärä..." required>
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>
                        <div class="section">
                          <label class="field prepend-icon">

			      <input type="number" class="gui-input maara" name="maara" placeholder="Syötä määrä...">
			      <div id="maara_check_result"></div>
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>
		      </div>

                      <div class="col-md-3">
                        <div class="section">
                          <label class="field prepend-icon">

			      <input type="number" class="gui-input" name="merkkien_maara" placeholder="Merkkien määrä...">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>			
                        </div>

                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="Kupongit[jatkuva]" id="maara_tyyppi">
       				<option value="0"><?php echo Yii::t('main', 'Kertakäyttöinen'); ?></option>
       				<option value="1"><?php echo Yii::t('main', 'Useampikäyttöinen'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
		      </div>

                      <div class="col-md-3">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Luo allenuskoodit'); ?>">
		      </div>

                    </div>



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
  <th><?php echo Yii::t('main', 'Luotu'); ?></th>
  <th><?php echo Yii::t('main', 'Alennuskoodi'); ?></th>
  <th><?php echo Yii::t('main', 'Voimassa'); ?></th>
  <th><?php echo Yii::t('main', 'Muoto'); ?></th>
  <th><?php echo Yii::t('main', 'Useampikäyttöinen'); ?></th>
  <!--<th><?php echo Yii::t('main', 'Käytetty'); ?></th>-->
  <th><?php echo Yii::t('main', 'Toimitettu'); ?></th>
  <th><?php echo Yii::t('main', 'Lähetä'); ?></th>
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

<?php
	$asetukset = Asetukset::model()->findByPk(1);
	$alennus_max_euro = $asetukset->alennus_max_euro;
	$alennus_max_prosentti = $asetukset->alennus_max_prosentti;
?>

<script type="text/javascript">
$(document).ready(function(){

if($("#akt").val())
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);

	var alennus_max_euro = parseInt("<?=$alennus_max_euro?>");
	var alennus_max_prosentti = parseInt("<?=$alennus_max_prosentti?>");

 $(document).delegate(".maara","keyup",function(){

	if( ($("#maara_tyyppi").val() == 'euro' ) && ($(this).val() > alennus_max_euro) ){
		$("#maara_check_result").html('<span class="required">Maksimi arvo on '+ alennus_max_euro +'. Tarkista asetukset</span>');
		$('.haemob').attr('disabled', 'yes');
	} else if($("#maara_tyyppi").val() == 'euro' ) {
		$('.haemob').removeAttr('disabled');
		$("#maara_check_result").html('');
	}

	if( ($("#maara_tyyppi").val() == 'prosentti' ) && ($(this).val() > alennus_max_prosentti) ){
		$("#maara_check_result").html('<span class="required">Maksimi arvo on '+ alennus_max_prosentti +'. Tarkista asetukset</span>');
		$('.haemob').attr('disabled', 'yes');
	} else if($("#maara_tyyppi").val() == 'prosentti' ) {
		$('.haemob').removeAttr('disabled');
		$("#maara_check_result").html('');
	}

 });


});
</script>
