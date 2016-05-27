describe('Creating a new DkPlayer object', function () {

    it('should create specific property values', function () {

        loadFixtures('lineups/create.html');

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

describe('Clicking the "Add" link for your 1st pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

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

describe('Clicking the "Add" link for your 2nd pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        $('tr.dk-lineup-player[data-position="P"]').eq(0).find('td.dk-lineup-player-name-dk').text('Donald Trump');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');
    }); 

    it('should add the "strikethrough" class', function () {

        expect(this.trDkPlayer).toHaveClass('strikethrough');
    });

    it('should show player data on both pitcher lineup rows', function () {

        expect($('tr.dk-lineup-player[data-position="P"]').eq(0).find('td.dk-lineup-player-name-dk')).toHaveText('Donald Trump');
        expect($('tr.dk-lineup-player[data-position="P"]').eq(1).find('td.dk-lineup-player-name-dk')).toHaveText('Bob Jones');
    });
});

describe('Clicking the "Add" link for your 3rd pitcher', function () {

    beforeEach(function() {

        loadFixtures('lineups/create.html');

        $('tr.dk-lineup-player[data-position="P"]').eq(0).find('td.dk-lineup-player-name-dk').text('Donald Trump');
        $('tr.dk-lineup-player[data-position="P"]').eq(1).find('td.dk-lineup-player-name-dk').text('Bernie Sanders');

        this.trDkPlayer = $('tr.dk-player[data-name-dk="Bob Jones"]');

        this.trDkPlayer.find('a.add-dk-player-link').trigger('click');
    }); 

    it('should not add the "strikethrough" class', function () {

        expect(this.trDkPlayer).not.toHaveClass('strikethrough');
    });

    it('should show player data on both pitcher lineup rows', function () {

        expect($('tr.dk-lineup-player[data-position="P"]').eq(0).find('td.dk-lineup-player-name-dk')).toHaveText('Donald Trump');
        expect($('tr.dk-lineup-player[data-position="P"]').eq(1).find('td.dk-lineup-player-name-dk')).toHaveText('Bernie Sanders');
    });

    it('should show an error', function () {

        expect($('span.lineup-error')).toHaveText('The "P" position is already filled.');

        expect($('div.alert.lineup')).not.toBeHidden();
    });
});
