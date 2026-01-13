# DreamTour WordPress Codebase Guide

## Architecture Overview

This is a WordPress-based travel tour management system with a custom theme and specialized plugins for tour administration.

### Core Components

1. **DreamTour Theme** (`wp-content/themes/dreamtour/`)
   - Modern, WeRoad-inspired design for travel agencies
   - Custom post type "Tours" with advanced metadata
   - Fully responsive, WCAG 2.1 accessible
   - Supports multilingual content (Italian default)

2. **DRTR Tour Management Plugin** (`wp-content/plugins/drtr-gestione-tours/`)
   - Complete CRUD interface at `/gestione-tours` (admin-only)
   - Custom post type `drtr_tour` (separate from theme's Tour CPT)
   - AJAX-based frontend management system
   - Taxonomies: Destinations (`drtr_destination`), Tour Types (`drtr_tour_type`)

3. **DRTR Reserved Area Plugin** (`wp-content/plugins/drtr-reserved-area/`)
   - User account and booking management functionality

## Design System & Styling

**Color Palette** (CSS variables in [style.css](wp-content/themes/dreamtour/style.css#L17)):
- Primary: `#003284` (navy blue)
- Primary Light: `#1aabe7`, `#46c7f0` (cyan variants)
- Secondary: `#082a5b` (dark navy)
- Accent: `#1ba4ce`

**Typography** (Poppins font stack):
- H1: 34px (900 weight), H2: 22px (700), H3: 18px (600)
- Body: 14px (400), Description: 14px (300)
- All font weights (300, 400, 600, 700, 900) loaded from Google Fonts

**Spacing Variables**: `--spacing-xs` through `--spacing-xxl` (8px to 64px)

## Custom Post Types & Taxonomies

### Theme's Tours CPT (functions.php)
- Post type slug: `tours`
- Supports: title, editor, thumbnail, excerpt, custom fields
- Archive at `/tours`

### Plugin's DRTR Tours CPT
- Post type slug: `tour` 
- Custom meta fields: price, duration, transport type, max people, dates, location, rating
- Managed exclusively through `/gestione-tours` admin page

**Note**: Two separate Tour CPTs exist—use plugin's version (`drtr_tour`) for new tour management features.

## Key Files & Their Purposes

| File | Purpose |
|------|---------|
| [functions.php](wp-content/themes/dreamtour/functions.php) | Theme setup, enqueue assets, register sidebars, custom excerpt filters |
| [single-tour.php](wp-content/themes/dreamtour/single-tour.php) | Single tour page template |
| [archive-tour.php](wp-content/themes/dreamtour/archive-tour.php) | Tours archive/listing page |
| [template-parts/content-tour-card.php](wp-content/themes/dreamtour/template-parts/content-tour-card.php) | Reusable tour card component |
| [drtr-gestione-tours.php](wp-content/plugins/drtr-gestione-tours/drtr-gestione-tours.php) | Plugin bootstrap, Singleton pattern initialization |
| [class-drtr-post-type.php](wp-content/plugins/drtr-gestione-tours/includes/class-drtr-post-type.php) | Registers `drtr_tour` CPT and taxonomies |
| [class-drtr-ajax-handler.php](wp-content/plugins/drtr-gestione-tours/includes/class-drtr-ajax-handler.php) | Handles AJAX CRUD operations for tours |
| [class-drtr-frontend.php](wp-content/plugins/drtr-gestione-tours/includes/class-drtr-frontend.php) | Renders `/gestione-tours` admin page |

## Localization & Language Support

- **Theme text domain**: `dreamtour` (Spanish/Italian default in style)
- **Plugin text domain**: `drtr-tours`
- **Locale detection**: `dreamtour_set_locale` filter in theme handles language switching
- Language files in `languages/` directories (`.mo` format)

## JavaScript & Asset Enqueuing

**Theme assets** ([functions.php](wp-content/themes/dreamtour/functions.php#L110)):
- Google Fonts (Poppins)
- `assets/css/main.css` + `style.css`
- `assets/js/main.js` + `navigation.js`
- AJAX data localized: `dreamtourData` object with `ajaxUrl`, `nonce`, `themeUrl`

**Plugin assets** (conditional enqueue):
- DRTR frontend CSS/JS only on `/gestione-tours` page
- Uses nonce verification (`dreamtour-nonce`) for security

## Widget Areas

Four footer columns + main sidebar:
- `footer-1`, `footer-2`, `footer-3`, `footer-4`
- `sidebar-1`

Registered in [functions.php](wp-content/themes/dreamtour/functions.php#L168).

## Plugin Architecture Pattern

The DRTR plugin uses a **Singleton + Class-based** pattern:

```php
class DRTR_Gestione_Tours {
    private static $instance = null;
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
```

Each component (Post Type, Meta Boxes, AJAX, Frontend) follows this pattern in its own class file.

## Security & Nonces

- All AJAX calls verify nonces (WordPress standard)
- Data sanitization required for post meta and user input
- Admin pages checked with `current_user_can('manage_options')`

## Common Development Tasks

**Adding a new tour field**:
1. Register meta box in `class-drtr-meta-boxes.php`
2. Update form in `class-drtr-frontend.php`
3. Handle AJAX save in `class-drtr-ajax-handler.php`
4. Update template display in `single-tour.php` or card template

**Styling tour cards**:
- Use CSS variables from `:root` (no hardcoded colors)
- Cards defined in [template-parts/content-tour-card.php](wp-content/themes/dreamtour/template-parts/content-tour-card.php)
- Modify [style.css](wp-content/themes/dreamtour/style.css) for card-specific styles

**Translation strings**:
- Always use `__()` or `_e()` with correct text domain
- Theme: `'dreamtour'`, Plugin: `'drtr-tours'`

## External Dependencies

- WordPress 5.0+
- PHP 7.4+
- MySQL 5.6+
- Hosting: Hostinger (cache management via litespeed-cache plugin)

## Codebase Conventions

- **Prefix naming**: All theme functions start with `dreamtour_`, plugin items with `drtr_`
- **Constants**: Defined at plugin/theme init (e.g., `DREAMTOUR_VERSION`, `DRTR_VERSION`)
- **File organization**: Includes in `includes/`, assets in `assets/css` and `assets/js`
- **Comments**: PHP function headers include `@package`, `@since`, descriptions
