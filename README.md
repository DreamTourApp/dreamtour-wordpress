# DreamTour - Piattaforma WordPress per Gestione Tour e Viaggi di Gruppo

Una soluzione completa e professionale per agenzie di viaggio che organizzano tour di gruppo, crociere, escursioni e viaggi organizzati. Sistema integrato di gestione tour, prenotazioni online, selezione posti pullman e area clienti.

## 🎯 Panoramica della Piattaforma

**DreamTour** è una piattaforma WordPress enterprise-grade sviluppata specificamente per agenzie di viaggio specializzate in tour di gruppo. Combina un tema moderno e responsive con plugin personalizzati per gestire l'intero ciclo di vita di un tour: dalla creazione alla prenotazione, dal pagamento alla gestione posti.

### Caratteristiche Principali

- ✅ **Sistema di Prenotazione Completo** - Booking online con selezione posti pullman interattiva
- ✅ **Area Riservata Clienti** - Dashboard personale per gestire prenotazioni e profili
- ✅ **Gestione Tour Avanzata** - CRUD completo con categorie, filtri e metadata estesa
- ✅ **Pagamenti Integrati** - Stripe Checkout, acconto/saldo, conferme automatiche
- ✅ **Email Automatiche** - Conferme, biglietti QR code, notifiche stato prenotazione
- ✅ **Design Moderno** - Ispirato a WeRoad, completamente responsive e accessibile
- ✅ **Mobile-First** - Ottimizzato per smartphone con modal booking dedicati
- ✅ **SEO Ottimizzato** - Ricerca limitata ai tour, metadata completi, sitemap
- ✅ **Multilingua Ready** - Sistema di traduzione integrato (IT/ES)

---

## 📦 Componenti del Sistema

### 1. Tema DreamTour (`wp-content/themes/dreamtour/`)

Tema WordPress professionale con design moderno ispirato alle piattaforme di viaggio contemporanee.

#### Caratteristiche Tema

**Design & UX:**
- Design moderno e pulito con palette colori navy/cyan (#003284, #1aabe7)
- Tipografia Poppins (300-900 weights) da Google Fonts
- Sistema di spacing e colori con CSS variables
- Responsive design con breakpoint ottimizzati (768px, 1024px, 1200px)
- Accessibilità WCAG 2.1 AA compliant
- Animazioni fluide e micro-interazioni

**Template & Layout:**
- Homepage con hero section e griglia tour
- Archivio tour con filtri e paginazione
- Singolo tour con galleria, dettagli e booking card
- Header con menu, search overlay, area riservata
- Footer con categorie, social links, info aziendali
- Template personalizzati per prenotazioni e checkout

**Custom Post Type:**
- **Tours** - Post type principale per i tour
- Supporto per immagini, excerpt, editor, custom fields
- Archive e single template dedicati
- Componente riutilizzabile `content-tour-card.php`

**Funzionalità Avanzate:**
- Search limitata ai soli tour (pre_get_posts hook)
- Language switcher italiano/spagnolo
- Widget areas (4 colonne footer + sidebar)
- Sistema di versioning asset con query string
- WhatsApp floating button customizzabile
- Truncated user display name per header

**File Chiave:**
- `functions.php` - Setup tema, enqueue, custom functions
- `single-tour.php` - Template singolo tour con booking card
- `archive-tour.php` - Listing tour con filtri
- `header.php` - Header con search overlay e menu
- `footer.php` - Footer con categorie tour dinamiche
- `style.css` - Stili principali e CSS variables
- `assets/css/main.css` - Stili aggiuntivi e responsive
- `assets/js/main.js` - JavaScript interazioni e AJAX

---

### 2. Plugin DRTR Gestione Tours (`wp-content/plugins/drtr-gestione-tours/`)

Sistema backend per la gestione completa dei tour con interfaccia admin dedicata.

#### Caratteristiche Plugin

**Custom Post Type & Taxonomies:**
- CPT `drtr_tour` con metadata estesa
- Tassonomia `drtr_destination` per destinazioni
- Tassonomia `drtr_tour_type` per tipologie tour

**Categorie Tour Disponibili:**
- 🚢 Crociere di gruppo
- ✈️ Voli di gruppo
- 🏖️ Giornate al mare
- 🇮🇹 Viaggi in Italia
- 🚂 Bernina Express panoramico
- 🎄 Mercatini di Natale
- ⛰️ Vacanze in montagna

**Campi Metadata Tour:**
- Prezzo, durata, tipo trasporto
- Numero massimo partecipanti
- Date partenze multiple
- Località, rating, descrizioni
- Immagini galleria
- Note e info aggiuntive

**Pannello Admin `/gestione-tours`:**
- Tabella tour con filtri per stato/categoria
- CRUD completo via AJAX
- Form modale per creazione/modifica
- Bulk actions e ordinamento
- Pagination e ricerca
- Solo admin con capability `manage_options`

**Architettura:**
- Pattern Singleton per ogni classe
- Separazione concerns (Post Type, Meta Boxes, AJAX, Frontend)
- Nonce verification per sicurezza
- Data sanitization completa

**File Chiave:**
- `drtr-gestione-tours.php` - Bootstrap plugin
- `includes/class-drtr-post-type.php` - Registrazione CPT/taxonomies
- `includes/class-drtr-meta-boxes.php` - Campi custom
- `includes/class-drtr-ajax-handler.php` - Handler AJAX CRUD
- `includes/class-drtr-frontend.php` - Render pagina admin

---

### 3. Plugin DRTR Reserved Area (`wp-content/plugins/drtr-reserved-area/`)

Area clienti completa con registrazione, login, profilo e gestione prenotazioni.

#### Caratteristiche Plugin

**Autenticazione:**
- Registrazione utenti con email validation
- Login/logout con redirect personalizzati
- Password reset flow completo
- Session management sicura

**Dashboard Clienti:**
- Overview prenotazioni attive
- Storico viaggi completati
- Statistiche personalizzate
- Quick actions (profilo, logout)

**Gestione Profilo:**
- Modifica dati personali
- Upload avatar
- Preferenze comunicazioni
- Cronologia modifiche

**Visualizzazione Prenotazioni:**
- Lista prenotazioni con filtri stato
- Dettagli tour prenotati
- Info pagamenti (acconto/saldo)
- Download biglietti QR code
- Posti assegnati pullman

**Shortcodes Disponibili:**
- `[drtr_login_form]` - Form login
- `[drtr_register_form]` - Form registrazione
- `[drtr_user_dashboard]` - Dashboard clienti
- `[drtr_user_profile]` - Profilo utente
- `[drtr_user_bookings]` - Lista prenotazioni

**Pagine Area Riservata:**
- `/area-riservata` - Dashboard principale
- `/il-mio-profilo` - Gestione profilo
- `/le-mie-prenotazioni` - Storico prenotazioni
- `/registrati` - Registrazione nuovo utente

---

### 4. Plugin DRTR Checkout (`wp-content/plugins/drtr-checkout/`)

Sistema completo di prenotazione e checkout con selezione posti e pagamenti.

#### Caratteristiche Plugin

**Booking CPT:**
- Custom Post Type `drtr_booking`
- Stati: pending, deposit, paid, completed, cancelled
- Metadata: tour, utente, posti, prezzi, pagamenti
- Relazioni con tour e utenti

**Selezione Posti Pullman:**
- Mappa interattiva posti (configurabile righe/colonne)
- Posti occupati/disponibili/selezionati in tempo reale
- Validazione disponibilità AJAX
- Lock temporaneo posti durante checkout
- Schema personalizzabile per tour (20-60 posti)

**Form Prenotazione:**
- Selezione numero partecipanti
- Scelta data partenza
- Calcolo prezzi automatico (adulti/bambini)
- Validazione disponibilità posti
- Riepilogo dettagliato

**Checkout Flow:**
1. Selezione tour e data
2. Scelta numero partecipanti
3. Selezione posti pullman (mappa interattiva)
4. Inserimento dati passeggeri
5. Scelta modalità pagamento (acconto 30% o saldo completo)
6. Pagamento Stripe
7. Conferma e invio email

**Integrazione Stripe:**
- Stripe Checkout Session
- Pagamento acconto (30%) o totale (100%)
- Webhook handlers per conferme
- Gestione errori e retry
- Test mode e production mode

**Email Automatiche:**
- Conferma prenotazione con dettagli
- Biglietto elettronico con QR code
- Reminder pagamento saldo
- Notifiche cambio stato
- Template HTML responsive

**Biglietti Elettronici:**
- QR Code univoco per prenotazione
- Dettagli tour e passeggeri
- Posti assegnati pullman
- Info pagamento e stato
- Download PDF

**Pannello Admin Prenotazioni `/gestione-prenotazioni`:**
- Tabella prenotazioni con filtri avanzati
- Filtri per: stato, tour, utente, date
- Cambio stato manuale con colori dinamici
- Paginazione 20 risultati per pagina
- Export dati (CSV/Excel)
- Bulk operations

**Color Coding Stati Prenotazione:**
- 🟡 **Pending** - Giallo (#fff3cd)
- 🔵 **Deposit** - Cyan (#d1ecf1)
- 🟢 **Paid** - Verde (#d4edda)
- 🔵 **Completed** - Blu (#cce5ff)
- 🔴 **Cancelled** - Rosso (#f8d7da)

**Debug Tools:**
- `/debug-pullman` - Visualizza mappa posti
- `/debug-posti-logs` - Log prenotazioni posti
- `/debug-checkout` - Test flow checkout
- Console log AJAX dettagliato

---

## 🛠️ Stack Tecnologico

### Backend
- **WordPress** 6.0+ (Core CMS)
- **PHP** 7.4+ (Server-side logic)
- **MySQL** 5.6+ (Database)
- **WordPress REST API** (API endpoints)

### Frontend
- **HTML5** (Semantic markup)
- **CSS3** (Flexbox, Grid, Variables)
- **JavaScript ES6+** (Vanilla JS + jQuery)
- **AJAX** (Comunicazione asincrona)
- **Responsive Design** (Mobile-first approach)

### Integrations
- **Stripe API** (Payment processing)
- **Google Fonts** (Typography - Poppins)
- **QR Code Library** (Ticket generation)
- **Email SMTP** (Transactional emails)

### Hosting & Deploy
- **Hostinger** (Production hosting)
- **LiteSpeed Cache** (Performance optimization)
- **Git** (Version control - GitHub)
- **SFTP/SSH** (Deployment)

---

## 🎨 Design System

### Color Palette

```css
/* Primary Colors */
--primary: #003284;          /* Navy Blue */
--primary-light: #1aabe7;    /* Cyan */
--primary-lighter: #46c7f0;  /* Light Cyan */

/* Secondary Colors */
--secondary: #082a5b;        /* Dark Navy */
--accent: #1ba4ce;           /* Accent Cyan */

/* Status Colors */
--success: #28a745;          /* Green */
--warning: #ffc107;          /* Yellow */
--danger: #dc3545;           /* Red */
--info: #17a2b8;             /* Cyan */

/* Neutrals */
--text: #333333;
--text-light: #666666;
--border: #dddddd;
--background: #f5f5f5;
```

### Typography

```css
/* Font Family */
font-family: 'Poppins', sans-serif;

/* Headings */
H1: 34px / 900 weight
H2: 22px / 700 weight
H3: 18px / 600 weight

/* Body */
Body: 14px / 400 weight
Small: 12px / 300 weight
```

### Spacing Scale

```css
--spacing-xs: 8px;
--spacing-sm: 16px;
--spacing-md: 24px;
--spacing-lg: 32px;
--spacing-xl: 48px;
--spacing-xxl: 64px;
```

### Breakpoints

```css
/* Mobile First */
mobile: < 768px (default)
tablet: 768px - 1023px
desktop: 1024px - 1199px
wide: >= 1200px
```

---

## 📱 Funzionalità Mobile

### Mobile Booking System
- **Sticky Bottom Trigger** - Pulsante fisso in basso con prezzo
- **Bottom Sheet Modal** - Slide-up modal per booking (90vh)
- **Touch Optimizations** - Target 44px minimum, swipe gestures
- **Responsive Forms** - Input ottimizzati per tastiere mobile
- **Mobile Menu** - Hamburger menu con overlay full-screen

### Mobile-Specific Features
- Tour title nel trigger mobile
- Heading margins ridotti su mobile
- Email layout responsive per ticket QR
- Tap-to-call sui numeri telefono
- Tap-to-email sui contatti

---

## 🔒 Sicurezza & Performance

### Sicurezza
- ✅ Nonce verification su tutti gli AJAX calls
- ✅ Data sanitization (sanitize_text_field, esc_html, esc_url)
- ✅ Capability checks (`manage_options`, `edit_posts`)
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (output escaping)
- ✅ CSRF protection (WordPress nonces)
- ✅ Secure password hashing (wp_hash_password)
- ✅ SSL/TLS per checkout e pagamenti

### Performance
- ✅ Asset versioning con query strings
- ✅ Conditional loading (enqueue solo dove necessario)
- ✅ Database query optimization
- ✅ Transient caching per query pesanti
- ✅ Image optimization e lazy loading
- ✅ Minification CSS/JS in production
- ✅ LiteSpeed Cache integration
- ✅ CDN per Google Fonts

---

## 🚀 Installazione & Setup

### Requisiti Minimi
- PHP 7.4 o superiore
- MySQL 5.6 o superiore
- WordPress 6.0 o superiore
- Server Apache/Nginx con mod_rewrite
- SSL Certificate per pagamenti
- 256MB PHP memory limit

### Installazione

```bash
# 1. Clone repository
git clone https://github.com/DreamTourApp/dreamtour-wordpress.git

# 2. Configure WordPress
cp wp-config-sample.php wp-config.php
# Edit wp-config.php con credenziali database

# 3. Attiva tema
# Dashboard > Aspetto > Temi > Attiva "DreamTour"

# 4. Attiva plugins
# Dashboard > Plugin > Attiva tutti i plugin DRTR

# 5. Configure Stripe
# Dashboard > DRTR Checkout > Settings
# Inserisci API keys Stripe (test/live)

# 6. Create pages
# Crea pagine: Area Riservata, Registrati, Il Mio Profilo, Le Mie Prenotazioni
# Assegna shortcodes appropriati

# 7. Configure menu
# Dashboard > Aspetto > Menu > Assegna menu a "Primary"

# 8. Setup permalink
# Dashboard > Impostazioni > Permalink > Nome articolo
```

### Configurazione Stripe

```php
// Test Mode
STRIPE_PUBLISHABLE_KEY = 'pk_test_...'
STRIPE_SECRET_KEY = 'sk_test_...'

// Production Mode
STRIPE_PUBLISHABLE_KEY = 'pk_live_...'
STRIPE_SECRET_KEY = 'sk_live_...'
```

---

## 📊 Costi di Sviluppo (Agenzia Web)

### Breakdown Sviluppo Professionale

#### Fase 1: Analisi e Progettazione (40 ore)
- **Analisi Requisiti Business** - 12 ore @ €60/h = €720
- **User Stories & Use Cases** - 8 ore @ €60/h = €480
- **Wireframe & Mockup Design** - 12 ore @ €70/h = €840
- **Database Schema & Architecture** - 8 ore @ €70/h = €560
- **Subtotale Fase 1:** €2,600

#### Fase 2: Sviluppo Tema DreamTour (80 ore)
- **Setup Base Tema WordPress** - 8 ore @ €50/h = €400
- **Design System & CSS Variables** - 12 ore @ €50/h = €600
- **Template Homepage & Archive** - 16 ore @ €55/h = €880
- **Single Tour Template** - 12 ore @ €55/h = €660
- **Header/Footer/Components** - 12 ore @ €50/h = €600
- **Responsive Mobile Design** - 16 ore @ €55/h = €880
- **Search & Filters** - 4 ore @ €55/h = €220
- **Subtotale Fase 2:** €4,240

#### Fase 3: Plugin DRTR Gestione Tours (60 ore)
- **Custom Post Type & Taxonomies** - 8 ore @ €60/h = €480
- **Admin CRUD Interface** - 20 ore @ €65/h = €1,300
- **AJAX Handlers & Validation** - 12 ore @ €65/h = €780
- **Meta Boxes & Custom Fields** - 12 ore @ €60/h = €720
- **Filtri e Ricerca Avanzata** - 8 ore @ €60/h = €480
- **Subtotale Fase 3:** €3,760

#### Fase 4: Plugin DRTR Reserved Area (50 ore)
- **Sistema Autenticazione** - 12 ore @ €65/h = €780
- **Dashboard Clienti** - 12 ore @ €60/h = €720
- **Gestione Profilo Utente** - 10 ore @ €60/h = €600
- **Visualizzazione Prenotazioni** - 12 ore @ €60/h = €720
- **Shortcodes & Templates** - 4 ore @ €55/h = €220
- **Subtotale Fase 4:** €3,040

#### Fase 5: Plugin DRTR Checkout & Payments (100 ore)
- **Booking CPT & Metadata** - 12 ore @ €60/h = €720
- **Form Prenotazione Multi-Step** - 16 ore @ €65/h = €1,040
- **Selezione Posti Pullman Interattiva** - 24 ore @ €70/h = €1,680
- **Integrazione Stripe Checkout** - 20 ore @ €75/h = €1,500
- **Webhook Handlers & Validation** - 12 ore @ €70/h = €840
- **Sistema Email Automatiche** - 8 ore @ €60/h = €480
- **Generazione QR Code Biglietti** - 4 ore @ €65/h = €260
- **Admin Panel Prenotazioni** - 4 ore @ €60/h = €240
- **Subtotale Fase 5:** €6,760

#### Fase 6: Testing & Quality Assurance (40 ore)
- **Unit Testing** - 12 ore @ €55/h = €660
- **Integration Testing** - 12 ore @ €55/h = €660
- **User Acceptance Testing** - 8 ore @ €50/h = €400
- **Bug Fixing** - 8 ore @ €55/h = €440
- **Subtotale Fase 6:** €2,160

#### Fase 7: Ottimizzazioni & Performance (30 ore)
- **Mobile Optimization** - 12 ore @ €60/h = €720
- **SEO On-Page** - 6 ore @ €55/h = €330
- **Performance Tuning** - 8 ore @ €60/h = €480
- **Security Hardening** - 4 ore @ €65/h = €260
- **Subtotale Fase 7:** €1,790

#### Fase 8: Deployment & Documentation (20 ore)
- **Setup Production Server** - 4 ore @ €60/h = €240
- **Migration & Deploy** - 6 ore @ €55/h = €330
- **Documentazione Tecnica** - 6 ore @ €50/h = €300
- **Training Utenti** - 4 ore @ €50/h = €200
- **Subtotale Fase 8:** €1,070

### Totale Costi Sviluppo

| Fase | Ore | Costo |
|------|-----|-------|
| Analisi e Progettazione | 40 | €2,600 |
| Sviluppo Tema | 80 | €4,240 |
| Plugin Gestione Tours | 60 | €3,760 |
| Plugin Area Riservata | 50 | €3,040 |
| Plugin Checkout & Payments | 100 | €6,760 |
| Testing & QA | 40 | €2,160 |
| Ottimizzazioni | 30 | €1,790 |
| Deployment | 20 | €1,070 |
| **TOTALE** | **420 ore** | **€25,420** |

### Costi Aggiuntivi (Opzionali)

- **Supporto Post-Launch (3 mesi)** - €300/mese = €900
- **Manutenzione Annuale** - €1,200/anno
- **Feature Aggiuntive** - €60-75/ora
- **Integrazioni Third-Party** - €500-2,000/integrazione
- **Custom Reports & Analytics** - €1,200-2,500

### Licenze & Servizi Esterni

- **Stripe Fees** - 1.4% + €0.25 per transazione (EU cards)
- **Hosting Premium** - €15-50/mese
- **SSL Certificate** - Incluso con hosting
- **Domain** - €12-15/anno
- **Email SMTP Service** - €10-25/mese
- **Backup Service** - €5-15/mese

---

## 🎓 Package di Sviluppo Disponibili

### Pacchetto STARTER - €18,900
**Ideale per agenzie piccole/medie (< 50 tour/anno)**

✅ Tema DreamTour completo  
✅ Plugin Gestione Tours  
✅ Plugin Area Riservata Base  
✅ Checkout con pagamento Stripe  
✅ Selezione posti semplice (no pullman)  
✅ 3 mesi supporto  
✅ Documentazione base  

**Tempo di sviluppo:** 8-10 settimane  
**Ore totali:** 300

---

### Pacchetto PROFESSIONAL - €25,420 ⭐ COMPLETO
**Soluzione completa come sviluppata**

✅ **Tutto incluso nel progetto attuale**  
✅ Selezione posti pullman interattiva  
✅ Email automatiche con QR code  
✅ Admin panel avanzato  
✅ Mobile optimization completa  
✅ Testing completo  
✅ 3 mesi supporto prioritario  
✅ Documentazione completa  

**Tempo di sviluppo:** 12-14 settimane  
**Ore totali:** 420

---

### Pacchetto ENTERPRISE - €38,500
**Per grandi agenzie con volumi elevati**

✅ Tutto del pacchetto Professional  
✅ Multi-currency support  
✅ Advanced reporting & analytics  
✅ CRM integration  
✅ Multi-language (3+ lingue)  
✅ API REST per integrazioni  
✅ Custom email templates builder  
✅ SMS notifications  
✅ Loyalty program system  
✅ Advanced SEO & Marketing tools  
✅ 6 mesi supporto prioritario  
✅ Training on-site  

**Tempo di sviluppo:** 18-20 settimane  
**Ore totali:** 600

---

## 📞 Contatti & Supporto

**Agenzia di Sviluppo Web Professionale**

- 📧 Email: info@dreamtourviaggi.it
- 🌐 Website: https://dreamtourviaggi.it
- 📱 WhatsApp: +39 389 1733185
- 📍 Indirizzo: Via E. Pecchi N 8, Turano Lodigiano (LO)

**Developer Contact:**
- GitHub: [@DreamTourApp](https://github.com/DreamTourApp)
- Repository: [dreamtour-wordpress](https://github.com/DreamTourApp/dreamtour-wordpress)

---

## 📄 Licenza & Copyright

**Copyright © 2026 DreamTour by Manuel Fernando Araujo Morales**  
Tutti i diritti riservati.

Questo progetto è proprietà privata e protetto da copyright.  
Non è permesso l'uso, la copia, la modifica o la distribuzione senza autorizzazione scritta.

---

## 🎉 Credits

**Sviluppato da:** Manuel Fernando Araujo Morales  
**Progetto:** DreamTour Viaggi  
**Anno:** 2026  
**Versione:** 1.0.0

**Tecnologie & Framework:**
- WordPress Core Team
- Stripe Payment API
- Google Fonts (Poppins)
- Font Awesome Icons

---

**🚀 Ready to launch your travel agency platform? Contact us today!**
