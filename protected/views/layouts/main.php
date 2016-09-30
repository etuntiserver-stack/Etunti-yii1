<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="fi" />
	<meta name="viewport" content="width=device-width, initial-scale=1">


  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/openSans.css" rel="stylesheet" type="text/css">



  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.2.min.js"></script>


<?php 
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');


if(isset(Yii::app()->user->nimi))
{
  /* online */
  $criteria = new CDbCriteria();
  $criteria->condition = " time < '".(time()-600)."' ";
  UsersOnline::model()->deleteAll($criteria);

  $criteria = new CDbCriteria();
  $criteria->condition = " user ='".Yii::app()->user->nimi."' ";
  $uo = UsersOnline::model()->find($criteria);
  
  if(isset($uo->id))
  {
  UsersOnline::model()->updatebypk($uo->id,array('time'=>time()));
  } else {
  $online = new UsersOnline();
  $online->time = time();
  $online->ip = CHttpRequest::getUserHostAddress();;
  $online->session = Yii::app()->getSession()->getSessionId();
  $online->user = Yii::app()->user->nimi;
  $online->save();
  }
  /* online */
}

?>



	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>


<div class="container-fluid">
	<!--
	<div id="header" class="row">
	<a href="index.php">Sivu</a>
	</div><!-- header -->

<!--
	<div id="mainmenu" class="row">
		<?php  $this->widget('zii.widgets.CMenu',array(
			'items'=>array(

				array('label'=>Yii::t('main', 'Etusivu'), 'url'=>array('/site/index')),
				array('label'=>Yii::t('main', 'Luetut'), 'url'=>array('/mobile/index'), 'visible'=>isset(Yii::app()->user->adminID)),
				//array('label'=>Yii::t('main', 'About'), 'url'=>array('/site/page', 'view'=>'about')),
				//array('label'=>Yii::t('main', 'Contact'), 'url'=>array('/site/contact')),
				array('label'=>Yii::t('main', 'Sisään'), 'url'=>array('/user/login'), 'visible'=>!isset(Yii::app()->user->adminID)),
				//array('label'=>Yii::t('main', 'Profile'), 'url'=>array('/user/profile'), 'visible'=>!Yii::app()->user->isGuest),
				array('label'=>Yii::t('main', 'Ulos'), 'url'=>array('/site/logout'), 'visible'=>isset(Yii::app()->user->adminID))
			),
		)); ?>
	</div>
-->



	<?php echo $content; ?>




</div><!-- page -->
</center>
</body>
</html>
