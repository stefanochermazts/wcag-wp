(function($){
	function uid(){ return 'node-' + Math.random().toString(36).slice(2,8) + '-' + Date.now().toString(36); }
	function ensureChildList($li){ var $ul=$li.children('ul.wcag-treeview-builder'); if(!$ul.length){ $ul=$('<ul class="wcag-treeview-builder" data-connect="treeview"></ul>'); $li.append($ul);} return $ul; }
	function createItem(){ var id=uid(); return $('<li class="wcag-treeview-item" data-id="'+id+'">\
		<span class="wcag-treeview-handle" aria-hidden="true">⋮⋮</span>\
		<input type="text" class="wcag-treeview-field" data-field="label" placeholder="etichetta" />\
		<input type="url" class="wcag-treeview-field" data-field="url" placeholder="url (opzionale)" />\
		<label class="wcag-treeview-flag"><input type="checkbox" class="wcag-treeview-field" data-field="expanded" /> Espanso</label>\
		<button type="button" class="button button-small" data-action="add-child">Aggiungi figlio</button>\
		<button type="button" class="button button-small" data-action="remove">Rimuovi</button>\
	</li>'); }
	function initSortables($root){ $root.find('ul.wcag-treeview-builder').each(function(){ var $ul=$(this); try { $ul.sortable({ axis:'y', items:'> li.wcag-treeview-item', handle:'.wcag-treeview-handle', placeholder:'wcag-treeview-placeholder', connectWith:'ul.wcag-treeview-builder' }); } catch(e){} }); }
	function serializeTree($root){ var nodes=[]; function walk($ul,parentId){ $ul.children('li.wcag-treeview-item').each(function(){ var $li=$(this); var id=$li.attr('data-id')||uid(); $li.attr('data-id',id); var label=$li.find('> .wcag-treeview-field[data-field="label"]').val()||''; var url=$li.find('> .wcag-treeview-field[data-field="url"]').val()||''; var expanded=$li.find('> .wcag-treeview-flag input[data-field="expanded"]').is(':checked'); nodes.push({ id:id, label:label, parent:parentId||'', expanded:expanded?1:0, url:url }); var $child=$li.children('ul.wcag-treeview-builder'); if($child.length) walk($child,id); }); } var $rootUl=$root.find('.wcag-treeview-builder').first(); if($rootUl.length){ walk($rootUl, ''); } return nodes; }
	$(function(){
		var $builder=$('[data-treeview-builder]');
		var $live=$builder.find('[data-live]');
		initSortables($builder);
		$builder.on('click','[data-action="add-root"]', function(){ var $root=$builder.find('.wcag-treeview-builder').first(); if(!$root || !$root.length){ $root=$('<ul class="wcag-treeview-builder" data-connect="treeview"></ul>').appendTo($builder.find('.wcag-treeview-builder-root')); } $root.append(createItem()); initSortables($builder); });
		$builder.on('click','[data-action="add-child"]', function(){ var $li=$(this).closest('li.wcag-treeview-item'); var $ul=ensureChildList($li); $ul.append(createItem()); initSortables($builder); });
		$builder.on('click','[data-action="remove"]', function(){ $(this).closest('li.wcag-treeview-item').remove(); });
		$builder.on('click','[data-action="expand-all"]', function(){ $builder.find('.wcag-treeview-flag input[data-field="expanded"]').prop('checked', true); $live.text('Tutti i nodi espansi'); });
		$builder.on('click','[data-action="collapse-all"]', function(){ $builder.find('.wcag-treeview-flag input[data-field="expanded"]').prop('checked', false); $live.text('Tutti i nodi chiusi'); });
		$builder.on('input','[data-action="search"]', function(){ var q=$(this).val().toLowerCase().trim(); $builder.find('li.wcag-treeview-item').each(function(){ var label=$(this).find('> .wcag-treeview-field[data-field="label"]').val()||''; var match=label.toLowerCase().indexOf(q)!==-1; $(this).toggle(match||q===''); }); $live.text(q?('Risultati per: '+q):'Ricerca pulita'); });
		$(document).on('submit', '#post', function(){ var nodes=serializeTree($builder); $('#wcag_treeview_nodes_json').val(JSON.stringify(nodes)); });
	});
})(jQuery);
