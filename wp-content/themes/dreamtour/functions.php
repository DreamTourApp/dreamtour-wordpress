<?php
/**
 * DreamTour Theme Functions
 * 
 * @package DreamTour
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Define constantes del tema
 */
define('DREAMTOUR_VERSION', '1.0.0');
define('DREAMTOUR_THEME_DIR', get_template_directory());
define('DREAMTOUR_THEME_URI', get_template_directory_uri());

/**
 * Configuración del tema
 */
function dreamtour_setup() {
    // Soporte para traducciones
    load_theme_textdomain('dreamtour', DREAMTOUR_THEME_DIR . '/languages');
    
    // Soporte para título dinámico
    add_theme_support('title-tag');
    
    // Soporte para imágenes destacadas
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(800, 600, true);
    
    // Tamaños de imagen adicionales
    add_image_size('dreamtour-hero', 1920, 800, true);
    add_image_size('dreamtour-card', 400, 300, true);
    add_image_size('dreamtour-card-large', 600, 450, true);
    add_image_size('dreamtour-thumbnail', 150, 150, true);
    
    // Soporte para HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ));
    
    // Soporte para logo personalizado
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Soporte para menús
    register_nav_menus(array(
        'primary' => __('Menú Principal', 'dreamtour'),
        'footer'  => __('Menú Footer', 'dreamtour'),
    ));
    
    // Soporte para estilos del editor
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    
    // Soporte para colores personalizados
    add_theme_support('custom-background');
    add_theme_support('custom-header');
    
    // Soporte para formatos de post
    add_theme_support('post-formats', array(
        'aside',
        'gallery',
        'link',
        'image',
        'quote',
        'video',
        'audio'
    ));
    
    // Soporte para RSS automático
    add_theme_support('automatic-feed-links');
    
    // Ancho del contenido
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'dreamtour_setup');

/**
 * Registrar y encolar estilos
 */
function dreamtour_enqueue_styles() {
    // Google Fonts - Poppins
    wp_enqueue_style('dreamtour-google-fonts', 
        'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;900&display=swap', 
        array(), 
        null
    );
    
    // Estilo principal
    wp_enqueue_style('dreamtour-style', 
        get_stylesheet_uri(), 
        array('dreamtour-google-fonts'), 
        DREAMTOUR_VERSION
    );
    
    // Estilos adicionales
    wp_enqueue_style('dreamtour-main', 
        DREAMTOUR_THEME_URI . '/assets/css/main.css', 
        array('dreamtour-style'), 
        DREAMTOUR_VERSION
    );
}
add_action('wp_enqueue_scripts', 'dreamtour_enqueue_styles');

/**
 * Registrar y encolar scripts
 */
function dreamtour_enqueue_scripts() {
    // Script principal
    wp_enqueue_script('dreamtour-main', 
        DREAMTOUR_THEME_URI . '/assets/js/main.js', 
        array('jquery'), 
        DREAMTOUR_VERSION, 
        true
    );
    
    // Script de navegación
    wp_enqueue_script('dreamtour-navigation', 
        DREAMTOUR_THEME_URI . '/assets/js/navigation.js', 
        array('jquery'), 
        DREAMTOUR_VERSION, 
        true
    );
    
    // Pasar datos a JavaScript
    wp_localize_script('dreamtour-main', 'dreamtourData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('dreamtour-nonce'),
        'themeUrl' => DREAMTOUR_THEME_URI,
    ));
    
    // Script de comentarios si es necesario
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'dreamtour_enqueue_scripts');

/**
 * Registrar áreas de widgets
 */
function dreamtour_widgets_init() {
    // Sidebar principal
    register_sidebar(array(
        'name'          => __('Sidebar Principal', 'dreamtour'),
        'id'            => 'sidebar-1',
        'description'   => __('Widgets para el sidebar principal', 'dreamtour'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer - columna 1
    register_sidebar(array(
        'name'          => __('Footer Columna 1', 'dreamtour'),
        'id'            => 'footer-1',
        'description'   => __('Widgets para la primera columna del footer', 'dreamtour'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer - columna 2
    register_sidebar(array(
        'name'          => __('Footer Columna 2', 'dreamtour'),
        'id'            => 'footer-2',
        'description'   => __('Widgets para la segunda columna del footer', 'dreamtour'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer - columna 3
    register_sidebar(array(
        'name'          => __('Footer Columna 3', 'dreamtour'),
        'id'            => 'footer-3',
        'description'   => __('Widgets para la tercera columna del footer', 'dreamtour'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer - columna 4
    register_sidebar(array(
        'name'          => __('Footer Columna 4', 'dreamtour'),
        'id'            => 'footer-4',
        'description'   => __('Widgets para la cuarta columna del footer', 'dreamtour'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'dreamtour_widgets_init');

/**
 * Función para obtener el extracto personalizado
 */
function dreamtour_excerpt($limit = 20) {
    $excerpt = get_the_excerpt();
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace('/\s+/', ' ', $excerpt));
    return $excerpt . '...';
}

/**
 * Personalizar longitud del extracto
 */
function dreamtour_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'dreamtour_excerpt_length', 999);

/**
 * Personalizar el "more" del extracto
 */
function dreamtour_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'dreamtour_excerpt_more');

/**
 * Agregar clases al body
 */
function dreamtour_body_classes($classes) {
    if (!is_multi_author()) {
        $classes[] = 'single-author';
    }
    
    if (is_active_sidebar('sidebar-1')) {
        $classes[] = 'has-sidebar';
    }
    
    return $classes;
}
add_filter('body_class', 'dreamtour_body_classes');

/**
 * Custom Post Type: Tours
 */
function dreamtour_register_tour_post_type() {
    $labels = array(
        'name'               => _x('Tours', 'post type general name', 'dreamtour'),
        'singular_name'      => _x('Tour', 'post type singular name', 'dreamtour'),
        'menu_name'          => _x('Tours', 'admin menu', 'dreamtour'),
        'name_admin_bar'     => _x('Tour', 'add new on admin bar', 'dreamtour'),
        'add_new'            => _x('Añadir Nuevo', 'tour', 'dreamtour'),
        'add_new_item'       => __('Añadir Nuevo Tour', 'dreamtour'),
        'new_item'           => __('Nuevo Tour', 'dreamtour'),
        'edit_item'          => __('Editar Tour', 'dreamtour'),
        'view_item'          => __('Ver Tour', 'dreamtour'),
        'all_items'          => __('Todos los Tours', 'dreamtour'),
        'search_items'       => __('Buscar Tours', 'dreamtour'),
        'not_found'          => __('No se encontraron tours', 'dreamtour'),
        'not_found_in_trash' => __('No se encontraron tours en la papelera', 'dreamtour')
    );

    $args = array(
        'labels'             => $labels,
        'description'        => __('Tours de viaje', 'dreamtour'),
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'tours'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'show_in_rest'       => true,
    );

    register_post_type('tour', $args);
}
add_action('init', 'dreamtour_register_tour_post_type');

/**
 * Taxonomía: Destinos
 */
function dreamtour_register_destination_taxonomy() {
    $labels = array(
        'name'              => _x('Destinos', 'taxonomy general name', 'dreamtour'),
        'singular_name'     => _x('Destino', 'taxonomy singular name', 'dreamtour'),
        'search_items'      => __('Buscar Destinos', 'dreamtour'),
        'all_items'         => __('Todos los Destinos', 'dreamtour'),
        'edit_item'         => __('Editar Destino', 'dreamtour'),
        'update_item'       => __('Actualizar Destino', 'dreamtour'),
        'add_new_item'      => __('Añadir Nuevo Destino', 'dreamtour'),
        'new_item_name'     => __('Nuevo Nombre de Destino', 'dreamtour'),
        'menu_name'         => __('Destinos', 'dreamtour'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'destino'),
        'show_in_rest'      => true,
    );

    register_taxonomy('destination', array('tour'), $args);
}
add_action('init', 'dreamtour_register_destination_taxonomy');

/**
 * Taxonomía: Tipo de Viaje
 */
function dreamtour_register_tour_type_taxonomy() {
    $labels = array(
        'name'              => _x('Tipos de Viaje', 'taxonomy general name', 'dreamtour'),
        'singular_name'     => _x('Tipo de Viaje', 'taxonomy singular name', 'dreamtour'),
        'search_items'      => __('Buscar Tipos', 'dreamtour'),
        'all_items'         => __('Todos los Tipos', 'dreamtour'),
        'edit_item'         => __('Editar Tipo', 'dreamtour'),
        'update_item'       => __('Actualizar Tipo', 'dreamtour'),
        'add_new_item'      => __('Añadir Nuevo Tipo', 'dreamtour'),
        'new_item_name'     => __('Nuevo Nombre de Tipo', 'dreamtour'),
        'menu_name'         => __('Tipos de Viaje', 'dreamtour'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'tipo-viaje'),
        'show_in_rest'      => true,
    );

    register_taxonomy('tour_type', array('tour'), $args);
}
add_action('init', 'dreamtour_register_tour_type_taxonomy');

/**
 * Función auxiliar para obtener precio del tour
 */
function dreamtour_get_tour_price($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $price = get_post_meta($post_id, 'tour_price', true);
    return $price ? '€' . number_format($price, 0, ',', '.') : '';
}

/**
 * Función auxiliar para obtener duración del tour
 */
function dreamtour_get_tour_duration($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $duration = get_post_meta($post_id, 'tour_duration', true);
    return $duration ? $duration . ' días' : '';
}

/**
 * Función auxiliar para obtener rating del tour
 */
function dreamtour_get_tour_rating($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    $rating = get_post_meta($post_id, 'tour_rating', true);
    return $rating ? floatval($rating) : 0;
}

/**
 * Paginación personalizada
 */
function dreamtour_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max   = intval($wp_query->max_num_pages);
    
    if ($paged >= 1) {
        $links[] = $paged;
    }
    
    if ($paged >= 3) {
        $links[] = $paged - 1;
        $links[] = $paged - 2;
    }
    
    if (($paged + 2) <= $max) {
        $links[] = $paged + 2;
        $links[] = $paged + 1;
    }
    
    echo '<div class="pagination"><ul>' . "\n";
    
    if (get_previous_posts_link()) {
        printf('<li>%s</li>' . "\n", get_previous_posts_link('← Anterior'));
    }
    
    if (!in_array(1, $links)) {
        $class = 1 == $paged ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');
        
        if (!in_array(2, $links)) {
            echo '<li>…</li>';
        }
    }
    
    sort($links);
    foreach ((array) $links as $link) {
        $class = $paged == $link ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($link)), $link);
    }
    
    if (!in_array($max, $links)) {
        if (!in_array($max - 1, $links)) {
            echo '<li>…</li>' . "\n";
        }
        
        $class = $paged == $max ? ' class="active"' : '';
        printf('<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($max)), $max);
    }
    
    if (get_next_posts_link()) {
        printf('<li>%s</li>' . "\n", get_next_posts_link('Siguiente →'));
    }
    
    echo '</ul></div>' . "\n";
}

/**
 * Soporte para Gutenberg
 */
function dreamtour_gutenberg_setup() {
    // Colores personalizados
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Azul Primario', 'dreamtour'),
            'slug'  => 'primary',
            'color' => '#003284',
        ),
        array(
            'name'  => __('Azul Claro', 'dreamtour'),
            'slug'  => 'primary-light',
            'color' => '#1aabe7',
        ),
        array(
            'name'  => __('Azul Más Claro', 'dreamtour'),
            'slug'  => 'primary-lighter',
            'color' => '#46c7f0',
        ),
        array(
            'name'  => __('Azul Oscuro', 'dreamtour'),
            'slug'  => 'secondary',
            'color' => '#082a5b',
        ),
        array(
            'name'  => __('Acento', 'dreamtour'),
            'slug'  => 'accent',
            'color' => '#1ba4ce',
        ),
        array(
            'name'  => __('Blanco', 'dreamtour'),
            'slug'  => 'white',
            'color' => '#ffffff',
        ),
    ));
    
    // Tamaños de fuente personalizados
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Pequeño', 'dreamtour'),
            'size' => 12,
            'slug' => 'small'
        ),
        array(
            'name' => __('Normal', 'dreamtour'),
            'size' => 14,
            'slug' => 'normal'
        ),
        array(
            'name' => __('Grande', 'dreamtour'),
            'size' => 18,
            'slug' => 'large'
        ),
        array(
            'name' => __('Muy Grande', 'dreamtour'),
            'size' => 22,
            'slug' => 'huge'
        )
    ));
}
add_action('after_setup_theme', 'dreamtour_gutenberg_setup');
