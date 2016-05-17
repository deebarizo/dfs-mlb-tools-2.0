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

		if (this.type === 'salary') {

			var salary = Number(this.value);

			if (this.modifier === 'greater-than') {

				console.log(this);
				console.log(salary);

				playerPoolTable.column(this.columnIndex).data().filter(function(value, salary) {

					return value > salary ? true : false; 
				}); 

			} else if (this.modifier === 'less-than' ){

			}
		}

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
	SALARY FILTER
	****************************************************************************************/

	// https://www.datatables.net/examples/plug-ins/range_filtering.html

	$.fn.dataTable.ext.search.push(

	    function(settings, data, dataIndex) {
	        
	        var targetSalary = parseInt($('.salary-input').val(), 10);

	        var modifier = $('input:radio[name=salary-toggle]:checked').val();

	        var salary = parseFloat(data[10]) || 0; // use data for the salary column

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