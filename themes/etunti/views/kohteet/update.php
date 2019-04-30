<?php

if(isset($_POST['uploaded_t']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/kohteet/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/kohteet/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/kohteet/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'_'.$_FILES['file']['name']);
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  } 
}

if(isset($_POST['poistaTamaTiedosto'])){
	unlink($_POST['poistaTamaTiedosto']);
exit;
}

   if(isset($_POST['poistaTamaKuva']))
   {
	KuviaKohteesta::model()->deletebypk($_POST['kuva_id']);
	if(file_exists(Yii::app()->basePath."/../".$_POST['poistaTamaKuva']))
	unlink(Yii::app()->basePath."/../".$_POST['poistaTamaKuva']);
	exit;
   }


if(isset($_POST['uploaded_tyonkuvaus']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/kohteet/".Yii::app()->user->domain."/tyonkuvaukset")) {
  	mkdir(Yii::app()->basePath."/../tiedostot/kohteet/".Yii::app()->user->domain."/tyonkuvaukset", 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/kohteet/'.Yii::app()->user->domain.'/tyonkuvaukset/';
  $uploadfile = $uploaddir . basename($model->id.'_'.$_FILES['file']['name']);
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  } 
}

if(isset($_POST['poistaTyonkuvaus'])){
	unlink($_POST['poistaTyonkuvaus']);
exit;
}






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
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Kohteiden hallinta'); ?>: <?php echo $model->osoite; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>


<div class="row">
  <div class="col-sm-6">



  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Tiedostot (sopimukset jne)'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUPSop').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_t" id="tiedostoUPSop" placeholder="Valitse tiedosto..">
         <label class="field-icon">
          <i class="fa fa-upload"></i>
         </label>
       </label>
	<span class="input-group-btn">
          <input type="submit" value="Lataa" class="btn btn-primary myBgColors" />
	</span>
    </div>
  </form>

<br>

    <?php
	$i = 0;
	foreach(array_reverse(glob('tiedostot/kohteet/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$ext = pathinfo(basename($file), PATHINFO_EXTENSION);
 	echo '
	<div class="form-inline" id="t_'.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$i.'">X </div> ';
				// <-- file_safe_opener
				$filepath = Yii::getPathOfAlias('application').'/../'.$file;
				echo CHtml::link(basename($file),
					array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
					array(
						'target'=>'_blank',
						'class'=>'link'
				));
				//     file_safe_opener// -->
	echo '</div>
	';
	$kuvat[$i] = $file;
	}
   ?>
     
   </div>
</div>





                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>



<script type="text/javascript">
$(document).ready(function(){


$(".poistaTyonkuvaus").click(function(){
	var forThis = $(this).attr("this");
	var model = $(this).attr("model");
	var forID = $(this).attr("for");

        $.ajax({
           url: "update?id="+model,
	   type:'POST',
	   data: { "poistaTyonkuvaus" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
});

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


});
</script>
