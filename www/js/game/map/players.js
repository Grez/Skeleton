$(function() {
	window.Players = $class({

		selector: '#mainMap',
		players: undefined,

		constructor: function (players) {
			var self = this;

			for (var i = 0; i < players.length; i++) {
				var player = players[i];
				var div = $('#' + player.positionId);
				div.addClass('player');

				var html = '<div class="player-description player"><b>' + player.name + '</b><br>';
				html += 'Level: ' + player.level + '<br>';
				html += '</div>';
				div.append(html);
			}
		},

	});
});
