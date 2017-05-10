<?php
/* @var $this DigistenTunnitKkController */
/* @var $dataProvider CActiveDataProvider */
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

<?php
    foreach(Yii::app()->user->getFlashes() as $key => $message) {
        echo '<div class="flash-' . $key . ' alert bg-success">' . $message . "</div>\n";
    }
?>


	<h2 class="myBgColors p10"> <?php echo Yii::t('main', 'Digisten hinnasto'); ?></h2>

            <div class="admin-form collapse" id="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                    <!-- Input Icons -->
                    <div class="section fill mb5">
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
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_digisten_hinnasto', array('model'=>$model)); ?>
                 </div>


                </div>
              </div>
            </div>

