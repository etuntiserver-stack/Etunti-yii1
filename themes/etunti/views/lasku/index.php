<?php
	//$asetukset=Asetukset::model()->findbypk(1);

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">




              <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU'); ?> 
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
                          <label class="field select">


    		<?php 
       		$criteria = new CDbCriteria();
		//$criteria->select = " COALESCE(NULLIF(yhteyshenkilo,yhteyshenkilo),'gg') AS yht ";
		$criteria->order = " yhteyshenkilo ";

        	$a = Asiakkaat::model()->findAll($criteria);
		echo '<select name="asiakasLaskulle" class="gui-input">';

		if(isset($_POST['asiakasLaskulle']) and !empty($_POST['asiakasLaskulle']))
		{
        	  $aon = Asiakkaat::model()->findbypk($_POST['asiakasLaskulle']);
		  if(!empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->asiakasnumero.'">'.$aon->yrityksen_nimi.'</option>';
		  elseif(empty($aon->yhteyshenkilo) and empty($aon->yrityksen_nimi))
		    echo '<option value="'.$aon->asiakasnumero.'">nimet puutuu '.$aon->id.'</option>';
		  else
		    echo '<option value="'.$aon->asiakasnumero.'">'.$aon->yhteyshenkilo.' ID:'.$aon->id.'</option>';
		} else {
	        echo '<option></option>';
		}

		foreach($a as $aa)
		{
		  if(!empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->asiakasnumero.'">'.$aa->yrityksen_nimi.'</option>';
		  elseif(empty($aa->yhteyshenkilo) and empty($aa->yrityksen_nimi))
		    echo '<option value="'.$aa->asiakasnumero.'">nimet puutuu '.$aa->id.'</option>';
		  else
		    echo '<option value="'.$aa->asiakasnumero.'">'.$aa->yhteyshenkilo.' ID:'.$aa->id.'</option>';
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

   			    <input type="text" class="gui-input datepicker" name="from" value="<?php echo $from; ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="section">
                          <label class="field prepend-icon">

   			    <input type="text" class="gui-input datepicker" name="to" value="<?php echo $to; ?>" >

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-calendar"></i>
                            </label>
                          </label>
                        </div>
                      </div>

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
  <th><?php echo Yii::t('main', 'Nro.'); ?></th>
  <th><?php echo Yii::t('main', 'Asiakas'); ?></th>
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



	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.modal.js"></script>
	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>


<script type="text/javascript">
$(document).ready(function(){

$(".haemob").click(function(){
	$("#mobForm").submit();
});


$(".fa-history").click(function(){

	var thisid = $(this).attr("for");

        $.ajax({
           url: 'get_historia',
	   type: 'POST',
	   data: { id : thisid },
           success: function(data){
		//console.log(data);
		$("#showres").modal().html(JSON.parse(data));
           }
        });

});

});
</script>
