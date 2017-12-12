<?php

?>
<br><br>
<?php if($tilanne == 1) : ?>
<div class="container">

<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
  <div class="panel panel-default">
    <div class="panel-heading"><?php echo Yii::t('main', 'Salasanan luominen'); ?></div>
    <div class="panel-body">


<form method="post" id="passwordForm">
<input type="password" class="input-lg form-control" name="password1" id="password1" placeholder="Uusi salasana" autocomplete="off">
<div class="row">
<div class="col-sm-6">
<span id="8char" class="fa fa-remove" style="color:#FF0004;"></span> 8 merkkiä pitkä<br>
<span id="ucase" class="fa fa-remove" style="color:#FF0004;"></span> Yksi iso kirjain
</div>
<div class="col-sm-6">
<span id="lcase" class="fa fa-remove" style="color:#FF0004;"></span> Yksi pieni kirjain<br>
<span id="num" class="fa fa-remove" style="color:#FF0004;"></span> Yksi numero
</div>
</div>
<input type="password" class="input-lg form-control" name="password2" id="password2" placeholder="Vahvista salasana" autocomplete="off">
<div class="row">
<div class="col-sm-12">
<span id="pwmatch" class="fa fa-remove" style="color:#FF0004;"></span> Salasana oikein
</div>
</div>
<input type="submit" class="col-xs-12 btn btn-primary btn-load btn-lg" data-loading-text="Changing Password..." value="Luo">
</form>

    </div>
  </div>
 </div>
</div>

<?php endif; ?>


<?php if($tilanne == 2) : ?>
<div class="container">
<div class="row">
<div class="col-sm-6 col-sm-offset-3">
<p class="text-center"><h1>Sivu on vanhentunut</h1></p>
</div><!--/col-sm-6-->
</div><!--/row-->
</div>
<?php endif; ?>

<script>
$(document).ready(function(){

$("input[type=password]").keyup(function(){
    var ucase = new RegExp("[A-Z]+");
	var lcase = new RegExp("[a-z]+");
	var num = new RegExp("[0-9]+");
	
	if($("#password1").val().length >= 8){
		$("#8char").removeClass("glyphicon-remove");
		$("#8char").addClass("glyphicon-ok");
		$("#8char").css("color","#00A41E");
	}else{
		$("#8char").removeClass("glyphicon-ok");
		$("#8char").addClass("glyphicon-remove");
		$("#8char").css("color","#FF0004");
	}
	
	if(ucase.test($("#password1").val())){
		$("#ucase").removeClass("glyphicon-remove");
		$("#ucase").addClass("glyphicon-ok");
		$("#ucase").css("color","#00A41E");
	}else{
		$("#ucase").removeClass("glyphicon-ok");
		$("#ucase").addClass("glyphicon-remove");
		$("#ucase").css("color","#FF0004");
	}
	
	if(lcase.test($("#password1").val())){
		$("#lcase").removeClass("glyphicon-remove");
		$("#lcase").addClass("glyphicon-ok");
		$("#lcase").css("color","#00A41E");
	}else{
		$("#lcase").removeClass("glyphicon-ok");
		$("#lcase").addClass("glyphicon-remove");
		$("#lcase").css("color","#FF0004");
	}
	
	if(num.test($("#password1").val())){
		$("#num").removeClass("glyphicon-remove");
		$("#num").addClass("glyphicon-ok");
		$("#num").css("color","#00A41E");
	}else{
		$("#num").removeClass("glyphicon-ok");
		$("#num").addClass("glyphicon-remove");
		$("#num").css("color","#FF0004");
	}
	
	if($("#password1").val() == $("#password2").val()){
		$("#pwmatch").removeClass("glyphicon-remove");
		$("#pwmatch").addClass("glyphicon-ok");
		$("#pwmatch").css("color","#00A41E");
	}else{
		$("#pwmatch").removeClass("glyphicon-ok");
		$("#pwmatch").addClass("glyphicon-remove");
		$("#pwmatch").css("color","#FF0004");
	}
});
});
</script>
