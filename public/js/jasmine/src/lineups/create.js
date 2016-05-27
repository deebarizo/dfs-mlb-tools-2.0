$(document).ready(function() {

	$('a.add-dk-player-link').on('click', function(e) {

		e.preventDefault();

		var trDkPlayer = $(this).closest('tr.dk-player');

		var dkPlayer = new DkPlayer(trDkPlayer);
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

	if (this.position.indexOf('P') > -1) {

		this.position = 'P';
	}

	switch(this.position) {

	    case 'P':
	        var positionCount = 2;
	        break;
	    
	    case 'OF':
	        var positionCount = 3;
	        break;
	    
	    default:
	        var positionCount = 1;
	}

	for (var i = 0; i < positionCount; i++) { 
		
		var lineupPlayerNameDk = $('tr.dk-lineup-player[data-position="'+this.position+'"]').eq(i).find('td.dk-lineup-player-name-dk');

		if (lineupPlayerNameDk.text().trim() == '') {

			lineupPlayerNameDk.text(this.nameDk);

			trDkPlayer.addClass('strikethrough');

			break;
		}
	}

	if (trDkPlayer.hasClass('strikethrough') === false) {

		$('span.lineup-error').text('');

		$('div.alert.lineup').hide();

    	$('span.lineup-error').text('The "'+this.position+'" position is already filled.');

    	$('div.alert.lineup').show();
	}
}