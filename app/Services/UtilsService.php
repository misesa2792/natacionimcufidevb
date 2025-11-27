<?php

namespace App\Services;

class UtilsService
{
    /**
     * Calcula el descuento aplicado y devuelve precio, descuento y total.
     */
    public function calcularDescuento(float $precio = 0, float $descuento = 0): array
    {
        // Sanitizar valores negativos
        $precio = max(0, $precio);
        $descuento = max(0, $descuento);

        // Valores por defecto
        $montoDescuento = 0;
        $total = $precio;

        // Si no aplica descuento
        if ($precio > 0 && $descuento > 0) {
            // Evitar descuentos mayores al 100%
            $descuento = min($descuento, 100);

            $montoDescuento = $precio * ($descuento / 100);
            $total = $precio - $montoDescuento;
        }

        return [
            'precio'     => round($precio, 2),
            'descuento'  => round($montoDescuento, 2),
            'total'      => round($total, 2),
        ];
    }
    public function buscarMes(int $mes): string
    {
        $meses = $this->listaMeses();
        return $meses[$mes];
    }
    public function listaMeses(){
        $meses = [1 => 'Enero',2 => 'Febrero',3 => 'Marzo',4 => 'Abril',5 => 'Mayo',6 => 'Junio',7 => 'Julio',8 => 'Agosto',9 => 'Septiembre',10 => 'Octubre',11 => 'Noviembre',12 => 'Diciembre'];
        return $meses;
    }

}
