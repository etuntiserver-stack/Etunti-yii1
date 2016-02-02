<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'ASIAKKAAT'); ?> 
		<?php echo CHtml::link('','/index.php/asiakkaat/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">
   	    <input type="hidden" name="mob_hae">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


   <?php
    $criteria = new CDbCriteria();
    $criteria->order = " osoite ASC ";
    $model = Kohteet::model()->findAll($criteria);
    $list = CHtml::listData($model, 'id', 'osoite');

    echo '<select class="gui-input" name="osoite">';
    if(isset($_POST['osoite']))
    {
       $k = Kohteet::model()->findbypk($_POST['osoite']);
       if(isset($k->id))
       echo '<option value="'.$k->id.'">'.$k->osoite.'</option>';
    } else {
       echo '<option value="">'.Yii::t('main', 'Osoite').'</option>';
    }

       echo '<option value="">Kaikki</option>';

    foreach($list as $key=>$val){
    echo '<option value="'.$key.'">'.$val.'</option>';

    }
    echo '</select>';
   ?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

		      <?php if(isset($_POST['aktiivinen'])) echo '<input type="hidden" id="akt" value="'.$_POST['aktiivinen'].'">'; ?>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


			   <select class="gui-input" name="aktiivinen" id="aktiivinen">
       				<option value="1">Aktiiviset</option>
       				<option value="0">Passiviset</option>
       				<option value="kaikki">Kaikki</option>
			   </select>


                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="yhteyshenkilo" value="<?php if(isset($_POST['yhteyshenkilo'])) echo $_POST['yhteyshenkilo']; ?>" placeholder="Yhteyshenkilö">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="puhelin"  class="gui-input" value="<?php if(isset($_POST['puhelin'])) echo $_POST['puhelin']; ?>" placeholder="Puhelin">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-phone"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="sahkoposti" class="gui-input" value="<?php if(isset($_POST['sahkoposti'])) echo $_POST['sahkoposti']; ?>" placeholder="<?php echo Yii::t('main','Sähköposti'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-at"></i>
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
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Yhteyshenkilo'); ?></th>
  <th><?php echo Yii::t('main', 'Postinumero'); ?></th>
  <th><?php echo Yii::t('main', 'Puhelin'); ?></th>
  <th><?php echo Yii::t('main', 'Sähköposti'); ?></th>
  <th><?php echo Yii::t('main', 'Tyyppi'); ?></th>
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

if($("#akt").val())
$("#aktiivinen").val($("#akt").val());
else
$("#aktiivinen").val(1);


$(".haemob").click(function(){
	$("#mobForm").submit();
});

});
</script>
