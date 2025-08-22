'use strict';

(function(){
    function init(root){
        const tabs = root.querySelectorAll('[role="tab"]');
        const panels = root.querySelectorAll('[role="tabpanel"]');
        const live = document.createElement('div');
        live.setAttribute('aria-live','polite');
        live.setAttribute('aria-atomic','true');
        live.className = 'wcag-wp-sr-only';
        root.appendChild(live);

        function announce(msg){ live.textContent = msg; setTimeout(()=>{ live.textContent=''; }, 800); }

        function activate(index, focus=true){
            tabs.forEach((t,i)=>{
                const selected = i===index;
                t.setAttribute('aria-selected', selected ? 'true':'false');
                t.tabIndex = selected ? 0 : -1;
            });
            panels.forEach((p,i)=>{
                if(i===index){ p.removeAttribute('hidden'); }
                else { p.setAttribute('hidden',''); }
            });
            if(focus) tabs[index].focus();
            const label = tabs[index].textContent.trim();
            announce(label + ' attivo');
        }

        tabs.forEach((tab, idx)=>{
            tab.addEventListener('click', ()=>activate(idx));
            tab.addEventListener('keydown', (e)=>{
                const key = e.key;
                let i = idx;
                if(key==='ArrowRight' || key==='ArrowDown'){ i = (idx+1)%tabs.length; e.preventDefault(); activate(i); }
                if(key==='ArrowLeft' || key==='ArrowUp'){ i = (idx-1+tabs.length)%tabs.length; e.preventDefault(); activate(i); }
                if(key==='Home'){ e.preventDefault(); activate(0); }
                if(key==='End'){ e.preventDefault(); activate(tabs.length-1); }
            });
        });
    }

    function boot(){
        document.querySelectorAll('.wcag-wp-tabpanel').forEach(init);
    }

    if(document.readyState==='loading'){
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();


