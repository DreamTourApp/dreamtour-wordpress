<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="site-wrapper">
    <header class="site-header">
        <div class="container">
            <div class="header-inner">
                <!-- Logo -->
                <div class="site-branding">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logos/logo.svg?v=' . DREAMTOUR_VERSION); ?>" alt="<?php bloginfo('name'); ?>" class="header-logo">
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Navegación principal', 'dreamtour'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'nav-menu',
                        'container'      => false,
                        'fallback_cb'    => false,
                    ));
                    ?>
                    
                    <!-- Language Switcher -->
                    <?php echo dreamtour_language_switcher(); ?>
                    
                    <!-- Area Riservata Link -->
                    <div class="header-reserved-area">
                        <a href="<?php echo esc_url(home_url('/area-riservata')); ?>" class="reserved-area-link" aria-label="<?php esc_attr_e('Area Riservata', 'dreamtour'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="reserved-area-text"><?php echo esc_html(dreamtour_get_user_display_name(10)); ?></span>
                        </a>
                    </div>
                    
                    <!-- Botón de búsqueda -->
                    <div class="header-search">
                        <button class="search-toggle" aria-label="<?php esc_attr_e('Abrir búsqueda', 'dreamtour'); ?>">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- CTA Button -->
                    <div class="header-cta">
                        <a href="<?php echo esc_url(home_url('/tours')); ?>" class="btn btn-primary">
                            <?php esc_html_e('Ver Tours', 'dreamtour'); ?>
                        </a>
                    </div>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                        <span class="menu-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </button>
                </nav>
            </div>
        </div>
        
        <!-- Search Form Overlay -->
        <div class="search-overlay">
            <div class="container">
                <div class="search-form-container">
                    <?php get_search_form(); ?>
                    <button class="search-close" aria-label="<?php esc_attr_e('Cerrar búsqueda', 'dreamtour'); ?>">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>
    
    <main id="main-content" class="site-content">
