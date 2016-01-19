<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

if(isset($_POST['uploaded']))
{

  if (!file_exists(Yii::app()->basePath."/../img/admins/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../img/admins/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../img/admins/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'.jpg');
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     echo "";
  } 
}


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo $model->adm_nimi; ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

		  <div class="col-sm-3">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
		  </div><div class="col-sm-4">
    		  <br>
    		  <img src="<?php echo Yii::app()->baseUrl.'/img/admins/'.Yii::app()->user->domain.'/'.$model->id; ?>.jpg" class="img-thumbnail">
  		  </div>


                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>




<div class="pull-right">
 <div class="kuva form-inline">
  <label><?php echo Yii::t('main', 'Järjestelmävalvojan kuva'); ?></label>
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
   <input type="hidden" name="uploaded" value="true" />
   <input type="file" name="file" id="i_file" data-icon="false" data-buttonText="Etsi kuvaa" class="form-group" />
   <input type="submit" value="Lataa" class="btn btn-primary btn-group" id="kuvaUP" /></button>
  </form>
 </div>
</div>


                      <div class="col-md-6">
                        <div class="section">
                          <label class="field prepend-icon append-button file">
                            <span class="button">Choose File</span>
                            <input type="file" class="gui-file" name="file1" id="file1" onChange="document.getElementById('uploader1').value = this.value;">
                            <input type="text" class="gui-input" id="uploader1" placeholder="Please Select A File">
                            <label class="field-icon">
                              <i class="fa fa-upload"></i>
                            </label>
                          </label>
                        </div>
                      </div>





<script type="text/javascript">
$(document).ready(function(){


  $("#i_file").filestyle({
	buttonText: "Etsi kuva"
  });


});
</script>
