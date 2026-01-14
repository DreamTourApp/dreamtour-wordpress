<?php
/**
 * Version Helper - Obtiene la versión automáticamente del último commit de git
 */

if (!function_exists('dreamtour_get_version')) {
    /**
     * Obtener la versión del proyecto basada en el último commit de git
     * 
     * @param string $base_version Versión base (ej: "1.1.0")
     * @param string $dir Directorio del repositorio (opcional, busca hacia arriba si no especificado)
     * @return string Versión completa (base + hash corto del commit)
     */
    function dreamtour_get_version($base_version = '1.0.0', $dir = null) {
        // Si no se especifica directorio, buscar .git desde ABSPATH hacia arriba
        if (!$dir) {
            $dir = defined('ABSPATH') ? rtrim(ABSPATH, '/') : dirname(__FILE__);
        }
        
        // Buscar .git directory hacia arriba
        $current_dir = $dir;
        $git_dir = null;
        $max_depth = 10;
        $depth = 0;
        
        while ($depth < $max_depth) {
            $potential_git = $current_dir . '/.git';
            if (is_dir($potential_git) || is_file($potential_git)) {
                $git_dir = $potential_git;
                break;
            }
            
            $parent_dir = dirname($current_dir);
            if ($parent_dir === $current_dir) {
                // Llegamos a la raíz del sistema de archivos
                break;
            }
            
            $current_dir = $parent_dir;
            $depth++;
        }
        
        // Si no existe .git, devolver la versión base
        if (!$git_dir || (!is_dir($git_dir) && !is_file($git_dir))) {
            return $base_version;
        }
        
        try {
            // Si .git es un archivo (git worktree), leer la referencia
            if (is_file($git_dir)) {
                $content = trim(file_get_contents($git_dir));
                if (strpos($content, 'gitdir:') === 0) {
                    $ref_path = trim(substr($content, 7));
                    if (!is_absolute_path($ref_path)) {
                        $ref_path = dirname($git_dir) . '/' . $ref_path;
                    }
                    $git_dir = $ref_path;
                }
            }
            
            $head_file = $git_dir . '/HEAD';
            if (!file_exists($head_file)) {
                return $base_version;
            }
            
            // Leer el contenido de HEAD
            $head_content = trim(file_get_contents($head_file));
            
            if (strpos($head_content, 'ref:') === 0) {
                // Es una rama, obtener el hash del archivo de ref
                $ref_path = trim(substr($head_content, 4));
                $ref_file = $git_dir . '/' . $ref_path;
                
                if (file_exists($ref_file)) {
                    $hash = trim(file_get_contents($ref_file));
                    if ($hash && strlen($hash) >= 7) {
                        $short_hash = substr($hash, 0, 8);
                        return $base_version . '+' . $short_hash;
                    }
                }
            } else if (strlen($head_content) >= 7) {
                // Es un hash directo (detached HEAD)
                $short_hash = substr($head_content, 0, 8);
                return $base_version . '+' . $short_hash;
            }
        } catch (Exception $e) {
            // Silenciosamente devolver la versión base
        }
        
        return $base_version;
    }
    
    /**
     * Helper para verificar si una ruta es absoluta
     */
    function is_absolute_path($path) {
        return (strpos($path, '/') === 0 || strpos($path, '\\') === 0 || preg_match('~^[a-z]:~i', $path));
    }
}

