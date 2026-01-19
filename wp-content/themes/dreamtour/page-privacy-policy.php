<?php
/**
 * Template Name: Privacy Policy
 * Template per la pagina Privacy Policy
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="privacy-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('Privacy Policy', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Informativa sul trattamento dei dati personali ai sensi del GDPR', 'dreamtour'); ?></p>
            </div>

            <!-- Last Update -->
            <div class="last-update">
                <p><?php esc_html_e('Ultimo aggiornamento:', 'dreamtour'); ?> <strong>19 Gennaio 2026</strong></p>
            </div>

            <!-- Privacy Content -->
            <div class="privacy-content">
                
                <?php if (get_the_content()) : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                
                <div class="privacy-section-item">
                    <h2>1. <?php esc_html_e('Titolare del Trattamento', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Il Titolare del trattamento dei dati è:', 'dreamtour'); ?></p>
                    <div class="info-box">
                        <p><strong>DreamTour by Manuel Fernando Araujo Morales</strong></p>
                        <p>Via E. Pecchi N 8, Turano Lodigiano (LO)</p>
                        <p>P.IVA: 14518590964</p>
                        <p>Email: info@dreamtourviaggi.it</p>
                    </div>
                </div>

                <div class="privacy-section-item">
                    <h2>2. <?php esc_html_e('Dati Raccolti', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Raccogliamo le seguenti categorie di dati personali:', 'dreamtour'); ?></p>
                    <ul>
                        <li><strong><?php esc_html_e('Dati Identificativi:', 'dreamtour'); ?></strong> <?php esc_html_e('nome, cognome, data di nascita', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Dati di Contatto:', 'dreamtour'); ?></strong> <?php esc_html_e('email, numero di telefono, indirizzo', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Dati di Pagamento:', 'dreamtour'); ?></strong> <?php esc_html_e('informazioni carta di credito (tramite Stripe)', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Dati di Navigazione:', 'dreamtour'); ?></strong> <?php esc_html_e('indirizzo IP, cookie, dati di utilizzo del sito', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Preferenze Viaggio:', 'dreamtour'); ?></strong> <?php esc_html_e('destinazioni preferite, esigenze alimentari/mediche', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="privacy-section-item">
                    <h2>3. <?php esc_html_e('Finalità del Trattamento', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('I tuoi dati personali sono trattati per:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Gestione prenotazioni e contratti di viaggio', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Erogazione dei servizi turistici acquistati', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Elaborazione pagamenti', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Comunicazioni relative al viaggio (conferme, modifiche, promemoria)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Assistenza clienti e gestione reclami', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Marketing e invio newsletter (previo consenso)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Adempimenti di legge (contabili, fiscali)', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="privacy-section-item">
                    <h2>4. <?php esc_html_e('Base Giuridica', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Il trattamento dei dati è basato su:', 'dreamtour'); ?></p>
                    <ul>
                        <li><strong><?php esc_html_e('Esecuzione contrattuale:', 'dreamtour'); ?></strong> <?php esc_html_e('per gestire la tua prenotazione', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Obbligo legale:', 'dreamtour'); ?></strong> <?php esc_html_e('per adempimenti fiscali e contabili', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Consenso:', 'dreamtour'); ?></strong> <?php esc_html_e('per attività di marketing', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Legittimo interesse:', 'dreamtour'); ?></strong> <?php esc_html_e('per migliorare i nostri servizi', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="privacy-section-item">
                    <h2>5. <?php esc_html_e('Destinatari dei Dati', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('I tuoi dati possono essere comunicati a:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Fornitori di servizi turistici (hotel, compagnie trasporto)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Processori di pagamento (Stripe)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Provider di hosting e servizi IT', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Commercialisti e consulenti legali', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Autorità pubbliche (se richiesto dalla legge)', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="privacy-section-item">
                    <h2>6. <?php esc_html_e('Trasferimento Dati Extra-UE', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Alcuni fornitori di servizi (es. Stripe) potrebbero trasferire dati al di fuori dell\'Unione Europea. In tali casi garantiamo che il trasferimento avvenga nel rispetto delle garanzie previste dal GDPR (clausole contrattuali standard, Privacy Shield).', 'dreamtour'); ?></p>
                </div>

                <div class="privacy-section-item">
                    <h2>7. <?php esc_html_e('Conservazione dei Dati', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('I dati personali sono conservati per:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Dati contrattuali: 10 anni (obblighi fiscali)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Dati di marketing: fino a revoca del consenso', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Dati di navigazione: 24 mesi', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="privacy-section-item">
                    <h2>8. <?php esc_html_e('Diritti dell\'Interessato', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Hai il diritto di:', 'dreamtour'); ?></p>
                    <ul>
                        <li><strong><?php esc_html_e('Accesso:', 'dreamtour'); ?></strong> <?php esc_html_e('ottenere conferma del trattamento e copia dei dati', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Rettifica:', 'dreamtour'); ?></strong> <?php esc_html_e('correggere dati inesatti o incompleti', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Cancellazione:', 'dreamtour'); ?></strong> <?php esc_html_e('ottenere la cancellazione dei dati (diritto all\'oblio)', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Limitazione:', 'dreamtour'); ?></strong> <?php esc_html_e('limitare il trattamento in determinati casi', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Portabilità:', 'dreamtour'); ?></strong> <?php esc_html_e('ricevere i dati in formato strutturato', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Opposizione:', 'dreamtour'); ?></strong> <?php esc_html_e('opporti al trattamento per marketing', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Revoca consenso:', 'dreamtour'); ?></strong> <?php esc_html_e('revocare il consenso in qualsiasi momento', 'dreamtour'); ?></li>
                        <li><strong><?php esc_html_e('Reclamo:', 'dreamtour'); ?></strong> <?php esc_html_e('presentare reclamo al Garante Privacy', 'dreamtour'); ?></li>
                    </ul>
                    <p><?php esc_html_e('Per esercitare i tuoi diritti, scrivi a: info@dreamtourviaggi.it', 'dreamtour'); ?></p>
                </div>

                <div class="privacy-section-item">
                    <h2>9. <?php esc_html_e('Cookie e Tecnologie di Tracciamento', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Il nostro sito utilizza cookie per migliorare l\'esperienza utente. Per maggiori informazioni consulta la nostra Cookie Policy.', 'dreamtour'); ?></p>
                </div>

                <div class="privacy-section-item">
                    <h2>10. <?php esc_html_e('Sicurezza dei Dati', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Adottiamo misure tecniche e organizzative adeguate per proteggere i tuoi dati da accessi non autorizzati, perdita, distruzione o divulgazione:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Crittografia SSL per le comunicazioni', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Backup regolari dei dati', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Accesso limitato ai dati solo al personale autorizzato', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Firewall e sistemi di protezione aggiornati', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="privacy-section-item">
                    <h2>11. <?php esc_html_e('Modifiche alla Privacy Policy', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Ci riserviamo il diritto di modificare questa informativa in qualsiasi momento. Le modifiche saranno pubblicate su questa pagina con l\'indicazione della data di ultimo aggiornamento.', 'dreamtour'); ?></p>
                </div>

                <?php endif; ?>

            </div>

            <!-- CTA -->
            <div class="page-cta">
                <div class="cta-card">
                    <h2><?php esc_html_e('Hai domande sulla privacy?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Contattaci per qualsiasi chiarimento sul trattamento dei tuoi dati', 'dreamtour'); ?></p>
                    <a href="mailto:info@dreamtourviaggi.it" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Invia Email', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* Privacy Styles - Based on Chi Siamo */

.privacy-section {
    padding: 2rem 0;
}

.page-hero {
    text-align: center;
    padding: 3rem 0;
    margin-bottom: 2rem;
    position: relative;
}

.page-hero::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, #003284 0%, #1ba4ce 100%);
    border-radius: 2px;
}

.page-title {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 1rem;
    color: #003284;
}

.page-subtitle {
    font-size: 1.25rem;
    color: #4a5568;
}

.last-update {
    text-align: center;
    margin-bottom: 3rem;
    padding: 1rem;
    background: #f7fafc;
    border-radius: 8px;
}

.last-update p {
    color: #4a5568;
    margin: 0;
}

.privacy-content {
    max-width: 900px;
    margin: 0 auto 4rem;
}

.privacy-section-item {
    background: #f7fafc;
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #1ba4ce;
}

.privacy-section-item h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1.5rem;
}

.privacy-section-item p {
    color: #2d3748;
    line-height: 1.8;
    margin-bottom: 1rem;
}

.privacy-section-item ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
}

.privacy-section-item ul li {
    padding-left: 2rem;
    position: relative;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 0.75rem;
}

.privacy-section-item ul li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #1ba4ce;
    font-weight: 900;
    font-size: 1.25rem;
}

.info-box {
    background: linear-gradient(135deg, #edf2f7 0%, #e2e8f0 100%);
    border-radius: 12px;
    padding: 2rem;
    margin: 1rem 0;
    border: 2px solid #cbd5e0;
}

.info-box p {
    margin: 0.5rem 0;
    color: #2d3748;
}

.page-cta {
    margin-top: 4rem;
}

.cta-card {
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    color: white;
}

.cta-card h2 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
    color: white;
}

.cta-card p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.125rem;
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .privacy-section-item {
        padding: 1.5rem;
    }
    
    .privacy-section-item h2 {
        font-size: 1.25rem;
    }
    
    .info-box {
        padding: 1.5rem;
    }
    
    .cta-card {
        padding: 3rem 1.5rem;
    }
    
    .cta-card h2 {
        font-size: 1.75rem;
    }
}
</style>

<?php
get_footer();
