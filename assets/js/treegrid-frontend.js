(function(){
	'use strict';
	document.addEventListener('click', function(e){
		var toggle = e.target.closest('.wcag-treegrid__toggle');
		if (!toggle) return;
		var row = toggle.closest('[role="row"]');
		if (!row) return;
		var expanded = row.getAttribute('aria-expanded') === 'true';
		row.setAttribute('aria-expanded', expanded ? 'false' : 'true');
		// L'hide dei figli è demandato al CSS con [data-parent]
	});
})();
