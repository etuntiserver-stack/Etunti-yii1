<?php
$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;
//echo $curpage;
?>

<?php if($curpage != 'kohteet/googlemap') : ?>
<!DOCTYPE html>
<?php endif; ?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="fi" />
	<meta name="viewport" content="width=device-width, initial-scale=1">


  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
  <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/openSans.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/footer-distributed.css">

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.11.2.min.js"></script>



  <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->



<?php 
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');

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
?>

<?php // endif; ?>


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<?php if(
	isset(Yii::app()->user->adminStatus) 
	and Yii::app()->user->adminStatus == 2 
	and $curpage == 'tyovuoroot/index')
 : ?>
	<?php echo ''; ?>
<?php  else : ?>
	<?php echo $this->renderPartial('//site/navbar'); ?>
<?php  endif; ?>

<div class="container-fluid">

	<?php echo $content; ?>

</div><!-- page -->
<br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<?php if($curpage != 'tyovuoroot/index') : ?>
		<footer class="footer-distributed">

			<div class="footer-right">

				<a href="#"><i class="fa fa-facebook"></i></a>
				<a href="#"><i class="fa fa-twitter"></i></a>
				<a href="#"><i class="fa fa-linkedin"></i></a>
				<a href="#"><i class="fa fa-github"></i></a>

			</div>

			<div class="footer-left">

				<p class="footer-links">
					<?php echo CHtml::link('Etusivu',array('/site/etusivu')); ?>
					·

				</p>

				<p>ETUNTI.FI &copy; 2015</p>
			</div>

		</footer>
<?php endif; ?>

</body>
</html>
