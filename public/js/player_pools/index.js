$(document).ready(function() {

	/****************************************************************************************
	FILTER
	****************************************************************************************/

	function Filter(type, columnIndex, value, modifier) {

		this.type = type;
		this.columnIndex = columnIndex;
		this.value = value;
		this.modifier = modifier;
	}

	Filter.prototype.execute = function() {

		// https://datatables.net/reference/api/column().search()

		if (this.type === 'position') {

			if (this.value !== 'Hitters' && this.value !== 'SP' && this.value !== 'All') {

				playerPoolTable.column(this.columnIndex).search(this.value, true, false, false); 

			} else if (this.value === 'SP') {

				playerPoolTable.column(this.columnIndex).search('(SP|RP)', true, false, false); 

			} else if (this.value === 'Hitters') {

				// http://stackoverflow.com/questions/1538512/how-can-i-invert-a-regular-expression-in-javascript
				playerPoolTable.column(this.columnIndex).search('^(?!.*P)', true, false, false); 
			
			} else if (this.value === 'All') {

				playerPoolTable.column(this.columnIndex).search('.*', true, false, false); 
			}
		}
		
		if (this.type === 'team') {

			if (this.value !== 'All') {

				playerPoolTable.column(this.columnIndex).search(this.value, true, false, false); 
			
			} else if (this.value === 'All') {

				playerPoolTable.column(this.columnIndex).search('.*', true, false, false); 
			}
		}

		playerPoolTable.draw();
	};


	/****************************************************************************************
	POSITION FILTER
	****************************************************************************************/

	$('select.position-filter').on('change', function() {

		var value = $('select.position-filter').val();
		
		var filter = new Filter('position', 3, value, null);

		filter.execute();
	});


	/****************************************************************************************
	TEAM FILTER
	****************************************************************************************/

	$('select.team-filter').on('change', function() {

		var value = $('select.team-filter').val();
		
		var filter = new Filter('team', 1, value, null);

		filter.execute();
	});

});