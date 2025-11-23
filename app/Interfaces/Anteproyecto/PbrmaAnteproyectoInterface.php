<?php

namespace App\Interfaces\Anteproyecto;
use Illuminate\Http\Request;

interface PbrmaAnteproyectoInterface
{
    // Vistas
    public function index(Request $request);        // Vista general (listado principal)
    public function createView(Request $request);   // Vista para crear
    public function editView(Request $request);     // Vista para editar

    // Acciones de datos
    public function store(Request $request);        // Guardar nuevo registro
    public function update(Request $request);       // Actualizar registro existente
    public function destroy(Request $request);      // Eliminar registro
    public function reverse(Request $request);      // Revertir una acción (anulación o reversión)

    // Generación y exportación
    public function generate(Request $request);     // Generar información (cálculos, reportes internos, etc.)
    public function pdf(Request $request);          // Generar PDF

    // Auxiliares
    public function depaux(Request $request);       // Cargar datos auxiliares (e.g., dependencias)
}
