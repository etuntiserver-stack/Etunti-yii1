<?php
$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;

/*
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
    	$agent = $_SERVER['HTTP_USER_AGENT'];
    }
*/
/*
$sub = explode(".",$_SERVER['HTTP_HOST']);
if (
		(! isset($_SERVER['HTTPS']) or $_SERVER['HTTPS'] == 'off') 
		and ($_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1')
) {

    if (strlen(strstr($agent, 'Firefox')) > 0) {

    } else {
    $redirect_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header("Location: $redirect_url");
    exit();
    }

}
*/
?>

<?php if($curpage != 'kohteet/googlemap') : ?>
<!DOCTYPE html>
<?php endif; ?>

<html>
<head>
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <title>Etunti - voita siivousalan haasteet</title>
  <meta name="keywords" content="etunti" />
  <meta name="description" content="AdminDesigns - A Responsive HTML5 Admin UI Framework">
  <meta name="author" content="AdminDesigns">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>
<?php
/*
    if (strlen(strstr($agent, 'Firefox')) > 0) {
    echo "<link rel='stylesheet' type='text/css' href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>";
    } else {
    echo "<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>";
    }
*/
?>

  

  <!-- Theme CSS -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/skin/default_skin/css/theme.css">

  <!-- Admin Forms CSS -->
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets_etunti/assets/admin-tools/admin-forms/css/admin-forms.css">

  <!-- Favicon -->
  <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/img/favicon.ico">

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

// <-- Huoltokatko
if(isset(Yii::app()->user->domain))
{
  $domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");
  if(isset($domainit->huoltokatko) and $domainit->huoltokatko == 1)
  {
	echo '

    <div class="container">
      <div class="header clearfix">

      <div class="jumbotron">
        <h1><i class="fa fa-wrench" aria-hidden="true"></i> '.Yii::t('main', 'HUOLTOKATKO').'</h1>
        <p class="lead">
		<p>
		Palvelussamme on huoltokatko<br>
		Verkkopalvelumme ovat tilapäisesti poissa käytöstä. Pahoittelemme katkosta aiheutuvaa häiriötä.<br>
		</p>

		<p>
		Service is temporarily unavailable
		Our service is temporarily unavailable. We apologize for any inconvenience this might cause for You.
		</p>
	</p>
      </div>

        </div>
      </div>

	';
	exit;
  }
}
// Huoltokatko -->



// <-- Aktiivinen
if(isset(Yii::app()->user->domain))
{
  $domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");
  if(isset($domainit->aktiivinen) and $domainit->aktiivinen == 0)
  {
	echo '

    <div class="container">
      <div class="header clearfix">

      <div class="jumbotron">
        <h1><i class="fa fa-wrench" aria-hidden="true"></i> '.Yii::t('main', 'VIRHE').'</h1>
        <p class="lead">
		<p>
		Yrityksenne domain on lukittu. Ota yhteyttä tukeemme tuki@etunti.fi.<br>
		</p>

		<p>
		Your company\'s domain is locked. Please contact our service tuki@etunti.fi.
		</p>
	</p>
      </div>

        </div>
      </div>

	';
	exit;
  }
}
// Aktiivinen -->


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

<body class="<?php if(isset(Yii::app()->user->currentBody)) echo Yii::app()->user->currentBody; ?>">

<?php  if($curpage == 'tyovuoroot/index' and isset($_GET['fullscreen']) and $_GET['fullscreen'] == true) : ?>
	<?php echo ''; ?>
<?php  else : ?>
	<?php echo $this->renderPartial('//site/navbar'); ?>
<?php  endif; ?>



<div class="container-fluid">

	<?php echo $content; ?>

	<footer>
	 <div class="row">
	  <div class="col-sm-3 col-sm-offset-1">
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
	</footer>

<script type="text/javascript" src="https://etunti.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/-3x7nu4/100023/c/1000.0.11/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=f67c367e"></script>

</div><!-- page -->





<?php if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) : ?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
{ (i[r].q=i[r].q||[]).push(arguments)}
,i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-72418912-1', 'auto');
ga('send', 'pageview');
</script>
<?php endif; ?>

</body>
</html>
