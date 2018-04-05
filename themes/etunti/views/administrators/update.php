<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

if(isset($_POST['uploaded']))
{
  Yii::app()->user->domain = strtolower(Yii::app()->user->domain);
  if (!file_exists(Yii::app()->basePath."/../img/admins/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../img/admins/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../img/admins/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'.jpg');
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     $this->redirect(Yii::app()->request->urlReferrer);
  } 
}


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	   <?php     
		if($model->adm_login != 'admin')
		{
		echo CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$model->id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));
		}
	   ?>
	   </div>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo $model->adm_nimi; ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

		  <div class="col-sm-4">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
		  </div><div class="col-sm-4">
    		  <br>

		<?php
		$filepath = dirname(Yii::app()->getBasePath()).'/img/admins/'.Yii::app()->user->domain.'/'.$model->id.'.jpg';
		if (file_exists($filepath)){
		   $imageData = base64_encode(file_get_contents($filepath));
		   $src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
		   echo '<img src="'.$src.'" class="img-thumbnail">';
		}
		?>

  		  </div>





<div class="row">
 <br>
 <div class="admin-form col-sm-5">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Valitse kuva'); ?></span>
         <input type="file" class="gui-file" name="file" id="i_file" onChange="document.getElementById('uploader1').value = this.value;">
         <input type="text" class="gui-input" name="uploaded" id="uploader1" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa" class="btn btn-primary btn-group myBgColors" />
	</span>
    </div>
  </form>
 </div>
</div>


                 </div>
                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>

