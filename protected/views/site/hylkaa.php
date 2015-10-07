<?php

if(isset($_POST['palaute'])){

  $model = AsiakasHyvaksynta::model()->find(" id='".$_POST['id']."' and code='".$_POST['code']."' ");

  if(isset($model['id']))
  {
	$ids = explode(",",$model['ids']);
	foreach($ids as $val)
	{
	    $explVal = explode("_", $val);
	    if(isset($explVal[1]))
	    {

		if($explVal[0] == 'mobile') 
		{
		   Mobile::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'2//'.date("d.m.Y").'//'.$_POST['palaute'],'status'=>2));
		   //echo $explVal[1].'<br>';
		}

		if($explVal[0] == 'toteutu')
		{
		   Toteutuneet::model()->updatebypk($explVal[1], array('asiakas_hyvaksy'=>'2//'.date("d.m.Y").'//'.$_POST['palaute'],'status'=>2));
		   //echo $explVal[1].'<br>';
		}

	    }

	}

  AsiakasHyvaksynta::model()->updatebypk($model['id'], array('code'=>''));

  }

}
?>

<?php if(!empty($id) and !empty($code) and !empty($domain)) {

  $model = AsiakasHyvaksynta::model()->find(" id='".$_GET['id']."' and code='".$_GET['code']."' ");
  if(isset($model['id']))
  {
?>
<div class="row form palauteDiv">
  <div class="col-sm-3">
	<form action="#" id="palaute" method="POST">
	<input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
	<input type="hidden" name="code" id="code" value="<?php echo $code; ?>">
	<input type="hidden" name="domain" id="domain" value="<?php echo $domain; ?>">
	<label><?php echo Yii::t('main','Pikku selitys'); ?></label>
	<textarea name="palaute" id="palauteText" maxlength="70" class="form-control"></textarea>
	<button class="btn btn-primary" id="tallenna-btn"><?php echo Yii::t('main','Lähetä'); ?></button>
	</form>
  </div>
</div>
<?php 

  }
} 
?>



<script type="text/javascript">
$(document).ready(function(){


$("#tallenna-btn").click(function(){
	$("#UusiAsiakasForm").submit();
});


$('#palaute').on('submit',function(e) {

  $("#tallenna-btn").replaceWith("Odota");
  var palauteText = $("#palauteText").val();

    if (palauteText  === '') {
        $('#palauteText').css({"border" : "2px #f14010 solid"}).focus();
        return false;
    }


$(this).attr("name");

  $.ajax({
  url:'hylkaa?id='+$("#id").val()+'&code='+$("#code").val()+'&domain='+$("#domain").val(),
  data:$(this).serialize(),
  type:'POST',
  success:function(data){
  console.log(data);
	//alert(data)
	$('.palauteDiv').html('<h2>Kiitos</h2>');
	return false;
  },
  error:function(data){

  }
});

e.preventDefault(); 
});


});
</script>

