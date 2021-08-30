<?php
$curpage = Yii::app()->getController()->getAction()->controller->id;
$curpage .= '/'.Yii::app()->getController()->getAction()->controller->action->id;
?>

<?php if($curpage != 'kohteet/googlemap') : ?>
<!DOCTYPE html>
<?php endif; ?>

<html>
<head>
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <title>Etunti - voita toiminnan haasteet</title>
  <meta name="keywords" content="etunti" />
  <meta name="description" content="AdminDesigns - A Responsive HTML5 Admin UI Framework">
  <meta name="author" content="AdminDesigns">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!--<link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600'>-->
  <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/css/fonts300600.css'>

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

  <!-- select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <script defer src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



  <!--<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">-->



<?php 
Yii::app()->clientScript->registerPackage('bootstrapCSS');
Yii::app()->clientScript->registerPackage('jquery');

/*
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('bootstrapJS');
Yii::app()->clientScript->registerPackage('bootstrapCSS');
*/


// <-- Huoltokatko
if(isset(Yii::app()->user->domain))
{
  $domainit = Domainit::model()->find(" domain='".Yii::app()->user->domain."' ");
  if(Yii::app()->user->username != 'etunti' and isset($domainit->huoltokatko) and $domainit->huoltokatko == 1)
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
  $asetukset_forall = AsetuksetForAll::model()->findByPk(1);
  if(isset($asetukset_forall->session_aikamaara) and $asetukset_forall->session_aikamaara > 0)
  {
echo '<script type="text/javascript">
$(document).ready(function(){
   var lukumaara = 3600 * 1000 * parseInt('.$asetukset_forall->session_aikamaara.');
   setTimeout(function () {
       window.location.href = location.protocol + "//" + location.host + "/index.php/user/logout";
    }, lukumaara);
   console.log("Sivu päivitetään "+ lukumaara +" sekunnin kuluttua");
});
</script>';
  }
}
// Aktiivinen -->


if(
	isset(Yii::app()->user->nimi) and !empty(Yii::app()->user->nimi)
	and isset(Yii::app()->user->username) and Yii::app()->user->username != 'etunti'
  ){
  /* online */
  $criteria = new CDbCriteria();
  $criteria->condition = " time < '".(time()-900)."' ";
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
  $online->ip = (isset($_SERVER['REMOTE_ADDR']))?$_SERVER['REMOTE_ADDR']:'';
  $online->session = Yii::app()->getSession()->getSessionId();
  $online->user = Yii::app()->user->nimi;
  if(!$online->save()){ var_dump($online->getErrors());}
  }
  /* online */
}

?>

<!-- Kokeiluversio -->
<?php 		
		if(
			isset(Yii::app()->user->ilmainen) 
			and Yii::app()->user->ilmainen == true
			and isset(Yii::app()->user->ilmainen_kayttotunnit) 
			and Yii::app()->user->ilmainen_kayttotunnit > $asetukset_forall->max_ilmaiset_tunnit)
		: ?>
<script type="text/javascript">
$(document).ready(function(){

	$('input[type="text"]').attr('readonly', 'yes').attr('data-toggle', 'tooltip').attr('title', 'Kokeiluversion aikaraja on täyttynyt.');
	$("select").attr('disabled', 'yes').attr('data-toggle', 'tooltip').attr('title', 'Kokeiluversion aikaraja on täyttynyt.');
	$('input[type="submit"]').attr('disabled', 'yes').attr('data-toggle', 'tooltip').attr('title', 'Kokeiluversion aikaraja on täyttynyt.');
	$('.plussa, #uusiTilaus').remove();

});
</script>
<?php endif; ?>
<!-- Kokeiluversio -->

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body class="<?php if(isset(Yii::app()->user->currentBody)) echo Yii::app()->user->currentBody; ?>">
<div id="hovertietoja" style="display:none"></div>

<?php  if($curpage == 'tyovuoroot/index' and isset($_GET['fullscreen']) and $_GET['fullscreen'] == true) : ?>
	<?php echo ''; ?>
<?php  else : ?>
	<?php echo $this->renderPartial('//site/navbar'); ?>
<?php  endif; ?>


	<?php
	foreach(Yii::app()->user->getFlashes() as $key => $message) {
        	echo '
		<p>
		<div class="container-fluid">
		 <div class="row">
		  <div class="col-sm-12">
		   <div class="tray-center">
			<div class="alert bg-' . $key . '">' . $message . "</div>
		   </div>
		  </div>
		 </div>
		</div>
		</p>
		";
    	}
	?>


<div class="">
	<?php echo $content; ?>

<?php if( $_SERVER['REMOTE_ADDR'] != '::1' and $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ) : ?>
<script type="text/javascript" src="https://etunti.atlassian.net/s/d41d8cd98f00b204e9800998ecf8427e-T/-3x7nu4/100023/c/1000.0.11/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=f67c367e"></script>
<?php endif; ?>

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
