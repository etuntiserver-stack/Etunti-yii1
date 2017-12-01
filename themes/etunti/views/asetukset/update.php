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
     $this->redirect(array('update', 'id'=>1));
  } 
}

if(isset($_POST['uploaded_toimitusehdot']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
  $temp = explode(".", $_FILES["file"]["name"]);
  $uploadfile = $uploaddir . basename('toimitusehdot.'.end($temp));
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     $this->redirect(array('update', 'id'=>1));
  } 
}

if(isset($_POST['uploaded_Konevuokraus_toimitusehdot']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/firma/'.Yii::app()->user->domain.'/';
  $temp = explode(".", $_FILES["file"]["name"]);
  if(end($temp) == 'pdf')
  {
	$uploadfile = $uploaddir . basename('Konevuokraus_toimitusehdot.'.end($temp));
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
	$this->redirect(array('update', 'id'=>1));

  } else {

	Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan PDF");
	$this->redirect(array('update', 'id'=>1));
  }
}


if(isset($_POST['uploaded_edico_kayttoehdot']))
{

  $path = Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain;
  if (!file_exists($path)) {
  	mkdir($path, 0777, true);
  }

  $path_html = Yii::app()->basePath."/../tiedostot/firma/".Yii::app()->user->domain.'/eDico_html';
  if (!file_exists($path_html)) {
  	mkdir($path_html, 0777, true);
  }

  $uploaddir = $path.'/';
  $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  $bname = 'eDico_kayttoehdot';
  if($ext == 'pdf')
  {
	$uploadfile = $uploaddir . basename($bname.'.pdf');
	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile))
	{
		$exec = 'pdftohtml -c -s -noframes '.$path.'/'.$bname.'.pdf '.$path_html.'/'.$bname.'.html';
		exec($exec.' 2>&1', $output, $return);

		if (file_exists($path_html.'/'.$bname.'.html')) {
		  	$html_content = file_get_contents($path_html.'/'.$bname.'.html');
		  	$html_content = str_replace("background image", "", $html_content);
		  	$html_content = str_replace("body bgcolor=\"#A0A0A0\"", "body bgcolor=\"#FFFFFF\"", $html_content);
			$html_content = preg_replace("/<img[^>]+\>/i", "", $html_content);
		  	$html_content = str_replace("p {margin: 0; padding: 0;}", "", $html_content);

			//$html_content = strip_tags($html_content, '<style>');
$html_content = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $html_content);
$html_content = preg_replace('/(<[^>]+) class=".*?"/i', '$1', $html_content);
			if(file_put_contents($path.'/'.basename($bname.'.html'), $html_content))
  				exec('rm -rf '.$path_html);
		}
		$this->redirect(array('update', 'id'=>1));
	}

  } else {

	Yii::app()->user->setFlash('danger', "Lataaminen ei onnistunut, odottelaan PDF");
	//$this->redirect(array('update', 'id'=>1));
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



            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>
                </div>
              </div>
            </div>


	   <p><div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#kayttajienoikeus"><h3><?php echo Yii::t('main','Käyttöoikeudet'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div></p>

            <div class="admin-form collapse" id="kayttajienoikeus">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Käyttöoikeudet');?></h2>
                <div class="panel-body bg-light">


   <?php
	echo $this->renderPartial('oikeudet');
   ?>


                </div>
              </div>
            </div>


<?php if(Yii::app()->user->username == 'admin' or Yii::app()->user->username == 'roman'): ?>
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
<?php endif; ?><!-- is admin -->


        <!-- loppu: .tray-center -->
        </div>



	


  <legend>
  <h2><?php echo Yii::t('main', 'TIEDOSTOT'); ?> <i class="glyphicon glyphicon-phone"></i></h2>
  </legend>


<div class="row">
 <div class="col-sm-6">
	<?php
	$i = 0;
	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/*')) as $file) {
	$i++;
	$ext = pathinfo(basename($file), PATHINFO_EXTENSION);
 	echo '
	<div class="form-inline" id="t_'.$model->id.$i.'">
	  <div class="btn btn-xs btn-danger poistaTiedosto" this="'.$file.'" model="'.$model->id.'" for="t_'.$model->id.$i.'">X</div> ';

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

<hr>



<div class="row">
 <div class="col-sm-12">

 <div class="admin-form col-sm-6" id="onlinevarausehdot">
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
     <div class="section input-group">
       <label class="field prepend-icon append-button file">
         <span class="button"><?php echo Yii::t('main', 'Onlinevarausehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_ove').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_onlinevarausehdot" id="tiedostoUP_ove" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'Sopimukset jne.'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_sop').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_t" id="tiedostoUP_sop" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'Toimitusehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_te').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_toimitusehdot" id="tiedostoUP_te" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'Konevuokraus toimitusehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_kv').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_Konevuokraus_toimitusehdot" id="tiedostoUP_kv" placeholder="Valitse tiedosto..">
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
         <span class="button"><?php echo Yii::t('main', 'eDico APP käyttöehdot'); ?></span>
         <input type="file" class="gui-file" name="file" id="t_file" onChange="document.getElementById('tiedostoUP_eke').value = this.value;">
         <input type="text" class="gui-input" name="uploaded_edico_kayttoehdot" id="tiedostoUP_eke" placeholder="Valitse tiedosto..">
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


        <div class="tray-center">
            <div class="admin-form">
              <div class="panel heading-border">
		<h2 class="p15"><?php echo Yii::t('main','Tietoja Etunnista');?></h2>
                <div class="panel-body bg-light">

                <div class="row">
	  	<?php
		// <-- GIT version
		    $version = array();
		    exec('git describe --always',$version_mini_hash);
		    exec('git rev-list HEAD | wc -l',$version_number);
		    exec('git log -1',$line);
	
		if(isset($version_number[0]))
		{
		    $version['short'] = "v1.".trim($version_number[0]);
		    $version['full'] = "v1.".trim($version_number[0]).".$version_mini_hash[0] (".str_replace('commit ','',$line[0]).")";
		    echo Yii::t('main', 'Versio').':  '.$version['short'];
		}
		// GIT version -->
	  	?>
                </div>


                </div>
              </div>
            </div>
        </div><!--tray-center-->




<script type="text/javascript">
$(document).ready(function(){


$(".poistaTiedosto").click(function(){
	var forThis = $(this).attr("this");
	var model = $(this).attr("model");
	var forID = $(this).attr("for");
	if(confirm('Oletko varmaa?'))
	{
        $.ajax({
           url: "update?id="+model,
	   type:'POST',
	   data: { "poistaTamaTiedosto" : forThis },
           success: function(data){
		console.log(data);
		$("#"+forID).remove();
           }
        });
	}
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

