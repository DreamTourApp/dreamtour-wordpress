<?php
/**
 * Template Name: Cookie Policy
 * Template per la pagina Cookie Policy
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="cookie-policy-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('Cookie Policy', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Informativa sull\'utilizzo dei cookie sul sito DreamTour', 'dreamtour'); ?></p>
            </div>

            <!-- Last Update -->
            <div class="last-update">
                <p><?php esc_html_e('Ultimo aggiornamento:', 'dreamtour'); ?> <strong>19 Gennaio 2026</strong></p>
            </div>

            <!-- Cookie Content -->
            <div class="cookie-content">
                
                <?php if (get_the_content()) : the_post(); ?>
                    <?php the_content(); ?>
                <?php else : ?>
                
                <div class="cookie-section-item">
                    <h2>1. <?php esc_html_e('Cosa sono i Cookie?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('I cookie sono piccoli file di testo che i siti web visitati inviano al terminale dell\'utente (solitamente al browser), dove vengono memorizzati per essere poi ritrasmessi agli stessi siti alla successiva visita del medesimo utente.', 'dreamtour'); ?></p>
                    <p><?php esc_html_e('I cookie permettono di migliorare l\'esperienza di navigazione, ricordare le preferenze e fornire contenuti personalizzati.', 'dreamtour'); ?></p>
                </div>

                <div class="cookie-section-item">
                    <h2>2. <?php esc_html_e('Tipologie di Cookie Utilizzati', 'dreamtour'); ?></h2>
                    
                    <div class="cookie-category">
                        <h3><span class="icon">🔧</span> <?php esc_html_e('Cookie Tecnici (Necessari)', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Essenziali per il funzionamento del sito. Non possono essere disabilitati.', 'dreamtour'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Gestione sessione utente e autenticazione', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Memorizzazione preferenze lingua', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Carrello prenotazioni e checkout', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Sicurezza e protezione CSRF', 'dreamtour'); ?></li>
                        </ul>
                    </div>

                    <div class="cookie-category">
                        <h3><span class="icon">📊</span> <?php esc_html_e('Cookie Analitici', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Utilizzati per comprendere come i visitatori utilizzano il sito.', 'dreamtour'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Google Analytics: analisi traffico e comportamento utenti', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Dati aggregati e anonimi', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Miglioramento servizi e contenuti', 'dreamtour'); ?></li>
                        </ul>
                    </div>

                    <div class="cookie-category">
                        <h3><span class="icon">🎯</span> <?php esc_html_e('Cookie di Marketing', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Utilizzati per tracciare i visitatori sui siti web per mostrare annunci pertinenti.', 'dreamtour'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Facebook Pixel: campagne pubblicitarie social', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Google Ads: remarketing e conversioni', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Profilazione per contenuti personalizzati', 'dreamtour'); ?></li>
                        </ul>
                    </div>

                    <div class="cookie-category">
                        <h3><span class="icon">⚙️</span> <?php esc_html_e('Cookie Funzionali', 'dreamtour'); ?></h3>
                        <p><?php esc_html_e('Permettono funzionalità avanzate e personalizzazione.', 'dreamtour'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Preferenze filtri ricerca tour', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Tour salvati nei preferiti', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Impostazioni visualizzazione sito', 'dreamtour'); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="cookie-section-item">
                    <h2>3. <?php esc_html_e('Elenco Cookie Utilizzati', 'dreamtour'); ?></h2>
                    
                    <div class="cookie-table-wrapper">
                        <table class="cookie-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Nome', 'dreamtour'); ?></th>
                                    <th><?php esc_html_e('Tipologia', 'dreamtour'); ?></th>
                                    <th><?php esc_html_e('Finalità', 'dreamtour'); ?></th>
                                    <th><?php esc_html_e('Durata', 'dreamtour'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>PHPSESSID</td>
                                    <td><?php esc_html_e('Tecnico', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Sessione utente', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Sessione', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>wordpress_logged_in</td>
                                    <td><?php esc_html_e('Tecnico', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Autenticazione', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('14 giorni', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>wp_lang</td>
                                    <td><?php esc_html_e('Tecnico', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Preferenza lingua', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('1 anno', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>_ga</td>
                                    <td><?php esc_html_e('Analitico', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Google Analytics', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('2 anni', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>_gid</td>
                                    <td><?php esc_html_e('Analitico', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Google Analytics', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('24 ore', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>_fbp</td>
                                    <td><?php esc_html_e('Marketing', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Facebook Pixel', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('3 mesi', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>_gcl_au</td>
                                    <td><?php esc_html_e('Marketing', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Google Ads', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('3 mesi', 'dreamtour'); ?></td>
                                </tr>
                                <tr>
                                    <td>dreamtour_preferences</td>
                                    <td><?php esc_html_e('Funzionale', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('Preferenze utente', 'dreamtour'); ?></td>
                                    <td><?php esc_html_e('1 anno', 'dreamtour'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="cookie-section-item">
                    <h2>4. <?php esc_html_e('Cookie di Terze Parti', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Alcuni cookie sono impostati da servizi di terze parti che appaiono sulle nostre pagine:', 'dreamtour'); ?></p>
                    <ul>
                        <li><strong>Google Analytics:</strong> <?php esc_html_e('servizio di analisi web fornito da Google LLC', 'dreamtour'); ?> - <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy Policy</a></li>
                        <li><strong>Facebook:</strong> <?php esc_html_e('Facebook Pixel per campagne pubblicitarie', 'dreamtour'); ?> - <a href="https://www.facebook.com/privacy/policy/" target="_blank" rel="noopener">Privacy Policy</a></li>
                        <li><strong>Stripe:</strong> <?php esc_html_e('processore di pagamenti', 'dreamtour'); ?> - <a href="https://stripe.com/privacy" target="_blank" rel="noopener">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="cookie-section-item">
                    <h2>5. <?php esc_html_e('Come Gestire i Cookie', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Puoi gestire le tue preferenze sui cookie in diversi modi:', 'dreamtour'); ?></p>
                    
                    <div class="management-option">
                        <h4><?php esc_html_e('🍪 Banner Cookie', 'dreamtour'); ?></h4>
                        <p><?php esc_html_e('Alla prima visita del sito, puoi scegliere quali categorie di cookie accettare tramite il nostro banner.', 'dreamtour'); ?></p>
                    </div>

                    <div class="management-option">
                        <h4><?php esc_html_e('⚙️ Impostazioni Browser', 'dreamtour'); ?></h4>
                        <p><?php esc_html_e('Puoi modificare le impostazioni del tuo browser per:', 'dreamtour'); ?></p>
                        <ul>
                            <li><?php esc_html_e('Bloccare tutti i cookie', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Accettare solo cookie di prima parte', 'dreamtour'); ?></li>
                            <li><?php esc_html_e('Eliminare i cookie alla chiusura del browser', 'dreamtour'); ?></li>
                        </ul>
                        <p><?php esc_html_e('Guide per i principali browser:', 'dreamtour'); ?></p>
                        <ul class="browser-links">
                            <li><a href="https://support.google.com/chrome/answer/95647" target="_blank">Chrome</a></li>
                            <li><a href="https://support.mozilla.org/it/kb/Gestione%20dei%20cookie" target="_blank">Firefox</a></li>
                            <li><a href="https://support.apple.com/it-it/guide/safari/sfri11471/mac" target="_blank">Safari</a></li>
                            <li><a href="https://support.microsoft.com/it-it/microsoft-edge" target="_blank">Edge</a></li>
                        </ul>
                    </div>

                    <div class="management-option">
                        <h4><?php esc_html_e('🚫 Opt-out Servizi Specifici', 'dreamtour'); ?></h4>
                        <ul>
                            <li><a href="https://tools.google.com/dlpage/gaoptout" target="_blank">Google Analytics Opt-out</a></li>
                            <li><a href="https://www.facebook.com/ads/preferences" target="_blank">Facebook Ads Settings</a></li>
                        </ul>
                    </div>
                </div>

                <div class="cookie-section-item">
                    <h2>6. <?php esc_html_e('Conseguenze della Disabilitazione', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('La disabilitazione di alcuni cookie potrebbe limitare la tua esperienza sul sito:', 'dreamtour'); ?></p>
                    <ul>
                        <li><?php esc_html_e('Impossibilità di effettuare prenotazioni', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Perdita preferenze e filtri salvati', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Necessità di reinserire dati ad ogni visita', 'dreamtour'); ?></li>
                        <li><?php esc_html_e('Funzionalità limitate nell\'area riservata', 'dreamtour'); ?></li>
                    </ul>
                </div>

                <div class="cookie-section-item">
                    <h2>7. <?php esc_html_e('Aggiornamenti della Policy', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Questa Cookie Policy può essere aggiornata periodicamente. Ti invitiamo a consultare regolarmente questa pagina per rimanere informato sulle modalità di utilizzo dei cookie.', 'dreamtour'); ?></p>
                </div>

                <?php endif; ?>

            </div>

            <!-- CTA -->
            <div class="page-cta">
                <div class="cta-card">
                    <h2><?php esc_html_e('Hai domande sui cookie?', 'dreamtour'); ?></h2>
                    <p><?php esc_html_e('Contattaci per qualsiasi chiarimento sulla nostra Cookie Policy', 'dreamtour'); ?></p>
                    <a href="mailto:info@dreamtourviaggi.it" class="btn btn-primary btn-lg">
                        <?php esc_html_e('Contattaci', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* Cookie Policy Styles - Based on Chi Siamo */

.cookie-policy-section {
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

.cookie-content {
    max-width: 1000px;
    margin: 0 auto 4rem;
}

.cookie-section-item {
    background: #f7fafc;
    border-radius: 16px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    border-left: 4px solid #1ba4ce;
}

.cookie-section-item h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1.5rem;
}

.cookie-section-item p {
    color: #2d3748;
    line-height: 1.8;
    margin-bottom: 1rem;
}

.cookie-section-item ul {
    list-style: none;
    padding: 0;
    margin: 1rem 0;
}

.cookie-section-item ul li {
    padding-left: 2rem;
    position: relative;
    color: #4a5568;
    line-height: 1.8;
    margin-bottom: 0.75rem;
}

.cookie-section-item ul li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #1ba4ce;
    font-weight: 900;
    font-size: 1.25rem;
}

/* Cookie Categories */
.cookie-category {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin: 1.5rem 0;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.cookie-category:hover {
    border-color: #1ba4ce;
    box-shadow: 0 4px 12px rgba(27, 164, 206, 0.1);
}

.cookie-category h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.cookie-category .icon {
    font-size: 1.5rem;
}

.cookie-category p {
    color: #4a5568;
    margin-bottom: 1rem;
}

/* Cookie Table */
.cookie-table-wrapper {
    overflow-x: auto;
    margin: 1.5rem 0;
}

.cookie-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
}

.cookie-table thead {
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
}

.cookie-table thead th {
    color: white;
    font-weight: 700;
    padding: 1rem;
    text-align: left;
}

.cookie-table tbody tr {
    border-bottom: 1px solid #e2e8f0;
    transition: background 0.2s ease;
}

.cookie-table tbody tr:hover {
    background: #f7fafc;
}

.cookie-table tbody td {
    padding: 1rem;
    color: #2d3748;
}

/* Management Options */
.management-option {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin: 1rem 0;
    border: 2px solid #e2e8f0;
}

.management-option h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 1rem;
}

.browser-links {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.browser-links li {
    padding-left: 0 !important;
}

.browser-links li::before {
    content: none !important;
}

.browser-links a {
    color: #1ba4ce;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.browser-links a:hover {
    color: #003284;
    text-decoration: underline;
}

/* CTA */
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

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .cookie-section-item {
        padding: 1.5rem;
    }
    
    .cookie-section-item h2 {
        font-size: 1.25rem;
    }
    
    .cookie-category {
        padding: 1.5rem;
    }
    
    .cookie-table thead th,
    .cookie-table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .management-option {
        padding: 1.25rem;
    }
    
    .browser-links {
        flex-direction: column;
        gap: 0.5rem;
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
