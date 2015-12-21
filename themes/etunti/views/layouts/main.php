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
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <title>AdminDesigns - A Responsive HTML5 Admin UI Framework</title>
  <meta name="keywords" content="HTML5 Bootstrap 3 Admin Template UI Theme" />
  <meta name="description" content="AdminDesigns - A Responsive HTML5 Admin UI Framework">
  <meta name="author" content="AdminDesigns">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Font CSS (Via CDN) -->
  <link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>

  <!-- Theme CSS -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/skin/default_skin/css/theme.css">

  <!-- Admin Forms CSS -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/admin-tools/admin-forms/css/admin-forms.css">

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/img/favicon.ico">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->



  <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->



<?php 
/*
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');
*/
?>

<?php // endif; ?>


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<?php if($curpage == 'tyovuoroot/index') : ?>
	<?php echo ''; ?>
<?php  else : ?>
	<?php echo $this->renderPartial('//site/navbar'); ?>
<?php  endif; ?>

<div class="container-fluid">

	<?php echo $content; ?>

</div><!-- page -->

</body>
</html>
