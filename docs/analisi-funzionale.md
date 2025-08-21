📄 Analisi Funzionale – Plugin WordPress Accessibile
1. Obiettivi

Il plugin ha lo scopo di fornire agli utenti WordPress un set di componenti accessibili per la pubblicazione di contenuti interattivi e strutturati, garantendo responsività mobile, WCAG 2.1 AA compliance e personalizzazione grafica tramite un design system unico.
La prima release (MVP) si concentrerà sulla gestione tabellare, evolvendo poi in un bundle di componenti accessibili.

2. Attori principali

Amministratore WP: installa, configura e aggiorna il plugin; definisce impostazioni globali (tema, palette, accessibilità).

Editor/Autore WP: utilizza il plugin per creare e inserire tabelle e altri componenti nelle pagine/articoli.

Utente finale (visitatori sito): interagisce con i componenti (tabelle, accordion, TOC, ecc.) con pieno supporto a tastiera e screen reader.

3. Requisiti funzionali principali
3.1 Gestione Tabellare (Core MVP)

Creazione e gestione di tabelle dal backend WP (Custom Post Type "Tabelle").

Definizione colonne (tipi: testo, numero, link, immagine).

Inserimento/edizione dei dati riga per riga tramite interfaccia semplice.

Salvataggio dati su DB dedicato (tabella custom o metadati CPT).

Shortcode e blocco Gutenberg per inserire la tabella nei contenuti.

Visualizzazione responsiva (stack su mobile o scrollabile).

Funzionalità frontend:

Ordinamento colonne.

Ricerca testuale interna.

Esportazione CSV (free).

Versione Pro: import/export Excel e PDF, editing inline, filtri avanzati, connessione a Google Sheets/API REST.

3.2 Altri Componenti Accessibili (Roadmap)
Accordion & Tab Panel

Creazione blocchi Gutenberg accordion e tab panel.

Navigazione tastiera (freccia sx/dx, su/giù).

Supporto ARIA (role="tablist", role="tabpanel").

Table of Contents (TOC)

Generazione automatica TOC dai titoli <h2>, <h3> ecc.

Navigazione accessibile e possibilità di collassare/espandere.

Opzione TOC “sticky” (Pro).

Slider/Carousel

Inserimento immagini/testi in slider.

Controlli tastiera, annuncio slide attiva a screen reader.

Pro: autoplay controllabile, video support.

Calendario/Eventi

Lista e calendario eventi.

Navigazione a tastiera tra giorni/mesi.

Pro: prenotazioni eventi, sync iCal/Google Calendar.

Notifiche & Alert

Componente per mostrare messaggi dinamici (aria-live).

Varianti: success, warning, error.

Pro: condizioni dinamiche, design multipli.

4. Requisiti non funzionali

Accessibilità: conformità WCAG 2.1 AA + test screen reader (NVDA, VoiceOver).

Responsività: mobile-first, layout fluido.

Compatibilità: WP 6.x+, supporto multisite.

Scalabilità: architettura modulare → ogni componente è un modulo indipendente.

Design System: unico set di CSS Custom Properties per colori, tipografia, spaziature.

Zero dipendenze JS: solo vanilla JS (no jQuery).

5. Requisiti Pro (premium)

Import/export avanzato (Excel, PDF).

Filtri avanzati (dropdown, range numerici/date).

Editing inline frontend.

Template avanzati (pricing tables, comparazioni prodotti, ranking sportivi).

Multi-lingua (WPML/Polylang).

Integrazione WooCommerce (listini comparativi).

Collegamento a fonti esterne (Google Sheets/API REST).

Sistema licenze e aggiornamenti automatici.

6. Estensioni future

Libreria di template pronti (per ogni componente).

Dashboard unica per gestire tutti i plugin accessibili come suite.

Marketplace interno per scaricare componenti aggiuntivi.

7. Benefici attesi

🟢 Utenti finali: esperienza coerente, leggibile, navigabile anche con screen reader e tastiera.

🟢 Editor WP: interfaccia semplice e consistente per inserire elementi avanzati.

🟢 Amministratori: plugin modulare, aggiornabile e facilmente personalizzabile con pochi CSS.

🟢 Mercato: posizionamento unico come “prima suite WordPress 100% accessibile”.