<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

if(isset($_POST['uploaded']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/migraatio/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/migraatio/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/migraatio/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . 'tyontekijat.xlsx';
  if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
     //echo "";
  }

}

?>


        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main', 'Työntekijän migraatio'); ?> 
		<?php echo CHtml::link('','/index.php/tyontekijat/create',array('class'=>'btn btn-default fa fa-plus')); ?></h2>



            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

<div class="row">
<div class="pull-right">
 <div class="kuva form-inline">
  <label><?php echo Yii::t('main', 'Syötä Excel tiedosto'); ?></label>
  <form id="uploadimage" action="#" class="form-input" method="post" enctype="multipart/form-data">
   <input type="hidden" name="uploaded" value="true" />
   <input type="file" name="file" id="i_file" data-icon="false" data-buttonText="Etsi kuvaa" class="form-group" />
   <input type="submit" value="Lataa" class="btn btn-primary btn-group myBgColors" id="kuvaUP" /></button>
  </form>
 </div>
</div>
</div>



                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>


  <div class="panel heading-border">
   <div class="panel-body">


<?php
	$i = 0;
	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/migraatio/'.Yii::app()->user->domain.'/tyontekijat.xlsx')) as $file) {
	$i++;
	$explNimi = explode("/",$file);
 	echo '
	<div class="form-inline">
	  <a href="../../'.$file.'">'.end($explNimi).'</a>
	</div>
	';


     	$phpExcelPath = Yii::import('ext.phpexcel.PHPExcel');
	$objPHPExcel = new PHPExcel();



	}
?>

   </div>
  </div>


