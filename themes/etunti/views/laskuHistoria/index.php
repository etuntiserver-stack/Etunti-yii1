<?php
/* @var $this LaskuHistoriaController */
/* @var $dataProvider CActiveDataProvider */
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-barcode"></i> <?php echo Yii::t('main', 'Lasku historia'); ?></h2>



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

   			    <input type="text" class="gui-input" name="lid" value="<?php if(isset($_POST['lid'])) echo $_POST['lid']; ?>" placeholder="Lasku ID..">

                            <label for="firstname" class="field-icon">
                              <i class="fa fa-user"></i>
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



  <div class="panel heading-border">
   <div class="panel-body">

<div class="table-responsive">
<table class="table table-bordered table-striped small">
 <tr>
  <thead class="myBgColors">
  <th><?php echo Yii::t('main','Lasku ID'); ?></th>
  <th><?php echo Yii::t('main','Tapahtuma pvm'); ?></th>
  <th><?php echo Yii::t('main','Tietoja'); ?></th>
  <th><?php echo Yii::t('main','Palvelu'); ?></th>
  <th><?php echo Yii::t('main','Yhteensä euro'); ?></th>
  <th></th>
  </thead>
 </tr>
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

