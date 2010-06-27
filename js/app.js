/**
 * Created by IntelliJ IDEA.
 * User: max
 * Date: 26.06.2010
 * Time: 16:32:24
 * To change this template use File | Settings | File Templates.
 */

Ext.ux.UniversalUI = Ext.extend(Ext.Panel, {
	fullscreen: true,
	layout: 'card',
	items: [
		{
			cls: 'launchscreen',
			html: '<h2>media</h2>'
		}
	],
	initComponent : function() {
		this.backButton = new Ext.Button({
			hidden: true,
			text: 'Back',
			ui: 'back',
			handler: this.onBackButtonTap,
			scope: this
		});

		this.navigationButton = new Ext.Button({
			hidden: Ext.platform.isPhone || Ext.orientation == 'landscape',
			text: 'Navigation',
			handler: this.onNavButtonTap,
			scope: this
		});

		this.navigationBar = new Ext.Toolbar({
			ui: 'dark',
			dock: 'top',
			title: this.title,
			items: [this.backButton, this.navigationButton].concat(this.buttons || [])
		});

		this.navigationPanel = new Ext.NestedList({
			items: this.navigationItems || [],
			dock: 'left',
			width: 250,
			height: 456,
			hidden: !Ext.platform.isPhone && Ext.orientation == 'portrait',
			toolbar: Ext.platform.isPhone ? this.navigationBar : null,
			listeners: {
				listchange: this.onListChange,
				scope: this
			}
		});

		this.dockedItems = this.dockedItems || [];
		this.dockedItems.unshift(this.navigationBar);

		if (!Ext.platform.isPhone && Ext.orientation == 'landscape') {
			this.dockedItems.unshift(this.navigationPanel);
		}
		else if (Ext.platform.isPhone) {
			this.items = this.items || [];
			this.items.unshift(this.navigationPanel);
		}

		this.addEvents('navigate');

		Ext.ux.UniversalUI.superclass.initComponent.call(this);
	},

	onListChange : function(list, item) {
		if (Ext.orientation == 'portrait' && !Ext.platform.isPhone && !item.items && !item.preventHide) {
			this.navigationPanel.hide();
		}
		if (item.card) {
			this.setCard(item.card, item.animation || 'slide');
			this.currentCard = item.card;
			if (item.text) {
				this.navigationBar.setTitle(item.text);
			}
			if (Ext.platform.isPhone) {
				this.backButton.show();
				this.navigationBar.doLayout();
			}
		}

		this.fireEvent('navigate', this, item, list);
	},

	onNavButtonTap : function() {
		this.navigationPanel.showBy(this.navigationButton, 'fade');
	},

	onBackButtonTap : function() {
		this.setCard(this.navigationPanel, {type: 'slide', direction: 'right'});
		this.currentCard = this.navigationPanel;
		if (Ext.platform.isPhone) {
			this.backButton.hide();
			this.navigationBar.setTitle(this.title);
			this.navigationBar.doLayout();
		}
		this.fireEvent('navigate', this, this.navigationPanel.activeItem, this.navigationPanel);
	},

	onOrientationChange : function(orientation, w, h) {
		Ext.ux.UniversalUI.superclass.onOrientationChange.call(this, orientation, w, h);

		if (!Ext.platform.isPhone) {
			if (orientation == 'portrait') {
				this.removeDocked(this.navigationPanel, false);
				this.navigationPanel.hide();
				this.navigationPanel.setFloating(true);
				this.navigationButton.show();
			}
			else {
				this.navigationPanel.setFloating(false);
				this.navigationPanel.show();
				this.navigationButton.hide();
				this.insertDocked(0, this.navigationPanel);
			}

			this.doComponentLayout();
			this.navigationBar.doComponentLayout();
		}
	}
});

media = {};

media.Main = {
	init: function() {
		this.ui = new Ext.ux.UniversalUI({
			title: 'Media',
			navigationItems: [
				{
					text: 'Filme',
					card: cards.Movies,
					source: 'js/cards/movies.js'
				}
			]
		});
	}
}

Ext.setup({
	onReady: function() {
		var app = new media.Main.init();
	}
});