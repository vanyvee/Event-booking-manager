

// src/main.js
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import "./assets/main.css";
import router from "/router"

const pinia = createPinia();
pinia.use(({ store }) => {
  store.router = router; // Inject router into all stores
});
const app = createApp(App);

app.use(pinia);
app.use(router);
app.mount('#app');

if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/service-worker.js")
        .then((registration) => {
            console.log("Service Worker registered: ", registration);
        })
        .catch((error) => {
            console.error("Service Worker registration failed:", error);
        });
}


/* main.js
const components = import.meta.glob('./components/global/*.vue', { eager: true });

for (const path in components) {
  const component = components[path].default;
  const name = component.name || path.split('/').pop().replace('.vue', '');
  app.component(name, component);
}

*/

.

find out how pwa is install for this service worker to work in vue

