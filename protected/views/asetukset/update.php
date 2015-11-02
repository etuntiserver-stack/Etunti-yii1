<?php
/* @var $this AsetuksetController */
/* @var $model Asetukset */

$this->breadcrumbs=array(
	'Asetuksets'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
/*
$this->menu=array(
	array('label'=>'List Asetukset', 'url'=>array('index')),
	array('label'=>'Create Asetukset', 'url'=>array('create')),
	array('label'=>'View Asetukset', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Asetukset', 'url'=>array('admin')),
);
*/

if(isset($_POST['uploaded_t']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'_'.$_FILES['file']['name']);
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  } 
}

if(isset($_POST['poistaTamaTiedosto'])){
	unlink($_POST['poistaTamaTiedosto']);
exit;
}

?>



<div class="row">
  <div class="col-sm-6">
  <legend>
  <h2><?php echo Yii::t('main', 'ASETUKSET'); ?> <i class="glyphicon glyphicon-phone"></i></h2>
  </legend>

	<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

  </div><div class="col-sm-6">
  <legend>
  <h2><?php echo Yii::t('main', 'TIEDOSTOT'); ?> <i class="glyphicon glyphicon-phone"></i></h2>
  </legend>
<?php
	$i = 0;
	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>
	  <a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';
	$kuvat[$i] = $file;
	}
?>
  </div>
</div>


<div class="row">
 <div class="col-sm-12">

<div class="pull-right">
 <div class="tiedosto form-inline">
  <label><?php echo Yii::t('main', 'Tiedostot (sopimukset jne)'); ?></label>
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
   <input type="hidden" name="uploaded_t" value="true" />
   <input type="file" name="file" id="t_file" data-icon="false" data-buttonText="Etsi kuvaa" class="form-group" />
   <input type="submit" value="Lataa" class="btn btn-primary btn-group" id="tiedostoUP" /></button>
  </form>
 </div>
</div>

 </div>
</div>



<script type="text/javascript">
$(document).ready(function(){


$(".poistaTiedosto").click(function(){
	var forThis = $(this).attr("this");
	var model = $(this).attr("model");
	var forID = $(this).attr("for");

        $.ajax({
           url: "update?id="+model,
	   type:'POST',
	   data: { "poistaTamaTiedosto" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
});


  $("#i_file").filestyle({
	buttonText: "Etsi kuva"
  });

  $("#t_file").filestyle({
	buttonText: "Etsi tiedosto"
  });

});
</script>

