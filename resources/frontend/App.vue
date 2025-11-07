<template>
  <div class="portfolio-app">
    <!-- Left Sidebar - Fixed -->
    <LeftSidebar :portfolio-data="portfolioData" :active-section="activeSection" @navigate-to="navigateToSection" />

    <!-- Right Content - Scrollable -->
    <RightContent :portfolio-data="portfolioData" :experience-data="experienceData" :projects-data="projectsData" />
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
        profile_image: ''
      },
      experienceData: [],
      projectsData: [],
      activeSection: 'about'
    }
  },
  mounted() {
    this.loadPortfolioData();
    this.loadExperienceData();
    this.loadProjectsData();
    this.setupScrollHandler();
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
        const handleScroll = () => {
          const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

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

            // Calculate the distance from section top to viewport top
            const sectionTop = rect.top;
            const distanceFromTop = Math.abs(sectionTop);

            // If section is visible and closer to top than others
            if (sectionTop <= 200 && distanceFromTop < minDistance) {
              minDistance = distanceFromTop;
              currentSection = section.id;
            }
          }

          // Handle edge case: if we're at the very bottom, always show Projects
          const documentHeight = Math.max(
            document.body.scrollHeight,
            document.body.offsetHeight,
            document.documentElement.clientHeight,
            document.documentElement.scrollHeight,
            document.documentElement.offsetHeight
          );

          if (window.innerHeight + scrollTop >= documentHeight - 50) {
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

        window.addEventListener('scroll', throttledScroll);

        // Initial call
        handleScroll();

        // Cleanup on component unmount
        this.$once('hook:beforeDestroy', () => {
          window.removeEventListener('scroll', throttledScroll);
        });
      });
    },
  }
}
</script>

<style scoped>
.portfolio-app {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  line-height: var(--line-height-base);
  color: rgb(148, 163, 184);
  display: flex;
  min-height: 100vh;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  position: relative;
}

/* Mobile Responsive - Stack layout */
@media (max-width: 768px) {
  .portfolio-app {
    flex-direction: column;
    min-height: 100vh;
    padding: 20px;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .portfolio-app {
    padding: 0 20px;
  }
}

/* Large screens */
@media (min-width: 1920px) {
  .portfolio-app {
    max-width: 1400px;
    margin: 0 auto;
  }
}
</style>

<!-- Global Styles -->
<style>
:root {
  /* Font Size Variables */
  --font-size-xs: 12px;
  --font-size-sm: 14px;
  --font-size-base: 16px;
  --font-size-lg: 18px;
  --font-size-xl: 20px;
  --font-size-2xl: 24px;
  --font-size-3xl: 32px;
  --font-size-4xl: 40px;
  --font-size-5xl: 48px;

  /* Line Height Variables */
  --line-height-base: 1.6;

  /* Profile Font Sizes */
  --profile-name-size: 48px;
  --profile-name-mobile: 40px;
  --profile-title-size: 20px;
  --profile-tagline-size: 16px;

  /* Section Font Sizes */
  --section-title-size: 20px;
  --body-text-size: 16px;
  --nav-link-size: 14px;
  --highlight-title-size: 20px;
  --highlight-text-size: 18px;

  /* Color Variables */
  --color-white: #ffffff;
  --color-white-90: rgba(255, 255, 255, 0.9);
  --color-white-80: rgba(255, 255, 255, 0.8);
  --color-white-70: rgba(255, 255, 255, 0.7);
  --color-white-60: rgba(255, 255, 255, 0.6);
  --color-white-50: rgba(255, 255, 255, 0.5);
  --color-white-30: rgba(255, 255, 255, 0.3);
  --color-white-15: rgba(255, 255, 255, 0.15);
  --color-white-10: rgba(255, 255, 255, 0.1);

  --color-background: rgb(15, 23, 42);
  --color-background-blur: rgba(15, 23, 42, 0.762);
  --color-background-blur-strong: rgba(15, 23, 42, 0.9);

  /* Text Colors */
  --color-text-primary: rgb(248, 250, 252);
  --color-text-secondary: rgb(148, 163, 184);

  /* UI Elements */
  --color-slate-bg-10: rgba(148, 163, 184, 0.1);
  --color-slate-bg-20: rgba(148, 163, 184, 0.2);

  /* Font Weight Variables */
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
}

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
  background: var(--color-background);
  color: var(--color-text-secondary);
  font-size: var(--body-text-size);
  /* Enable bounce effect for whole app */
  -webkit-overflow-scrolling: touch;
}

html {
  scroll-behavior: smooth;
  background: var(--color-background);
  /* Enable bounce effect for whole app */
  -webkit-overflow-scrolling: touch;
}

@media (max-width: 768px) {
  :root {
    /* Profile Font Sizes */
    --profile-name-size: 38px;
    --profile-title-size: 18px;
    --profile-tagline-size: 16px;

    /* Section Font Sizes */
    --section-title-size: 20px;
    --body-text-size: 16px;
    --nav-link-size: 14px;
    --highlight-title-size: 20px;
    --highlight-text-size: 18px;
  }

  html {
    height: auto;
    min-height: 100vh;
  }

  body {
    height: auto;
    overflow: auto;
    min-height: 100vh;
  }
  .profile-tagline {
    max-width: 80% !important;
  }
}

/* Typography */
h1,
h2,
h3,
h4,
h5,
h6 {
  color: var(--color-secondary);
  font-weight: var(--font-weight-medium);
  line-height: var(--line-height-base);
}

p {
  margin: 0;
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  font-weight: var(--font-weight-light);
}

h1,
h2,
h3,
h4,
h5,
h6 {
  margin: 0;
  color: var(--color-text-primary);
  font-weight: var(--font-weight-semibold);
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
  max-width: 1280px;
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
