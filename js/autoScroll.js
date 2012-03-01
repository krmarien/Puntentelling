(function ($) {
	var defaults = {
		method	 : 'continiously',
		speed	 : 5000,
		waitTime : 15000,
	};
	
	var methods = {
		init : function ( options ) {
			options = $.extend(defaults, options);
			
			var $this = $(this),
				data = $this.data('aS');
				
			if ( ! data ) {
				$this.data('aS', {
						options: options,
						direction: 1,
						pauzed: true,
					}
				);
			}
			
			$(document).unbind('keydown.aS').bind('keydown.aS', function (e) {
				if (e.keyCode == 32) {
					e.preventDefault();
					if (data.pauzed)
						_start($this);
					else
						_pauze($this);
				}
			});
			
			return this;
		},
		start : function () {
			_start($(this));
			return this;
		},
		pauze : function () {
			_pauze($(this));
			return this;
		},
		destroy : function () {
			_destroy($(this));
			return this;
		}
	};
	
	$.fn.autoScroll = function ( method ) {
		if ( methods[ method ] ) {
			return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on autoScroll' );
		}
	};
	
	_start = function (table) {
		var data = table.data('aS');
		table.data('aS').pauzed = false;

		if (data.options.method == 'continiously')
			_scroll(table);
		else if (data.options.method == 'alwaysDown')
			_followDown(table);
	};
	
	_scroll = function (table) {
		var data = table.data('aS');

		var timer = setTimeout(function () {
				var scrollTop = table.find('tbody').scrollTop();
				if (data.direction == 1) {
					scrollTop += table.find('tbody').height();
					if (scrollTop >= table.find('tbody').prop('scrollHeight') - table.find('tbody').height()) {
						scrollTop = table.find('tbody').prop('scrollHeight') - table.find('tbody').height();
						table.data('aS').direction = -1;
					}
				} else {
					scrollTop -= table.find('tbody').height();
					if (scrollTop < 0) {
						scrollTop = 0;
						table.data('aS').direction = 1;
					}
				}
				table.find('tbody').animate({scrollTop: scrollTop}, data.options.speed);
				_scroll(table);
			}, data.options.waitTime + data.options.speed);
		data.timer = timer;
	}
	
	_followDown = function (table) {
		table.bind('change.aS', function () {
				var scrollTop = table.find('tbody').prop('scrollHeight') - table.find('tbody').height();
				table.find('tbody').scrollTop(scrollTop);
			});
	}
	
	_pauze = function (table) {
		table.data('aS').pauzed = true;
		clearTimeout(table.data('aS').timer);
		table.unbind('change.aS');
	}
	
	_destroy = function (table) {
		_pauze(table);
		table.removeData('aS');
	}
}) (jQuery);