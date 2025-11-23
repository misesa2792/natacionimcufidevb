<aside class="control-sidebar control-sidebar-{{ config('adminlte.right_sidebar_theme') }}">
    <div class="p-3">
        <h5 class="mb-2">Preferencias</h5>
        <small class="text-muted d-block mb-3">Personaliza tu interfaz</small>

        {{-- Apariencia --}}
        <div class="mb-3">
            <h6 class="mb-2"><i class="fas fa-adjust mr-1"></i> Tema</h6>
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="pref-darkmode">
                <label class="custom-control-label" for="pref-darkmode">Modo oscuro</label>
            </div>
        </div>

        <div class="mb-3">
            <h6 class="mb-2"><i class="fas fa-text-height mr-1"></i> Tamaño de letra</h6>
            <select id="pref-fontsize" class="custom-select">
                <option value="base">Normal</option>
                <option value="sm">Pequeña</option>
                <option value="lg">Grande</option>
            </select>
            <small class="form-text text-muted">Afecta el sidebar y contenido</small>
        </div>

        <div class="mb-3">
            <h6 class="mb-2"><i class="fas fa-palette mr-1"></i> Color del menú lateral</h6>
            <select id="pref-sidebarcolor" class="custom-select">
                <option value="sidebar-dark-primary">Oscuro / Primary</option>
                <option value="sidebar-dark-info">Oscuro / Info</option>
                <option value="sidebar-dark-success">Oscuro / Success</option>
                <option value="sidebar-light-primary">Claro / Primary</option>
                <option value="sidebar-light-warning">Claro / Warning</option>
            </select>
            <small class="form-text text-muted">Usa clases nativas de AdminLTE</small>
        </div>

        <hr>

        {{-- Acciones rápidas --}}
        <h6 class="mb-2"><i class="fas fa-bolt mr-1"></i> Acciones rápidas</h6>
        <div class="btn-group btn-group-sm d-flex flex-wrap">
            <a href="{{ url('metas/create') }}" class="btn btn-primary m-1">
                <i class="fas fa-plus-circle mr-1"></i> Nueva Meta
            </a>
            <a href="{{ url('indicadores/create') }}" class="btn btn-info m-1">
                <i class="fas fa-chart-line mr-1"></i> Nuevo Indicador
            </a>
            <a href="{{ url('poa/reportes') }}" class="btn btn-success m-1">
                <i class="fas fa-file-excel mr-1"></i> Exportar POA
            </a>
        </div>

        <hr>

        {{-- Utilidades --}}
        <h6 class="mb-2"><i class="fas fa-tools mr-1"></i> Utilidades</h6>
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="pref-compact" />
            <label class="custom-control-label" for="pref-compact">Modo compacto</label>
        </div>

        <div class="mt-3">
            <button id="pref-reset" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-undo mr-1"></i> Restablecer
            </button>
        </div>
    </div>
</aside>
