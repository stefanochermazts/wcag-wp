(function(){
	'use strict';
	function $all(sel, root){ return Array.prototype.slice.call((root||document).querySelectorAll(sel)); }
	function closest(el, sel){ while (el && el.nodeType===1){ if (el.matches(sel)) return el; el = el.parentElement; } return null; }

	document.addEventListener('DOMContentLoaded', function(){
		// Insert carets if enabled via data attribute optional class
		$all('[data-wcag-treeview]').forEach(function(tree){
			var showCaret = !tree.classList.contains('no-caret');
			if (showCaret) {
				$all('[role="treeitem"]', tree).forEach(function(item){
					var hasGroup = item.parentElement && item.parentElement.querySelector(':scope > ul[role="group"]');
					if (hasGroup && !item.querySelector('.wcag-treeview-caret')){
						var btn = document.createElement('button');
						btn.type = 'button';
						btn.className = 'wcag-treeview-caret';
						btn.setAttribute('aria-hidden','true');
						btn.tabIndex = -1;
						item.prepend(btn);
					}
				});
			}
		});
	});

	document.addEventListener('click', function(e){
		var item = closest(e.target, '[role="treeitem"]');
		if (!item) return;
		var tree = closest(item, '[data-wcag-treeview]');
		var hasGroup = item.parentElement && item.parentElement.querySelector(':scope > ul[role="group"]');
		if (!hasGroup) return;
		var labelToggles = !tree.classList.contains('label-no-toggle');
		var isCaret = e.target.classList && e.target.classList.contains('wcag-treeview-caret');
		if (!isCaret && !labelToggles) return;
		var expanded = item.getAttribute('aria-expanded') === 'true';
		item.setAttribute('aria-expanded', expanded ? 'false' : 'true');
		var group = item.parentElement.querySelector(':scope > ul[role="group"]');
		if (group){ group.hidden = expanded; }
		var live = tree.querySelector('[data-treeview-live]');
		if (live && !tree.classList.contains('live-off')){
			live.textContent = expanded ? 'Sezione chiusa' : 'Sezione aperta';
		}
	});

	document.addEventListener('keydown', function(e){
		var current = document.activeElement;
		if (!current || current.getAttribute('role') !== 'treeitem') return;
		var li = current.closest('li');
		var tree = closest(current, '[data-wcag-treeview]');
		if (!li || !tree) return;
		var items = $all('[role="treeitem"]', tree);
		var idx = items.indexOf(current);
		switch(e.key){
			case 'ArrowDown': e.preventDefault(); if (idx < items.length - 1) items[idx+1].focus(); break;
			case 'ArrowUp': e.preventDefault(); if (idx > 0) items[idx-1].focus(); break;
			case 'ArrowRight': if (current.getAttribute('aria-expanded') === 'false') { current.setAttribute('aria-expanded', 'true'); e.preventDefault(); } break;
			case 'ArrowLeft': if (current.getAttribute('aria-expanded') === 'true') { current.setAttribute('aria-expanded', 'false'); e.preventDefault(); } break;
			case 'Home': e.preventDefault(); items[0].focus(); break;
			case 'End': e.preventDefault(); items[items.length-1].focus(); break;
		}
	});
})();
