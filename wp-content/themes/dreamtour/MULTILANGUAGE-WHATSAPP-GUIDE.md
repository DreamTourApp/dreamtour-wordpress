# DreamTour - Multilanguage & WhatsApp Configuration Guide

## 🌍 Sistema Multilenguaje

### Idiomas Disponibles

El tema DreamTour viene con soporte completo para **3 idiomas**:

1. **🇬🇧 Inglés (English)** - Idioma por defecto
2. **🇪🇸 Español** 
3. **🇮🇹 Italiano**

### ¿Cómo Funciona?

El sistema de cambio de idioma funciona mediante:
- **Selector de idiomas** en el header (banderas + código de idioma)
- **Cookies** para recordar la preferencia del usuario durante 30 días
- **Parámetro URL** `?lang=` para compartir enlaces en un idioma específico

### Cambiar de Idioma

Los usuarios pueden cambiar el idioma de dos formas:

#### 1. Usando el Selector en el Header
- Haz clic en el botón del idioma actual (ej: 🇬🇧 EN)
- Se desplegará un menú con los 3 idiomas disponibles
- Selecciona el idioma deseado
- La página se recargará en el nuevo idioma

#### 2. Usando Parámetros URL
- Inglés: `?lang=en`
- Español: `?lang=es`
- Italiano: `?lang=it`

Ejemplo: `https://tudominio.com/?lang=it`

### Archivos de Traducción

Los archivos de traducción se encuentran en:
```
wp-content/themes/dreamtour/languages/
├── dreamtour.pot     (Plantilla)
├── en_US.po          (Inglés)
├── es_ES.po          (Español)
└── it_IT.po          (Italiano)
```

### Modificar Traducciones

Para editar las traducciones:

1. **Opción 1: Usar Poedit (Recomendado)**
   - Descarga [Poedit](https://poedit.net/)
   - Abre el archivo `.po` del idioma que quieres editar
   - Modifica las traducciones
   - Guarda el archivo (se generará automáticamente el `.mo`)

2. **Opción 2: Manualmente**
   - Edita el archivo `.po` con un editor de texto
   - Compila a `.mo` usando herramientas de línea de comandos:
   ```bash
   msgfmt es_ES.po -o es_ES.mo
   ```

### Agregar Nuevas Cadenas de Traducción

Si añades nuevo texto al tema:

```php
// En tus archivos PHP, usa:
<?php esc_html_e('Tu texto aquí', 'dreamtour'); ?>

// O para obtener la traducción como variable:
<?php $texto = __('Tu texto aquí', 'dreamtour'); ?>
```

Luego actualiza los archivos `.po` con las nuevas cadenas.

---

## 💬 Configuración de WhatsApp

### Características del Botón WhatsApp

- ✅ Botón flotante en la esquina inferior derecha
- ✅ Animación de pulso para llamar la atención
- ✅ Número de teléfono configurable
- ✅ Mensaje predeterminado personalizable
- ✅ Responsive (se adapta a móviles)
- ✅ Se puede activar/desactivar fácilmente

### Configurar WhatsApp desde el Customizer

1. **Accede al Customizer:**
   - Ve a `Apariencia → Personalizar` en el admin de WordPress
   - Busca la sección **"WhatsApp Settings"**

2. **Configuraciones disponibles:**

   #### Enable WhatsApp Button
   - **Checkbox** para activar/desactivar el botón
   - Por defecto: **Activado**

   #### WhatsApp Number
   - Ingresa tu número de WhatsApp **con código de país**
   - Formato: `+393123456789`
   - Ejemplos:
     - Italia: `+393123456789`
     - España: `+34612345678`
     - México: `+525512345678`
     - Argentina: `+541112345678`

   #### Default Message
   - El mensaje que aparecerá pre-escrito cuando el usuario haga clic
   - Se traduce automáticamente según el idioma activo
   - Ejemplos:
     - 🇬🇧 EN: "Hello! I would like more information about your tours."
     - 🇪🇸 ES: "¡Hola! Me gustaría más información sobre vuestros tours."
     - 🇮🇹 IT: "Ciao! Vorrei maggiori informazioni sui vostri tour."

3. **Guarda los cambios:**
   - Haz clic en **"Publicar"** en el Customizer

### Personalizar el Estilo del Botón WhatsApp

El botón se puede personalizar editando el CSS en [style.css](style.css):

```css
/* Línea 724 - Botón WhatsApp */
.whatsapp-float {
  position: fixed;
  bottom: 24px;        /* Distancia desde abajo */
  right: 24px;         /* Distancia desde la derecha */
  width: 60px;         /* Tamaño del botón */
  height: 60px;
  background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
  /* ... */
}
```

### Desactivar WhatsApp

Hay dos formas de desactivar el botón:

1. **Desde el Customizer** (Recomendado):
   - Ve a `Apariencia → Personalizar → WhatsApp Settings`
   - Desmarca "Enable WhatsApp Button"

2. **Mediante código**:
   - Añade este filtro en `functions.php`:
   ```php
   add_filter('theme_mod_dreamtour_whatsapp_enabled', '__return_false');
   ```

---

## 🎨 Personalización Avanzada

### Cambiar el Idioma por Defecto

Si quieres cambiar el idioma por defecto de inglés a otro:

En [functions.php](functions.php), línea ~535:
```php
function dreamtour_set_default_locale($locale) {
    return 'es_ES';  // Cambia a 'es_ES' o 'it_IT'
}
```

### Agregar Más Idiomas

Para agregar un nuevo idioma (ej: Francés):

1. Copia el archivo `dreamtour.pot`
2. Renómbralo a `fr_FR.po`
3. Traduce las cadenas con Poedit
4. Actualiza la función `dreamtour_language_switcher()` en `functions.php`:

```php
$languages = array(
    'en_US' => array('name' => 'English', 'flag' => '🇬🇧', 'code' => 'en'),
    'es_ES' => array('name' => 'Español', 'flag' => '🇪🇸', 'code' => 'es'),
    'it_IT' => array('name' => 'Italiano', 'flag' => '🇮🇹', 'code' => 'it'),
    'fr_FR' => array('name' => 'Français', 'flag' => '🇫🇷', 'code' => 'fr'), // Nuevo
);
```

También actualiza el `$locale_map` en la función `dreamtour_switch_language()`.

### Posicionar el Selector de Idiomas

El selector está en el header. Para moverlo, edita [header.php](header.php) línea ~38:

```php
<!-- Language Switcher -->
<?php echo dreamtour_language_switcher(); ?>
```

Puedes moverlo antes o después de otros elementos del header.

---

## 🔧 Troubleshooting

### El selector de idiomas no aparece
- Verifica que `dreamtour_language_switcher()` esté llamado en `header.php`
- Limpia la caché de WordPress
- Revisa la consola del navegador por errores JavaScript

### Las traducciones no funcionan
- Asegúrate de que los archivos `.po` estén en `/languages/`
- Verifica que WordPress tenga permisos de escritura en la carpeta
- Intenta regenerar los archivos `.mo` con Poedit

### El botón WhatsApp no aparece
- Verifica que esté activado en el Customizer
- Revisa que `dreamtour_add_whatsapp_button` esté en el hook `wp_footer`
- Limpia la caché del navegador

### El número de WhatsApp no funciona
- Asegúrate de incluir el código de país con `+`
- Elimina espacios, guiones y paréntesis
- Formato correcto: `+393123456789`

---

## 📱 Testing

### Probar el Multilenguaje
1. Abre el sitio web
2. Haz clic en el selector de idiomas
3. Cambia entre inglés, español e italiano
4. Verifica que todo el contenido se traduzca
5. Cierra y abre el navegador - debería recordar tu preferencia

### Probar WhatsApp
1. Haz clic en el botón flotante de WhatsApp
2. Debería abrir WhatsApp Web o la app (en móvil)
3. El mensaje predeterminado debe aparecer en el chat
4. El número debe ser correcto

---

## 📞 Soporte

Para más ayuda:
- 📧 Email: support@dreamtourviaggi.it
- 🌐 Website: https://dreamtourviaggi.it
- 📖 Documentación: https://dreamtourviaggi.it/docs

---

**Actualizado:** 13 de enero de 2026
**Versión del tema:** 1.0.0
