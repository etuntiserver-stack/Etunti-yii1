<?php
	//$asetukset=Asetukset::model()->findbypk(1);

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo strtoupper(Yii::t('main', 'Laskutuksen automaatio')); ?></h2>


   	    <form id="mobForm" action="luolaskut" class="form-inline" method="GET">
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php $arr = array('henkilo' => 'Yksityishenkilö', 'yritys' => 'Yritys', 'kaikki' => 'Kaikkki'); ?>
				<?php echo CHtml::dropDownList('filter_tyyppi', 'filter_tyyppi', $arr, array('empty'=>'Valitse tyyppi', 'class'=>'gui-input')); ?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" name="from" class="gui-input datepickerFI" placeholder="Mistä">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
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
                          <label class="field select">
				<?php
				$criteria=new CDbCriteria;
				$criteria->group = "kaupunki";
				$criteria->condition = " 
					kaupunki!=''
				";
				?>
				<?php echo CHtml::dropDownList('filter_postitoimipaikka', 'filter_postitoimipaikka', CHtml::listData(Asiakkaat::model()->findAll($criteria), 'kaupunki', 'kaupunki'), 
				array('empty'=>'Valitse postitoimipaika', 'class'=>'gui-input')); ?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" name="to" class="gui-input datepickerFI" placeholder="Mihin">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field select">
			    <select name="viestikenta[]" id="viestikenta" class="gui-input mult" multiple>
			     <option value="pvm" selected><?php echo Yii::t('main', 'Päivämäärä'); ?></option>
			     <option value="osoite" selected><?php echo Yii::t('main', 'Osoite'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
				$criteria=new CDbCriteria;
				$criteria->group = "tyoryhma";
				$criteria->condition = " 
					tyoryhma!='' and tyoryhma IS NOT NULL
				";
				?>
				<?php echo CHtml::dropDownList('filter_tyoryhma', 'filter_tyoryhma', CHtml::listData(Asiakkaat::model()->findAll($criteria), 'tyoryhma', 'valikkotyoryhma'), 
				array('empty'=>'Valitse työryhmä', 'class'=>'gui-input')); ?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field select">
			    <select name="alvsis" id="alvsis" class="gui-input">
			     <option value="0"><?php echo Yii::t('main', 'Hinnat ALV 0%'); ?></option>
			     <option value="1"><?php echo Yii::t('main', 'Hinnat sis. ALV'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field select">
			    <select name="decimal" id="decimal" class="gui-input">
			     <option value="2">2 decimalia</option>
			     <option value="3">3 decimalia</option>
			     <option value="4">4 decimalia</option>
			     <option value="5">5 decimalia</option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
				<?php
				$criteria=new CDbCriteria;
				$criteria->order = "value";
				$criteria->condition = " 
					select_type='asiakas_ryhma_real'
				";
				?>
				<?php echo CHtml::dropDownList('filter_asiakasryhma', 'filter_asiakasryhma', CHtml::listData(Valikkoot::model()->findAll($criteria), 'id', 'value'), 
				array('empty'=>'Valitse asiakasryhmä', 'class'=>'gui-input')); ?>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" name="paivays" class="gui-input datepickerFI" placeholder="Päiväys" required>
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">
   			                      <input type="text" name="toimituspaiva" class="gui-input datepickerFI" placeholder="Toimituspäivä" required>
                            <label for="toimituspaiva" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>


                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="tunnit" id="tunnit" class="gui-input">
			     <option value="tv"><?php echo Yii::t('main', 'Työvuorot'); ?></option>
			     <option value="mob"><?php echo Yii::t('main', 'Tunnit'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" name="erapaiva" class="gui-input datepickerFI" placeholder="Eräpäivä">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
		      </div>
                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="<?php echo Yii::t('main', 'Esikatseluun'); ?>">
		      </div>
                    </div>

                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>




  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <legend>Muokatut lähetteet</legend>
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?=Yii::t('main', 'Luotu')?></th>
  <th><?=Yii::t('main', 'Asiakas')?></th>
  <th><?=Yii::t('main', 'Aikaväli')?></th>
  <?php /* <th><?=Yii::t('main', 'Tilanne')?></th> */ ?>
  <th></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_muokatut_lahetteet',
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

  ));  ?>
  </table>
</div>

   </div>
  </div>



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

$('.mult').multiselect({
	//inheritClass: true,
	//enableFiltering: true,
        includeSelectAllOption: true,
	nonSelectedText: '<?php echo Yii::t("main", "Viestikentän sisältö"); ?>',
	selectAllText: '<?php echo Yii::t("main", "Valitse kaikki"); ?>',
	allSelectedText: '<?php echo Yii::t("main", "Viestikentä, Kaikki"); ?>',
	nSelectedText: '<?php echo Yii::t("main", "valittu"); ?>',
	numberDisplayed: 0,
	buttonWidth: '100%',
});

});
</script>
