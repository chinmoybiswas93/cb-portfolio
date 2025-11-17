import { createApp } from 'vue';
import App from './App.vue';

// Create and mount the Vue app
// Note: Vue will replace the server-rendered HTML, but since we pass the same data
// via JavaScript variables, the content will be identical. This is fine for SEO
// because search engines will have already crawled the server-rendered HTML.
const app = createApp(App);
app.mount('#cb-portfolio-frontend');
