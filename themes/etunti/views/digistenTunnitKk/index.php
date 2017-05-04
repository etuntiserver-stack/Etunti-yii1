<?php
/* @var $this DigistenTunnitKkController */
/* @var $dataProvider CActiveDataProvider */
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <?php echo Yii::t('main', 'Digisten Tunnit Kk'); ?></h2>

            <div class="admin-form collapse" id="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="row">
                      <div class="col-md-6">
                        <div class="section">

                        </div>
                      </div>
                    </div>

                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>



<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th></th>
  <th><?php echo Yii::t('main', 'Luotu'); ?></th>
  <th><?php echo Yii::t('main', 'Yritys'); ?></th>
  <th><?php echo Yii::t('main', 'Vuosi / kk'); ?></th>
  <th><?php echo Yii::t('main', 'Tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Tuntihinta'); ?></th>
  <th><?php echo Yii::t('main', 'Hinta'); ?></th>
  <th><?php echo Yii::t('main', 'Lasku'); ?></th>
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
  </div>
</div>
