<template>
  <div class="portfolio-app">
    <!-- Left Sidebar - Fixed -->
    <LeftSidebar 
      :portfolio-data="portfolioData"
      :active-section="activeSection"
      :sidebar-open="sidebarOpen"
      @toggle-sidebar="toggleSidebar"
      @navigate-to="navigateToSection"
    />

    <!-- Right Content - Scrollable -->
    <RightContent 
      :portfolio-data="portfolioData"
      :experience-data="experienceData"
      :projects-data="projectsData"
      :sidebar-open="sidebarOpen"
    />

    <!-- Mobile Overlay -->
    <div 
      v-if="sidebarOpen" 
      class="mobile-overlay"
      @click="toggleSidebar"
    ></div>
  </div>
</template>

<script>
import LeftSidebar from './components/LeftSidebar.vue'
import RightContent from './components/RightContent.vue'

export default {
  name: 'PortfolioApp',
  components: {
    LeftSidebar,
    RightContent
  },
  data() {
    return {
      portfolioData: {
        name: '',
        title: '',
        tagline: '',
        about: '',
        email: '',
        phone: '',
        location: '',
        github_url: '',
        linkedin_url: '',
        twitter_url: '',
        website_url: '',
        resume_url: '',
        profile_image: ''
      },
      experienceData: [],
      projectsData: [],
      activeSection: 'about',
      sidebarOpen: false
    }
  },
  mounted() {
    this.loadPortfolioData();
    this.loadExperienceData();
    this.loadProjectsData();
    this.setupScrollHandler();
    this.setupScrollForwarding();
  },
  methods: {
    async loadPortfolioData() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/portfolio');
        if (response.ok) {
          const data = await response.json();
          console.log(data);
          if (data && Object.keys(data).length > 0) {
            this.portfolioData = { ...this.portfolioData, ...data };
          }
        }
      } catch (err) {
        console.error('Error loading portfolio data:', err);
      }
    },

    async loadExperienceData() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/experience');
        if (response.ok) {
          const data = await response.json();
          this.experienceData = data || [];
        }
      } catch (err) {
        console.error('Error loading experience data:', err);
      }
    },

    async loadProjectsData() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/projects');
        if (response.ok) {
          const data = await response.json();
          this.projectsData = data || [];
        }
      } catch (err) {
        console.error('Error loading projects data:', err);
      }
    },

    toggleSidebar() {
      this.sidebarOpen = !this.sidebarOpen;
    },

    navigateToSection(sectionId) {
      const element = document.getElementById(sectionId);
      if (element) {
        element.scrollIntoView({ 
          behavior: 'smooth',
          block: 'start'
        });
        this.activeSection = sectionId;
      }
    },

    setupScrollHandler() {
      const sections = ['about', 'experience', 'projects'];
      
      this.$nextTick(() => {
        const rightContent = this.$el.querySelector('.right-content');
        
        const handleScroll = () => {
          const scrollTop = rightContent.scrollTop;
          const scrollHeight = rightContent.scrollHeight;
          const clientHeight = rightContent.clientHeight;
          
          // Get all section elements and their positions
          const sectionElements = sections.map(id => ({
            id,
            element: document.getElementById(id)
          })).filter(item => item.element);
          
          if (sectionElements.length === 0) return;
          
          let currentSection = 'about'; // default
          let minDistance = Infinity;
          
          // Find the section closest to the top of the viewport
          for (let i = 0; i < sectionElements.length; i++) {
            const section = sectionElements[i];
            const rect = section.element.getBoundingClientRect();
            const containerRect = rightContent.getBoundingClientRect();
            
            // Calculate the distance from section top to viewport top
            const sectionTop = rect.top - containerRect.top;
            const distanceFromTop = Math.abs(sectionTop);
            
            // If section is visible and closer to top than others
            if (sectionTop <= 200 && distanceFromTop < minDistance) {
              minDistance = distanceFromTop;
              currentSection = section.id;
            }
          }
          
          // Handle edge case: if we're at the very bottom, always show Projects
          if (scrollHeight - scrollTop - clientHeight < 50) {
            currentSection = 'projects';
          }
          
          // Handle edge case: if we're at the very top, always show About
          if (scrollTop < 50) {
            currentSection = 'about';
          }
          
          // Only update if section actually changed to prevent flickering
          if (this.activeSection !== currentSection) {
            this.activeSection = currentSection;
          }
        };

        // Add scroll listener with throttling to improve performance
        let ticking = false;
        const throttledScroll = () => {
          if (!ticking) {
            requestAnimationFrame(() => {
              handleScroll();
              ticking = false;
            });
            ticking = true;
          }
        };

        rightContent.addEventListener('scroll', throttledScroll);
        
        // Initial call
        handleScroll();
      });
    },

    setupScrollForwarding() {
      this.$nextTick(() => {
        const leftSidebar = this.$el.querySelector('.left-sidebar');
        const rightContent = this.$el.querySelector('.right-content');
        
        if (leftSidebar && rightContent) {
          // Forward wheel events from left sidebar to right content
          leftSidebar.addEventListener('wheel', (e) => {
            e.preventDefault();
            rightContent.scrollTop += e.deltaY;
          }, { passive: false });
        }
      });
    }
  }
}
</script>

<style scoped>
.portfolio-app {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  line-height: 1.6;
  color: rgb(148, 163, 184);
  height: 100vh;
  overflow: hidden;
  display: flex;
  max-width: 1880px;
  margin: 0 auto;
  padding: 0 40px;
}

/* Mobile Overlay */
.mobile-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 999;
  display: none;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .portfolio-app {
    padding: 0;
  }
  
  .mobile-overlay {
    display: block;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .portfolio-app {
    padding: 0 20px;
  }
}

/* Large screens - ensure container doesn't get too wide */
@media (min-width: 1920px) {
  .portfolio-app {
    max-width: 1880px;
  }
}
</style>

<!-- Global Styles -->
<style>
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
  background: rgb(15, 23, 42);
  color: rgb(148, 163, 184);
  height: 100vh;
  overflow: hidden;
}

html {
  scroll-behavior: smooth;
  height: 100vh;
  background: rgb(15, 23, 42);
}

/* Typography */
h1, h2, h3, h4, h5, h6 {
  color: rgb(148, 163, 184);
  font-weight: 600;
  line-height: 1.3;
}

p {
  color: rgb(148, 163, 184);
  line-height: 1.6;
}

a {
  color: #667eea;
  text-decoration: none;
  transition: color 0.3s ease;
}

a:hover {
  color: #5a67d8;
}

/* Utility Classes */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1rem;
}

.text-center {
  text-align: center;
}

.mb-4 {
  margin-bottom: 1rem;
}

.mb-8 {
  margin-bottom: 2rem;
}

/* Animation Classes */
.fade-in {
  animation: fadeIn 0.8s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.slide-up {
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
