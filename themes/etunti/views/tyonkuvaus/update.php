<?php
	$head = '';
	$as = Asiakkaat::model()->findbypk($model->asiakas_id);
	if(isset($as->id))
		$head= $as->Fullname;
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	   <?php     
		echo CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$model->id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));
	   ?>
	   </div>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo $head; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>


                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>

