'use strict';

(function(){
    function buildTOC(root){
        const inner = root.querySelector('.wcag-wp-toc-inner');
        if(!inner) return;
        const levels = (inner.getAttribute('data-levels')||'h2,h3').split(',').map(s=>s.trim()).filter(Boolean);
        const numbered = inner.getAttribute('data-numbered') === 'true';
        const collapsible = inner.getAttribute('data-collapsible') === 'true';
        const smooth = inner.getAttribute('data-smooth') === 'true';
        const selector = inner.getAttribute('data-container-selector') || 'main';

        // Trova i contenitori specificati
        let containers = Array.from(document.querySelectorAll(selector));
        if (containers.length === 0) {
            console.warn('WCAG TOC: Nessun contenitore trovato per il selettore:', selector);
            return; // Non genera TOC se non trova i contenitori specificati
        }

        // Raccogli i titoli nei contenitori specificati, evitando duplicazioni
        const levelSelector = levels.join(',');
        let headings = [];
        let processedHeadings = new Set(); // Per evitare duplicazioni
        
        containers.forEach(c => {
            const foundInContainer = Array.from(c.querySelectorAll(levelSelector));
            
            // Aggiungi solo heading non ancora processati
            foundInContainer.forEach(h => {
                if (!processedHeadings.has(h)) {
                    headings.push(h);
                    processedHeadings.add(h);
                }
            });
        });
        
        // Escludi i titoli interni alla TOC stessa
        headings = headings.filter(h => !h.closest('.wcag-wp-toc'));
        if (headings.length === 0) return;

        // Ensure each heading has an id
        headings.forEach((h, idx)=>{
            if(!h.id){ h.id = 'toc-h-' + (idx+1); }
        });

        const list = inner.querySelector('.wcag-wp-toc-list');
        list.innerHTML='';

        let currentList = list;
        let previousLevel = parseInt(headings[0].tagName.substring(1),10);

        headings.forEach(h => {
            const level = parseInt(h.tagName.substring(1),10);

            // Se scendiamo di livello, annida in una nuova OL dentro l'ultimo LI
            while (level > previousLevel) {
                const parentLi = currentList.lastElementChild;
                if (!parentLi) { previousLevel = level; break; }
                const nested = document.createElement('ol');
                parentLi.appendChild(nested);
                currentList = nested;
                previousLevel++;
            }
            // Se risaliamo, torna alla lista padre
            while (level < previousLevel) {
                const maybeParentOl = currentList.parentElement && currentList.parentElement.closest('ol');
                currentList = maybeParentOl || list;
                previousLevel--;
            }

            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = '#' + h.id;
            a.textContent = h.textContent.trim();
            a.addEventListener('click', (e)=>{
                if(smooth){ e.preventDefault(); document.getElementById(h.id).scrollIntoView({behavior:'smooth', block:'start'}); history.replaceState(null,'', '#'+h.id); }
            });
            li.appendChild(a);
            currentList.appendChild(li);
        });

        if(numbered){ root.classList.add('wcag-wp-toc--numbered'); }
        if(collapsible){
            const title = root.querySelector('.wcag-wp-toc-title');
            if(title){
                title.tabIndex = 0;
                const listWrap = list.parentElement;
                title.addEventListener('click', ()=>{ listWrap.toggleAttribute('hidden'); });
                title.addEventListener('keydown', (e)=>{ if(e.key==='Enter' || e.key===' '){ e.preventDefault(); listWrap.toggleAttribute('hidden'); }});
            }
        }
    }

    function boot(){
        document.querySelectorAll('.wcag-wp-toc').forEach(buildTOC);
    }

    if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();


