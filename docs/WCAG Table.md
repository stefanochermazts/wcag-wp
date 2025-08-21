# WCAG Table - Tabelle Accessibili WordPress

> **Componente WCAG 2.1 AA compliant per la creazione di tabelle completamente accessibili**

## 📋 Panoramica

Il componente **WCAG Table** permette di creare tabelle completamente accessibili che rispettano gli standard WCAG 2.1 AA. Ogni tabella include supporto completo per screen reader, navigazione da tastiera, responsive design e funzionalità interattive.

### ✨ Caratteristiche Principali

- ♿ **WCAG 2.1 AA Compliant** - Piena accessibilità garantita
- ⌨️ **Navigazione Tastiera** - Tab, frecce direzionali, Home, End
- 🔊 **Screen Reader Ready** - ARIA completo e annunci vocali
- 📱 **Mobile Responsive** - Stack verticale e scroll orizzontale
- 🔍 **Ricerca Integrata** - Filtro testuale in tempo reale
- 📊 **Ordinamento Colonne** - Click su intestazioni per ordinare
- 📤 **Export CSV** - Download diretto dei dati
- 🎨 **Design Customizzabile** - CSS classes e override tema

---

## 🚀 Come Utilizzare WCAG Table

### 1. Creazione Tabella (Admin WordPress)

1. **Accedi al Menu WCAG-WP** nell'admin di WordPress
2. Clicca su **"WCAG Tables"** nel menu laterale
3. Clicca **"Aggiungi Nuovo"** per creare una nuova tabella
4. Compila i campi nelle 4 sezioni principali:

#### 📋 **Configurazione WCAG Table**
```
✅ Titolo e Descrizione
- Titolo tabella (per <caption>)
- Descrizione breve per screen reader
- Classe CSS personalizzata

✅ Opzioni Accessibilità
- Navigazione tastiera avanzata
- Ricerca e filtri
- Ordinamento colonne
- Export CSV

✅ Responsive Design
- Comportamento mobile (stack/scroll)
- Breakpoint personalizzati
- Touch targets ottimizzati
```

#### 🗂️ **Definizione Colonne**
```
Per ogni colonna puoi configurare:
- 📝 Nome colonna (intestazione)
- 🔤 Tipo dati (testo, numero, link, data, immagine)
- 📐 Larghezza (%, px, auto)
- 🔗 Ordinamento (abilitato/disabilitato)
- 🎯 Scope (col, colgroup per accessibilità)
- 🎨 Classe CSS aggiuntiva

Tipi di dato supportati:
• Testo - Contenuto testuale generale
• Numero - Valori numerici con ordinamento intelligente
• Link - URL con testo personalizzato
• Data - Date con formattazione localizzata
• Immagine - URL immagini con alt text
```

#### 📊 **Inserimento Dati**
```
Editor visual per inserire i dati:
- ➕ Aggiungi righe dinamicamente
- 📝 Editor inline per ogni cella
- 🔄 Drag & drop per riordinare righe
- 📋 Copy/paste da Excel o CSV
- 🗑️ Elimina righe selezionate
- 👁️ Preview live durante editing
```

#### 👁️ **Anteprima & Shortcode**
```
Strumenti per il deployment:
- 📋 Shortcode generato automaticamente
- 👁️ Preview live della tabella
- 📊 Statistiche accessibilità (score WCAG)
- 📱 Test responsive integrato
- 📤 Export CSV diretto
```

### 2. Inserimento con Shortcode

#### **Uso Base**
```php
[wcag-table id="123"]
```

#### **Con Parametri Personalizzati**
```php
// Classe CSS custom
[wcag-table id="123" class="my-custom-table"]

// Disabilita ricerca
[wcag-table id="123" searchable="false"]

// Disabilita ordinamento
[wcag-table id="123" sortable="false"]

// Comportamento mobile specifico
[wcag-table id="123" responsive="scroll"]

// Combinazione parametri
[wcag-table id="123" class="pricing-table" searchable="true" responsive="stack"]
```

#### **Parametri Disponibili**

| Parametro | Valori | Default | Descrizione |
|-----------|--------|---------|-------------|
| `id` | number | required | ID della tabella creata in admin |
| `class` | string | '' | Classe CSS aggiuntiva |
| `searchable` | true/false | null | Override impostazione ricerca |
| `sortable` | true/false | null | Override impostazione ordinamento |
| `responsive` | stack/scroll | null | Override comportamento mobile |

### 3. Utilizzo in Template PHP

```php
// In un template WordPress
echo do_shortcode('[wcag-table id="123"]');

// Con variabili dinamiche
$table_id = get_post_meta(get_the_ID(), 'selected_table', true);
echo do_shortcode('[wcag-table id="' . $table_id . '"]');

// Con parametri condizionali
$mobile_class = wp_is_mobile() ? 'mobile-optimized' : 'desktop-view';
echo do_shortcode('[wcag-table id="123" class="' . $mobile_class . '"]');
```

### 4. Integrazione Gutenberg (Futuro)

> **🔮 Prossimamente:** Blocco Gutenberg nativo per inserimento visual

```
📦 Blocco "WCAG Table" pianificato:
- 🎨 Selezione tabella da dropdown
- 👁️ Preview live nell'editor
- ⚙️ Configurazione parametri inline
- 📱 Preview responsive nell'editor
- 🔄 Sincronizzazione automatica modifiche
```

---

## ♿ Accessibilità Implementata

### 🎯 **Standard WCAG 2.1 AA**

```html
✅ Semantic HTML
<table role="table">
  <caption>Titolo della tabella accessibile</caption>
  <thead>
    <tr>
      <th scope="col">Intestazione colonna</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Dato cella</td>
    </tr>
  </tbody>
</table>

✅ ARIA Attributes
- role="table", "row", "columnheader", "cell"
- aria-label per descrizioni aggiuntive
- aria-sort per stato ordinamento colonne
- aria-live per annunci dinamici

✅ Keyboard Navigation
- Tab: Navigazione tra elementi interattivi
- Frecce: Movimento tra celle
- Space: Attivazione ordinamento
- Enter: Conferma azioni
- Home/End: Prima/ultima cella di riga
```

### 🔊 **Screen Reader Support**

```
📢 Annunci Automatici:
- "Tabella con X righe e Y colonne"
- "Colonna [nome] ordinata crescente/decrescente"
- "Ricerca attiva: X risultati trovati"
- "Dati della tabella aggiornati"

📋 Struttura Leggibile:
- Caption con titolo e descrizione
- Intestazioni collegate correttamente
- Celle associate alle intestazioni
- Navigazione logica e prevedibile
```

### 📱 **Mobile & Touch**

```
👆 Touch Targets: 44x44px minimum
📱 Responsive Modes:
  • Stack: Colonne impilate verticalmente
  • Scroll: Scroll orizzontale preservando struttura
📏 Text Scaling: Supporto zoom fino 200%
🎨 High Contrast: Supporto modalità ad alto contrasto
```

---

## 🎨 Personalizzazione CSS

### **Classi CSS Disponibili**

```css
/* Container principale */
.wcag-wp-table-container {
  /* Stili container tabella */
}

/* Tabella */
.wcag-wp-table {
  /* Stili tabella base */
}

/* Intestazioni */
.wcag-wp-table th {
  /* Stili intestazioni colonne */
}

/* Celle dati */
.wcag-wp-table td {
  /* Stili celle dati */
}

/* Controlli ricerca/ordinamento */
.wcag-wp-table-controls {
  /* Stili controlli superiori */
}

/* Responsive stack mode */
.wcag-wp-table--stacked {
  /* Stili modalità mobile stack */
}

/* Stato ordinamento */
.wcag-wp-table th[aria-sort="ascending"] {
  /* Stili colonna ordinata crescente */
}
```

### **Personalizzazione Avanzata**

```css
/* Override nel tema */
.my-custom-table {
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.my-custom-table th {
  background: #2271b1;
  color: white;
  font-weight: 600;
}

.my-custom-table tr:nth-child(even) {
  background: #f8f9fa;
}

/* Focus personalizzato (mantenere accessibilità) */
.my-custom-table td:focus,
.my-custom-table th:focus {
  outline: 3px solid #005fcc;
  outline-offset: 2px;
}
```

---

## 📊 Esempi Pratici

### **1. Tabella Prezzi**

```php
[wcag-table id="456" class="pricing-table" responsive="stack"]
```

**Configurazione suggerita:**
- Colonne: Piano, Prezzo, Caratteristiche, Azione
- Tipo dati: Testo, Numero, Testo, Link
- Ordinamento: Abilitato su Prezzo
- Ricerca: Abilitata

### **2. Elenco Dipendenti**

```php
[wcag-table id="789" searchable="true" sortable="true"]
```

**Configurazione suggerita:**
- Colonne: Nome, Ruolo, Dipartimento, Email, Telefono
- Tipo dati: Testo, Testo, Testo, Link, Testo
- Ordinamento: Tutti abilitati
- Ricerca: Abilitata per filtro rapido

### **3. Calendario Eventi**

```php
[wcag-table id="101" class="events-calendar" responsive="scroll"]
```

**Configurazione suggerita:**
- Colonne: Data, Ora, Evento, Luogo, Iscrizioni
- Tipo dati: Data, Testo, Testo, Testo, Link
- Ordinamento: Data e Ora
- Export: Abilitato per download calendario

---

## 🔧 Troubleshooting

### **Problemi Comuni**

**❌ Tabella non visualizzata**
```
✅ Verifica:
- ID tabella corretto nel shortcode
- Tabella pubblicata (non bozza)
- Almeno una riga di dati inserita
- Plugin attivo e aggiornato
```

**❌ Stili non applicati**
```
✅ Soluzioni:
- Svuota cache se presente
- Verifica conflitti con tema/plugin
- Controlla override CSS tema
- Testa in tema default WordPress
```

**❌ Ricerca non funziona**
```
✅ Controlla:
- JavaScript abilitato nel browser
- Ricerca attivata nelle impostazioni tabella
- Dati presenti nelle celle per filtrare
- Console browser per errori JS
```

**❌ Problemi accessibility**
```
✅ Valida:
- HTML validator (validator.w3.org)
- WAVE accessibility checker
- Screen reader test (NVDA gratuito)
- Navigazione solo tastiera
```

---

## 📈 Performance

### **Ottimizzazioni Implementate**

- 📦 **Asset condizionali**: CSS/JS caricati solo se shortcode presente
- 🚀 **Vanilla JavaScript**: Zero dipendenze, performance massima
- 💾 **Caching smart**: Meta dati ottimizzati per query veloci
- 📱 **Mobile-first**: CSS ottimizzato per dispositivi mobili
- 🔄 **Lazy loading**: Tabelle grandi caricate progressivamente

### **Benchmark Tipici**

- 📏 **Dimensioni**: ~15KB CSS + 12KB JS totali
- ⚡ **Load Time**: <100ms per tabelle fino a 100 righe
- 💾 **Memory**: <2MB RAM per tabelle standard
- 📊 **Database**: 1-3 query per caricamento tabella

---

## 🆕 Roadmap Sviluppo

### **Versione Attuale (2.0)**
- ✅ Shortcode completo
- ✅ Admin interface avanzata
- ✅ WCAG 2.1 AA compliance
- ✅ Export CSV
- ✅ Mobile responsive

### **Prossimi Aggiornamenti**
- 🔮 **Blocco Gutenberg** nativo
- 🔮 **Import Excel/CSV** diretto
- 🔮 **Template predefiniti** (prezzi, confronti)
- 🔮 **API REST** per integrazioni
- 🔮 **Filtri avanzati** (date range, multiselect)

---

## 📞 Supporto

**🐛 Bug Report**: [GitHub Issues](https://github.com/stefanochermazts/wcag-wp/issues)  
**💡 Feature Request**: [GitHub Discussions](https://github.com/stefanochermazts/wcag-wp/discussions)  
**📚 Documentazione**: [Plugin Repository](https://github.com/stefanochermazts/wcag-wp)

---

*Documentazione aggiornata per WCAG-WP v2.0 - Agosto 2025*
