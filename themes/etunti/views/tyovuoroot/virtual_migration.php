<div class="page-header">
	<h1>Virtuaalisten Työvuorojen Migraatio</h1>
</div>

<!-- Current status and controls -->
<a id="lbl-stage" href="#" class="btn btn-info btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Tämänhetkinen Vaihe: 0</a>
<a id="btn-next" href="#" class="btn btn-primary btn-lg" tabindex="-1" role="button">Seuraava</a>
<a id="btn-stop" href="#" class="btn btn-danger btn-lg" tabindex="-1" role="button">Pysäytä</a>
<a id="btn-reset" href="#" class="btn btn-warning btn-lg" tabindex="-1" role="button">Resetoi</a><br><br>

<!-- Stage finish output -->
<div class="well well-sm"><b style="float:left">Viimeisin Tilanne:&nbsp;</b>
	<strong>
		<div id="finish-text">&nbsp;</div>
	</strong>
</div>

<!-- Full AJAX output -->
<div class="well well-sm" style="height:550px;overflow-y:scroll;" id="output"></div>


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

		var running = false;
		var stop = false;

		var output = function(o) {

			let s = '{1} ({2}:{3})';
			let cls = '';

			switch (Math.abs(o['type'])) {
				case 1:
					s += ' (VIRHE)';
					cls = 'text-danger';
					break;
				case 2:
					s += ' (ONGELMA)';
					cls = 'text-warning';
					break;
				case 3:
					cls = 'text-success';
					break;
				case 5:
					break;
				case 6:
					s = '(debug) ' + s;
					break;
				default:
					cls = 'text-primary';
					break;
			}

			s += ': {4}';
			s = s.f([o['time'], o['step'], o['cycle'], o['text']]);

			if (cls)
				cls = ' class="{1}"'.f(cls);
			s = `<b>${s}</b>`;

			if (o['type'] <= 0)
				$("#finish-text").html(`<span${cls}>${s}</span>`);

			s = `<p${cls}>${s}</p>`;

			if (o['type'] != 0)
				$("#output").prepend(s);

			return s;
		};

		var outputm = function(type, text) {
			return output({ 'type': type, 'time': "00:00:00", 'step': 0, 'cycle': 0, 'text': text });
		}

		var next = function(step = -1) {
			var step_str = (step >= 0) ? ("?step=" + step) : "";

			$.ajax({
				url: location.protocol + "//" + location.host + "/index.php/tyovuoroot/vmigrate_ajax_next" + step_str,
				type: 'GET',

				error: function(xhr, status, error) {
					outputm(1, xhr.responseText);
					if (!confirm("Virhe, jatketaanko? " + xhr.responseText))
						stop = true;
				},

				success: function(data) {
					var result;
					try {
						result = JSON.parse(data);
					}
					catch (e) {
						console.log("error: "+e);
						stop = true;
						return false;
					};

					$.each(result['output'], function(index, item) {
						var o = $.extend(result, item);
						output(o);
					});

					$("#lbl-stage").text(`Vaihe: ${result['next']} (${result['cycle']})`);

					if (result['errors']) {
						stop = true;
						outputm(0, "Pysäytetty virheiden takia");
						outputm(1, "Pysäytetty virheiden takia");
					} else if (result['next'] >= 99) {
						stop = true;
						outputm(0, "Migraatio Valmis");
						outputm(3, "Migraatio Valmis");
					} else if (result['step'] != result['next']) {
						stop = true;
						outputm(0, "Vaihe valmis, seuraava: {1}".f(result['next']));
						outputm(3, "Vaihe valmis, seuraava: {1}".f(result['next']));
					}
				},

				complete: function() {
					if (stop) {
						running = false;
						stop = false;
						$("#btn-next, #btn-reset").removeClass("disabled");
						$("#btn-next, #btn-reset").attr("aria-disabled", false);
						return true;
					} else {
						return next();
					}
				}
			});
		};

		var start = function(step = -1) {
			if (!running) {
				$("#btn-next, #btn-reset").addClass("disabled");
				$("#btn-next, #btn-reset").attr("aria-disabled", true);
				running = true;
				stop = false;
				next(step);
			}
		};

		$("#btn-next").on("click", function(e) {
			e.preventDefault();
			start();
		});

		$("#btn-stop").on("click", function(e) {
			e.preventDefault();
			stop = true;
		});

		$("#btn-reset").on("click", function(e) {
			e.preventDefault();
			start(0);
		});
	});
</script>