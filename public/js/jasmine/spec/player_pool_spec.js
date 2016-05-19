var fixture = $('<tr data-dk-player-id="2505" data-player-pool-id="4" data-player-id="31" data-team-name-dk="Pit" data-position="SP" data-salary="10400" data-name="Francisco Liriano" class="player-row odd" role="row"><td>Francisco Liriano</td><td>Pit</td><td>Atl</td><td></td><td>SP</td><td>9</td><td>0</td><td>Live</td><td>23.58</td><td>20.50</td><td>0.00</td><td class="sorting_1">22.04</td><td>11.79</td><td>10400</td><td>2.27</td><td>1.97</td><td>0.00</td><td class="">2.12</td><td class="">1.13</td></tr>')

describe('Player Pool', function () {
  	
  	it('should show only Pit players', function () {
    	
    	expect(fixture).toContainText('Liriano');
  	});
});