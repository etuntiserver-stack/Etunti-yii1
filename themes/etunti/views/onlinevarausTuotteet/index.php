<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Online-Varaus tuotteet'); ?> 
		<?php echo CHtml::link('','/index.php/onlinevarausTuotteet/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

		      <?php if(isset($_POST['nayta_sivuilla'])) echo '<input type="hidden" id="akt" value="'.$_POST['nayta_sivuilla'].'">'; ?>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			   <select class="gui-input" name="nayta_sivuilla" id="nayta_sivuilla">
       				<option value=1><?php echo Yii::t('main', 'Aktiiviset'); ?></option>
       				<option value=0><?php echo Yii::t('main', 'Passiviset'); ?></option>
       				<option value="kaikki"><?php echo Yii::t('main', 'Kaikki'); ?></option>
			   </select>
                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="nimike"  class="gui-input" value="<?php if(isset($_POST['nimike'])) echo $_POST['nimike']; ?>" placeholder="Nimike..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-tag"></i>
                            </label>
                          </label>
                        </div>
                      </div>


                      <div class="col-md-2">
        	        <input type="submit" class="btn btn-primary btn-lg haemob btn-block myBgColors" value="Hae">
		      </div>

                    </div>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

<style>
.table > tbody > tr > td {
     vertical-align: top;
}
</style>

  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Nimike'); ?></th>
  <th><?php echo Yii::t('main', 'Selitysteksti'); ?></th>
  <th><?php echo Yii::t('main', 'Alasvetovalikon rakenne'); ?></th>
  <th><?php echo Yii::t('main', 'Lisäpalvelut rakenne'); ?></th>
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

if($("#akt").val())
$("#nayta_sivuilla").val($("#akt").val());
else
$("#nayta_sivuilla").val(1);

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
