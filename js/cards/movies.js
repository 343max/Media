/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 26.06.2010
 * Time: 17:06:30
 * To change this template use File | Settings | File Templates.
 */

cards.MovieContactModel = Ext.regModel('Contact', {
    fields: ['firstName', 'lastName']
});


cards.MovieMediaFilesModel = Ext.regModel('Mediafiles', {
    fields: ['name', 'url', 'modificationTime', 'creationTime']
});

cards.MovieTestData = new Ext.data.Store({
	model: 'Contact',
	sorters: 'firstName',
	getGroupString : function(record) {
		return record.get('firstName')[0];
	},
	data: [
		{firstName: 'Julio', lastName: 'Benesh'},
		{firstName: 'Julio', lastName: 'Minich'},
		{firstName: 'Tania', lastName: 'Ricco'},
		{firstName: 'Odessa', lastName: 'Steuck'},
		{firstName: 'Nelson', lastName: 'Raber'},
		{firstName: 'Tyrone', lastName: 'Scannell'},
		{firstName: 'Allan', lastName: 'Disbrow'},
		{firstName: 'Cody', lastName: 'Herrell'},
		{firstName: 'Julio', lastName: 'Burgoyne'},
		{firstName: 'Jessie', lastName: 'Boedeker'},
		{firstName: 'Allan', lastName: 'Leyendecker'},
		{firstName: 'Javier', lastName: 'Lockley'},
		{firstName: 'Guy', lastName: 'Reasor'},
		{firstName: 'Jamie', lastName: 'Brummer'},
		{firstName: 'Jessie', lastName: 'Casa'},
		{firstName: 'Marcie', lastName: 'Ricca'},
		{firstName: 'Gay', lastName: 'Lamoureaux'},
		{firstName: 'Althea', lastName: 'Sturtz'},
		{firstName: 'Kenya', lastName: 'Morocco'},
		{firstName: 'Rae', lastName: 'Pasquariello'},
		{firstName: 'Ted', lastName: 'Abundis'},
		{firstName: 'Jessie', lastName: 'Schacherer'},
		{firstName: 'Jamie', lastName: 'Gleaves'},
		{firstName: 'Hillary', lastName: 'Spiva'},
		{firstName: 'Elinor', lastName: 'Rockefeller'},
		{firstName: 'Dona', lastName: 'Clauss'},
		{firstName: 'Ashlee', lastName: 'Kennerly'},
		{firstName: 'Alana', lastName: 'Wiersma'},
		{firstName: 'Kelly', lastName: 'Holdman'},
		{firstName: 'Mathew', lastName: 'Lofthouse'},
		{firstName: 'Dona', lastName: 'Tatman'},
		{firstName: 'Clayton', lastName: 'Clear'},
		{firstName: 'Rosalinda', lastName: 'Urman'},
		{firstName: 'Cody', lastName: 'Sayler'},
		{firstName: 'Odessa', lastName: 'Averitt'},
		{firstName: 'Ted', lastName: 'Poage'},
		{firstName: 'Penelope', lastName: 'Gayer'},
		{firstName: 'Katy', lastName: 'Bluford'},
		{firstName: 'Kelly', lastName: 'Mchargue'},
		{firstName: 'Kathrine', lastName: 'Gustavson'},
		{firstName: 'Kelly', lastName: 'Hartson'},
		{firstName: 'Carlene', lastName: 'Summitt'},
		{firstName: 'Kathrine', lastName: 'Vrabel'},
		{firstName: 'Roxie', lastName: 'Mcconn'},
		{firstName: 'Margery', lastName: 'Pullman'},
		{firstName: 'Avis', lastName: 'Bueche'},
		{firstName: 'Esmeralda', lastName: 'Katzer'},
		{firstName: 'Tania', lastName: 'Belmonte'},
		{firstName: 'Malinda', lastName: 'Kwak'},
		{firstName: 'Tanisha', lastName: 'Jobin'},
		{firstName: 'Kelly', lastName: 'Dziedzic'},
		{firstName: 'Darren', lastName: 'Devalle'},
		{firstName: 'Julio', lastName: 'Buchannon'},
		{firstName: 'Darren', lastName: 'Schreier'},
		{firstName: 'Jamie', lastName: 'Pollman'},
		{firstName: 'Karina', lastName: 'Pompey'},
		{firstName: 'Hugh', lastName: 'Snover'},
		{firstName: 'Zebra', lastName: 'Evilias'}
	]
});

(function() {

	var proxy = new Ext.data.HttpProxy({
		url: 'test.json'
	});

	proxy.addListener('load', function(proxy, o, options) {
		console.dir(o);
	});

	cards.MovieStore = new Ext.data.Store({
		model: 'Mediafiles',
		sorters: 'firstName',
		url: 'test.json',
		proxy: proxy,
		autoLoad: true,
		getGroupString : function(record) {
			return record.get('firstName')[0];
		},
		reader: new Ext.data.JsonReader({
			idIndex: 'firstName',
			root: 'contacts',
			model: cards.MovieContactModel
		}),
		listeners: {
			datachanged: function(store) {
				console.dir(this);
			},
			load: function(store, reocrds, options) {
				console.log('load');
			}
		}
	});

	cards.MovieStore.read();

	cards.Movies = new Ext.Panel({
		cls: 'pane-movies',
		items: [
			{
				xtype: 'list',
				singleSelect: true,
				grouped: true,
				store: cards.MovieStore,
				itemSelector: 'div.contact',
				tpl: '<tpl for="."><div class="contact"><strong>{firstName}</strong> {lastName}</div></tpl>',
				indexBar: true,
				height: 641
			}
		]
	});
})();