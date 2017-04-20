
;(function(document, window, undefined) {

	'use strict';

	var password_ref = null,
		output_ref = null;

	if (!document.addEventListener) {
		return;
	}

	function password_change(e) {
		var password = password_ref.value;
		if (password) {
			password = ' Thanks for your password - ' + password;
		}
		output_ref.textContent = password;
	}

	function init() {

		password_ref = document.getElementById('fld_password');
		password_ref.addEventListener('keyup', password_change);

		output_ref = document.createElement('span');
		password_ref.parentNode.appendChild(output_ref);

	}

	if (document.readyState !== 'loading') {
		window.setTimeout(init); // Handle asynchronously
	} else {
		document.addEventListener('DOMContentLoaded', init);
	}

})(document, window);
