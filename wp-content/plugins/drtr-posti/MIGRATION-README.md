# 🔄 Pagina di Migrazione Posti Pullman

## Descrizione

Pagina per convertire i numeri di posto dal formato **alfanumerico** (1A, 1B, 1C...) al formato **numerico progressivo** (1, 2, 3...53).

## Accesso

### Opzione 1: URL Diretto
```
https://dreamtourviaggi.it/migra-posti-pullman/
```

### Opzione 2: Dopo attivazione plugin
1. Vai in WordPress Admin → Plugin
2. Disattiva e riattiva il plugin "DRTR Gestione Posti"
3. La pagina verrà creata automaticamente
4. Accedi all'URL sopra

## Schema di Conversione

| Formato Vecchio | Formato Nuovo |
|----------------|---------------|
| 1A, 1B, 1C, 1D | 1, 2, 3, 4   |
| 2A, 2B, 2C, 2D | 5, 6, 7, 8   |
| 3A, 3B, 3C, 3D | 9, 10, 11, 12|
| ...            | ...          |
| 13A, 13B, 13C, 13D, 13E | 49, 50, 51, 52, 53 |

## Funzionalità

✅ **Anteprima dati** - Mostra i primi 10 record prima della migrazione
✅ **Contatore record** - Visualizza quanti posti verranno migrati  
✅ **Conferma sicurezza** - Richiede doppia conferma per prevenire errori
✅ **Transazione DB** - Usa transazioni per garantire integrità dei dati
✅ **Rollback automatico** - In caso di errore, annulla tutte le modifiche
✅ **Report dettagliato** - Mostra ogni conversione effettuata
✅ **Skip intelligente** - Salta i posti già in formato numerico

## Procedura Consigliata

### Prima della Migrazione

1. **Backup Database**
   ```bash
   # Esporta il database tramite phpMyAdmin o:
   mysqldump -u username -p database_name > backup_pre_migrazione.sql
   ```

2. **Verifica dati attuali**
   - Vai su https://dreamtourviaggi.it/debug-pullman/
   - Controlla che i posti siano in formato 1A, 2B, etc.

### Durante la Migrazione

1. Accedi a https://dreamtourviaggi.it/migra-posti-pullman/
2. Leggi attentamente le informazioni
3. Controlla l'anteprima dei dati
4. Clicca su "🚀 Avvia Migrazione"
5. Conferma l'operazione nel popup
6. Attendi il completamento

### Dopo la Migrazione

1. **Verifica risultati**
   - Controlla il report dettagliato
   - Verifica che tutti i posti siano stati aggiornati

2. **Test funzionalità**
   - Vai su https://dreamtourviaggi.it/visualizza-posti-pullman/
   - Seleziona un tour
   - Verifica che i posti appaiano come numeri (1, 2, 3...)

3. **Test selezione cliente**
   - Crea una prenotazione di test
   - Apri il link di selezione posti
   - Verifica che il layout sia corretto

## Troubleshooting

### Errore "Token non valido"
- La pagina richiede autenticazione admin
- Assicurati di essere loggato come amministratore

### Errore durante migrazione
- Il sistema farà automaticamente rollback
- Controlla i log in wp-content/debug.log
- Verifica i permessi database

### Alcuni posti non migrati
- Verranno listati nella sezione "Saltati"
- Probabilmente già in formato numerico
- Controlla il report dettagliato

## Codice Chiave

### Funzione di Conversione
```php
function convert_seat_to_numeric($seat_number) {
    // 1A -> 1, 1B -> 2, 1C -> 3, 1D -> 4
    // 2A -> 5, 2B -> 6, 2C -> 7, 2D -> 8
    // ...
    // 13A -> 49, 13B -> 50, 13C -> 51, 13D -> 52, 13E -> 53
}
```

### Logica Matematica
- Righe 1-12: `(riga-1) × 4 + posizione_lettera`
- Riga 13: `48 + posizione_lettera`

Dove posizione_lettera: A=1, B=2, C=3, D=4, E=5

## File Modificati

- `wp-content/plugins/drtr-posti/templates/migrate-seats.php` - Pagina migrazione
- `wp-content/plugins/drtr-posti/drtr-posti.php` - Registrazione pagina
- `wp-content/plugins/drtr-posti/templates/admin-bus-view.php` - Visualizzazione admin (numeri)
- `wp-content/plugins/drtr-posti/includes/class-drtr-posti-frontend.php` - Selezione cliente (numeri)

## Sicurezza

- ✅ Solo admin possono accedere
- ✅ Nonce verification
- ✅ Escape di tutti i dati in output
- ✅ Prepared statements per query DB
- ✅ Transazioni per atomicità
- ✅ Doppia conferma richiesta

## Note Importanti

⚠️ **La migrazione è irreversibile!** Assicurati di avere un backup.

⚠️ **Esegui una sola volta** - I posti già migrati verranno saltati automaticamente.

✅ **Sicuro per produzione** - Usa transazioni DB e rollback automatico in caso di errore.

## Supporto

Per problemi o domande, contatta il team di sviluppo DreamTour.
