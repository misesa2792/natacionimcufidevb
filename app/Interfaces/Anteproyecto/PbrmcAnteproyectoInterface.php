<?php

namespace App\Interfaces\Anteproyecto;
use Illuminate\Http\Request;

interface PbrmcAnteproyectoInterface
{
    public function index(Request $request);
    public function createView(Request $request);   // Vista para crear

    // Acciones de datos
    public function store(Request $request);        // Guardar nuevo registro
    public function meta(Request $request);      // Eliminar registro
    public function reverse(Request $request);      // Revertir una acción (anulación o reversión)
    
    // Generación y exportación
    public function generate(Request $request);     // Generar información (cálculos, reportes internos, etc.)
    public function pdf(Request $request);          // Generar PDF
}
