<?php

?>



        <!-- begin: .tray-center -->
        <div class="tray-center">


	<h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main', 'Kirjallinen varoitus'); ?> 
	</h2>



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

   <?php 
   $criteria = new CDbCriteria();
   $criteria->order = " tekijan_nimi ";
   $criteria->condition = " aktiivinen='1' ";

    $list = CHtml::listData(Tyontekijat::model()->findAll($criteria), 'id', 'tekijan_nimi');
    echo '<select name="tyontekija" id="tyontekijat" title="Työntekijät">';
       	 echo '<option>'.Yii::t('main', 'Valitse työntekijä').'</option>';
    foreach($list as $key=>$val){
       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';
   ?>

                            <i class="arrow double"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" class="gui-input datepickerFI" name="aika" placeholder="<?php echo Yii::t('main', 'Aika'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" class="gui-input" name="paikka" placeholder="<?php echo Yii::t('main', 'Paikka'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>

                        <div class="section">
                          <label class="field prepend-icon">
   			    <input type="text" class="gui-input" name="johtaja" placeholder="<?php echo Yii::t('main', 'Johtaja'); ?>">
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>

                      </div>

                      <div class="col-md-4">
                        <div class="section">
                          <label class="field prepend-icon">
   			    <textarea class="form-control" name="text" placeholder="<?php echo Yii::t('main', 'Kirjallinen varoitus'); ?>" rows="7" cols="60"></textarea>
                            <label for="firstname" class="field-icon">
                              <i class="fa fa-bookmark"></i>
                            </label>
                          </label>
                        </div>
                      </div>



                    </div>

                      <div class="">
        	        <input type="submit" class="btn btn-primary btn-lg haemob myBgColors" value="<?php echo Yii::t('main', 'Luo'); ?>">
		      </div>

<br>

<?php
if(!empty($file))
{
	$explNimi = explode("/",$file);
 	echo '
	<div class="">
	  <a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';
}

?>



                </div>
              </div>
            </div>

	    </form>


        <!-- loppu: .tray-center -->
        </div>

