


describe('Create Lineup', function () {

	beforeEach(function() {

		loadFixtures('lineups_create.html');
	});	

  	it('should see that table exists', function () {

  		// var tableRow;
 
 		// tableRow.find('a.add-dk-player-link').trigger("click");
    	// expect($('tr[data-name="Chris Sale"]')).toHaveCss({text-decoration: "line-through"});

    	expect($('table#dk-players')).toExist();
  	});

  	it('should see that table exists 2', function () {

  		var tableRow = $('tr[data-name="Chris Sale"]');

  		expect(tableRow).toExist();
 
 		tableRow.find('a.add-dk-player-link').trigger('click');
    	expect($('tr[data-name="Chris Sale"]')).toHaveCss({'text-decoration': 'line-through'});
  	});
});