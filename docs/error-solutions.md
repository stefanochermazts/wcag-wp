# Soluzioni agli Errori - WCAG-WP Plugin

Questo file documenta le soluzioni ai problemi identificati e risolti durante lo sviluppo del plugin WCAG-WP.

## Problema 1: Pulsante "Aggiungi Colonna WCAG" Non Funziona

### **Data**: 2024-12-19
### **Descrizione del Problema**
Premendo il pulsante "Aggiungi Colonna WCAG" nella sezione di gestione delle tabelle WCAG non succede niente e non si riesce a creare nuove colonne.

### **Cause Identificate**
1. **Controllo post_type troppo restrittivo**: Nel metodo `enqueue_admin_assets()` della classe `WCAG_WP_Tables`, il controllo per determinare se si è nella pagina corretta era troppo rigido e poteva fallire in alcune situazioni.

2. **JavaScript si ferma completamente**: Il file `tables-admin.js` aveva un controllo che fermava completamente l'esecuzione se l'oggetto `wcag_wp_tables` non era definito, impedendo a tutte le funzionalità di funzionare.

### **Soluzione Implementata**

#### 1. Migliorato il controllo del post_type (file: `includes/class-wcag-wp-tables.php`)
**Prima:**
```php
global $post_type;
if ($post_type !== 'wcag_tables') {
    return;
}
```

**Dopo:**
```php
// More robust post type check
global $post_type, $post;
$current_post_type = $post_type ?? (isset($post) ? $post->post_type : '');

// Also check URL parameter for post type
if (!$current_post_type && isset($_GET['post_type'])) {
    $current_post_type = sanitize_text_field($_GET['post_type']);
}

// Check if we're editing an existing post
if (!$current_post_type && isset($_GET['post'])) {
    $post_id = absint($_GET['post']);
    $current_post_type = get_post_type($post_id);
}

if ($current_post_type !== 'wcag_tables') {
    return;
}
```

#### 2. Aggiunto fallback JavaScript (file: `assets/js/tables-admin.js`)
**Prima:**
```javascript
// Ensure wcag_wp_tables object exists
if (typeof wcag_wp_tables === 'undefined') {
    console.warn('WCAG-WP Tables: Configuration object not found');
    return;
}
```

**Dopo:**
```javascript
// Ensure wcag_wp_tables object exists, or create a fallback
if (typeof wcag_wp_tables === 'undefined') {
    console.warn('WCAG-WP Tables: Configuration object not found, using fallback');
    window.wcag_wp_tables = {
        ajax_url: ajaxurl || '/wp-admin/admin-ajax.php',
        nonce: '',
        strings: {
            confirm_delete_column: 'Sei sicuro di voler eliminare questa colonna?',
            confirm_delete_row: 'Sei sicuro di voler eliminare questa riga?',
            error_generic: 'Si è verificato un errore. Riprova.',
            column_added: 'Colonna aggiunta con successo.',
            row_added: 'Riga aggiunta con successo.',
            changes_saved: 'Modifiche salvate.'
        }
    };
}
```

### **Risultato**
Il pulsante "Aggiungi Colonna WCAG" ora funziona correttamente e permette di aggiungere nuove colonne alle tabelle WCAG.

## Problema 2: Tasto Publish disabilitato e impossibile creare la tabella

### **Data**: 2025-08-22
### **Descrizione del Problema**
Nel contenuto tipo `wcag_tables` il pulsante Publish risultava disabilitato e i pulsanti come "Aggiungi Colonna WCAG" non rispondevano; lo shortcode mostrava un ID tabella ma l’editor non riusciva a salvare/creare il post correttamente.

### **Cause Identificate**
1. **Doppia registrazione del CPT `wcag_tables`**: il CPT veniva registrato sia in `src/class-wcag-wp.php` sia in `includes/class-wcag-wp-tables.php`, causando conflitti nell’admin editor.
2. **ReferenceError su `ajaxurl` nel fallback JS**: il fallback iniziale in `tables-admin.js` usava `ajaxurl` senza verificarne l’esistenza.
3. **Validazioni HTML5 bloccanti nei template**: gli input dei template (colonne/righe) con `required` in combinazione con markup di template potevano impedire l’invio del form e disabilitare Publish se gli elementi non erano ancora popolati.

### **Soluzione Implementata**
1. **Eliminata la doppia registrazione del CPT**: rimossa la `register_post_type('wcag_tables', ...)` da `src/class-wcag-wp.php`; la registrazione resta unica in `includes/class-wcag-wp-tables.php`.
2. **Corretto il fallback `ajaxurl`**: ora controlla con `typeof ajaxurl !== 'undefined'` prima di usarlo, altrimenti usa `'/wp-admin/admin-ajax.php'`.
3. **Resi non bloccanti i template di input**: nei template per nuove colonne/righe gli input partono `disabled` e con `data-required="1"`; all’inserimento via JS vengono riabilitati e l’attributo `required` viene ripristinato. Modifiche in `templates/admin/table-columns-meta-box.php`, `templates/admin/table-data-meta-box.php` e logica in `assets/js/tables-admin.js` (riabilitazione campi dopo `addNewColumn`/`addNewRow`).

### **Risultato**
Il pulsante Publish è nuovamente abilitato e funzionante; la creazione/modifica delle tabelle è possibile. Il pulsante "Aggiungi Colonna WCAG" funziona anche in assenza temporanea della localizzazione JS.

## Problema 3: "Invalid post type" su `post-new.php?post_type=wcag_tables`

### **Data**: 2025-08-22
### **Descrizione del Problema**
La pagina di creazione nuovo post per il tipo `wcag_tables` restituiva "Invalid post type" e la voce Tabelle WCAG non appariva nel menu.

### **Causa Identificata**
La registrazione del CPT avveniva su un hook `init` separato che, nella sequenza di bootstrap del plugin, poteva essere troppo tardo rispetto a quando WordPress valuta il routing admin per `post-new.php`. 

### **Soluzione Implementata**
Invocata direttamente `register_post_type()` all'interno di `WCAG_WP_Tables::init()` (che viene già eseguito su `init` dal bootstrap principale). Modifica in `includes/class-wcag-wp-tables.php`:

```php
// Register custom post type immediately (we are already on 'init' when components initialize)
$this->register_post_type();
```

### **Risultato**
Il CPT `wcag_tables` è disponibile e l'URL `post-new.php?post_type=wcag_tables` funziona senza errori.

## Problema 4: Alla pressione di Salva viene creata automaticamente una colonna vuota

### **Data**: 2025-08-22
### **Descrizione del Problema**
Durante il salvataggio, veniva aggiunta una colonna vuota (senza `id`) che poi appariva anche nella sezione Dati della tabella, causando errore di campo obbligatorio al salvataggio successivo.

### **Causa Identificata**
I template di form e alcune interazioni potevano inviare array con una voce placeholder vuota in `wcag_wp_table_columns` (priva di `id`), che finiva persino salvata lato server.

### **Soluzione Implementata**
- Aggiornata la sanitizzazione lato server in `includes/class-wcag-wp-tables.php` → metodo `sanitize_table_columns()` per IGNORARE qualsiasi voce con `id` vuoto, normalizzando anche valori di `type`/`align` e reindicizzando l’array.

Snippet chiave:
```php
// Skip empty placeholder rows; require at least a non-empty ID
if ($id === '') {
    continue;
}

// Reindex array to avoid sparse indices
return array_values($sanitized);
```

### **Risultato**
Nessuna colonna viene creata automaticamente: solo le colonne aggiunte tramite pulsante vengono salvate. Gli errori sul campo obbligatorio `id` non ricompaiono.

## Problema 5: In frontend mancano Ricerca e Ordinamento nonostante attivi in backend

### **Data**: 2025-08-22
### **Descrizione del Problema**
La tabella frontend non mostrava il campo ricerca e non rispondeva all’ordinamento anche se le opzioni erano abilitate nella configurazione.

### **Cause Identificate**
1. I data-attributes venivano letti come sola presenza, non come valore “true/false”.
2. Le opzioni dello shortcode con valori `null` sovrascrivevano la configurazione salvata.
3. Gli header ordinabili non avevano un marker esplicito per l’inizializzazione JS.

### **Soluzione Implementata**
- `assets/js/frontend.js`: lettura corretta dei flag dagli attributi (`data-*-="true"`).
- `includes/class-wcag-wp-tables.php`: merge opzioni shortcode filtrando null/"" e normalizzando booleani.
- `templates/frontend/wcag-table.php`: aggiunti `data-stack-mobile` sul container e `data-sortable="true"` sugli header ordinabili.

### **Risultato**
Ricerca e ordinamento sono di nuovo funzionanti; export CSV già operativo.

### **Note per il Futuro**
- Sempre testare che gli asset JavaScript si carichino correttamente in tutte le situazioni
- Implementare fallback robusti per evitare che errori blochino completamente le funzionalità
- Usare controlli multipli per determinare il contesto (post_type, URL, parametri GET)

---

## Problema 9: Configurazione TOC non passata al template frontend

**Sintomo**: Il titolo del TOC mostrava sempre il nome del post invece del titolo configurato, e le opzioni di configurazione non venivano applicate.

**Causa**: Nel metodo `shortcode_toc()`, la configurazione veniva letta ma non veniva sanificata e passata correttamente alla variabile `$config` nel template.

**Soluzione**: 
1. Aggiunta chiamata a `$this->sanitize_config($config_raw)` per sanificare e applicare i default
2. Aggiunte nuove opzioni nella configurazione: `show_title` e `title_text`
3. Modificato il template per usare `$config['title_text']` invece del titolo del post

**File modificati**: 
- `includes/class-wcag-wp-toc.php` - Metodo `shortcode_toc()` e `sanitize_config()`
- `templates/admin/toc-config-meta-box.php` - Aggiunti campi per il titolo
- `templates/frontend/wcag-toc.php` - Usa titolo configurato invece del titolo post

**Codice prima**:
```php
$config = get_post_meta($toc_id, self::META_CONFIG, true);
// ... template usa $config senza sanitize
```

**Codice dopo**:
```php
$config_raw = get_post_meta($toc_id, self::META_CONFIG, true);
$config = $this->sanitize_config($config_raw);
// ... template ora ha accesso alla configurazione corretta
```

## Problema 10: Placeholder del vecchio shortcode TOC sovrascriveva la nuova implementazione

**Sintomo**: Nonostante la correzione della configurazione, continuava ad apparire "TOC WCAG (Implementazione Fase 3)" invece del vero indice.

**Causa**: Nel file principale `src/class-wcag-wp.php` era rimasto un vecchio metodo `shortcode_toc()` placeholder che sovrascriveva la registrazione del shortcode della classe dedicata `WCAG_WP_TOC`.

**Soluzione**: 
1. Rimosso il metodo placeholder `shortcode_toc()` dal file principale
2. Rimossa la registrazione `add_shortcode('wcag-toc', ...)` dal metodo `register_shortcodes()` del file principale
3. Il shortcode è ora gestito esclusivamente dalla classe `WCAG_WP_TOC`

**File modificato**: `src/class-wcag-wp.php`

**Codice rimosso**:
```php
public function shortcode_toc(array $atts): string {
    return '<div class="wcag-wp-placeholder">TOC WCAG (Implementazione Fase 3)</div>';
}

// E nella registrazione shortcode:
add_shortcode('wcag-toc', [$this, 'shortcode_toc']);
```

## Problema 11: TOC trova heading anche fuori dal contenitore specificato

**Sintomo**: Nonostante sia stato configurato il selettore `#main`, il TOC include anche heading dal footer e da altre parti della pagina.

**Causa**: Il JavaScript aveva due fallback troppo aggressivi:
1. Se non trova il contenitore specificato, usa `document` come fallback
2. Se non trova heading nei contenitori, cerca in tutto il documento

**Soluzione**: Rimossi entrambi i fallback per rispettare rigorosamente il selettore configurato dall'utente.

**File modificato**: `assets/js/toc-frontend.js`

**Codice prima**:
```javascript
let containers = Array.from(document.querySelectorAll(selector));
if (containers.length === 0) containers = [document];

// ... e più avanti:
if (headings.length === 0) {
    headings = Array.from(document.querySelectorAll(levelSelector));
}
```

**Codice dopo**:
```javascript
let containers = Array.from(document.querySelectorAll(selector));
if (containers.length === 0) {
    console.warn('WCAG TOC: Nessun contenitore trovato per il selettore:', selector);
    return; // Non genera TOC se non trova i contenitori specificati
}

// ... fallback rimosso: cerca solo nei contenitori specificati
```

## Problema 12: TOC duplica heading per contenitori annidati e usa selettore sbagliato

**Sintomo**: Il TOC mostrava molti heading duplicati (es. per 1 H2 reale, ne mostrava 3) e usava il selettore di default `#main, .wp-block-group` invece del valore configurato `main`.

**Causa**: Due problemi:
1. La configurazione non veniva salvata/letta correttamente (valori di default sempre usati)
2. I contenitori `.wp-block-group` erano annidati, causando heading duplicati

**Soluzione**:
1. Cambiato il default del selettore da `#main, .entry-content` a `main` per supportare l'elemento `<main>`
2. Aggiunto meccanismo di de-duplicazione nel JavaScript per evitare heading ripetuti

**File modificati**:
- `includes/class-wcag-wp-toc.php` - Default cambiato in `'container_selector' => 'main'`
- `assets/js/toc-frontend.js` - Aggiunto `Set` per tracciare heading già processati

**Codice JS prima**:
```javascript
containers.forEach(c => {
    headings = headings.concat(Array.from(c.querySelectorAll(levelSelector)));
});
```

**Codice JS dopo**:
```javascript
let processedHeadings = new Set();
containers.forEach(c => {
    const foundInContainer = Array.from(c.querySelectorAll(levelSelector));
    foundInContainer.forEach(h => {
        if (!processedHeadings.has(h)) {
            headings.push(h);
            processedHeadings.add(h);
        }
    });
});
```

## Problema 13: Pulsante "Seleziona Immagine" non funziona nel Carousel

**Sintomo**: Il pulsante "Seleziona Immagine" nel componente Carousel non apriva il media uploader di WordPress.

**Causa**: Mancava l'inizializzazione del media uploader di WordPress tramite `wp_enqueue_media()`.

**Soluzione**:
1. Aggiunta `wp_enqueue_media()` nella funzione `enqueue_admin_assets()` della classe `WCAG_WP_Carousel`
2. Aggiunti log di debug nel JavaScript per verificare il funzionamento

**File modificato**: `includes/class-wcag-wp-carousel.php`

**Codice aggiunto**:
```php
// Enqueue WordPress media uploader
wp_enqueue_media();
```

**File modificato**: `assets/js/carousel-admin.js`

**Codice aggiunto**:
```javascript
selectImage(button) {
    console.log('WCAG Carousel: selectImage chiamato');
    
    // Verifica che wp.media sia disponibile
    if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
        console.error('WCAG Carousel: wp.media non è disponibile');
        return;
    }
    // ... resto del codice
}
```

**Risultato**: Il pulsante "Seleziona Immagine" ora apre correttamente il media uploader di WordPress.

---

## Problema 14: Calendario non accessibile (no navigation, no focus) e configurazioni non lette

**Descrizione:** Il componente Calendario non è accessibile - non si può navigare con frecce né con tab, non riceve il focus. Inoltre sembra non leggere le configurazioni, né da admin, né se le aggiungi da shortcode.

**Cause identificate:**
1. Configurazione non caricata correttamente dal database
2. Ordine errato in `wp_parse_args()` - le opzioni shortcode sovrascrivevano la configurazione salvata
3. Configurazione non sanitizzata con valori di default quando caricata dal database
4. Navigazione tastiera non controllava se era abilitata nella configurazione

**Soluzioni implementate:**

### 1. Correzione ordine wp_parse_args
```php
// PRIMA (ERRATO)
$config = wp_parse_args($options, $config);

// DOPO (CORRETTO)
$config = wp_parse_args($config, $options);
```

### 2. Aggiunta sanitizzazione configurazione
```php
// Sanitize config with defaults
$config = $this->sanitize_config($config);
$config = wp_parse_args($config, $options);
```

### 3. Controllo configurazione navigazione tastiera
```javascript
handleKeyboardNavigation(e) {
    // Check if keyboard navigation is enabled
    if (!this.config.keyboard_navigation) {
        console.log('WCAG Calendar: Navigazione tastiera disabilitata');
        return;
    }
    // ... resto del codice
}
```

### 4. Debug logging aggiunto
- Log PHP per configurazione e eventi caricati
- Log JavaScript per inizializzazione e navigazione
- Output HTML comment per debug configurazione

**File modificati:**
- `includes/class-wcag-wp-calendar.php` - Correzione ordine wp_parse_args e aggiunta sanitizzazione
- `assets/js/calendar-frontend.js` - Controllo configurazione e debug logging
- `templates/frontend/wcag-calendar.php` - Debug output HTML

**Stato:** Risolto

### 5. Miglioramento visibilità focus outline
```css
.wcag-wp-calendar-day:focus {
    outline: 3px solid var(--wcag-wp-color-primary);
    outline-offset: 2px;
    z-index: 10;
    box-shadow: 0 0 0 1px var(--wcag-wp-color-primary);
    background: var(--wcag-wp-color-background-highlight);
}
```

**Risultato**: L'outline di focus è ora chiaramente visibile sui giorni del calendario, con supporto per alto contrasto e stati speciali (oggi, con eventi).

### 6. Correzione gestione vista calendario
**Problema**: La vista del calendario non veniva rispettata - rimaneva sempre in vista mensile indipendentemente dalla configurazione.

**Soluzione**: Aggiunto metodo `setInitialView()` nel JavaScript frontend che:
1. Legge correttamente il `viewType` dal data attribute
2. Imposta la vista corretta all'inizializzazione (calendar o list)
3. Aggiorna lo stato dei pulsanti di toggle
4. Mostra/nasconde le viste appropriate

**Codice aggiunto**:
```javascript
setInitialView() {
    // Reset all buttons and views
    // Set the correct initial view based on this.viewType
    if (this.viewType === 'list') {
        // Show list view
    } else {
        // Show calendar view (default)
    }
}
```

**Risultato**: Il calendario ora rispetta la configurazione della vista iniziale sia da admin che da shortcode.

---

*File aggiornato automaticamente dal sistema di risoluzione errori*
