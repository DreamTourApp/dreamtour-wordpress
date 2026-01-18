# Pagina Profilo - DRTR Reserved Area

## Funzionalità Implementate

### 1. Gestione Informazioni Personali
- Modifica Nome e Cognome
- Aggiornamento Email
- Numero di telefono
- Validazione email in tempo reale
- Controllo duplicati email

### 2. Sicurezza
- Cambio password sicuro
- Verifica password attuale
- Validazione lunghezza minima (8 caratteri)
- Conferma password
- Re-autenticazione automatica dopo cambio password

### 3. Preferenze Utente
- **Notifiche Email**: Abilita/disabilita email di conferma prenotazioni
- **Newsletter**: Iscrizione a offerte e novità

### 4. Conformità GDPR

#### Esportazione Dati
- Download completo di tutti i dati personali in formato JSON
- Include:
  - Informazioni account
  - Metadati utente
  - Storico prenotazioni

#### Eliminazione Account
- Eliminazione completa e irreversibile
- Richiede conferma password
- Modal di conferma con warning
- Elimina:
  - Dati personali
  - Prenotazioni associate
  - Preferenze e impostazioni
- Protezione amministratori (non possono eliminarsi da frontend)

## File Creati/Modificati

### Nuovi File
- `includes/class-drtr-ra-profile.php` - Classe principale per gestione profilo

### File Modificati
- `drtr-reserved-area.php` - Aggiunta inizializzazione classe profilo
- `assets/css/style.css` - Stili per pagina profilo, modal, GDPR
- `assets/js/script.js` - Handler AJAX per tutte le funzionalità profilo
- `includes/class-drtr-ra-dashboard.php` - Aggiornato link profilo

## Shortcode

```
[drtr_profile]
```

La pagina viene creata automaticamente all'attivazione del plugin con slug `/profilo`.

## AJAX Endpoints

1. **drtr_update_profile** - Aggiorna dati personali e preferenze
2. **drtr_update_password** - Cambia password utente
3. **drtr_export_data** - Esporta dati in JSON
4. **drtr_delete_account** - Elimina account permanentemente

## Sicurezza

- Tutti gli endpoint verificano nonce
- Controllo autenticazione utente
- Sanitizzazione input
- Validazione email
- Protezione amministratori
- Password verification per azioni critiche

## Layout Responsive

- Desktop: Layout a 2 colonne per form
- Tablet: Colonna singola, pulsanti adattati
- Mobile: Ottimizzato per schermi piccoli

## Messaggi e Alert

- Successo: Verde con bordo
- Errore: Rosso con bordo
- Auto-dismiss dopo 5 secondi
- Scroll automatico al messaggio

## Modal Eliminazione Account

- Overlay scuro
- Animazioni smooth (fadeIn, slideUp)
- Chiusura con X, overlay o bottone annulla
- Form con campo password
- Warning visivi (colore rosso, icona warning)
- Double confirmation (modal + browser confirm)

## Conformità GDPR

### Diritti Implementati

1. **Diritto di Accesso** - Visualizzazione dati nel profilo
2. **Diritto alla Portabilità** - Esportazione dati JSON
3. **Diritto alla Cancellazione** - Eliminazione account completa
4. **Diritto di Rettifica** - Modifica dati personali

### Avvisi Legali

- Warning esplicito sull'irreversibilità dell'eliminazione
- Elenco di cosa viene eliminato
- Conferma password richiesta

## Database

La funzionalità utilizza le tabelle WordPress standard:
- `wp_users` - Dati utente
- `wp_usermeta` - Metadati (telefono, preferenze)
- `wp_drtr_bookings` - Prenotazioni (se plugin checkout attivo)

## URL e Routing

- Pagina profilo: `/profilo`
- Redirect dopo eliminazione: Homepage
- Redirect login richiesto: `/area-riservata`

## Testing

### Test Case Raccomandati

1. ✅ Aggiorna nome/cognome/email/telefono
2. ✅ Cambia password con password errata
3. ✅ Cambia password con conferma non corrispondente
4. ✅ Cambia password con successo
5. ✅ Modifica preferenze notifiche/newsletter
6. ✅ Esporta dati e verifica contenuto JSON
7. ✅ Tenta eliminazione account con password errata
8. ✅ Elimina account e verifica redirect + logout
9. ✅ Verifica responsive su mobile
10. ✅ Test con utente amministratore (non può eliminarsi)

## Browser Support

- Chrome/Edge: ✅
- Firefox: ✅
- Safari: ✅
- Mobile browsers: ✅

## Dipendenze

- jQuery
- WordPress 5.8+
- PHP 7.4+
- Dashicons (inclusi in WordPress)
