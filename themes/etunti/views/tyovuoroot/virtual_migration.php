<div class="page-header">
	<h1>Virtual migration script</h1>
</div>

<!-- Current status and controls -->
<a id="lbl-stage" href="#" class="btn btn-info btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Current status: <?= $step ?? 0 ?></a>
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
				url: location.protocol + "//" + location.host + "/index.php/tyovuoroot/vmigrate_ajax_next",
				type: 'GET',

				// type: 'POST',
				// data: {
				// 	"var": t
				// },

				error: function(xhr, status, error) {
					var err = eval("(" + xhr.responseText + ")");
					alert(err.Message);
				},

				success: function(data) {
					var result = JSON.parse(data);

					$.each(result['output'], function(index, item) {
						$("#output").prepend(`<p>${item['time']} (${item['verbosity']}) (Step: ${result['step']}:${result['cycle']}): ${item['text']}</p>`);
					});
					$.each(result['errors'], function(index, item) {
						$("#output").prepend(`<p class="text-danger">${item['time']} (ERROR) (${item['verbosity']}) (Step: ${result['step']}:${result['cycle']}): ${item['text']}</p>`);
					});

					$("#lbl-stage").text(`Current status: ${result['next']} (${result['cycle']})`);
					$('#finish-text').text(`Step ${result['step']}, cycle ${result['cycle']}`);

					if (result['step'] != result['next']) {
						finished = true;
						$("#output").prepend(`<p><b>${result['time']} - Step ${result['step']} done.</b></p>`);
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