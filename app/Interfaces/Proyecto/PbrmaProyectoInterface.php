<?php

namespace App\Interfaces\Proyecto;
use Illuminate\Http\Request;

interface PbrmaProyectoInterface
{
    public function view(Request $request);
    public function store(Request $request);
    public function generate(Request $request);
    public function pdf(Request $request);
    public function destroy(Request $request);
    public function reverse(Request $request);
    public function edit(Request $request);
    public function update(Request $request);
    public function depaux(Request $request);
    
}
