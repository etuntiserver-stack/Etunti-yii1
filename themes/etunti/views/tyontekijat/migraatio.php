<?php
/* @var $this KohteetController */
/* @var $dataProvider CActiveDataProvider */

if(isset($_POST['uploaded']))
{

  if (!file_exists(Yii::app()->basePath."/../tiedostot/migraatio/".Yii::app()->user->domain)) {
  	mkdir(Yii::app()->basePath."/../tiedostot/migraatio/".Yii::app()->user->domain, 0777, true);
  }

  $uploaddir = Yii::app()->basePath.'/../tiedostot/migraatio/'.Yii::app()->user->domain.'/';
  $uploadfile = $uploaddir . 'excel.xlsx';
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
if(isset($_POST['esikatselu']))
{
	  unset($_POST['esikatselu']);

	  $file = Yii::app()->baseUrl.'tiedostot/migraatio/'.Yii::app()->user->domain.'/excel.xlsx';
	  Yii::import('ext.phpexcel.PHPExcel',true);
	  function getXLS($xls){
	
	    $objPHPExcel = PHPExcel_IOFactory::load($xls);
	    $objPHPExcel->setActiveSheetIndex(0);
	    $aSheet = $objPHPExcel->getActiveSheet();
	 
	    $array = array();
	    foreach($aSheet->getRowIterator() as $row){
	      $cellIterator = $row->getCellIterator();
	      $item = array();
	      foreach($cellIterator as $cell){
	        array_push($item, $cell->getCalculatedValue());
	      }
	      array_push($array, $item);
	    }
	    return $array;
	  }
	 

  	  $xlsData = getXLS($file);
  	  if(isset($xlsData[0]))
	  {
	  $i = 0;
	  $sar = array();
	  foreach($_POST as $key=>$val)
	  {
		$sar[$i] = $val;
		$i++;
	  }

	  	unset($xlsData[0]);
		foreach($xlsData as $xls)
		{
		    $model=new Tyontekijat;
		    foreach($xls as $datak=>$datav)
		    {
			$model->$sar[$datak]=$datav;
		    }
			$model->aktiivinen=1;
		     	if($model->save())
			{
				//echo 'ok '.$model->id.'<br>';
			} else {
		   		var_dump($model->getErrors());
			}
		}
	  }




} else {


	$i = 0;
	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/migraatio/'.Yii::app()->user->domain.'/excel.xlsx')) as $file) {
	$i++;
	$explNimi = explode("/",$file);

	echo '<h2>Aktiivinen tiedosto: <a href="../../tiedostot/migraatio/'.Yii::app()->user->domain.'/'.end($explNimi).'">'.end($explNimi).'</a></h2>';

	  Yii::import('ext.phpexcel.PHPExcel',true);
	  function getXLS($xls){
	
	    $objPHPExcel = PHPExcel_IOFactory::load($xls);
	    $objPHPExcel->setActiveSheetIndex(0);
	    $aSheet = $objPHPExcel->getActiveSheet();
	 
	    $array = array();
	    foreach($aSheet->getRowIterator() as $row){
	      $cellIterator = $row->getCellIterator();
	      $item = array();
	      foreach($cellIterator as $cell){
	        array_push($item, $cell->getCalculatedValue());
	      }
	      array_push($array, $item);
	    }
	    return $array;
	  }
	 
  	  $xlsData = getXLS($file);
  	  if(isset($xlsData[0]))
	  {


		echo '
		<form action="#" method="POST">
		<table class="table">
		<tr>
		<th>Teidän sarake</th>
		<th>Meidän sarakkeet</th>
		</tr>	
		';
		foreach($xlsData[0] as $sarakkeet)
		{
	  	echo '<tr><td>';

		echo $sarakkeet;

		$attributes = Tyontekijat::model()->getAttributes();
		unset(	
		$attributes['laiten_puh'],
		$attributes['tyoryhma'],
		$attributes['tyoehtosopimus'],
		$attributes['tekijan_konttori'],
		$attributes['aktiivinen'],
		$attributes['tekijan_muisti'],
		$attributes['salasana'],
		$attributes['online_varauksen_valmina'],
		$attributes['kortit'],
		$attributes['ayjasenyys'],
		$attributes['gcm_reg_id'],
		$attributes['position'],
		$attributes['tekijan_kulunvalvonta']
		);

		$m = new Tyontekijat();
		echo '</td><td>
		<select name="'.$sarakkeet.'" class="form-control">';
		foreach($attributes as $key=>$val)
		{
			echo '<option value="'.$key.'">'.$m->getAttributeLabel($key).'</option>';
		}
		echo '</select>
		  </td>
		</tr>';

		}
		echo '</table>
		<br>
		<input type="submit" name="esikatselu" value="Vie tietokantaan" class="btn btn-primary myBgColors">
		</form>';
		

	  }

	}
}
?>

   </div>
  </div>




  <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main', 'Nykyinen tietokanta'); ?></h2>


  <div class="panel heading-border">
   <div class="panel-body">

<div id="adminTable">
<?php
	$model=new Tyontekijat('search');
	$model->unsetAttributes();  // clear any default values


	$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'tyontekijat-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,


        'pagerCssClass' => 'dataTables_paginate paging_bootstrap',
        'itemsCssClass' => 'table table-hover',


	'columns'=>array(
		'id',
		'imei',
		'tekijan_nimi',
		'tekijan_puh',
		'tekijan_email',
		'tyoryhma',

		array(
			'class'=>'CButtonColumn',
		),

	),
)); ?>
</div>

   </div>
 </div>

