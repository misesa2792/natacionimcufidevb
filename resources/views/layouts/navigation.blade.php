<nav class="bg-white">

  <header class="border-bottom">
    <div class="container">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
          <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"/></svg>
        </a>

        <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
          <li>
            <a href="{{ route('dashboard') }}"
              class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              {{ __('Dashboard') }}
          </a>
          </li>
        
        </ul>
        <div class="dropdown">
          <button class="btn btn-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Avance
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ URL::to('metas') }}">Metas</a></li>
            </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              POA
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ URL::to('poa?type=PA&title=Anteproyecto') }}">Anteproyecto</a></li>
              <li><a class="dropdown-item" href="{{ URL::to('poa?type=PP&title=Proyecto') }}">Proyecto</a></li>
              <li><a class="dropdown-item" href="{{ URL::to('poa?type=PD&title=PresupuestoDefinitivo') }}">Presupuesto Definitivo</a></li>
            </ul>
        </div>
        <div class="dropdown">
          <button class="btn btn-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              Catálogos
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ URL::to('usuarios') }}">Usuarios</a></li>
              <li><a class="dropdown-item" href="{{ URL::to('catalogos/periodo') }}">Periodos</a></li>
              <li><a class="dropdown-item" href="{{ URL::to('catalogos/year') }}">Años</a></li>
              <li><a class="dropdown-item" href="{{ URL::to('catalogos/municipio') }}">Municipios</a></li>
              <li><a class="dropdown-item" href="{{ URL::to('catalogos/institucion') }}">Instituciones</a></li>
        
              <li class="dropdown-submenu position-relative">
                <a class="dropdown-item dropdown-toggle" href="#">Dependencias</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/depgen') }}">Generales</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/depaux') }}">Auxiliares</a></li>
                </ul>
              </li>
        
              <li class="dropdown-submenu position-relative">
                <a class="dropdown-item dropdown-toggle" href="#">Estructura programática</a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/pilares') }}">Pilares</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/clasificacion') }}">Clasificación CONAC</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/finalidad') }}">Finalidad</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/funcion') }}">Función</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/subfuncion') }}">Subfunción</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/programa') }}">Programa</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/subprograma') }}">Subrograma</a></li>
                  <li><a class="dropdown-item" href="{{ URL::to('catalogos/proyecto') }}">Proyecto</a></li>
                </ul>
              </li>
            </ul>
      </div>


        <div class="dropdown text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
            <li>
              <a class="dropdown-item" href="{{ route('profile.edit') }}">
                  {{ __('Profile') }}
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item">
                      {{ __('Log Out') }}
                  </button>
              </form>
          </li>
          </ul>
        </div>
      </div>
    </div>
  </header>

                <style>
                    .dropdown-submenu {
  position: relative;
}

.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -0.125rem;
  display: none;
}
                  </style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
      const dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');
  
      dropdownSubmenus.forEach((submenu) => {
        submenu.addEventListener('mouseenter', function () {
          this.querySelector('.dropdown-menu').style.display = 'block';
        });
        submenu.addEventListener('mouseleave', function () {
          this.querySelector('.dropdown-menu').style.display = 'none';
        });
      });
    });
  </script>
</nav>
