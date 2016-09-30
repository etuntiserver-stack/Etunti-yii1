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


if(isset($_POST['uploaded_onlinevarausehdot']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
  $temp = explode(".", $_FILES["file"]["name"]);
  $uploadfile = $uploaddir . basename('onlinevarausehdot.'.end($temp));
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  } 
}





if(isset($_POST['poistaTamaTiedosto'])){
	unlink($_POST['poistaTamaTiedosto']);
exit;
}

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p20"> <i class="fa fa-gear"></i> <?php echo Yii::t('main', 'ASETUKSET'); ?> </h2>


	   <p><div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#yrityksentiedot"><h3><?php echo Yii::t('main','Yrityksen tiedot'); ?> &nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i> </h3></div></p>

            <div class="admin-form collapse" id="yrityksentiedot">
              <div class="panel heading-border">

		<h2 class="p15"><?php echo Yii::t('main','Yrityksen tiedot'); ?></h2>
                 <div class="panel-body bg-light">
                  <div class="row">
		  <?php echo $this->renderPartial('//firmanTiedot/_form', array('model'=>$f)); ?>
                  </div>
                 </div>

              </div>
            </div>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>
                </div>
              </div>
            </div>


	   <p><div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#kayttajienoikeus"><h3><?php echo Yii::t('main','Käyttäjien oikeudet'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div></p>

            <div class="admin-form collapse" id="kayttajienoikeus">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Käyttäjien oikeudet');?></h2>
                <div class="panel-body bg-light">


   <?php
	echo $this->renderPartial('oikeudet');
   ?>


                </div>
              </div>
            </div>

	   <p><div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#kehitys"><h3><?php echo Yii::t('main','Kehitys'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div></p>

            <div class="admin-form collapse" id="kehitys">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Kehitys');?></h2>
                <div class="panel-body bg-light">
                 <div class="row">
		<a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu?theme=admin" class="animated animated-short fadeInUp"><span class="fa fa-gear"></span> <?php echo Yii::t('main','Perus teema'); ?> </a> |  
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/etusivu_esimerki" class="animated animated-short fadeInUp"><span class="fa fa-gear"></span> <?php echo Yii::t('main','Etusivun esimerki'); ?> </a> | 
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/site/mobemu" class="animated animated-short fadeInUp"><span class="fa fa-gear"></span> <?php echo Yii::t('main','Mobiili emulattori'); ?> </a> | 
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/valikkoot/index" class="animated animated-short fadeInUp"><span class="fa fa-gear"></span> <?php echo Yii::t('main','Valikot'); ?> </a>
                <a href="<?php echo Yii::app()->request->baseUrl; ?>/index.php/tyontekijat/migraatio" class="animated animated-short fadeInUp"><span class="fa fa-gear"></span> <?php echo Yii::t('main','Työntekijän migraatio'); ?> </a>
		<a href="#" id="clearLocalStorage" class="btn btn-primary">Clear LocalStorage</a>
                 </div>
                </div>
              </div>
            </div>



	   <p><div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#dumpit"><h3><?php echo Yii::t('main','Varmuskopio dumpit'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div></p>

            <div class="admin-form collapse" id="dumpit">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Varmuskopio dumpit');?></h2>
                <div class="panel-body bg-light">


   <?php
   foreach(array_reverse(glob(Yii::app()->baseUrl.'backup/'.Yii::app()->user->domain.'/*')) as $file) 
   {
	$explNimi = explode("/",$file);

 	echo '
	<div class="row">
	  <a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';
	
   }
   ?>


                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>



	


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




<div class="row">
 <div class="col-sm-12">

 <div class="admin-form col-sm-6" id="onlinevarausehdot">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Onlinevarausehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_onlinevarausehdot" id="tiedostoUP" placeholder="Valitse tiedosto..">
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

 <div class="admin-form col-sm-6" id="onlinevarausehdot">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Sopimukset jne.'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_t" id="tiedostoUP" placeholder="Valitse tiedosto..">
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

/*
  $("#i_file").filestyle({
	buttonText: "Etsi kuva"
  });

  $("#t_file").filestyle({
	buttonText: "Etsi tiedosto"
  });
*/
});
</script>

