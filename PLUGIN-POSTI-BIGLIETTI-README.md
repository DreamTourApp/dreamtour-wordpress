# DRTR Posti & Biglietto - Guida Installazione e Uso

## Panoramica

Due plugin integrati per la gestione completa di posti autobus e biglietti con QR code:

- **DRTR Posti**: Gestione posti nell'autobus con selezione visuale
- **DRTR Biglietto**: Generazione biglietti con QR code

## Installazione

### 1. Attivare i Plugin

Nel pannello WordPress:
1. Vai in **Plugin > Plugin installati**
2. Attiva **DRTR Gestione Posti**
3. Attiva **DRTR Biglietto QR Code**

### 2. Creare la Pagina per Selezione Posti

1. Vai in **Pagine > Aggiungi nuova**
2. Titolo: **Seleziona Posti**
3. URL: `/seleziona-posti`
4. Template: Seleziona **Seleziona Posti** dal menu Template
5. Pubblica la pagina

## Flusso Completo

### 1. Admin Conferma Pagamento

Quando un cliente paga:

1. Admin va in **Gestione Prenotazioni** (`/gestione-prenotazioni`)
2. Seleziona la prenotazione
3. Cambia lo status in:
   - **Acconto Pagato** oppure
   - **Pagato**

### 2. Email Automatica al Cliente

Il sistema invia automaticamente:
- Email con link per selezione posti
- Link valido per 7 giorni
- Include informazioni tour e numero posti

### 3. Cliente Seleziona Posti

Il cliente:
1. Clicca sul link nell'email
2. Vede la mappa del bus (pullman gran turismo)
3. Seleziona i posti (evidenziati in blu)
4. Inserisce il nome di ogni passeggero
5. Conferma la selezione

### 4. Generazione Biglietti

Automaticamente:
- Genera QR code unico per ogni posto
- Crea PDF con tutti i biglietti
- Invia email al cliente con:
  - Biglietti visualizzabili
  - Link download PDF
  - QR code stampabili

## Configurazione Bus

### Layout Predefinito

- **Totale posti**: 50
- **Righe**: 13
- **Disposizione**: 2-2 (corridoio centrale)
- **Ultima riga**: 5 posti

### Schema Posti

```
        AUTISTA 🚗
   
1   A  B  | corridoio |  C  D
2   A  B  | corridoio |  C  D
3   A  B  | corridoio |  C  D
...
13  A  B  | corridoio |  C  D  E
```

## Funzionalità Admin

### Assegnazione Manuale Posti

Per assegnare posti manualmente:

```php
// Da implementare nel pannello gestione prenotazioni
// L'admin può cliccare su "Assegna Posti" e selezionare dalla mappa
```

### Auto-Assegnazione

Per abilitare l'assegnazione automatica per un tour:

```php
// Impostazioni tour
$settings = [
    'selection_enabled' => 0,  // Disabilita selezione cliente
    'auto_assign' => 1         // Abilita auto-assegnazione
];
DRTR_Posti_DB::update_tour_settings($tour_id, $settings);
```

I posti vengono assegnati automaticamente:
- In ordine progressivo (1A, 1B, 1C, 1D, 2A...)
- Cerca di mantenere i passeggeri dello stesso gruppo vicini

## Sicurezza Biglietti

### QR Code

Ogni QR code contiene:
- ID biglietto unico
- ID prenotazione
- Numero posto
- Timestamp
- Firma digitale HMAC

### Verifica Biglietto

```php
// Verifica validità QR code
$qr_data = '...'; // Dati scansionati
$info = DRTR_Biglietto_QR::get_ticket_info($qr_data);

if ($info['valid']) {
    echo "Biglietto valido per: " . $info['passenger'];
    echo "Posto: " . $info['seat'];
    echo "Tour: " . $info['tour'];
}
```

## Database Tables

### wp_drtr_posti
Memorizza assegnazioni posti:
- `booking_id`: ID prenotazione
- `tour_id`: ID tour
- `passenger_name`: Nome passeggero
- `seat_number`: Numero posto (es: 5B)
- `assigned_by`: 'customer', 'admin', 'auto'

### wp_drtr_posti_tokens
Token per selezione posti:
- `booking_id`: ID prenotazione
- `token`: Token sicuro (64 caratteri)
- `expires_at`: Data scadenza (7 giorni)
- `used`: Flag utilizzo

### wp_drtr_tour_seat_settings
Impostazioni per tour:
- `tour_id`: ID tour
- `selection_enabled`: Selezione abilitata (0/1)
- `auto_assign`: Auto-assegnazione (0/1)

## Personalizzazioni

### Modificare Layout Bus

Modifica il file `class-drtr-posti-db.php`:

```php
private static function insert_default_bus_config() {
    $layout = json_encode([
        'rows' => 15,              // Cambia numero righe
        'seats_per_row' => 4,      // Posti per riga
        'aisle_position' => 2,     // Posizione corridoio
        'last_row_seats' => 5      // Posti ultima riga
    ]);
    
    $wpdb->insert($table, [
        'total_seats' => 60,       // Nuovo totale
        'rows_count' => 15,
        // ...
    ]);
}
```

### Personalizzare Email

Modifica `class-drtr-posti-email.php` e `class-drtr-biglietto-email.php`:

```php
$message = '
<html>
    <!-- Il tuo template personalizzato -->
</html>';
```

### Modificare Design Biglietto

Modifica `class-drtr-biglietto-pdf.php`:

```php
private static function generate_ticket_html() {
    // Modifica HTML e CSS del biglietto
}
```

## AJAX Endpoints

### Ottenere Posti Disponibili

```javascript
$.ajax({
    url: ajaxUrl,
    data: {
        action: 'drtr_get_available_seats',
        nonce: nonce,
        tour_id: 123
    },
    success: function(response) {
        // response.data.occupied_seats
    }
});
```

### Prenotare Posti

```javascript
$.ajax({
    url: ajaxUrl,
    data: {
        action: 'drtr_reserve_seats',
        nonce: nonce,
        token: token,
        seats: [
            {
                seat_number: '5B',
                row_number: 5,
                position: 'B',
                passenger_name: 'Mario Rossi'
            }
        ]
    }
});
```

## Troubleshooting

### Email non arrivano

1. Verifica che `wp_mail()` funzioni
2. Controlla spam/junk
3. Usa plugin SMTP (es: WP Mail SMTP)

### QR Code non si generano

1. Verifica connessione internet (usa Google Charts API)
2. Controlla permessi cartella `/wp-content/uploads/drtr-tickets`
3. Installa libreria phpqrcode per generazione locale

### Posti non si salvano

1. Verifica che le tabelle siano create (disattiva/riattiva plugin)
2. Controlla log errori PHP
3. Verifica permessi database

## Hooks Disponibili

### Actions

```php
// Quando cambia status prenotazione
do_action('drtr_booking_status_changed', $booking_id, $old_status, $new_status);

// Quando posti confermati
do_action('drtr_seats_confirmed', $booking_id, $seats_data);
```

### Filters

```php
// Personalizzare dati QR code
$qr_data = apply_filters('drtr_qr_code_data', $data, $booking_id, $seat_number);

// Personalizzare subject email
$subject = apply_filters('drtr_ticket_email_subject', $subject, $tour);
```

## Miglioramenti Futuri

- [ ] Scanner QR code nell'app admin
- [ ] Export lista passeggeri per tour
- [ ] Statistiche occupazione posti
- [ ] Integrazione con calendar per date tour
- [ ] Notifiche push per promemoria
- [ ] Multi-bus per tour grandi

## Support

Per assistenza: info@dreamtourviaggi.it
