<?php

namespace App\Interfaces\Poa\Settings;
use Illuminate\Http\Request;

interface DependenciesInterface
{
    public function index(Request $request);
    public function create(Request $request);
    public function store(Request $request);

}
