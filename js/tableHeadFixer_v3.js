(function($) {

	$.fn.tableHeadFixer = function(param) {
		var defaults = {
			head: true,
			foot: false,
			left: 0,
			right: 0
		};

		var settings = $.extend({}, defaults, param);

		return this.each(function() {
			settings.table = this;
			settings.parent = $("<div id='scrp'></div>");
			setParent();

			if(settings.head == true)
				fixHead();

			if(settings.foot == true)
				fixFoot();

			if(settings.left > 0)
				fixLeft();

			if(settings.right > 0)
				fixRight();

			// self.setCorner();

			$(settings.parent).trigger("scroll");

			$(window).resize(function() {
				$(settings.parent).trigger("scroll");
			});
		});

		function setTable(table) {

		}

function isScrolledIntoView(elem){
    var $elem = $(elem);
    var $window = $(window);
    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();
    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

function trlaatikkot(lt){
	var forThis = $(lt).attr("for");
	$("#"+forThis).html('odota..');
	var pvm = $(lt).attr("pvm");
	var tid = $(lt).attr("tid");
	var from = $(lt).attr("from");
	var kohteet_siivous = $(lt).attr("kohteet_siivous");
	var asiakas = $(lt).attr("asiakas");
	var kohde = $(lt).attr("kohde");

	var xhr = new XMLHttpRequest();
	xhr.open("POST", 'didnew3', true);
	xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	xhr.onload = function () {
		d = JSON.parse(xhr.responseText);
		//console.log(xhr.responseText)
		$("#"+forThis).html(d);
	};
	xhr.send('pvm='+pvm+'&tid='+tid+'&from='+from+'&kohteet_siivous='+kohteet_siivous+'&asiakas='+asiakas+'&kohde='+kohde);
}


		function setParent() {
			var container = $(settings.table).parent();
			var parent = $(settings.parent);
			var table = $(settings.table);
			var ajettuid = [];

			table.before(parent);
			parent.append(table);
			parent
				.css({
					'width' : '100%',
					'height' : container.css("height"),
					'overflow' : 'scroll',
					'max-height' : container.css("max-height"),
					'min-height' : container.css("min-height"),
					'max-width' : container.css('max-width'),
					'min-width' : container.css('min-width')
				});

			parent.scroll(function() {
				var scrollWidth = parent[0].scrollWidth;
				var clientWidth = parent[0].clientWidth;
				var scrollHeight = parent[0].scrollHeight;
				var clientHeight = parent[0].clientHeight;
				var top = parent.scrollTop();
				var left = parent.scrollLeft();

				if(settings.head)
					this.find("thead tr > *").css("top", top);

				if(settings.foot)
					this.find("tfoot tr > *").css("bottom", scrollHeight - clientHeight - top);

				if(settings.left > 0)
					settings.leftColumns.css("left", left);

				if(settings.right > 0)
					settings.rightColumns.css("right", scrollWidth - clientWidth - left);

				var thisparent = this;
				ajaaLt (thisparent, ajettuid);


			}.bind(table));
		}

		function ajaaLt (th, ajettuid) {
		   	$( $(th).find(".luolaatiko") ).not(".opened").each(function( index ) {
			   if( isScrolledIntoView( $(this).closest('tr') ) ){
				var thisID = $(this).closest('tr').attr('id');
				ajettuid[thisID] = thisID;
				$(this).closest('tr').addClass('opened');
				trlaatikkot(this);
				//console.log( 'ajettu id: ' +thisID );
			   }
			});
		}

		function fixHead () {
			var thead = $(settings.table).find("thead");
			var tr = thead.find("tr");
			var cells = thead.find("tr > *");

			setBackground(cells);
			cells.css({
				'position' : 'relative'
			});
		}

		function fixFoot () {
			var tfoot = $(settings.table).find("tfoot");
			var tr = tfoot.find("tr");
			var cells = tfoot.find("tr > *");

			setBackground(cells);
			cells.css({
				'position' : 'relative'
			});
		}

		function fixLeft () {
			var table = $(settings.table);

			var fixColumn = settings.left;

			settings.leftColumns = $();

			for(var i = 1; i <= fixColumn; i++) {
				settings.leftColumns = settings.leftColumns
					.add(table.find("tr > *:nth-child(" + i + ")"));
			}

			var column = settings.leftColumns;

			column.each(function(k, cell) {
				var cell = $(cell);

				setBackground(cell);
				cell.css({
					'position' : 'relative'
				});
			});
		}

		function fixRight () {
			var table = $(settings.table);

			var fixColumn = settings.right;

			settings.rightColumns = $();

			for(var i = 1; i <= fixColumn; i++) {
				settings.rightColumns = settings.rightColumns
					.add(table.find("tr > *:nth-last-child(" + i + ")"));
			}

			var column = settings.rightColumns;

			column.each(function(k, cell) {
				var cell = $(cell);

				setBackground(cell);
				cell.css({
					'position' : 'relative'
				});
			});

		}

		function setBackground(elements) {
			elements.each(function(k, element) {
				var element = $(element);
				var parent = $(element).parent();

				var elementBackground = element.css("background-color");
				elementBackground = (elementBackground == "transparent" || elementBackground == "rgba(0, 0, 0, 0)") ? null : elementBackground;

				var parentBackground = parent.css("background-color");
				parentBackground = (parentBackground == "transparent" || parentBackground == "rgba(0, 0, 0, 0)") ? null : parentBackground;

				var background = parentBackground ? parentBackground : "white";
				background = elementBackground ? elementBackground : background;

				element.css("background-color", background);
			});
		}
	};

})(jQuery);
