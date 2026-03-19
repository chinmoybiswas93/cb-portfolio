import { createApp } from 'vue';
import ProjectsApp from './ProjectsApp.vue';

const mountElement = document.getElementById('cb-portfolio-projects');

if (!mountElement) {
    console.error('CB Portfolio Projects: Mount element #cb-portfolio-projects not found');
} else {
    try {
        const app = createApp(ProjectsApp);
        app.mount('#cb-portfolio-projects');
    } catch (error) {
        console.error('CB Portfolio Projects: Error mounting Vue app', error);
        const loader = document.getElementById('cb-portfolio-loader');
        if (loader) {
            loader.classList.add('cb-portfolio-loader--hidden');
        }
        mountElement.classList.remove('cb-portfolio-hidden');
        mountElement.classList.add('cb-portfolio-ready');
        mountElement.innerHTML = '<div style="padding: 40px; text-align: center; color: rgb(148, 163, 184);"><p>Error loading projects page. Please refresh.</p></div>';
    }
}
