<?php
/**
 * Template Name: FAQ
 * Template per la pagina FAQ
 * 
 * @package DreamTour
 */

get_header();
?>

<section class="faq-section">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero -->
            <div class="page-hero">
                <h1 class="page-title"><?php esc_html_e('Domande Frequenti', 'dreamtour'); ?></h1>
                <p class="page-subtitle"><?php esc_html_e('Trova risposte alle domande più comuni sui nostri tour', 'dreamtour'); ?></p>
            </div>

            <!-- Content -->
            <div class="page-content">
                <?php the_content(); ?>
            </div>

            <!-- FAQ Categories -->
            <div class="faq-categories">
                
                <!-- Prenotazioni -->
                <div class="faq-category">
                    <h2 class="category-title"><?php esc_html_e('Prenotazioni', 'dreamtour'); ?></h2>
                    
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Come posso prenotare un tour?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Puoi prenotare direttamente dal nostro sito web. Scegli il tour che ti interessa, seleziona la data, compila il form con i tuoi dati e procedi al pagamento sicuro con carta di credito. Riceverai immediatamente una conferma via email.', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Posso modificare o cancellare la mia prenotazione?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Sì, puoi modificare o cancellare la prenotazione fino a 7 giorni prima della partenza. Contattaci via email o telefono. Le cancellazioni oltre i 7 giorni prevedono un rimborso del 100%, entro 7 giorni il 50%, entro 48 ore non è previsto rimborso.', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Devo versare un acconto o pagare tutto subito?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Puoi scegliere! Offriamo la possibilità di pagare un acconto del 30% alla prenotazione e il saldo entro 30 giorni prima della partenza, oppure pagare l\'intero importo subito.', 'dreamtour'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Viaggi -->
                <div class="faq-category">
                    <h2 class="category-title"><?php esc_html_e('Durante il Viaggio', 'dreamtour'); ?></h2>
                    
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('È incluso il vitto durante il tour?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Dipende dal tour. Alcuni pacchetti includono la pensione completa, altri solo la colazione o nessun pasto. Controlla sempre i dettagli specifici di ogni tour nella pagina descrittiva.', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Posso portare bagaglio al seguito?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Sì, ogni partecipante può portare 1 trolley medio (max 20kg) + 1 bagaglio a mano. Per tour in pullman, lo spazio è limitato quindi ti consigliamo di non portare valigie troppo grandi.', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('C\'è un coordinatore che ci accompagna?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Assolutamente sì! Tutti i nostri tour prevedono un coordinatore DreamTour esperto che vi accompagnerà per tutta la durata del viaggio, gestendo ogni aspetto logistico e garantendo la migliore esperienza possibile.', 'dreamtour'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Documenti -->
                <div class="faq-category">
                    <h2 class="category-title"><?php esc_html_e('Documenti e Assicurazioni', 'dreamtour'); ?></h2>
                    
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Che documenti servono per partecipare?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Per destinazioni UE basta la carta d\'identità valida. Per destinazioni extra-UE serve il passaporto con validità residua di almeno 6 mesi. Alcuni paesi richiedono anche il visto - verifica sempre le info specifiche del tour.', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('L\'assicurazione di viaggio è inclusa?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('L\'assicurazione base di responsabilità civile è sempre inclusa. Puoi aggiungere assicurazione annullamento viaggio e spese mediche al momento della prenotazione con un piccolo supplemento.', 'dreamtour'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Pagamenti -->
                <div class="faq-category">
                    <h2 class="category-title"><?php esc_html_e('Pagamenti', 'dreamtour'); ?></h2>
                    
                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Quali metodi di pagamento accettate?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Accettiamo tutte le principali carte di credito e debito (Visa, Mastercard, American Express) tramite il nostro gateway di pagamento sicuro Stripe. Al momento non accettiamo PayPal o bonifici bancari.', 'dreamtour'); ?></p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <button class="faq-question" aria-expanded="false">
                            <span><?php esc_html_e('Riceverò una fattura?', 'dreamtour'); ?></span>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="faq-icon">
                                <polyline points="6 9 12 15 18 9"></polyline>
                            </svg>
                        </button>
                        <div class="faq-answer">
                            <p><?php esc_html_e('Sì, riceverai automaticamente una fattura elettronica via email dopo ogni pagamento. Se hai bisogno di fattura intestata a un\'azienda, comunicaci i dati al momento della prenotazione.', 'dreamtour'); ?></p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Contact CTA -->
            <div class="faq-contact">
                <h2><?php esc_html_e('Non hai trovato la risposta?', 'dreamtour'); ?></h2>
                <p><?php esc_html_e('Il nostro team è a tua disposizione per qualsiasi domanda', 'dreamtour'); ?></p>
                <div class="contact-buttons">
                    <a href="<?php echo esc_url(home_url('/contatti')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Contattaci', 'dreamtour'); ?>
                    </a>
                    <a href="mailto:info@dreamtourviaggi.it" class="btn btn-outline">
                        <?php esc_html_e('Invia Email', 'dreamtour'); ?>
                    </a>
                </div>
            </div>

        <?php endwhile; ?>
    </div>
</section>

<style>
/* FAQ Styles - Based on Chi Siamo */

.faq-section {
    padding: 2rem 0;
}

.page-hero {
    text-align: center;
    padding: 3rem 0;
    margin-bottom: 4rem;
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

.page-content {
    margin-bottom: 3rem;
    font-size: 1.125rem;
    line-height: 1.8;
    color: #2d3748;
    text-align: center;
}

.faq-categories {
    display: grid;
    gap: 3rem;
    margin-bottom: 4rem;
}

.faq-category {
    background: #f7fafc;
    border-radius: 20px;
    padding: 2.5rem;
}

.category-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #003284;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e2e8f0;
}

.faq-item {
    margin-bottom: 1rem;
    border-radius: 12px;
    overflow: hidden;
    background: white;
}

.faq-question {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1.5rem;
    background: white;
    border: none;
    text-align: left;
    font-size: 1.125rem;
    font-weight: 600;
    color: #003284;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.faq-question:hover {
    background: #f7fafc;
}

.faq-icon {
    flex-shrink: 0;
    transition: transform 0.3s ease;
    color: #1ba4ce;
}

.faq-question[aria-expanded="true"] .faq-icon {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease, padding 0.3s ease;
}

.faq-question[aria-expanded="true"] + .faq-answer {
    max-height: 500px;
    padding: 0 1.5rem 1.5rem;
}

.faq-answer p {
    color: #4a5568;
    line-height: 1.8;
}

.faq-contact {
    background: linear-gradient(135deg, #003284 0%, #1ba4ce 100%);
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    color: white;
}

.faq-contact h2 {
    font-size: 2.5rem;
    font-weight: 900;
    margin-bottom: 1rem;
    color: white;
}

.faq-contact p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.contact-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .faq-category {
        padding: 1.5rem;
    }
    
    .category-title {
        font-size: 1.5rem;
    }
    
    .faq-question {
        font-size: 1rem;
        padding: 1.25rem;
    }
    
    .faq-contact {
        padding: 3rem 1.5rem;
    }
    
    .faq-contact h2 {
        font-size: 1.75rem;
    }
    
    .contact-buttons {
        flex-direction: column;
    }
    
    .contact-buttons .btn {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            
            // Close all other FAQ items
            faqQuestions.forEach(q => {
                q.setAttribute('aria-expanded', 'false');
            });
            
            // Toggle current item
            this.setAttribute('aria-expanded', !isExpanded);
        });
    });
});
</script>

<?php
get_footer();
