$(document).ready(function() {

	$('a.add-dk-player-link').on('click', function(e) {

		e.preventDefault();

		$(this).closest('tr.player-row').css('text-decoration', 'line-through');
	});
});