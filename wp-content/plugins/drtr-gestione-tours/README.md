# DRTR - Gestione Tours

Plugin completo de gestión de tours para WordPress con interfaz AJAX.

## Características

- ✅ Custom Post Type "Tour" con todos los campos necesarios
- ✅ Página frontend `/gestione-tours` para administradores
- ✅ CRUD completo con AJAX (Crear, Leer, Actualizar, Eliminar)
- ✅ Paginación
- ✅ Búsqueda de tours
- ✅ Diferenciación entre tours en bus, avión, tren, barco o mixto
- ✅ Campos completos: precio, duración, fechas, ubicación, máximo personas, etc.
- ✅ Interfaz moderna y responsive

## Instalación

1. Subir la carpeta `drtr-gestione-tours` a `/wp-content/plugins/`
2. Activar el plugin desde el panel de WordPress
3. Ir a `/gestione-tours` para gestionar los tours (solo administradores)

## Campos del Tour

- **Título** (obligatorio)
- **Descripción corta** (excerpt)
- **Descripción completa** (content)
- **Precio** (€)
- **Duración** (días)
- **Tipo de transporte**: Bus, Avión, Tren, Barco, Mixto
- **Máximo de personas**
- **Fecha de inicio**
- **Fecha de fin**
- **Ubicación/Ciudad**
- **Valoración** (0-5)
- **Qué incluye**
- **Qué no incluye**
- **Itinerario**

## Uso

### Acceso a la página de gestión

Solo los usuarios con rol de administrador pueden acceder a `/gestione-tours`.

### Crear un tour

1. Hacer clic en "Añadir Nuevo Tour"
2. Rellenar el formulario
3. Hacer clic en "Guardar Tour"

### Editar un tour

1. Hacer clic en el botón "Editar" del tour deseado
2. Modificar los campos
3. Hacer clic en "Guardar Tour"

### Eliminar un tour

1. Hacer clic en el botón "Eliminar"
2. Confirmar la eliminación

### Buscar tours

Usar el campo de búsqueda en la parte superior para filtrar tours por título.

## Taxonomías

El plugin registra dos taxonomías:

- **Destinos**: Para organizar tours por destino
- **Tipos de Tour**: Para categorizar tours

## Shortcode

La página de gestión se renderiza automáticamente en `/gestione-tours`.

## Seguridad

- Verificación de nonce en todas las peticiones AJAX
- Verificación de permisos (solo administradores)
- Sanitización de datos
- Validación de formularios

## Soporte

Para soporte, contactar con el equipo de DreamTour.

## Versión

1.0.0

## Autor

DreamTour Team
