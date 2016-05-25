describe('Create Lineup', function () {
  	
  	it('offers crucial function', function () {
    	
    	expect(loadFixtures).toBeDefined();
  	});

  	it('loads fixture from a file', function () {
    	
    	loadFixtures('index.html');
    	expect($('#dk-players')).toExist();
  	});
});