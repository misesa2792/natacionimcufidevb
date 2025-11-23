<?php
namespace App\Helpers;

class FunctionHelper
{
    /**
     * Retorna un arreglo de opciones predefinidas para la paginación.
     *
     * @return array
     */
    public static function paginationOptions()
    {
        return array("10", "20", "50", "100", "500");
    }

    /**
     * Agrega ceros a la izquierda de un número hasta alcanzar una longitud deseada.
     *
     * @param int|string $numero   El número a completar.
     * @param int        $longitud Longitud total deseada.
     * @return string
     */
    public static function addZerosLeft($numero, $longitud)
    {
        return str_pad($numero, $longitud, '0', STR_PAD_LEFT);
    }

    /**
     * Construye un nombre de archivo PDF basado en datos institucionales y un identificador.
     *
     * Estructura del nombre generado:
     * - Módulo (ej. PD1A)
     * - ID de institución (5 dígitos con ceros a la izquierda)
     * - Dependencia General (ej. A00)
     * - Fecha (mes y día)
     * - Número aleatorio de 5 dígitos
     * - Año (formato 0024, es decir, 00 + año)
     * - ID del PDF (10 dígitos con ceros a la izquierda)
     *
     * @param array  $data        Datos como no_institucion, no_dep_gen, etc.
     * @param string $abreviatura Abreviatura del módulo.
     * @param string $id          ID de la tabla PDF.
     * @return string
     */
    public static function buildFilename(array $data, string $abreviatura, string $id)
    {
        $filename = $abreviatura .
                    self::addZerosLeft($data['no_institucion'], 5) .
                    $data['no_dep_gen'] .
                    date('md') .
                    self::addZerosLeft(rand(0, 99999), 5) .
                    "00" . date('y') .
                    self::addZerosLeft($id, 10);

        return $filename;
    }

    /**
     * Crea un directorio en el sistema de archivos si no existe.
     *
     * @param string $folder Ruta del directorio a crear.
     * @return void
     */
    public static function createDirectoryIfNotExists(string $folder): void
    {
        if (!is_dir($folder)) 
            mkdir($folder, 0755, true);
    }

    /**
     * Construye la ruta donde se almacenará el PDF según los parámetros del sistema.
     *
     * @param array  $data      Datos que contienen no_institucion, year, no_dep_gen, etc.
     * @param string $modulo    Nombre del módulo (ej. "proyecto").
     * @param string $submodulo Submódulo correspondiente (ej. "anteproyecto").
     * @return string
     */
    public static function buildStoragePath(array $data, string $modulo, string $submodulo): string
    {
        return "{$data['no_institucion']}/{$modulo}/{$submodulo}/{$data['year']}/{$data['no_dep_gen']}";
    }
    public static function toCents($amount): int
    {
        $clean = preg_replace('/[^\d.]/', '', $amount);
        $float = floatval($clean);
        // Truncar a 2 decimales SIN redondear
        $truncated = floor($float * 100) / 100;
        // Luego multiplicar por 100
        return (int) ($truncated * 100);
    }
    public static function fromCents(int $amount): array
    {
        $decimal = ($amount / 100);
        $formatted = number_format($decimal, 2);
        return [
            'float' => $decimal,
            'string' => $formatted,
        ];
    }
        
}
