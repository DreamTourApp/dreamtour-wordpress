<?php
/**
 * Version Helper - Obtiene la versión automáticamente del último commit de git
 */

if (!function_exists('dreamtour_get_version')) {
    /**
     * Obtener la versión del proyecto basada en el último commit de git
     * 
     * @param string $base_version Versión base (ej: "1.1.0")
     * @param string $dir Directorio del repositorio
     * @return string Versión completa (base + hash corto del commit)
     */
    function dreamtour_get_version($base_version = '1.0.0', $dir = null) {
        if (!$dir) {
            $dir = dirname(dirname(__FILE__));
        }
        
        $git_dir = $dir . '/.git';
        
        // Si no existe .git, devolver la versión base
        if (!is_dir($git_dir)) {
            return $base_version;
        }
        
        $head_file = $git_dir . '/HEAD';
        if (!file_exists($head_file)) {
            return $base_version;
        }
        
        try {
            // Leer el contenido de HEAD
            $head_content = trim(file_get_contents($head_file));
            
            if (strpos($head_content, 'ref:') === 0) {
                // Es una rama, obtener el hash del archivo de ref
                $ref_path = str_replace('ref: ', '', $head_content);
                $ref_file = $git_dir . '/' . $ref_path;
                
                if (file_exists($ref_file)) {
                    $hash = trim(file_get_contents($ref_file));
                    $short_hash = substr($hash, 0, 8);
                    return $base_version . '+' . $short_hash;
                }
            } else {
                // Es un hash directo (detached HEAD)
                $short_hash = substr($head_content, 0, 8);
                return $base_version . '+' . $short_hash;
            }
        } catch (Exception $e) {
            return $base_version;
        }
        
        return $base_version;
    }
}
