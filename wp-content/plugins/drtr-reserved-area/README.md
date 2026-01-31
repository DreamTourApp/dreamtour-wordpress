# DRTR Reserved Area

Sistema di login personalizzato e area riservata con gestione permessi per ruoli utente WordPress.

## Caratteristiche

✅ **Login Personalizzato** - Formulario di accesso elegante e moderno fuori da wp-admin
✅ **Password Toggle** - Bottone con icona occhio per mostrare/nascondere la password
✅ **Dashboard Dinamico** - Interfaccia adattiva basata sul ruolo utente
✅ **Gestione Permessi** - Contenuti diversi per amministratori e utenti standard
✅ **Multilingue** - Supporto completo per Italiano, Spagnolo e Inglese
✅ **AJAX Login** - Autenticazione veloce senza ricaricare la pagina
✅ **Responsive Design** - Ottimizzato per tutti i dispositivi
✅ **Integrazione Tema** - Si adatta automaticamente al tema DreamTour

## Struttura File

```
drtr-reserved-area/
├── drtr-reserved-area.php          # Plugin principale
├── includes/
│   ├── class-drtr-ra-page-manager.php   # Gestione pagina /area-riservata
│   ├── class-drtr-ra-auth.php           # Sistema di autenticazione
│   └── class-drtr-ra-dashboard.php      # Rendering dashboard
├── assets/
│   ├── css/
│   │   └── style.css                # Stili login e dashboard
│   └── js/
│       └── script.js                # JavaScript per login AJAX
└── languages/
    ├── drtr-reserved-area.pot       # Template traduzione
    ├── it_IT.po/mo                  # Italiano
    ├── es_ES.po/mo                  # Spagnolo
    └── en_US.po/mo                  # Inglese
```

## Installazione

1. Copia la cartella `drtr-reserved-area` in `/wp-content/plugins/`
2. Attiva il plugin dal pannello di amministrazione WordPress
3. La pagina `/area-riservata` verrà creata automaticamente
4. Visita `https://tuosito.com/area-riservata` per accedere

## Utilizzo

### Per Utenti Non Loggati

- Vedranno un formulario di login elegante
- Possono inserire username/email e password
- Opzione "Ricordami" per mantenere la sessione
- Link per recupero password

### Per Utenti Standard

Dopo il login, gli utenti vedranno:
- Card "Il Mio Profilo" per gestire i propri dati
- Messaggio di benvenuto personalizzato
- Pulsante per uscire

### Per Amministratori

Gli amministratori hanno accesso a funzionalità aggiuntive:
- **Gestione Tours** - Link diretto a `/gestione-tours`
- **Dashboard WordPress** - Accesso al pannello wp-admin
- **Gestione Utenti** - Amministrazione utenti
- **Impostazioni** - Configurazione sito
- **Statistiche Rapide** - Tours pubblicati, utenti totali, pagine

## Shortcode

Il plugin crea automaticamente lo shortcode `[drtr_reserved_area]` nella pagina `/area-riservata`.

Puoi usarlo manualmente in qualsiasi pagina:
```
[drtr_reserved_area]
```

## Sicurezza

- ✅ Autenticazione nativa WordPress (wp_signon)
- ✅ Verifica nonce per tutte le richieste AJAX
- ✅ Sanitizzazione di tutti gli input utente
- ✅ Protezione contro CSRF
- ✅ Verifica permessi con `current_user_can()`

## Personalizzazione

### Modificare i Permessi

Modifica `includes/class-drtr-ra-dashboard.php` linea 131:
```php
$is_admin = current_user_can('manage_options');
```

Cambia `manage_options` con altre capability:
- `edit_posts` - Editori
- `publish_posts` - Autori
- `read` - Abbonati

### Aggiungere Card Personalizzate

Nel file `includes/class-drtr-ra-dashboard.php`, aggiungi nuove card nel metodo `render_dashboard()`:

```php
<div class="drtr-ra-card">
    <div class="drtr-ra-card-icon">
        <i class="dashicons dashicons-chart-bar"></i>
    </div>
    <h3><?php _e('Statistiche', 'drtr-reserved-area'); ?></h3>
    <p><?php _e('Visualizza le statistiche del sito', 'drtr-reserved-area'); ?></p>
    <a href="<?php echo esc_url(home_url('/statistiche')); ?>" class="drtr-ra-btn drtr-ra-btn-outline">
        <?php _e('Vedi Statistiche', 'drtr-reserved-area'); ?>
    </a>
</div>
```

### Cambiare Colori

Modifica `assets/css/style.css` per personalizzare il gradiente principale:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

## Compatibilità

- **WordPress:** 5.8+
- **PHP:** 7.4+
- **Tema:** DreamTour (integrato con sistema multilingue)
- **Plugin:** DRTR Gestione Tours (opzionale, per card admin)

## Traduzioni

Il plugin supporta completamente:
- 🇮🇹 **Italiano** (default)
- 🇪🇸 **Spagnolo**
- 🇬🇧 **Inglese**

Le traduzioni si sincronizzano automaticamente con il selettore di lingua del tema DreamTour.

## Hooks Disponibili

Il plugin non espone ancora custom hooks, ma puoi usare i filtri standard WordPress:

```php
// Modificare URL di redirect dopo login
add_filter('login_redirect', function($redirect_to, $request, $user) {
    if (isset($_POST['drtr_ra_login'])) {
        return home_url('/area-riservata');
    }
    return $redirect_to;
}, 10, 3);
```

## Crediti

- **Sviluppatore:** Dream Tour
- **Email:** info@dreamtour.app
- **Versione:** 1.0.0
- **Licenza:** GPL v2 or later

## Changelog

### 1.0.0 - 13/01/2026
- ✅ Release iniziale
- ✅ Sistema di login personalizzato
- ✅ Dashboard con permessi ruoli
- ✅ Multilingue (IT, ES, EN)
- ✅ Design responsive
- ✅ Login AJAX
- ✅ Integrazione tema DreamTour
