<?php

?>

<!-- begin: .tray-center -->
<div class="tray-center">

<div class="pull-right">
	<span class="btn btn-primary myBgColors poista" id="<?=$model->id?>">poista</span>
</div>
<h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo $model->nimike; ?> </h2>
<p id="varoitus"></p>

    <div class="admin-form">
      <div class="panel heading-border">
        <div class="panel-body bg-light">
         <div class="row">
			<?php echo $this->renderPartial('_form', array('model' => $model, 'netvisor' => $netvisor)); ?>
         </div>
        </div>
      </div>
    </div>

<!-- loppu: .tray-center -->
</div>

<script type="text/javascript">
$(document).ready(function(){

	$(".poista").click(function(e){
	
		var model_id = $(this).attr("id");
		e.preventDefault();
		
		if(confirm('Haluatko varmaasti poistaa tämän?'))
		{
			$.ajax({
				url: "delete?id="+ model_id +'&confirm=false',
				success: function(data){
					data = JSON.parse(data);
					//console.log(data);
					if(data['varoitus'])
					{
						$("#varoitus").html('<div class="well">' + data['varoitus'] + ' <br><p><button class="btn btn-primary poistanyt" model_id="'+ model_id +'">Poista nyt</button></p></div>');
					}
						
				}
			});
		} else {
			return false;
		}
	});

	$(document).delegate(".poistanyt","click",function(){

		var model_id = $(this).attr("model_id");
		var poistettava_hinnastot = [];
		$( ".poistettava_hinnasto:checkbox:checked" ).each(function() {
		  	poistettava_hinnastot.push($( this ).attr('id'));
		});

		$.ajax({
			url: "delete?id="+ model_id +'&confirm=true',
			type: "POST",
			data: { poistettava_hinnastot : JSON.stringify(poistettava_hinnastot) },
			success: function(data){
				data = JSON.parse(data);
				//console.log(data);
				if(data['return'] == 'ok')
						window.location.href="index"
			}
		});
	});    
});
</script>
