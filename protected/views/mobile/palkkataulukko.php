<div class="row">
<?php
/* @var $this MobileController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	Yii::t('main', 'Tunnit'),
);

?>

<legend>
<button class="pull-right btn btn-info btn-sm" data-toggle="collapse"  data-target="#haku"><?php echo Yii::t('main', 'Extrat'); ?> <b class="caret"></b></button>
<h1> <?php echo Yii::t('main', 'PALKKATAULUKKO'); ?> <i class="glyphicon glyphicon-ok"></i></h1>
</legend>

<div class="row collapse" id="haku">
  <div class="col-md-12">
   <br><br><br>
   <?php
	$ko = new Korvaukset;
	echo $this->renderPartial('//korvaukset/_form',array('model'=>$ko)); 
   ?>
   <?php
	$lt = new Lisatyotunnit;
	echo $this->renderPartial('//lisatyotunnit/_form',array('model'=>$lt)); 
   ?>
   <?php
	$en = new Ennakko;
	echo $this->renderPartial('//ennakko/_form',array('model'=>$en)); 
   ?>
  </div>
</div>

<div class="row">
  <form action="#" id="yhtveto" class="form-inline" method="POST">
  <input type="hidden" name="yhtvetoform">
  <div class="col-md-12">
   <a href="#" id="deselAll" class="glyphicon glyphicon-minus"></a>
   <a href="#" id="selAll" class="glyphicon glyphicon-plus"></a>
   <?php
    $list = CHtml::listData(Tyontekijat::model()->findAll(array('order' => 'tekijan_nimi','group'=>'tekijan_nimi')), 'id', 'tekijan_nimi');

    echo '<select name="Tekija[]" class="selectpicker" id="tyontekijat" multiple title="Työntekijät">';
    foreach($list as $key=>$val){
     if(!empty($val))
     {
       if(isset(Yii::app()->session['Tekija']) and in_array($key,Yii::app()->session['Tekija']))
       	 echo '<option value="'.$key.'" selected>'.$val.'</option>';
       else
       	 echo '<option value="'.$key.'">'.$val.'</option>';
     }
    }
    echo '</select>';
   ?>
	
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="from" id="from" class="form-control input-sm form-group datepicker" value="<?php echo $from; ?>">
   <b class="glyphicon glyphicon-calendar"></b>
   <input type="text" name="to" id="to" class="form-control input-sm form-group datepicker" value="<?php echo $to; ?>">

   <input type="submit" class="btn btn-sm btn-primary" value="<?php echo Yii::t('main', 'haku'); ?>">
   </form>

   <!-- tulostus -->
   <div class="pull-right">
     <form action="#" target="_blank" method="POST">
      <input type="hidden" name="from" value="<?php echo $from; ?>">
      <input type="hidden" name="to" value="<?php echo $to; ?>">
      <input type="submit" name="tulosta" class="btn btn-sm btn-success" value="PDF">
     </form>
   </div>
   <!-- tulostus -->

  </div>
</div>




<br>

<?php if(isset($_POST['Tekija']) and $from and $to) : ?>
  <table class="table table-striped table-bordered small">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Työpäiviä'); ?></th>
  <th><?php echo Yii::t('main', 'Matkat'); ?></th>
  <th><?php echo Yii::t('main', 'Työtunnit'); ?></th>
  <th><?php echo Yii::t('main', 'matka+<br>tunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Ilta'); ?></th>
  <th><?php echo Yii::t('main', 'iltamatka+<br>iltatunnit yht'); ?></th>
  <th><?php echo Yii::t('main', 'Yö'); ?></th>
  <th><?php echo Yii::t('main', 'Su'); ?></th>
  <th><?php echo Yii::t('main', 'PY'); ?></th>
  <th><?php echo Yii::t('main', 'EL'); ?></th>
  <th><?php echo Yii::t('main', 'SL'); ?></th>
  <th><?php echo Yii::t('main', 'SPL<br>Pvm'); ?></th>
  <th><?php echo Yii::t('main', 'LS'); ?></th>
  <th><?php echo Yii::t('main', 'Korvaus'); ?></th>
  <th><?php echo Yii::t('main', 'Lisätyötunnit'); ?></th>
  <th><?php echo Yii::t('main', 'Ennakko'); ?></th>
  </tr>
  </thead>

  <?php 
  $tids = array();
  $totalTp	= 0;
  $tot_sun	= 0;
  $tp		= 0;
  $sl 		= 0;
  $ls 		= 0;
  $spl 		= 0;
  $slYht	= 0;
  $splYht	= 0;
  $lsYht	= 0;
  $pyhatYht	= 0;
  $elYht	= 0;
  foreach($model as $data)
  {
	$tids[] = $data->id;
	$tp = $this->Tp($data->id,$from,$to);
  	$sl = $this->TidfromtoSL($from,$to,$data->id);
	$slYht += $sl;
  	$ls = $this->TidfromtoLS($from,$to,$data->id);
	$lsYht += $ls;
  	$spl = $this->TidfromtoSPL($from,$to,$data->id);
	$splYht += $spl;
	$totalTp += $tp;
  	$pyhat = $this->pyhapaivat($data->id,$from,$to,"pyhat");
	$pyhatYht += $pyhat;
  	$el = $this->pyhapaivat($data->id,$from,$to,"el");
	$elYht += $el;

	$this->renderPartial('_palkkataulukko',array(
			'data'=>$data,
			'tp'=>$tp,
			'sl'=>$sl,
			'spl'=>$spl,
			'ls'=>$ls,
			'from'=>$from,
			'to'=>$to,
			'pyhat'=>$pyhat,
			'el'=>$el,
	));
  }

	$yht[0] = 0;
	$yht[1] = 0;
	$yht[2] = 0;
	$yht[3] = 0;
	$matka = 0;
	$matkaIlta = 0;

  foreach($tids as $t)
  {
	$matka += $this->renderPartial('//mobile/tidfromtomatkat',array(
		'from'=>$from,
		'to'=>$to,
		'tid'=>$t
		),true);


	$matkaIlta += $this->matkaIlta($t,$from,$to);

	$return = $this->toteutu($t,"palkkataulukko",$from,$to);
	$yht[0] += $return[0];
	$yht[1] += $return[1];
	$yht[2] += $return[2];
	$yht[3] += $return[3];
  }

  if($matka != 0)
  $matka = $this->sprint($matka).'<br>('.$this->num($matka).')';
  if($matkaIlta != 0)
  $matkaIlta = '<br><b>Matkat</b>:<br>'.$this->num($matkaIlta);
  ?>
  <tfoot>
  <tr>
  	<th><?php echo Yii::t('main', 'Yhteensä'); ?></th>
	<td><?php echo $totalTp; ?></td>
	<td><?php echo $matka; ?></td>
	<td><?php echo $this->num($yht[0]); ?></td>
	<td><?php echo '<b>Työt</b>:<br>'.$this->num($yht[1]).$matkaIlta; ?></td>
	<td><?php echo $this->sprint($yht[2]); ?></td>
	<td><?php echo $this->sprint($yht[3]); ?></td>
	<td></td>
	<td></td>
	<td><?php echo $this->num($pyhatYht); ?></td>
	<td><?php echo $this->num($elYht); ?></td>
	<td><?php echo $this->num($slYht); ?></td>
	<td><?php echo $splYht; ?></td>
	<td><?php echo $this->num($lsYht); ?></td>
	<td></td>
	<td></td>
	<td></td>
  </tr>
  </tfoot>
  </table>
<?php endif; ?>
</div>

	<div id="showres" class="modal fade" tabindex="-1" role="dialog"></div>




<script type="text/javascript">
$(document).ready(function(){


$('.selectpicker').selectpicker({
      style: 'btn-default btn-sm',
      //size: 4
});

$('#deselAll').click(function(){
   $('#tyontekijat').selectpicker('deselectAll');
});


$('#selAll').click(function(){
   $('#tyontekijat').selectpicker('selectAll');
});




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



});
</script>
