<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'ASIAKKAAT'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/asiakkaat/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>



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

   			    <input type="text" class="gui-input" name="domain_nimi" value="<?php if(isset($_POST['domain_nimi'])) echo $_POST['domain_nimi']; ?>" placeholder="<?php echo Yii::t('main', 'Domain'); ?>..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
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
  <th><?php echo Yii::t('main', 'Domain'); ?></th>
  <th><?php echo Yii::t('main', 'Yritys'); ?></th>
  <th><?php echo Yii::t('main', 'Modulit'); ?></th>
  <th><?php echo Yii::t('main', 'Kuukauden käyttö (tunnit)'); ?></th>
  <th><?php echo Yii::t('main', 'Hinnat'); ?></th>
  <th><?php echo Yii::t('main', 'Kuukauden laskuri'); ?></th>
  <th></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_etunnin_asiakkaat',
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
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);


$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
