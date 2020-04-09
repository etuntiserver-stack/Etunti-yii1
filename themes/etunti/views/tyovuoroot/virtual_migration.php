<div class="page-header">
	<h1>Virtuaalisten Työvuorojen Migraatio</h1>
</div>

<!-- Current status and controls -->
<a id="lbl-stage" href="#" class="btn btn-info btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Tämänhetkinen Vaihe: 0</a>
<a id="btn-next" href="#" class="btn btn-primary btn-lg" tabindex="-1" role="button">Aloita Seuraava Vaihe</a><br><br>

<!-- Stage finish output -->
<div class="well well-sm"><b style="float:left">Viimeisin Tilanne:&nbsp;</b>
	<strong>
		<div id="finish-text">&nbsp;</div>
	</strong>
</div>

<!-- Full AJAX output -->
<div class="well well-sm overflow-auto" id="output"></div>


<script>
	$(function() {

		String.prototype.f = function() {
			var s = this, i = arguments.length;
			if (i == 1 && Array.isArray(arguments[0]))
				return s.f(...arguments[0]);
			while (i)
				s = s.replace(new RegExp('\\{' + i-- + '\\}', 'gm'), arguments[i]);
			return s;
		};

		var outputm = function(type, result, text) {
			return output({ 'type': type, 'time': result['time'], 'step': result['step'], 'cycle': result['cycle'], 'text': text });
		}

		var output = function(o) {

			let args = [o['time'], o['step'], o['cycle'], o['text']];
			let cls, s = '';
			let bold = false;

			switch (Math.abs(o['type'])) {
				case 1:
					s = '{1} ({2}:{3}) (ERROR): {4}'.f(args);
					cls = 'text-danger';
					bold = true;
					break;
				case 2:
					s = '{1} ({2}:{3}) (PROBLEM): {4}'.f(args);
					cls = 'text-warning';
					bold = true;
					break;
				case 3:
					s = '{1} ({2}:{3}) (DONE): {4}'.f(args);
					cls = 'text-success';
					bold = true;
					break;
				case 5:
					s = ' - {4}'.f(args);
					break;
				case 6:
					s = '(debug) {1} ({2}:{3}): {4}'.f(args);
					break;
				default:
					s = '{1} ({2}:{3}): {4}'.f(args);
					cls = 'text-primary';
					bold = true;
					break;
			}

			if (cls)
				cls = ' class="{1}"'.f(cls);
			if (bold)
				s = `<b>${s}</b>`;

			if (o['type'] <= 0)
				$("#finish-text").html(`<span${cls}>${s}</span>`);

			s = `<p${cls}>${s}</p>`;

			if (o['type'] != 0)
				$("#output").prepend(s);

			return s;
		};

		var outputPreviewAll = function() {
			output({'type': 6, 'time': '15:31:12', 'step': 1, 'cycle': 1,  'text': 'Debug (6) information: examining something at startup, variables.'});
			output({'type': 5, 'time': '15:31:12', 'step': 2, 'cycle': 23, 'text': 'General (5) information or listing during a cycle, e.g. modified chains.'});
			output({'type': 4, 'time': '15:31:12', 'step': 3, 'cycle': 1,  'text': 'Primary (4) notification; Third step with init.'});
			output({'type': 3, 'time': '15:31:12', 'step': 3, 'cycle': 1,  'text': 'Success (3) notification.'});
			output({'type': 2, 'time': '15:31:12', 'step': 4, 'cycle': 1,  'text': 'Problem (2) (or warning) in step 4 first cycle.'});
			output({'type': 1, 'time': '15:31:12', 'step': 4, 'cycle': 41, 'text': 'Error (1) happened during step 4 and stopped.'});
		};

		var next = function(break_counter = 0) {
			var stop = false;

			$.ajax({
				url: location.protocol + "//" + location.host + "/index.php/tyovuoroot/vmigrate_ajax_next",
				type: 'GET',

				error: function(xhr, status, error) {
					stop = true;
					alert(xhr.responseText);
				},

				success: function(data) {
					var result = JSON.parse(data);

					$.each(result['output'], function(index, item) {
						var o = $.extend(result, item);
						output(o);
					});

					$("#lbl-stage").text(`Vaihe: ${result['next']} (${result['cycle']})`);

					if (result['errors'] || result['next'] < 0) {
						stop = true;
						outputm(3, result, "Pysäytetty virheiden takia");
						$("#btn-next").html("Jatka");
						$("#btn-next").removeClass("disabled");
						$("#btn-next").attr("aria-disabled", false);
					} else if (result['next'] == 99) {
						stop = true;
						$("#output").prepend("<p class='text-success'><b><span>Migraatio Valmis.</span></b></p>".f(result['next']));
						$("#btn-next").html("Migraatio Valmis");
						$("#btn-next").addClass("disabled");
						$("#btn-next").attr("aria-disabled", true);
					} else if (result['step'] != result['next'] && result['next'] >= 0) {
						stop = true;
						$("#output").prepend("<p class='text-success'><b><span>Vaihe valmis, seuraava: {1}</span></b></p>".f(result['next']));
						$("#btn-next").html("Aloita Seuraava Vaihe: ({1})".f(result['next']));
						$("#btn-next").removeClass("disabled");
						$("#btn-next").attr("aria-disabled", false);
					}
				},

				complete: function() {
					return stop ? true : next(++break_counter);
				}
			});
		};

		$("#btn-next").on("click", function(e) {
			// outputPreviewAll();
			e.preventDefault();

			$(this).html("Prosessoidaan ...");
			$(this).addClass("disabled");
			$(this).attr("aria-disabled", true);

			$("#output").prepend("<p class='text-primary'><b><span>Aloitettiin seuraava vaihe</span></b></p>");
			next();
		});
	});
</script>