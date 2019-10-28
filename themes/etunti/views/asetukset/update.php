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


	   <!--Varmuskopiot-->
	   <p><div class="btn btn-primary btn-block myBgColors" data-toggle="collapse" data-target="#dumpit"><h3><?php echo Yii::t('main','Varmuskopio dumpit'); ?>&nbsp; <i class="fa fa-caret-square-o-down" aria-hidden="true"></i></h3></div></p>

            <div class="admin-form collapse" id="dumpit">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
		<p><?php echo CHtml::link('Luo uusi varmuskopio','http://host.fi/'.strtolower(Yii::app()->user->domain), array('class' => 'btn btn-lg btn-primary myBgColors')); ?></p>

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
	   <!--Varmuskopiot-->

<?php if(Yii::app()->user->username == 'roman'): ?>
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

<?php endif; ?><!-- is admin -->


        <!-- loppu: .tray-center -->
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
/*
		    $version['short'] = "v1.".trim($version_number[0]);
		    $version['full'] = "v1.".trim($version_number[0]).".$version_mini_hash[0] (".str_replace('commit ','',$line[0]).")";
		    echo Yii::t('main', 'Versio').':  '.$version['short'];
*/
		}
		// GIT version -->
	  	?>
                </div>


                </div>
              </div>
            </div>
        </div><!--tray-center-->






