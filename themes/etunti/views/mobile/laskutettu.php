<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Laskutettavat kohteet'); ?> 
	      </h2>



   	    <form id="mobForm" action="#" class="form-inline" method="POST">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">


   <?php
    $criteria = new CDbCriteria();
    $criteria->order = " tekijan_nimi ASC ";
    $criteria->condition = " aktiivinen=1 ";
    $model = Tyontekijat::model()->findAll($criteria);
    $list = CHtml::listData($model, 'id', 'tekijan_nimi');

    echo '<select class="gui-input" name="tekijaPaaSivulla">';
    if(isset($_POST['tekijaPaaSivulla']))
    {
       $tekija = Tyontekijat::model()->findbypk($_POST['tekijaPaaSivulla']);
       if(isset($tekija->tekijan_nimi))
       echo '<option value="'.$tekija->id.'">'.$tekija->tekijan_nimi.'</option>';
    } else {
       echo '<option value="">'.Yii::t('main', 'Työntekijät').'</option>';
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
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input" name="etsi_kohteet" value="<?php if(isset($_POST['etsi_kohteet'])) echo $_POST['etsi_kohteet']; ?>" placeholder="Osoite">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="fromP" id="from" class="gui-input datepicker" value="<?php if(isset($_POST['fromP'])) echo $_POST['fromP']; ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" name="toP" id="to" class="gui-input datepicker" value="<?php if(isset($_POST['toP'])) echo $_POST['toP']; ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

                      <div class="col-md-2">
                        <div class="section">
                          <label class="field select">
			    <select name="laskutettu" class="gui-input">
			     <?php
			     if(isset($_POST['laskutettu']) and $_POST['laskutettu'] == '1')
			     echo '<option value="1">Laskutettu</option>';
			     if(isset($_POST['laskutettu']) and $_POST['laskutettu'] == '0')
			     echo '<option value="0">Laskuttamatta</option>';
			     ?>
			     <option value="">Tilanne</option>
			     <option value="1">Laskutettu</option>
			     <option value="0">Laskuttamatta</option>
			    </select>
                            <i class="arrow double"></i>
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

  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Päivä'); ?></th>
  <th><?php echo Yii::t('main', 'Kartta'); ?></th>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Kohde'); ?></th>
  <th><?php echo Yii::t('main', 'Lyhyt viesti'); ?></th>
  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  <th><?php echo Yii::t('main', 'Laskutettu'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view_laskutettu',
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


  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/mobile.js"></script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/asetukset.js"></script>


<script type="text/javascript">
$(document).ready(function(){


$(".haemob").click(function(){
	$("#mobForm").submit();
});



$(".chckbxHyvaksynta").click(function(){
  $(this).each(function() {
      var label = $(this).prop("checked");
      var thisID = $(this).attr("id").split("_");
      if(label)
      {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/laskutettu',
           type: "POST",
	   data: { id : thisID[1], ajax : "true", las : "1" },
           success: function(data){
		console.log(data);
           }
        });
      } else {
        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/laskutettu',
           type: "POST",
	   data: { id : thisID[1], ajax : "true", las : "0" },
           success: function(data){
		console.log(data);
           }
        });
      }
  });
});

});
</script>


