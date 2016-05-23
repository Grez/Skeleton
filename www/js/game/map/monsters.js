$(function() {
	window.Monsters = $class({

		selector: '#mainMap',
		monsters: undefined,

		constructor: function (monsters) {
			var self = this;

			for (var i = 0; i < monsters.length; i++) {
				var monster = monsters[i];
				var div = $('#' + monster.positionId);
				div.addClass('monster');

				var html = '<div class="monster-description"><b>' + monster.name + '</b><br>';
				html += 'Level: ' + monster.level + '<br>';
				html += 'Stats: ' + monster.attack + '/' + monster.hp;
				html += '</div>';
				div.append(html);
			}
		},

	});
});
