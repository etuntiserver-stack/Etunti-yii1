<?php

?>
<br>
    <div class="container">
      <div class="header clearfix">

      <div class="jumbotron">
        <h1><i class="fa fa-info-circle" aria-hidden="true"></i> </h1>
        <p class="lead">
		<p>
		<?=$vastaus?>
		</p>

	</p>
      </div>

      </div>
     </div>


<script type="text/javascript">
$(document).ready(function(){

  $(".yes").click(function(){
	var link = document.URL;
	window.location.href=link+ '&confirm=1';
  });

});
</script>

