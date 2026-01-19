<?php
/**
 * Template Name: Termini e Condizioni
 * Template per la pagina Termini e Condizioni
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="terms-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('Termini e Condizioni', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Condizioni generali di vendita dei pacchetti turistici DreamTour', 'dreamtour'); ?></p>
            </div>

            <!-- Last Update -->
            <div class="last-update">
                <p><?php esc_html_e('Ultimo aggiornamento:', 'dreamtour'); ?> <strong>19 Gennaio 2026</strong></p>
            </div>

            <!-- Terms Content -->
            <div class="terms-content">
                
                <?php if (get_the_content()) : ?>
                    <?php the_content(); ?>
                <?php else : ?>
                
                <div class="term-section">
                    <h2>1. <?php esc_html_e('Definizioni', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Per "Organizzatore" si intende DreamTour di Manuel Fernando Araujo Morales con sede in Via E. Pecchi N 8, Turano Lodigiano (LO), P.IVA 14518590964.', 'dreamtour'); ?></p>
                    <p><?php esc_html_e('Per "Viaggiatore" si intende il cliente che acquista un pacchetto turistico.', 'dreamtour'); ?></p>
                    <p><?php esc_html_e('Per "Pacchetto turistico" si intende la combinazione di almeno due servizi di viaggio diversi (trasporto, alloggio, altri servizi turistici) ai fini dello stesso viaggio.', 'dreamtour'); ?></p>
                </div>

                <div class="term-section">
                    <h2>2. <?php esc_html_e('Prenotazioni e Pagamenti', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('La prenotazione si perfeziona con:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Compilazione del modulo di prenotazione online', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Pagamento dell\'acconto (30% del totale) o dell\'intero importo', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Ricezione della conferma via email da parte di DreamTour', 'dreamtour'); ?></li>
                    </ul>
                    <p><?php esc_html_e('Il saldo deve essere versato entro 30 giorni prima della partenza. In caso di prenotazione a meno di 30 giorni dalla partenza, il pagamento deve essere integrale.', 'dreamtour'); ?></p>
                </div>

                <div class="term-section">
                    <h2>3. <?php esc_html_e('Modifiche e Cancellazioni da parte del Viaggiatore', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Il viaggiatore può recedere dal contratto in qualsiasi momento prima dell\'inizio del pacchetto. Le penali di cancellazione sono:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Oltre 30 giorni prima: 0% (rimborso totale)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Da 30 a 15 giorni prima: 25% del totale', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Da 14 a 7 giorni prima: 50% del totale', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Da 6 a 3 giorni prima: 75% del totale', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Entro 2 giorni o mancata presentazione: 100% (nessun rimborso)', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="term-section">
                    <h2>4. <?php esc_html_e('Modifiche da parte dell\'Organizzatore', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('DreamTour si riserva il diritto di modificare le condizioni del contratto prima dell\'inizio del pacchetto, dandone immediata comunicazione al viaggiatore. Il viaggiatore può:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Accettare la modifica proposta', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Recedere dal contratto con diritto al rimborso integrale', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Accettare un pacchetto sostitutivo di qualità equivalente o superiore', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="term-section">
                    <h2>5. <?php esc_html_e('Annullamento del Viaggio', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('DreamTour può annullare il pacchetto turistico se il numero minimo di partecipanti (15 persone) non viene raggiunto entro 20 giorni dalla partenza. In tal caso il viaggiatore ha diritto al rimborso integrale senza ulteriori compensazioni.', 'dreamtour'); ?></p>
                </div>

                <div class="term-section">
                    <h2>6. <?php esc_html_e('Responsabilità dell\'Organizzatore', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('DreamTour è responsabile dell\'esecuzione dei servizi turistici previsti dal contratto, indipendentemente dal fatto che tali servizi siano prestati da terzi. La responsabilità è limitata ai danni diretti effettivamente subiti dal viaggiatore.', 'dreamtour'); ?></p>
                    <p><?php esc_html_e('DreamTour non è responsabile per:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Circostanze inevitabili e straordinarie (eventi atmosferici, scioperi, atti terroristici)', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Inadempimenti imputabili al viaggiatore', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Inadempimenti di terzi estranei alla fornitura dei servizi', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="term-section">
                    <h2>7. <?php esc_html_e('Obblighi del Viaggiatore', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Il viaggiatore è tenuto a:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Fornire dati personali corretti e completi', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Verificare di possedere documenti di identità validi', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Rispettare gli orari e i luoghi di ritrovo comunicati', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Informare l\'organizzatore di eventuali esigenze particolari', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Comportarsi in modo da non arrecare disturbo agli altri partecipanti', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="term-section">
                    <h2>8. <?php esc_html_e('Assicurazione', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Il prezzo include assicurazione di responsabilità civile. È possibile stipulare assicurazioni aggiuntive per:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Annullamento viaggio', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Spese mediche e rimpatrio sanitario', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Bagaglio', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="term-section">
                    <h2>9. <?php esc_html_e('Reclami e Contestazioni', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Eventuali contestazioni durante il viaggio devono essere segnalate immediatamente al coordinatore DreamTour. Reclami successivi devono essere inviati via email a info@dreamtourviaggi.it entro 10 giorni dal rientro.', 'dreamtour'); ?></p>
                </div>

                <div class="term-section">
                    <h2>10. <?php esc_html_e('Privacy e Trattamento Dati', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('I dati personali forniti saranno trattati in conformità al GDPR (Regolamento UE 2016/679). Per maggiori informazioni consulta la nostra Privacy Policy.', 'dreamtour'); ?></p>
                </div>

                <div class="term-section">
                    <h2>11. <?php esc_html_e('Legge Applicabile', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('I presenti termini e condizioni sono regolati dalla legge italiana (D.Lgs. 79/2011 - Codice del Turismo). Per eventuali controversie è competente il Foro di Lodi.', 'dreamtour'); ?></p>
                </div>

                <?php endif; ?>

            </div>

            <!-- CTA -->
            <div class="page-cta">
                <div class="cta-card">
                    <h2><?php esc_html_e('Hai domande sui nostri termini?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Contattaci per qualsiasi chiarimento', 'dreamtour'); ?></p>
                    <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Contattaci', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* Terms Styles - Based on Chi Siamo */

.terms-section {
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

.terms-content {
    max-width: 900px;
    margin: 0 auto 4rem;
}

.term-section {
    background: #f7fafc;
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #1ba4ce;
}

.term-section h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1.5rem;
}

.term-section p {
    color: #2d3748;
    line-height: 1.8;
    margin-bottom: 1rem;
}

.term-section ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
}

.term-section ul li {
    padding-left: 2rem;
    position: relative;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 0.5rem;
}

.term-section ul li::before {
    content: '→';
    position: absolute;
    left: 0;
    color: #1ba4ce;
    font-weight: 700;
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
    
    .term-section {
        padding: 1.5rem;
    }
    
    .term-section h2 {
        font-size: 1.25rem;
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
