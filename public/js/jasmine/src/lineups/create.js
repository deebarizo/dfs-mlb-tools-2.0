$(document).ready(function() {

	$('a.add-dk-player-link').on('click', function(e) {

		e.preventDefault();

		var trDkPlayer = $(this).closest('tr.dk-player');

		if (trDkPlayer.hasClass('strikethrough') === false) {

			var dkPlayer = new DkPlayer(trDkPlayer);
		}
	});
});

function DkPlayer(trDkPlayer) {

	this.id = trDkPlayer.attr('data-id');
	this.playerPoolId = trDkPlayer.attr('data-player-pool-id');
	this.position = trDkPlayer.attr('data-position');
	this.nameDk = trDkPlayer.attr('data-name-dk');
	this.teamNameDk = trDkPlayer.attr('data-team-name-dk');
	this.oppTeamNameDk = trDkPlayer.attr('data-opp-team-name-dk');
	this.salary = trDkPlayer.attr('data-salary');
	this.fpts = trDkPlayer.attr('data-fpts');

	var nameField = $('tr.dk-lineup-player[data-position="'+this.position+'"] td.dk-lineup-player-name-dk:empty:first');

	if (nameField.length > 0) {

		trDkPlayer.addClass('strikethrough');

		nameField.text(this.nameDk);
	
	} else {

		this.alert = true;

		return;
	}

	var trLineupPlayer = nameField.closest('tr');

	trLineupPlayer.find('td.dk-lineup-player-team-name-dk').text(this.teamNameDk);
	trLineupPlayer.find('td.dk-lineup-player-opp-team-name-dk').text(this.oppTeamNameDk);
	trLineupPlayer.find('td.dk-lineup-player-salary').text(this.salary);
	trLineupPlayer.find('td.dk-lineup-player-fpts').text(this.fpts);

	trLineupPlayer.attr('data-player-pool-id', this.playerPoolId);
	trLineupPlayer.attr('data-dk-player-id', this.id);
}