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

				playerPoolTable.column(10).visible(true);
				playerPoolTable.column(11).visible(true);
				playerPoolTable.column(14).visible(true);
				playerPoolTable.column(15).visible(true);

				playerPoolTable.column(12).visible(false);
				playerPoolTable.column(13).visible(false);
				playerPoolTable.column(16).visible(false);
				playerPoolTable.column(17).visible(false);

				playerPoolTable.column(14).order('desc');

				playerPoolTable.draw(); 

				return;

			} else if (this.value === 'Hitters') {

				// http://stackoverflow.com/questions/1538512/how-can-i-invert-a-regular-expression-in-javascript
				playerPoolTable.column(this.columnIndex).search('^(?!.*P)', true, false, false); 
			
			} else if (this.value === 'All') {

				playerPoolTable.column(this.columnIndex).search('.*', true, false, false); 
			}

			playerPoolTable.column(12).visible(true);
			playerPoolTable.column(13).visible(true);
			playerPoolTable.column(16).visible(true);
			playerPoolTable.column(17).visible(true);

			playerPoolTable.column(10).visible(false);
			playerPoolTable.column(11).visible(false);
			playerPoolTable.column(14).visible(false);
			playerPoolTable.column(15).visible(false);

			playerPoolTable.column(16).order('desc');
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
	SALARY FILTER
	****************************************************************************************/

	// https://www.datatables.net/examples/plug-ins/range_filtering.html

	$.fn.dataTable.ext.search.push(

	    function(settings, data, dataIndex) {
	        
	        var targetSalary = parseInt($('.salary-input').val(), 10);

	        var modifier = $('input:radio[name=salary-toggle]:checked').val();

	        var salary = parseFloat(data[18]) || 0; // use data for the salary column

	        if (modifier === 'greater-than') {

	        	if (salary >= targetSalary) {

	        		return true;
	        	
	        	} else {

	        		return false;
	        	}
	        }

	        if (modifier === 'less-than') {

	        	if (salary <= targetSalary) {

	        		return true;
	        	
	        	} else {

	        		return false;
	        	}
	        }		 
	    }
	);

	$('.salary-input').keyup(function() {

		playerPoolTable.draw();
	});

	$("input[name=salary-toggle]:radio").change(function() {

		playerPoolTable.draw();
	});


	$('.salary-reset').on('click', function(event) { 
		$('.salary-input').val(100000);
		$('#less-than').prop('checked', true);

		playerPoolTable.draw();
	});


	/****************************************************************************************
	POSITION FILTER
	****************************************************************************************/

	$('select.position-filter').on('change', function() {

		var value = $('select.position-filter').val();
		
		var filter = new Filter('position', 4, value, null);

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