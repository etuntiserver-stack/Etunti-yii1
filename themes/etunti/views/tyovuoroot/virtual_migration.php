<div class="page-header">
	<h1>Virtual migration script</h1>
</div>

<!-- Current status and controls -->
<a id="lbl-stage" href="#" class="btn btn-info btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Current status: <?= $status ?? 0 ?></a>
<a id="btn-next" href="#" class="btn btn-primary btn-lg" tabindex="-1" role="button">Next Step</a><br><br>

<!-- Stage finish output -->
<div class="well well-sm"><b style="float:left">Last:&nbsp;</b>
	<strong>
		<div id="finish-text">&nbsp;</div>
	</strong>
</div>

<!-- Full AJAX output -->
<div class="well well-sm" id="output"></div>


<script>
	$(function() {
		var next = function(break_counter = 0) {
			if (break_counter >= 1000) {
				$("#output").prepend('<p>Break on 1000 iterations.</p>');
				return false;
			}

			var finished = false;

			$.ajax({
				url: location.protocol + "//" + location.host + "/index.php/tyovuoroot/vm_next",
				type: 'GET',

				// type: 'POST',
				// data: {
				// 	"var": t
				// },

				success: function(data) {
					result = JSON.parse(data);
					$("#output").prepend(`<p>${result['text']}</p>`);
					$("#lbl-stage").text(`Current status: ${result['next']}`);
					if (result['finished']) {
						finished = true;
						if (result['finish_text']) {
							$('#finish-text').text(result['finish_text']);
							$('#output').prepend(`<p><b>${result['finish_text']}</b></p>`);
						} else {
							$('#finish-text').text(result['text']);
						}
					}
				},

				complete: function() {
					return finished ? true : next(++break_counter);
				}
			});
		};

		$("#btn-next").on("click", function() {
			next();
		});
	});
</script>