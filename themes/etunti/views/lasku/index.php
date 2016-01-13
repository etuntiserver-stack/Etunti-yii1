<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">




              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-barcode"></i> <?php echo Yii::t('main', 'LASKU'); ?> 
		<?php echo CHtml::link('','/index.php/lasku/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="laskunumero" value="<?php if(isset($_POST['laskunumero'])) echo $_POST['laskunumero']; ?>" placeholder="Laskunumero..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="viitenumero"  class="gui-input" value="<?php if(isset($_POST['viitenumero'])) echo $_POST['viitenumero']; ?>" placeholder="Viitenumero..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2 col-md-offset-6">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Hae</button>
		      </div>
                    </div>





   <div class="form-inline">
     <select class="form-control" id="raportit">
     <option><?php echo Yii::t('main','Raportit'); ?></option>
     <option value="../laskuHistoria/avoimet"><?php echo Yii::t('main','Avoimet laskut'); ?></option>
     <option value="../laskuHistoria/paivakirja"><?php echo Yii::t('main','Lasku päiväkirjа'); ?></option>
     <option value="../laskuHistoria/paakirja"><?php echo Yii::t('main','Lasku pääkirja'); ?></option>
     <option value="../laskuHistoria/maksu_paivakirja"><?php echo Yii::t('main','Maksu päiväkirjа'); ?></option>
     <option value="../laskuHistoria/maksu_paakirja"><?php echo Yii::t('main','Maksu pääkirja'); ?></option>
     <option><?php echo Yii::t('main','ALV lista'); ?></option>
     <option value="../laskuHistoria/admin"><?php echo Yii::t('main','Lasku historia'); ?></option>
     <option value="../laskuHistoria/reskontraluettelo"><?php echo Yii::t('main','Reskontraluettelo'); ?></option>
     </select>
     <button class="btn btn-primary myBgColors" id="haeRaportti"><?php echo Yii::t('main','Hae'); ?></button>
   </div>

                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>


  <div class="panel heading-border">
   <div class="panel-body">

  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Laskunumero'); ?></th>
  <th><?php echo Yii::t('main', 'Viitenumero'); ?></th>
  <th><?php echo Yii::t('main', 'Luotu'); ?></th>
  <th><?php echo Yii::t('main', 'Tilanne'); ?></th>
  <th><?php echo Yii::t('main', 'Tapahtuma pvm'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
  <th><?php echo Yii::t('main', 'Avoinna'); ?></th>
  <th><?php echo Yii::t('main', 'Laskun tyyppi'); ?></th>
  <th></th>
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




<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#mobForm").submit();
});

$("#haeRaportti").click(function() {
	window.location.href=$("#raportit").val();
});

});
</script>
