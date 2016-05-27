describe('Creating a new DkPlayer object', function () {

    it('should create specific property values', function () {

        loadFixtures('lineups_create.html');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.dkPlayer = new DkPlayer(this.trDkPlayer);

        expect(this.dkPlayer.id).toBe('7482');
        expect(this.dkPlayer.playerPoolId).toBe('11');
        expect(this.dkPlayer.position).toBe('P');
        expect(this.dkPlayer.nameDk).toBe('Bob Jones');
        expect(this.dkPlayer.teamNameDk).toBe('CWS');
        expect(this.dkPlayer.oppTeamNameDk).toBe('Cle');
        expect(this.dkPlayer.salary).toBe('13000');
        expect(this.dkPlayer.fpts).toBe('20.21');
    });  
});

describe('Clicking the "Add" link for your first pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups_create.html');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');
    }); 

    it('should add the "strikethrough" class', function () {

        expect(this.trDkPlayer).toHaveClass('strikethrough');
    });

    it('should show player data on only the first pitcher lineup row', function () {

        expect($('tr.dk-lineup-player[data-position="P"]').eq(0).find('td.dk-lineup-player-name-dk')).toHaveText('Bob Jones');
        expect($('tr.dk-lineup-player[data-position="P"]').eq(1).find('td.dk-lineup-player-name-dk')).toHaveText('');
    });
});
/*
describe('Clicking the "Add" link for your second pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups_create.html');

        $('tr.dk-lineup-player[data-position="P"]').find('td.dk-lineup-player-name-dk').eq(0).text('Mike Smith');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');

        this.dkPlayer = new DkPlayer(this.trDkPlayer);

        this.trLineupPlayer = $('tr.dk-lineup-player[data-position="'+this.dkPlayer.position+'"] td.dk-lineup-player-name-dk:empty:first').closest('tr');
    }); 

    it('should show player data on only the first pitcher lineup row', function () {

        expect(this.trLineupPlayer.find('td.dk-lineup-player-name-dk').eq(0)).toHaveText('Mike Smith');
        expect(this.trLineupPlayer.find('td.dk-lineup-player-name-dk').eq(1)).toHaveText('Bob Jones');
    });
});
*/
