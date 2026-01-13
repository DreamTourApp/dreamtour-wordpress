# DreamTour WordPress Theme

Un tema moderno para WordPress inspirado en WeRoad, diseñado específicamente para agencias de viajes y tours en grupo.

## Características

- ✨ Diseño moderno y limpio inspirado en WeRoad
- 🎨 Paleta de colores personalizada (#003284, #1aabe7, #46c7f0, #082a5b, #1ba4ce)
- 📝 Tipografía Poppins con múltiples pesos (300, 400, 600, 700, 900)
- 🎯 Custom Post Type para Tours
- 📱 Totalmente responsive
- 🚀 Optimizado para rendimiento
- ♿ Accesible (WCAG 2.1)
- 🌍 Preparado para traducciones

## Requisitos

- WordPress 5.0 o superior
- PHP 7.4 o superior
- MySQL 5.6 o superior

## Instalación

1. Descarga el tema
2. Sube la carpeta `dreamtour` a `/wp-content/themes/`
3. Activa el tema desde el panel de administración de WordPress
4. Configura los menús en Apariencia > Menús
5. Personaliza el tema en Apariencia > Personalizar

## Tipografía

El tema utiliza Poppins con las siguientes especificaciones:

- **H1**: 34pt Poppins Black (900)
- **H2**: 22pt Poppins Bold (700)
- **H3**: 18pt Poppins SemiBold (600)
- **Body Text**: 14pt Poppins Regular (400)
- **Description**: 14pt Poppins Light (300)

## Paleta de Colores

- **Azul Primario**: #003284
- **Azul Claro**: #1aabe7
- **Azul Más Claro**: #46c7f0
- **Azul Oscuro**: #082a5b
- **Acento**: #1ba4ce
- **Blanco**: #ffffff

## Custom Post Types

### Tours
El tema incluye un custom post type "Tours" con los siguientes campos personalizados:
- Precio del tour
- Duración (días)
- Rating
- Ubicación
- Número máximo de personas
- Badge destacado

### Taxonomías
- **Destinos**: Para organizar tours por ubicación geográfica
- **Tipo de Viaje**: Para categorizar tours por estilo (aventura, cultural, playa, etc.)

## Templates Disponibles

- `index.php` - Página principal
- `page.php` - Páginas estáticas
- `single.php` - Posts del blog
- `single-tour.php` - Tour individual
- `archive-tour.php` - Archivo de tours
- `search.php` - Resultados de búsqueda
- `404.php` - Página de error
- `template-home.php` - Template para página de inicio

## Áreas de Widgets

- Sidebar Principal
- Footer - 4 columnas

## Menús

- Menú Principal (Header)
- Menú Footer

## Personalización

El tema se puede personalizar fácilmente mediante:

1. **CSS Variables**: Todas las variables están definidas en `:root` en style.css
2. **Customizer de WordPress**: Colores, logo, etc.
3. **Funciones del tema**: `functions.php` para añadir nuevas funcionalidades

## Soporte

Para soporte y actualizaciones, visita:
- Website: https://dreamtour.com
- Documentación: https://dreamtour.com/docs
- Soporte: https://dreamtour.com/support

## Créditos

- Diseño inspirado en WeRoad.it
- Desarrollado por DreamTour Team
- Tipografía: Poppins (Google Fonts)
- Iconos: SVG personalizados

## Licencia

Este tema está licenciado bajo GPL v2 o posterior.

## Changelog

### Versión 1.0.0
- Lanzamiento inicial
- Custom Post Type: Tours
- Taxonomías: Destinos y Tipos de Viaje
- Templates principales
- Sistema de diseño completo
- Responsive design
- Optimizaciones de rendimiento

## Próximas Características

- [ ] Integración con WooCommerce para reservas
- [ ] Filtros avanzados de tours
- [ ] Calendario de disponibilidad
- [ ] Sistema de reseñas
- [ ] Integración con redes sociales
- [ ] Newsletter popup
- [ ] Modo oscuro
