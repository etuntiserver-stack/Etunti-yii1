<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Kohteet'),
);

?>

<legend>
<h1> <?php echo Yii::t('main', 'Tuntiyhteenveto kohteet'); ?> <i class="glyphicon glyphicon-home"></i></h1>
</legend>



<div class="row" id="haku">

  <form action="#" id="yhtveto" class="form-inline" method="POST">
  <div class="col-md-12">
   <?php
/*
    $model=new Mobile;
    $list = CHtml::listData(Mobile::model()->findAll(array('group' => 'kohde_kannasta','order' => 'kohde_kannasta')), 'kohde_kannasta', 'kohde_kannasta');

    echo '<select name="kohteet" class="form-control form-group" id="kohteet">';
    if(isset(Yii::app()->session['kohteet']) and Yii::app()->session['kohteet'] != 'kaikki')
       	 echo '<option value="'.Yii::app()->session['kohteet'].'">'.Yii::app()->session['kohteet'].'</option>';

       	 echo '<option value="kaikki">Kaikki</option>';
    foreach($list as $key=>$val){

	   $strlen = strlen($val);
	   if($strlen > 27)
	    $val = substr($val,0,27).'..';
	   else
	    $val = $val;

       	 echo '<option value="'.$key.'">'.$val.'</option>';
    }
    echo '</select>';


   <select class="form-control form-group" name="mitkatKohteet">
	<?php
	if(isset(Yii::app()->session['mitkatKohteet']) and Yii::app()->session['mitkatKohteet'] == 'kohdenID')
	echo '<option value="kohdenID">'.Yii::t('main', 'Asiakkaan kohteet').'</option>';
	if(isset(Yii::app()->session['mitkatKohteet']) and Yii::app()->session['mitkatKohteet'] == 'kohde_kannasta')
	echo '<option value="kohdenID">'.Yii::t('main', 'Tuntemattomat kohteet').'</option>';
	?>
	<option value="kohdenID"><?php echo Yii::t('main', 'Asiakkaan kohteet'); ?></option>
	<option value="kohde_kannasta"><?php echo Yii::t('main', 'Tuntemattomat kohteet'); ?></option>
   </select>
*/
   ?>
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control form-group input-sm datepicker" value="<?php echo $from; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control form-group input-sm datepicker" value="<?php echo $to; ?>">


   <div class="form-group input-group-btn">
      <input type="submit" class="btn btn-primary btn-sm" value="<?php echo Yii::t('main', 'Hae'); ?>">
   </div>

   </form>

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-success btn-sm" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

  </div>
</div>

<br>

<?php if($from and $to) : ?>
  <table class="table table-striped table-bordered small">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Osoite'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th><?php echo Yii::t('main', 'Suunniteltu tunnit'); ?></th>
  <?php endif; ?>

  <th><?php echo Yii::t('main', 'Luetut tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Toteutuneet tunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Kpl'); ?></th>
  </tr>
  </thead>

  <?php 
  foreach($lu as $key=>$val)
	$this->renderPartial('_kyhteenveto',array('kohde_kannasta'=>$key,'kohdenID'=>$val,'from'=>$from,'to'=>$to));
  ?>

  <tfoot>
  <?php
	$lu = '0';
	$tot = '0';
	$suunn = '0';
	$kplyht = '0';

		$suunn = $this->yhtSUUNN($from,$to);
		$lu = $this->yhtLU($from,$to);
		$tot = $this->yhtTOT($from,$to);


	//kpl
	$kpl = 0;
	$kpl1 = 0;
	$kpl2 = 0;
	$cr4 = new CDbCriteria();
	$this->totKpl($cr4,"kaikki",$from,$to);
	$cr4->addCondition (" id NOT IN (SELECT kid FROM sivexkuitti_repaired) ");
	$k = Mobile::model()->findAll($cr4);
	foreach($k as $kk)
	$kpl1 += $kk->count;

	$cr5 = new CDbCriteria();
	$this->totKpl($cr5,"kaikki",$from,$to);
	$k = Toteutuneet::model()->findAll($cr5);
	foreach($k as $kk)
	$kpl2 += $kk->count;

	$kplyht = $kpl1+$kpl2;


  ?>
  <tr>
  <th><?php echo Yii::t('main', 'Yhteensä'); ?></th>

  <?php
  $tas = explode(",",Yii::app()->user->adminPaketti);
  if(in_array('2',$tas)) : 
  ?>
  <th>/ <?php echo Yii::t('main', 'oikeasti:'); ?><?php echo $this->sprint($suunn); ?></th>
  <?php endif; ?>

  <th><?php echo $this->sprint($lu); ?></th>
  <th><?php echo $this->sprint($tot); ?></th>
  <th><?php echo $kplyht; ?></th>
  </tr>
  </tfoot>

  </table>
<?php endif; ?>


	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>

	<input type="hidden" id="from" value="<?php echo $from; ?>">
	<input type="hidden" id="to" value="<?php echo $to; ?>">


<script type="text/javascript">
$(document).ready(function(){

$("#yhtveto").on('submit',function(e){

  var from = $("#from").val();
  var to = $("#to").val();

    if (from  === '') {
        $('#from').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }
    if (to  === '') {
        $('#to').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }

});


$(".showKuka").click(function(){
	
	var thisID = $(this).attr("id").split("_");
	var kohde_kannasta = $(this).attr("for").split("_");
	var from = $("#from").val();
	var to = $("#to").val();

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/mobile/tyobykohde',
           type: "GET",
	   data: { kohdenID : kohde_kannasta[1], from : from, to : to },
           success: function(data){
		console.log(data);
		$("#showtyo_"+thisID[1]).html(data);
           }
        });
	

});

});
</script>
