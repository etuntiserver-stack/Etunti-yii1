<?php

?>

<legend>
  <h1> 
	<?php echo Yii::t('main', 'TIETOKANNAT'); ?> <i class="glyphicon glyphicon-phone"></i> 
  </h1>
</legend>

<div class="row">
  <div class="col-sm-4">
    <form action="#" class="form-inline" method="POST">
	<input type="text" name="domain" class="form-control form-group" placeholder="domain">
	<input type="hidden" name="method" value="getStrukture">
	<input type="submit" class="btn btn-success" value="<?php echo Yii::t('main', 'GET MySQL strukture'); ?>">
    </form>
  </div>
</div>

<?php if(isset($_POST['method']) and $_POST['method'] == 'getStrukture') : ?>

<?php
$connection=new DB2ActiveRecord('localhost','root','');
$connection->active=true;
/*
  $results = Yii::app()->db->createCommand()->
          select('id')->
          from('sivex')->
          order('id DESC')->
          limit(5)->
          queryAll();

  var_dump($results);
*/
?>
<br>
<div class="row">
  <div class="col-sm-12">
	<textarea class="form-control" rows="20"></textarea>
  </div>
</div>
<?php endif; ?>
