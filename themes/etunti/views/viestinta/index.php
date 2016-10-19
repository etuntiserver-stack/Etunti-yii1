<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'VIESTIT'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/viestinta/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">


                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepicker" name="pvm" value="<?php if(isset($_POST['pvm'])) echo $_POST['pvm']; ?>" placeholder="<?php echo Yii::t('main', 'Päivämäärä'); ?>...">

                            <label for="firstname" class="field-icon">
                              <i class="glyphicon glyphicon-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="id"  class="gui-input" value="<?php if(isset($_POST['id'])) echo $_POST['id']; ?>" placeholder="<?php echo Yii::t('main', 'Keskustelu nro..'); ?>..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-file-text-o"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="sisalto"  class="gui-input" value="<?php if(isset($_POST['sisalto'])) echo $_POST['sisalto']; ?>" placeholder="<?php echo Yii::t('main', 'Viestin sisältö'); ?>..">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-file-text-o"></i>
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


  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Päivämäärä'); ?></th>
  <th><?php echo Yii::t('main', 'Viesti'); ?></th>
  <th><?php echo Yii::t('main', 'Lähettäjä'); ?></th>
  <th><?php echo Yii::t('main', 'Vastaanottaja'); ?></th>
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

$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
