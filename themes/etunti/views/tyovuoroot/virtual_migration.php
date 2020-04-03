<div class="page-header">
	<h1>Virtual migration script</h1>
</div>

<!-- Current status and controls -->
<a id="lbl-stage" href="#" class="btn btn-info btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Current status: 0</a>
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

		String.prototype.f = function() {
			var s = this, i = arguments.length;
			if (i == 1 && Array.isArray(arguments[0]))
				return s.f(...arguments[0]);
			while (i)
				s = s.replace(new RegExp('\\{' + i-- + '\\}', 'gm'), arguments[i]);
			return s;
		};

		var output = function(o) {

			let tags = [
				[ 'p',    { "class": [ "text" ] } ],
				[ 'span', {                     } ],
			];

			let args = [o['time'], o['step'], o['cycle'], o['text']];
			let s = '';

			switch (o['type']) {
				case 1:
					tags[0][1].class.push('text-danger', 'font-weight-bold');
					s = '{1} ({2}:{3}) (ERROR): {4}'.f(args);
					break;
				case 2:
					tags[0][1].class.push('text-warning', 'font-weight-bold');
					s = '{1} ({2}:{3}) (PROBLEM): {4}'.f(args);
					break;
				case 3:
					tags[0][1].class.push('text-primary', 'font-weight-bold');
					s = '{1} ({2}:{3}): {4}'.f(args);
					break;
				case 5:
					s = '(debug) {1} ({2}:{3}): {4}'.f(args);
					break;
				default:
					s = ' - {4}'.f(args);
					break;
			}

			for (let i = tags.length - 1; i >= 0; i--) {
				if (tags[i].length < 2 || !tags[i][1] instanceof Object) tags[i].push({});
				let attrs = '';
				for (let attr in tags[i][1])
					attrs += ' {1}="{2}"'.f(attr, Array.isArray(tags[i][1].attr) ? [...tags[i][1].attr] : tags[i][1].attr);
				s = '<{1}{2}>{3}</{1}>'.f(tags[i][0], attrs, s);
			}

			return s;
		};

		var outputPreviewAll = function() {
			$("#output").prepend(output({'type': 5, 'time': '15:31:12', 'step': 1, 'cycle': 1,  'text': 'Debug (5) information: examining something at startup, variables.'}));
			$("#output").prepend(output({'type': 4, 'time': '15:31:12', 'step': 2, 'cycle': 23, 'text': 'General (4) information or listing during a cycle, e.g. modified chains.'}));
			$("#output").prepend(output({'type': 3, 'time': '15:31:12', 'step': 3, 'cycle': 1,  'text': 'Primary (3) notification; Third step with init.'}));
			$("#output").prepend(output({'type': 2, 'time': '15:31:12', 'step': 4, 'cycle': 1,  'text': 'Problem (2) (or warning) in step 4 first cycle.'}));
			$("#output").prepend(output({'type': 1, 'time': '15:31:12', 'step': 4, 'cycle': 41, 'text': 'Error (1) happened during step 4 and stopped.'}));
		};

		var next = function(break_counter = 0) {
			if (break_counter >= 1000) {
				$("#output").prepend('<p>Break on 1000 iterations.</p>');
				return false;
			}

			var stop = false;

			$.ajax({
				url: location.protocol + "//" + location.host + "/index.php/tyovuoroot/vmigrate_ajax_next",
				type: 'GET',

				error: function(xhr, status, error) {
					stop = true;
					alert(xhr.responseText);
					// var err = eval("(" + xhr.responseText + ")");
					// alert(err.Message);
				},

				success: function(data) {
					var result = JSON.parse(data);
					stop = result['stop'];

					$.each(result['output'], function(index, item) {
						var o = $.extend(result, item);
						$("#output").prepend(output(o));
					});

					$("#lbl-stage").text(`Current status: ${result['next']} (${result['cycle']})`);
					$('#finish-text').text(`Step ${result['step']}, cycle ${result['cycle']}`);

					if (result['next'] == 99) {
						$("#output").prepend(`<p><b>${result['time']} - FINISHED</b></p>`);
						stop = true;
					} else if (result['stop']) {
						$("#output").prepend(`<p>${result['time']}: Stopped on ${result['step']}:${result['cycle']}.</p>`);
						stop = true;
					} else if (result['step'] != result['next']) {
						$("#output").prepend(`<p><b>${result['time']} - Step ${result['step']} done.</b></p>`);
						stop = true;
					}
				},

				complete: function() {
					return stop ? true : next(++break_counter);
				}
			});
		};

		$("#btn-next").on("click", function() {
			// outputPreviewAll();
			next();
		});
	});
</script>