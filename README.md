# WCAG-WP - Plugin WordPress Accessibile

[![WordPress](https://img.shields.io/badge/WordPress-6.0%2B-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://php.net/)
[![WCAG](https://img.shields.io/badge/WCAG-2.1%20AA-green.svg)](https://www.w3.org/WAI/WCAG21/quickref/)
[![License](https://img.shields.io/badge/License-GPL%20v2-blue.svg)](https://www.gnu.org/licenses/gpl-2.0)

> **Plugin WordPress per componenti 100% accessibili e conformi alle linee guida WCAG 2.1 AA**

WCAG-WP è la prima suite di componenti WordPress progettata da zero per l'accessibilità. Ogni elemento è testato con screen reader, navigabile da tastiera e rispetta tutti i criteri di successo WCAG 2.1 AA.

## 🚀 Caratteristiche Principali

### ✅ **Accessibilità Garantita**
- **WCAG 2.1 AA Compliant** - Tutti i componenti rispettano gli standard internazionali
- **Screen Reader Ready** - Testato con NVDA, VoiceOver e JAWS
- **Navigazione Tastiera** - Supporto completo Tab, frecce, Space, Enter, Escape
- **Focus Management** - Outline visibili e gestione focus logica
- **ARIA Implementation** - Semantica corretta per tecnologie assistive

### 📱 **Responsive & Mobile-First**
- **Design Adattivo** - Layout fluido per tutti i dispositivi
- **Touch Targets** - Elementi di almeno 44x44px per tocco
- **Stack Tables** - Tabelle che si impilano su mobile per leggibilità
- **Zoom Support** - Funziona perfettamente fino al 200% di zoom

### 🎨 **Design System Integrato**
- **CSS Custom Properties** - Sistema di design coerente e personalizzabile
- **Palette Accessibili** - Schemi colore con contrasto WCAG AA (4.5:1)
- **Font Sans-Serif** - Tipografia ottimizzata per leggibilità
- **No Google Fonts** - Rispetto della privacy con font di sistema

### ⚡ **Performance Ottimizzate**
- **Zero Dipendenze** - Vanilla JavaScript, no jQuery
- **Asset Leggeri** - <25KB totali (CSS + JS minificati)
- **Caricamento Condizionale** - Asset caricati solo quando necessari
- **Cache Friendly** - Ottimizzato per plugin di cache

## 📦 Componenti Disponibili

### 🆕 **Versione 1.0 (MVP)**
- **[📊 Tabelle Accessibili](#tabelle)** - Ordinamento, ricerca, responsive

### 🔜 **Prossimi Rilasci**
- **🎵 Accordion & Tab Panel** - Navigazione tastiera e ARIA completi
- **📑 Table of Contents** - Indice automatico accessibile
- **🖼️ Carousel/Slider** - Controlli tastiera e annunci screen reader
- **📅 Calendario Eventi** - Navigazione date accessibile
- **🔔 Notifiche & Alert** - Messaggi dinamici con aria-live

## 📋 Requisiti di Sistema

- **WordPress:** 6.0 o superiore
- **PHP:** 7.4 o superiore
- **Browser:** Supporto CSS Grid e Custom Properties
- **JavaScript:** Attivo (per funzionalità interattive)

## 🔧 Installazione

### Via WordPress Admin
1. Scarica l'ultima release da [GitHub](https://github.com/stefanochermazts/wcag-wp/releases)
2. Vai su **Plugin → Aggiungi nuovo → Carica plugin**
3. Seleziona il file `wcag-wp.zip` e clicca **Installa**
4. Attiva il plugin

### Via Git
```bash
cd wp-content/plugins/
git clone https://github.com/stefanochermazts/wcag-wp.git
cd wcag-wp
composer install --no-dev
```

### Via Composer
```bash
composer require stefanochermazts/wcag-wp
```

## 📚 Utilizzo Rapido

### Tabelle Accessibili

#### 1. Crea una Nuova Tabella
```
Dashboard → WCAG-WP → Aggiungi Nuova Tabella
```

#### 2. Inserisci nei Contenuti

**Shortcode:**
```php
[wcag-table id="123"]
```

**Blocco Gutenberg:**
```
/wcag-table
```

**Codice PHP:**
```php
echo do_shortcode('[wcag-table id="123"]');
```

#### 3. Personalizza Design
```
Dashboard → WCAG-WP → Impostazioni → Design System
```

## 🎨 Personalizzazione

### CSS Custom Properties
```css
:root {
  --wcag-primary: #2271b1;
  --wcag-success: #00a32a;
  --wcag-font-family: system-ui, sans-serif;
}
```

### Hook WordPress
```php
// Personalizza impostazioni default
add_filter('wcag_wp_default_settings', function($settings) {
    $settings['design_system']['color_scheme'] = 'green';
    return $settings;
});

// Modifica output tabella
add_filter('wcag_wp_table_output', function($html, $table_id) {
    // Personalizza HTML tabella
    return $html;
}, 10, 2);
```

## ♿ Accessibilità

### Standard Supportati
- **WCAG 2.1 AA** - Conformità completa Level AA
- **Section 508** - Compatibile con standard US
- **EN 301 549** - Standard europeo di accessibilità
- **ARIA 1.1** - Implementazione semantica corretta

### Test di Accessibilità
```bash
# Test automatici
npm run test:accessibility

# Test screen reader
npm run test:screen-reader

# Validazione WCAG
npm run validate:wcag
```

### Screen Reader Supportati
- ✅ **NVDA** (Windows) - Testato
- ✅ **VoiceOver** (macOS/iOS) - Testato  
- ✅ **JAWS** (Windows) - Compatibile
- ✅ **TalkBack** (Android) - Compatibile
- ✅ **Orca** (Linux) - Compatibile

## 🛠️ Sviluppo

### Setup Ambiente
```bash
git clone https://github.com/stefanochermazts/wcag-wp.git
cd wcag-wp
composer install
npm install
```

### Build Assets
```bash
# CSS e JS minificati
npm run build

# Modalità sviluppo con watch
npm run dev

# Analisi accessibilità
npm run audit:accessibility
```

### Testing
```bash
# PHPUnit tests
composer test

# Code style
composer cs

# Compatibilità PHP
composer compat

# Test completo
npm run test:all
```

## 📖 Documentazione

- **[📘 Guida Utente](docs/user-guide.md)** - Come utilizzare i componenti
- **[👩‍💻 API Reference](docs/api-reference.md)** - Hook e filtri per sviluppatori
- **[🎨 Design System](docs/design-system.md)** - Personalizzazione avanzata
- **[♿ Accessibility Guide](docs/accessibility.md)** - Linee guida accessibilità
- **[🔧 Contribuire](CONTRIBUTING.md)** - Come contribuire al progetto

## 🤝 Contribuire

Benvenuti i contributi! Questo progetto segue le [linee guida di contribuzione](CONTRIBUTING.md).

### Come Contribuire
1. **Fork** il repository
2. **Crea** un branch per la tua feature (`git checkout -b feature/amazing-feature`)
3. **Commit** le modifiche (`git commit -m 'Add amazing feature'`)
4. **Push** al branch (`git push origin feature/amazing-feature`)
5. **Apri** una Pull Request

### Aree di Contribuzione
- 🐛 **Bug Report** - Segnala problemi di accessibilità
- ✨ **Feature Request** - Proponi nuovi componenti
- 📝 **Documentazione** - Migliora guide e esempi
- 🧪 **Testing** - Test con screen reader e dispositivi
- 🎨 **Design** - Miglioramenti UI/UX accessibili
- 🌍 **Traduzioni** - Localizzazione plugin

## 📊 Roadmap

### Q1 2025 - v1.0 (MVP)
- [x] ~~Struttura plugin WordPress~~
- [x] ~~Design system CSS accessibile~~
- [x] ~~Tabelle responsive con ordinamento~~
- [ ] Blocchi Gutenberg per tabelle
- [ ] Sistema di esportazione CSV

### Q2 2025 - v1.1
- [ ] Accordion e Tab Panel accessibili
- [ ] Table of Contents automatico
- [ ] Miglioramenti performance

### Q3 2025 - v1.2
- [ ] Slider/Carousel accessibile
- [ ] Calendario eventi
- [ ] Sistema notifiche aria-live

### Q4 2025 - v2.0
- [ ] Versione PRO con funzionalità avanzate
- [ ] Marketplace template
- [ ] Integrazione WooCommerce

## 🆘 Supporto

### Canali di Supporto
- **[🐛 Issues GitHub](https://github.com/stefanochermazts/wcag-wp/issues)** - Bug e feature request
- **[💬 Discussions](https://github.com/stefanochermazts/wcag-wp/discussions)** - Domande e discussioni
- **[📧 Email](mailto:stefano@example.com)** - Supporto diretto per accessibilità

### FAQ
**Q: Funziona con il mio tema?**  
A: Sì, WCAG-WP è progettato per funzionare con qualsiasi tema WordPress che supporta gli standard moderni.

**Q: È compatibile con plugin di cache?**  
A: Sì, completamente compatibile con WP Rocket, W3 Total Cache, WP Super Cache e altri.

**Q: Posso personalizzare i colori?**  
A: Sì, attraverso il pannello impostazioni o CSS Custom Properties.

## 📄 Licenza

Questo progetto è rilasciato sotto licenza [GPL v2](LICENSE) - vedi il file LICENSE per i dettagli.

```
WCAG-WP - Plugin WordPress Accessibile
Copyright (C) 2025 Stefano Chermazts

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

## 🙏 Ringraziamenti

- **[W3C WAI](https://www.w3.org/WAI/)** - Per le linee guida WCAG
- **[WordPress Community](https://wordpress.org/)** - Per la piattaforma e gli standard
- **[A11Y Project](https://www.a11yproject.com/)** - Per risorse e best practice
- **Screen Reader Users** - Per feedback e test preziosi

---

<p align="center">
  <strong>Sviluppato con ♿ per l'accessibilità web</strong><br>
  <a href="https://github.com/stefanochermazts/wcag-wp">⭐ Stella questo repository se ti è utile!</a>
</p>
