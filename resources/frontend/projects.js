import { createApp, nextTick } from 'vue';
import ProjectsApp from './ProjectsApp.vue';

// Hide loader after Vue mounts
function revealAppShell() {
    const loader = document.getElementById('cb-portfolio-loader');
    const appShell = document.getElementById('cb-portfolio-projects');
    
    if (!loader || !appShell) return;
    
    // Calculate remaining animation time
    const getLoaderCycleDuration = () => {
        const rootStyles = getComputedStyle(document.documentElement);
        const duration = rootStyles.getPropertyValue('--cb-loader-duration').trim();
        return parseDuration(duration) || 2400; // Default 2.4s
    };
    
    const parseDuration = (duration) => {
        const match = duration.match(/^(\d+(?:\.\d+)?)s$/);
        return match ? parseFloat(match[1]) * 1000 : null;
    };
    
    const cycleDuration = getLoaderCycleDuration();
    const elapsed = performance.now() - (window.cbPortfolioLoaderStart || performance.now());
    const remaining = (cycleDuration * 1.5) - elapsed; // 1 full cycle + half
    
    if (remaining > 0) {
        setTimeout(() => {
            loader.classList.add('cb-portfolio-loader--hidden');
            appShell.classList.remove('cb-portfolio-hidden');
            appShell.classList.add('cb-portfolio-ready');
        }, remaining);
    } else {
        loader.classList.add('cb-portfolio-loader--hidden');
        appShell.classList.remove('cb-portfolio-hidden');
        appShell.classList.add('cb-portfolio-ready');
    }
}

// Create and mount the Vue app for projects page
const mountElement = document.getElementById('cb-portfolio-projects');

if (!mountElement) {
    console.error('CB Portfolio Projects: Mount element #cb-portfolio-projects not found');
} else {
    try {
        const app = createApp(ProjectsApp);
        app.mount('#cb-portfolio-projects');
        
        // Wait for Vue to mount and then reveal
        nextTick(() => {
            requestAnimationFrame(() => {
                revealAppShell();
            });
        });
    } catch (error) {
        console.error('CB Portfolio Projects: Error mounting Vue app', error);
        // Show error to user
        const loader = document.getElementById('cb-portfolio-loader');
        if (loader) {
            loader.classList.add('cb-portfolio-loader--hidden');
        }
        mountElement.classList.remove('cb-portfolio-hidden');
        mountElement.classList.add('cb-portfolio-ready');
        mountElement.innerHTML = '<div style="padding: 40px; text-align: center; color: rgb(148, 163, 184);"><p>Error loading projects page. Please refresh.</p></div>';
    }
}

