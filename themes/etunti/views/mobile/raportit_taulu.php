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
		   		$site = Yii::app()->createController('Site');
		   		$tyontekiatLista = $site[0]->tyontekiatListaNoMulti( 
						'tekijaPaaSivulla', // name
						'gui-input', //class
						null, // id
						Yii::app()->request->getPost('tekijaPaaSivulla'), //selected
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


<div class="admin-form">
  <div class="panel-header">
      <div class="row">
       <div class="col-sm-12">
        <div class="pull-right">
    	  <button class="btn btn-primary myBgColors submitPrintSivuLuetut"><i class="fa fa-print" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitExcelluetut"><i class="fa fa-file-excel-o" aria-hidden="true"></i></button>
    	  <button class="btn btn-primary myBgColors submitPDFluetut"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></button>
        </div>
       </div>
      </div>
  </div>
  <div class="panel heading-border">

   <div class="panel-body">

<div class="row">
 <div class="table-responsive raporti_taulu">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Kohde'); ?></th>
  <th><?php echo Yii::t('main', 'Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Viesti'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_raportit_taulu',
	'viewData' => array( 'from' => $from, 'to' => $to, 'raporti_tyyppi' => $raporti_tyyppi, 'osoite' => $osoite ),
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



<script type="text/javascript">
$(document).ready(function(){

  $('.submitPDFluetut').click(function(){
	var raporti_taulu = $(".raporti_taulu").html();
	alert(raporti_taulu)
  });

  $(".haemob").click(function(){
	$("#mobForm").submit();
  });

});
</script>
