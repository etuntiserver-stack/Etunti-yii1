<?php
if(isset($_POST['num'])){
	$num = $_POST['num'];
}

?>

     <TR class="kaikkiTR" id="trRivi_<?php echo $num; ?>">
	<TD><span class="btn btn-sm btn-danger poista" for="poista_<?php echo $num; ?>">X</span></TD>
	<TD>
<input type="text" size="1" name="tkoodi[<?php echo $num; ?>]" id="tkoodi_<?php echo $num; ?>" class="for_tkoodi form-control input-sm" value="" data-toggle="collapse"  data-target="#lt_<?php echo $num; ?>">
	<?php
	echo CHtml::dropdownList('','palvelu', CHtml::listData(LaskutusTuotteet::model()->findAll(), 'id', 'tuotenimi'), array('empty'=>'Valitse tuote/palvelu','class'=>'form-control collapse valitseTuote input-sm','id'=>'lt_'.$num,'num'=>$num));
	?>
	</TD>

	<TD><input type="text" size="5" name="kpl[<?php echo $num; ?>]" id="kpl_<?php echo $num; ?>" class="onlyDigits form-control input-sm" value=""><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="yksikko[<?php echo $num; ?>]" id="yksikko_<?php echo $num; ?>" class="form-control input-sm">
		<?php echo $this->yksikkot(null); ?>
		</select>
	</TD>
	<TD><input type="text" size="10" name="hinta[<?php echo $num; ?>]" id="hinta_<?php echo $num; ?>" class="onlyDigits form-control input-sm" value=""><span class="errmsg"></span></TD>
	<TD>
		<select type="text" name="alv[<?php echo $num; ?>]" id="alv_<?php echo $num; ?>" class="form-control input-sm">
		<?php echo $this->alv(null); ?>
		</select>
	</TD>
	<TD><input class="yhteensa_total_verot form-control input-sm" size="10" type="text" name="hinta_alv[<?php echo $num; ?>]" id="hinta_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input type="text" size="10" name="ale[<?php echo $num; ?>]" id="ale_<?php echo $num; ?>" value="0" class="onlyDigits form-control input-sm"><span class="errmsg"></span></TD>
	<TD><input class="yhteensa_total_veroton form-control input-sm" type="text" size="10" name="veroton[<?php echo $num; ?>]" id="veroton_<?php echo $num; ?>" value="0.00" readonly></TD>
	<TD><input class="yhteensa_total form-control input-sm" type="text" size="10" name="yhteensa_alv[<?php echo $num; ?>]" id="yhteensa_alv_<?php echo $num; ?>" value="0.00" readonly></TD>
     </TR>




<script type="text/javascript">
$(document).ready(function(){

$(".valitseTuote").change(function() {
    var tuoteID = $(this).val();
    var num = $(this).attr("num");

        $.ajax({
           url: location.protocol + "//" + location.host + '/index.php/lasku/valitsetuote',
           type: "POST",
           data: { tuoteID : tuoteID },
           success: function(data){
		var sp = data.split("//");

		$("#kpl_"+num).val("1");

		if(sp[0])
		$("#tkoodi_"+num).val(sp[0]);
		if(sp[1])
		$("#hinta_"+num).val(sp[1]);
		if(sp[3])
		$("#yksikko_"+num+" option[value="+sp[3]+"]").attr('selected','selected');
		if(sp[2])
		$("#alv_"+num+" option[value="+sp[2]+"]").attr('selected','selected');

		eachLaskenta();
		console.log(data)
           }
        });

});





function eachLaskenta(){

  $("#rivit input").each(function() {

	var inputKenta = $(this).attr("id").split("_");
	var hinta_alv_0 = $("#hinta_"+inputKenta[1]).val();
	var alv = $("#alv_"+inputKenta[1]).val();
	var kpl = $("#kpl_"+inputKenta[1]).val();
	var ale = $("#ale_"+inputKenta[1]).val();


	var laske = parseFloat(((hinta_alv_0*kpl)/100*alv), 10);
	var laskeAleY = parseFloat((($("#yhteensa_alv_"+inputKenta[1]).val())/100*ale), 10);
	var laskeAleV = parseFloat((($("#veroton_"+inputKenta[1]).val())/100*ale), 10);

	var veroton = parseFloat(hinta_alv_0, 10)*kpl;
	yhteensa = laske+veroton;

	$("#hinta_alv_"+inputKenta[1]).val((laske).toFixed(2));

	if(veroton-laskeAleV > 0)
	  $("#veroton_"+inputKenta[1]).val((veroton-laskeAleV).toFixed(2));
	else
	  $("#veroton_"+inputKenta[1]).val('0.00');

	if(yhteensa-laskeAleY > 0)
	  $("#yhteensa_alv_"+inputKenta[1]).val((yhteensa-laskeAleY).toFixed(2));
	else
	  $("#yhteensa_alv_"+inputKenta[1]).val('0.00');

  });
    	yhteensaTotal();

}

function yhteensaTotal(){

	var sum = 0;
	$('.yhteensa_total_verot').each(function(){
	    sum += parseFloat(this.value);
	    $('#yhteensa_total_verot').val(sum.toFixed(2));
	});
	var sum1 = 0;
	$('.yhteensa_total_veroton').each(function(){
	    sum1 += parseFloat(this.value);
	    $('#yhteensa_total_veroton').val(sum1.toFixed(2));
	});
	var sum2 = 0;
	$('.yhteensa_total').each(function(){
	    sum2 += parseFloat(this.value);
	    $('#yhteensa_total').val(sum2.toFixed(2));
	});
}


});
</script>
