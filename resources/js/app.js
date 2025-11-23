import 'bootstrap';
import Alpine from 'alpinejs'; // Alpine.js
//import '@fortawesome/fontawesome-free/css/all.css';
import { createApp } from 'vue'
//axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

window.Alpine = Alpine;
Alpine.start();

// 1️⃣ Creamos la app shell vacía
const app = createApp({})

// 2️⃣ Auto-registro de todos los .vue bajo /components
const modules = import.meta.glob('./components/**/*.vue', { eager: true })
Object.entries(modules).forEach(([path, module]) => {
  // extraemos el nombre del archivo sin extensión
  const filename = path.split('/').pop().replace(/\.vue$/, '')
  // convertimos a kebab-case: MyComponent.vue → my-component
  const name = filename
    .replace(/([a-z0-9])([A-Z])/g, '$1-$2')
    .toLowerCase()

  app.component(name, module.default)
})

// 3️⃣ Montamos al contenedor Blade
app.mount('#appVue')