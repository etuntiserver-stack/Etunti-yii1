<?php

if(isset($_POST['uploaded']))
{
  Yii::app()->user->domain = strtolower(Yii::app()->user->domain);

  if (!file_exists(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../img/tekijat/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../img/tekijat/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'.jpg');

  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {


	// <-- Image resize
	//header('Content-Type: image/jpeg');
	$url = $uploadfile;
	$width = 640;
	$image = imagecreatefromjpeg($url);
	$orig_width = imagesx($image);
	$orig_height = imagesy($image);
	$height = (($orig_height * $width) / $orig_width);
	$new_image = imagecreatetruecolor($width, $height);
	imagecopyresized($new_image, $image,
		0, 0, 0, 0,
		$width, $height,
		$orig_width, $orig_height);
	
	imagejpeg($new_image, $uploadfile);
	//     Image resize -->


     echo "";
  } 
}


if(isset($_POST['uploaded_t']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/tekijat/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/tekijat/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/tekijat/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . basename($model->id.'_'.$_FILES['file']['name']);
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  } 
}

if(isset($_POST['poistaTamaTiedosto'])){
	unlink($_POST['poistaTamaTiedosto']);
exit;
}

$site = Yii::app()->createController('Site');
$checkLaaja = "tyontekijat_4_".Yii::app()->user->adminStatus;
$laaja = $site[0]->checkOikeusFields($checkLaaja);
?>


        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	    <div class="form-inline">
	   <?php /*    
		echo CHtml::link("poista", '#', array(
		'submit'=>array('delete', "id"=>$model->id), 
		'confirm' => 'Haluatko varmaasti poistaa?',
		'class'=>'btn btn-primary myBgColors'
		));
	   */ ?>

  	     <!-- tulostus -->
     	      <form action="tulosta?id=<?php echo $model->id; ?>" class="form-group" target="_blank" method="POST">
      	      <input type="submit" name="tulosta" class="btn btn-success myBgColors" value="PDF">
     	      </form>
  	     <!-- tulostus -->
	    </div>
           </div>


	   <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo $model->tekijan_nimi; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model, 'laaja' =>$laaja)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>



<br>

<div class="row">
  <div class="col-sm-12">
<?php

	$i = 0;
	foreach(array_reverse(glob('tiedostot/tekijat/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$ext = pathinfo(basename($file), PATHINFO_EXTENSION);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>';
				// <-- file_safe_opener
				$filepath = Yii::getPathOfAlias('application').'/../'.$file;
				echo CHtml::link(basename($file),
					array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => $ext),
					array(
						'target'=>'_blank',
						'class'=>'link'
				));
				//     file_safe_opener// -->
	echo '</div>';
	$kuvat[$i] = $file;
	}
?>
  </div>
</div>

<br>


        <!-- begin: .tray-center -->
        <div class="tray-center">

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

		<div class="row">
		  <div class="col-sm-12">

			<?php echo CHtml::link(Yii::t('main', 'Merkitse työsuhde päättyneeksi'), 
				array('update', 'id'=>$model->id, 'tyosuhteet_talteen' => true), 
				array(
					'confirm' => 'Oletko varma?',
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Merkitse työsuhde päättyneeksi') 
				)
				); 
			?>

			<?php 
			$ts = Tyosuhdet::model()->find(" tid='".$model->id."' ");
			if(isset($ts->id))
			{
				echo $this->renderPartial('//tyosuhdet/_form',array('model'=>$ts, 'laaja' =>$laaja));
			} else {
				$ts = new Tyosuhdet;
				echo $this->renderPartial('//tyosuhdet/_form',array('model'=>$ts, 'laaja' =>$laaja));
			}
			?>
		  </div>
		</div>

                </div>
              </div>
            </div>




<div class="row">



 <div class="admin-form col-sm-6">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Työntekijän kuva'); ?></span>
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


 <div class="admin-form col-sm-6">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Tiedostot (sopimukset jne)'); ?></span>
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




<?php /*
            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-heading"><h2><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></h2></div>
                <div class="panel-body bg-light">


  <table class="table table-striped" id="mobileTable">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>
  <th><?php echo Yii::t('main', 'Aloitus'); ?></th>
  <th><?php echo Yii::t('main', 'Lopetus'); ?></th>
  <th><?php echo Yii::t('main', 'Kesto'); ?></th>
  </tr>
  </thead>
  <?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataToteutuneet,
	'itemView'=>'_view_toteutuneet',
  	'template'=>'{items}<table class="table table-striped table-condensed"></table><br/>{pager}',


	'pager' => array(
           'firstPageLabel'=>'<<',
           'prevPageLabel'=>'< Edellinen',
           'nextPageLabel'=>'Seuraava >',
           'lastPageLabel'=>'>>',
           //'maxButtonCount'=>'10',
           'header'=>'<h3>Siirry sivulle:</h3>',
           'cssFile'=>false,
       ), 

  )); ?>
  </table>


                </div>
              </div>
            </div>
*/ ?>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-heading"><h2><?php echo Yii::t('main', 'Kirjallinen varoitus'); ?></h2></div>
                <div class="panel-body bg-light">


<div class="row">
  <div class="col-sm-4">
<?php

	$i = 0;
	foreach(array_reverse(glob('tiedostot/varoitukset/'.Yii::app()->user->domain.'/'.$model->id.'_*.*')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">

	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div>
	  &nbsp;&nbsp;&nbsp;<a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
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
