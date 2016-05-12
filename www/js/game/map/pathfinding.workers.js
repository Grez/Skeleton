$(function() {
	window.Pathfinding = $class({

		selector: '#mainMap',
		map: undefined,
		start: undefined,
		target: undefined,

		constructor: function(graph, start) {
			var self = this;
			this.map = $(this.selector);
			this.setStart(start);

			$(this.map).on('click', '.cell', function () {
				var target = self.getPositionFromCell($(this));
				self.setTarget(target);
			});
		},

		setStart: function (start) {
			this.start = start;
			var cell = this.getCellFromPosition(start);

			cell.addClass('start');
			var html = '<div class="start-description"><b>Yup! That\'s you!</b><br>';
			cell.append(html);
		},

		setTarget: function (target) {
			var self = this;
			self.map.addClass('loading');
			self.createPath(target);
		},

		erasePath: function () {
			this.map.find('.path').removeClass('path');
			this.map.find('.target').removeClass('target');
		},

		getCellFromPosition: function (position) {
			var virtualX = position[0];
			var virtualY = position[1];
			return this.map.find(' .cell[data-virtual-x=' + virtualX + '][data-virtual-y=' + virtualY + ']');
		},

		getPositionFromCell: function (cell) {
			var virtualX = cell.data('virtualX');
			var virtualY = cell.data('virtualY');
			return [virtualX, virtualY];
		},

		createPath: function (target) {
			var self = this;
			var cell = this.getCellFromPosition(target);
			this.target = target;

			this.erasePath();
			cell.addClass('target');

			var worker = new Worker('/main/map-js-worker');
			worker.onmessage = function (e) {
				var result = e.data;
				if (!result.length) {
					self.erasePath();
					$('#frm-map-moveForm input[name=cost]').val('');
					$('#frm-map-moveForm input[name=positions]').val('');
					$('#frm-map-moveForm').fadeOut('slow');
					self.map.removeClass('loading');
					return;
				}

				var positionIds = [];
				var weight = 0;
				for (var i = 0; i < result.length; i++) {
					var gridNode = result[i];
					var x = gridNode.x;
					var y = gridNode.y;
					var cell = $('.cell[data-virtual-x=' + x + '][data-virtual-y=' + y + ']');
					cell.addClass('path');
					positionIds.push(cell.attr('title'));
					weight += result[i].weight;
				}
				weight = Math.ceil(weight);

				$('#frm-map-moveForm input[name=cost]').val(weight);
				$('#frm-map-moveForm input[name=positions]').val(positionIds.join(','));
				$('#frm-map-moveForm').fadeIn('slow');
				self.map.removeClass('loading');
			};
			var data = { map: localStorage.getItem('mapJs'), start: this.start, target: this.target };
			worker.postMessage(data);
		}

	});
});
