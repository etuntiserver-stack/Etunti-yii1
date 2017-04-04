<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-default btn-sm" value="<?php echo Yii::t('main', 'ALV raportti'); ?>">
     </form>
   </div>
   <!-- tulostus -->


              <h2 class="myBgColors p10"> <?php echo Yii::t('main', 'Onlinevaraukset'); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">

			    <?php if( Yii::app()->request->getPost('tila') ) : ?>
			    <input type="hidden" id="posttila" value="<?php echo Yii::app()->request->getPost('tila'); ?>">
			    <?php endif; ?>

			    <select name="tila" id="tila" class="gui-input">
			     <option value="Kaikki"><?php echo Yii::t('main', 'Kaikki'); ?></option>
			     <option value="1"><?php echo Yii::t('main', 'Maksettu'); ?></option>
			    </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="from" value="<?php echo date('d.m.Y', strtotime($from)); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepickerFI" name="to" value="<?php echo date('d.m.Y', strtotime($to)); ?>">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>



                      <div class="col-md-2">
        	        <button class="btn btn-primary btn-lg haemob btn-block myBgColors" type="button"><i class="glyphicon glyphicon-search"> </i> Hae</button>
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
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Maksupäivä'); ?></th>
  <th><?php echo Yii::t('main', 'Työvuoro'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Hinta'); ?></th>
  <th><?php echo Yii::t('main', 'Tila'); ?></th>
  <th><?php echo Yii::t('main', 'Kuvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteyshenkilo'); ?></th>
  <th><?php echo Yii::t('main', 'Puhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Tietoja'); ?></th>
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
  </div>




<script type="text/javascript">
$(document).ready(function(){

if( $('#posttila').val() ){
	$('#tila').val( $('#posttila').val() );
}

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
