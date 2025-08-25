(function($){
	function uid(){ return 'row-' + Math.random().toString(36).slice(2,8) + '-' + Date.now().toString(36); }
	function ensureChildList($li){ var $ul=$li.children('ul.wcag-treegrid-rows-builder'); if(!$ul.length){ $ul=$('<ul class="wcag-treegrid-rows-builder" data-connect="treegrid"></ul>'); $li.append($ul);} return $ul; }
	function createRow($builder){ var id=uid(); var $li=$('<li class="wcag-treegrid-row" data-id="'+id+'">\
		<span class="wcag-treegrid-handle" aria-hidden="true">⋮⋮</span>\
		<input type="text" class="wcag-treegrid-field" data-field="id" placeholder="row id" />\
		<label class="wcag-treegrid-flag"><input type="checkbox" class="wcag-treegrid-field" data-field="expanded" /> Espanso</label>\
	</li>'); var numCols=$('[data-treegrid-columns] .wcag-treegrid-columns-list li').length; for(var i=0;i<numCols;i++){ $li.append('<input type="text" class="wcag-treegrid-cell" data-cell-index="'+i+'" placeholder="col '+(i+1)+'" />'); } return $li; }
	function initSortables($root){ $root.find('ul.wcag-treegrid-rows-builder').each(function(){ var $ul=$(this); try { $ul.sortable({ axis:'y', items:'> li.wcag-treegrid-row', handle:'.wcag-treegrid-handle', placeholder:'wcag-treegrid-placeholder', connectWith:'ul.wcag-treegrid-rows-builder' }); } catch(e){} }); }
	function serializeRows($root){ var rows=[]; function walk($ul,parentId,level){ $ul.children('li.wcag-treegrid-row').each(function(){ var $li=$(this); var id=$li.attr('data-id')||uid(); $li.attr('data-id', id); var expanded=$li.find('> .wcag-treegrid-flag input[data-field="expanded"]').is(':checked'); var cells=[]; $li.find('> .wcag-treegrid-cell').each(function(){ cells.push($(this).val()||''); }); rows.push({ id:id, parent:parentId||'', level:level||1, expanded:expanded?1:0, cells:cells }); var $child=$li.children('ul.wcag-treegrid-rows-builder'); if($child.length) walk($child,id,(level||1)+1); }); } walk($root.find('> .wcag-treegrid-rows-builder'), '', 1); return rows; }
	$(function(){
		var $builder=$('[data-treegrid-builder]');
		var $live=$builder.find('[data-live]');
		initSortables($builder);
		$builder.on('click','[data-action="add-root-row"]', function(){ var $root=$builder.find('> .wcag-treegrid-rows-builder'); if(!$root.length){ $root=$('<ul class="wcag-treegrid-rows-builder" data-connect="treegrid"></ul>').appendTo($builder.find('.wcag-treegrid-builder-root')); } $root.append(createRow($builder)); initSortables($builder); });
		// Bulk
		$builder.on('click','[data-action="expand-all"]', function(){ $builder.find('.wcag-treegrid-flag input[data-field="expanded"]').prop('checked', true); $live.text('Tutte le righe espanse'); });
		$builder.on('click','[data-action="collapse-all"]', function(){ $builder.find('.wcag-treegrid-flag input[data-field="expanded"]').prop('checked', false); $live.text('Tutte le righe chiuse'); });
		// Search
		$builder.on('input','[data-action="search"]', function(){ var q=$(this).val().toLowerCase().trim(); $builder.find('li.wcag-treegrid-row').each(function(){ var text=''; $(this).find('> .wcag-treegrid-cell').each(function(){ text += ' ' + ($(this).val()||''); }); var match=text.toLowerCase().indexOf(q)!==-1; $(this).toggle(match||q===''); }); $live.text(q?('Risultati per: '+q):'Ricerca pulita'); });
		// Submit
		$(document).on('submit', '#post', function(){ var rows=serializeRows($builder); $('#wcag_treegrid_rows_json').val(JSON.stringify(rows)); });
	});
})(jQuery);
