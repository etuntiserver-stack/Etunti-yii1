<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-file-video-o"></i> <?php echo Yii::t('main', 'Ohjevideot'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">



   <?php
	Yii::app()->db1->setActive(true);
	if(!isset($_GET['id'])){
		$gr = Ohjevideot::model()->findAll(array('group'=>'ohjevideo_ryhma')); // gr
		foreach($gr as $item){

			echo '<div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#ryhma_'.$item->id.'"><h3>'.$item->ohjevideo_ryhma.'&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div>';
			echo '<div class="collapse" id="ryhma_'.$item->id.'">';
       			$criteria = new CDbCriteria();
			$criteria->order = "id DESC";
			$criteria->condition="ohjevideo_ryhma='".$item->ohjevideo_ryhma."'";
			$model = Ohjevideot::model()->findAll($criteria);
			echo '<table class="table table-bordered">';
			echo '<tr>';
			echo '<th>Kuvaus</th>';
			echo '<th>Video</th>';
			echo '</tr>';
		   	foreach($model as $data){
				echo '<tr>';
				echo '<td width="50%">' . CHtml::link('<h2>'.$data->otsiko.'</h2><br>'.$data->kuvaus, array('ohjevideot', 'id' => $data->id)).'</td>';
				echo '
				<td>
					'.$data->embed.'
				</td>';
				echo '</tr>';
		   	}
			echo '</table>';
			echo '</div>';
		}
	} else {
		$model = Ohjevideot::model()->findByPk($_GET['id']);
		if(isset($model->id)){
		echo CHtml::link(Yii::t('main', 'Näytä kaikki'), array('ohjevideot'));
	 	echo '<div class="row">
		 <div class="col-sm-8 col-sm-offset-2" style="margin-bottom:20px">
			<video class="img-thumbnail" controls="controls" style="width:100%">
			  <source src="../../ohjevideot/'.$model->tiedoston_nimi.'" type="video/mp4">
			</video>'
			. CHtml::link('<b>'.$model->otsiko.'</b><br>'.$model->kuvaus, array('ohjevideot', 'id' => $model->id)).'
		 </div></div>';
		}
	}

   ?>
 </div>



                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
