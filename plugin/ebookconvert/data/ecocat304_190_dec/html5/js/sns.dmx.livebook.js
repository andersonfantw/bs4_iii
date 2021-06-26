/**
 * 
 * Digitalmax SNS plugin
 * Version 1.0.3
 * 
 * Copyright (c) 2014 - 2015 Digitalmax Co.,Ltd.
 * 
 */

window.snsdmx = (function() {
	
	function _share_url (url, page) {
		var port = jQuery.url('port', url);
		var protocol = jQuery.url('protocol', url);
		var host = jQuery.url('hostname', url);
		var domain = jQuery.url('domain', url);
		var searchobj = jQuery.url('?', url);
		var html5dir = 'html5';
		var sp_file = 'index.html';
		var pc_file;
		
		if (searchobj === undefined) {
			searchobj = {};
		}
		searchobj.startpage = page;
		
		var searchparams = new Array();
		for (it in searchobj) {
			searchparams.push(it+'='+searchobj[it]);
		}
		
		var searchstr = searchparams.join('&');
		
		url = url || window.location.toString();
		
		var base_path = jQuery.url('path', url);
		var base_name = jQuery.url('file', url);
		var sp_check_path = html5dir+'/'+sp_file;
		
		var loop_adjust = 0;
		if (base_path.indexOf(sp_check_path) == base_path.length - sp_check_path.length) {
			loop_adjust = 1;
		}
		
		if (domain == 'ecocat-cloud.com') {
			pc_file = 'book_swf.php';
		} else {
			pc_file = 'book.html';
		}
		
		var temp, cnt = 0;
		var dir = new Array();
		while(temp = jQuery.url(++cnt, url)) {
			if (base_name == temp || undefined === temp) {
				break;
			}
			dir.push(temp);
		}
		
		var path = '';
		for (var i = 0; i < dir.length - loop_adjust; i++) {
			path += dir[i] + '/';
		}
		
		return protocol + '://' + host + (port != '80' ? port : '') + '/' + path + pc_file + (searchstr !== '' ? '?' + searchstr : '');
	}
	
	function _open_window (url) {
		window.open(url, 'popup', 'width=500,height=500');
	}
	
	return function(type, part, url, page) {
		var retval = '';
		
		if (!type) { return undefined; }
		type = type.toString();
		
		if (type == 'facebook') {
			if (part == 'head') {
				retval = '';
			} else if (part == 'button') {
				retval = '<a class="fb-share-button share-button" href="https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(_share_url(url, page))+'" target="_blank">Facebook</a>';
			} else if (part == 'refreshhref') {
				jQuery('.fb-share-button').attr('href', 'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(_share_url(url, page)));
				retval = 0;
			} else if (part == 'openwin') {
				retval = _open_window('https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(_share_url(url, page)));
			} else if (part == 'shareurl') {
				retval = _share_url(url, page);
			}
		} else if (type == 'twitter') {
			if (part == 'head') {
				retval = '';
			} else if (part == 'button') {
				retval = '<a class="twitter-share-button share-button" href="https://twitter.com/share?url='+encodeURIComponent(_share_url(url, page))+'" target="_blank">Twitter</a>';
			} else if (part == 'refreshhref') {
				jQuery('.twitter-share-button').attr('href', 'https://twitter.com/share?url='+encodeURIComponent(_share_url(url, page)));
				retval = 0;
			} else if (part == 'openwin') {
				retval = _open_window('https://twitter.com/share?url='+encodeURIComponent(_share_url(url, page)));
			} else if (part == 'shareurl') {
				retval = _share_url(url, page);
			}
		}
		
		return retval;
	};
	
})();

if(typeof jQuery !== 'undefined') {
    jQuery.extend({
    	snsdmx: function(type, part, url, page) { return window.snsdmx(type, part, url, page); }
    });
}