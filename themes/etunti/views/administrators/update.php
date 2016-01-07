<?php
/* @var $this AdministratorsController */
/* @var $model Administrators */

$this->breadcrumbs=array(
	Yii::t('main', 'Administrators')=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	Yii::t('main', 'Päivitä'),
);

$this->menu=array(
	array('label'=>'Hallinta', 'url'=>array('admin')),
);


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

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'JÄRJESTELMÄNVALVOJA PÄIVÄYS')." ID# ".$model->id; ?> </h2>


        <!-- loppu: .tray-center -->
        </div>



<div class="row">
 <div class="col-sm-12">

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

 </div>
</div>


<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

  <div class="col-sm-8">
   <div class="col-sm-3">
    <img src="<?php echo Yii::app()->baseUrl.'/img/admins/'.Yii::app()->user->domain.'/'.$model->id; ?>.jpg" class="thumbnail">
   </div>
  </div>
</div><!-- form -->


<script type="text/javascript">
$(document).ready(function(){


  $("#i_file").filestyle({
	buttonText: "Etsi kuva"
  });


});
</script>
