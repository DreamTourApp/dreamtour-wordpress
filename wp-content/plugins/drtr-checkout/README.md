# DRTR - Checkout & Prenotazioni

Plugin WordPress per la gestione completa del sistema di checkout e prenotazioni per DreamTour.

## Caratteristiche

- **Custom Post Type Prenotazioni**: Gestione completa delle prenotazioni con stati personalizzati
- **Stati Prenotazione**:
  - In Attesa (`booking_pending`)
  - Acconto Pagato (`booking_deposit`)
  - Pagato (`booking_paid`)
  - Cancellato (`booking_cancelled`)
  - Completato (`booking_completed`)
- **Checkout Completo**: Form responsive con validazione
- **Metodi di Pagamento**:
  - Bonifico Bancario
  - Carta di Credito (preparato per integrazione)
- **Email Automatiche**: Notifiche a cliente e admin
- **Campi Precompilati**: Per utenti loggati

## Dipendenze

Richiede il plugin `drtr-gestione-tours` per funzionare.

## Installazione

1. Caricare la cartella `drtr-checkout` in `/wp-content/plugins/`
2. Attivare il plugin dal menu "Plugin" di WordPress
3. Creare una pagina "Checkout" con shortcode `[drtr_checkout]`
4. Creare una pagina "Grazie Prenotazione" con template "Grazie Prenotazione"

## Shortcodes

- `[drtr_checkout]` - Mostra il form di checkout

## Template

Il plugin include il template `checkout.php` che può essere personalizzato.

## Configurazione

1. Aggiornare i dati IBAN in `class-drtr-checkout.php` (riga ~196)
2. Configurare l'email mittente nelle impostazioni WordPress
3. Per pagamenti con carta, integrare Stripe o PayPal in `class-drtr-checkout.php`

## Sviluppo

### Struttura File

```
drtr-checkout/
├── drtr-checkout.php           # File principale plugin
├── includes/
│   ├── class-drtr-booking.php  # Gestione CPT prenotazioni
│   └── class-drtr-checkout.php # Processamento checkout
└── templates/
    └── checkout.php            # Template pagina checkout
```

### Filtri e Azioni

Il plugin espone diversi hook per personalizzazione avanzata.

## Supporto

Per supporto, contattare il team DreamTour.

## Versione

1.0.0 - Release iniziale
