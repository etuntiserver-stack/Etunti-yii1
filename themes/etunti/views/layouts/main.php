<?php
$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;


$sub = explode(".",$_SERVER['HTTP_HOST']);
if (
		(! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') 
		and ($_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1')
		and $sub[0] != 'staging'
) {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
}
?>

<?php if($curpage != 'kohteet/googlemap') : ?>
<!DOCTYPE html>
<?php endif; ?>

<html>
<head>
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <title>ETUNTI</title>
  <meta name="keywords" content="etunti" />
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
Yii::app()->clientScript->registerPackage('bootstrapCSS');
/*
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');
*/


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

<?php // endif; ?>


	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="sb-l-o sb-r-c onload-check sb-l-m sb-l-disable-animation">

<?php if($curpage == 'tyovuoroot/index') : ?>
	<?php echo ''; ?>
<?php  else : ?>
	<?php echo $this->renderPartial('//site/navbar'); ?>
<?php  endif; ?>

<div class="container-fluid">

	<?php echo $content; ?>

</div><!-- page -->


<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
{ (i[r].q=i[r].q||[]).push(arguments)}
,i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-72418912-1', 'auto');
ga('send', 'pageview');
</script>

</body>
</html>
