<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

//echo Yii::app()->user->isGuest ;
//print_r(Yii::app()->user);

/*
$criteria = new CDbCriteria;
$criteria->select = " COUNT(*) as count,imei ";
$criteria->group = " imei ";
$t = Tyontekijat::model()->findAll($criteria); 
foreach($t as $g)
echo $g->imei.' '.$g->count.'<br>';
*/
?>




<?php
/*

define( 'API_ACCESS_KEY', 'AIzaSyCnMuvBry0dxLPntaAW-HSLrcyrkwy6BzY' );
$registrationIds = array( 'fhmNdmNvSi4:APA91bFggwwe1OZfYtNUAJeTTz9_pzhEVcKR_cbCJUkoeMA8n50RmxmOHIYQ4syOD7d-qxow2GGALm1dJHl4bgRYj7RrrUug70uVW-cBWLhTFUP9kPryp8piT2lr3lGi9ocJod88Pizp' );
// prep the bundle
$msg = array
(
	'message' 	=> 'here is a message. message',
	'title'		=> 'This is a title. title',
	'subtitle'	=> 'This is a subtitle. subtitle',
	'tickerText'	=> 'Ticker text here...Ticker text here...Ticker text here',
	'vibrate'	=> 1,
	'sound'		=> 1,
	'largeIcon'	=> 'large_icon',
	'smallIcon'	=> 'small_icon'
);
$fields = array
(
	'registration_ids' 	=> $registrationIds,
	'data'			=> $msg
);
 
$headers = array
(
	'Authorization: key=' . API_ACCESS_KEY,
	'Content-Type: application/json'
);
 
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
echo $result;

*/
?>






<?php 
if(isset(Yii::app()->user->adminID))
{
  if(isset($_SESSION['domain']))
  echo '<h1>Domaini: '.$_SESSION['domain'].'</h1>';

for ($i = 1; $i <= 12; $i++) {


	$criteria = new CDbCriteria;
	$criteria->addCondition('YEAR(time) = '.date("Y"));
	$criteria->addCondition('MONTH(time) = '.$i);
	$result = Mobile::model()->findAll($criteria);
  

    $res = $result;
    $kk[$i] = count ( $res );
}

        $this->widget(
            'chartjs.widgets.ChBars', 
            array(
                'width' => 600,
                'height' => 300,
                'htmlOptions' => array(),
                'labels' => array("Tammikuu","Helmikuu","Maaliskuu","Huhtikuu","Toukokuu","Kesäkuu","Heinäkuu","Elokuu","Syyskuu","Lokakuu","Marraskuu", "Joulukuu"),
                'datasets' => array(
                    array(
                        "fillColor" => "rgba(100,100,220,1)",
                        "strokeColor" => "rgba(220,220,220,1)",
                        "data" => array($kk[1],$kk[2],$kk[3],$kk[4],$kk[5],$kk[6],$kk[7],$kk[8],$kk[9],$kk[10],$kk[11],$kk[12])
                    )       
                ),
                'options' => array()
            )
        ); 
    
}
?>


<?php /* if(!isset(Yii::app()->user->adminID)) : ?>
<!--
<div class="row">
 <div class="col-md-12">
   <div class="alert alert-success">
	<b><?php echo Yii::t('main', 'TilaamisenJalkeen'); ?></b>
   </div>
 </div>
</div>
-->
<br>


    <div class="row">
        <div class="col-md-4 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'TILAUKSEN VALINTA'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong><?php echo Yii::t('main', 'Valitse sopiiva paketti'); ?></strong>
                    </p>
                </div>
                <ul class="list-group list-group-flush text-center">
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työajanseuranta liikuvalle työlle ja Mobiili sovellus'); ?>
                        <strong class="pull-right"><input type="checkbox" checked value="15" disabled></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työvuorosuunnittelu ohjelma'); ?>
                        <strong class="pull-right"><input type="checkbox" value="10"></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Asiakkuuksien hallinta ohjelma'); ?>
                        <strong class="pull-right"><input type="checkbox" value="20"></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Työnjohdon kuntopuntari'); ?>
                        <strong class="pull-right"><input type="checkbox" value="10"></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Laskutus ohjelma'); ?>
                        <strong class="pull-right"><input type="checkbox" value="10"></strong>
                    </li>
                    <li class="list-group-item">
                        <?php echo Yii::t('main', 'Online varaus'); ?>
                        <strong class="pull-right"><input type="checkbox" value="10"></strong>
                    </li>
                </ul>
                <div class="panel-footer">
                    <a class="btn btn-lg btn-block btn-success"><?php echo Yii::t('main', 'Tilaa heti!'); ?> <i id="res"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h4 class="text-center"><?php echo Yii::t('main', 'TIEDOT'); ?></h4>
                </div>
                <div class="panel-body text-center">
                    <p class="lead">
                        <strong><?php echo Yii::t('main', 'Tähään tulee tiedot paketista'); ?></strong>
                    </p>
                </div>
            </div>
	</div>
    </div>



<?php endif; */ ?>




<script type="text/javascript">
$(document).ready(function(){

   $(':checkbox').click(function() {
      	$("#res").html(checkChecked()+" &euro;")
   });

   function checkChecked(){
     var str = 0;
      $(':checkbox').each(function() {
         str += this.checked ? Number($(this).val()) : 0;
      });
     return str;
   }

      	$("#res").html(checkChecked()+" &euro;")

});
</script>



