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
  color: var(--color-text-secondary);
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
  /* Standardized Font Sizes */
  --font-size-profile: 48px;
  /* Profile name */
  --font-size-title: 20px;
  /* Profile title */
  --font-size-body: 16px;
  /* Body text, tagline, item titles */
  --font-size-description: 14px;
  /* Item descriptions */
  --font-size-small: 12px;
  /* Navigation, duration, skills tags */

  --line-height-base: 1.6;

  /* Color System - Two Colors Only */
  --color-text-primary: rgb(226, 232, 240);
  /* Highlights, headings, profile name/title */
  --color-text-secondary: rgb(148, 163, 184);
  /* Regular text */

  /* Background Colors */
  --color-background: rgb(15, 23, 42);
  --color-background-blur: rgba(15, 23, 42, 0.762);
  --color-background-blur-strong: rgba(15, 23, 42, 0.9);

  /* UI Elements */
  --color-ui-border: rgba(148, 163, 184, 0.2);
  --color-ui-bg: rgba(148, 163, 184, 0.1);
  --color-ui-hover: rgba(226, 232, 240, 0.1);

  /* Font Weight Variables */
  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;

  /* Deprecated - Legacy compatibility */
  --text-light: var(--color-text-secondary);
  --color-secondary: var(--color-text-primary);
}

* {
  box-sizing: border-box;
}

a {
  text-decoration: none;
  outline: none;
}

body {
  margin: 0;
  padding: 0;
  background: var(--color-background);
  color: var(--color-text-secondary);
  font-size: var(--font-size-body);
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

/* Slide In Left Animation */
.slide-left {
  animation: slideInLeft 0.6s ease-out;
  animation-fill-mode: both;
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Common Item Card Styles */
.item-card {
  display: flex;
  gap: 2rem;
  border-radius: 8px;
  cursor: pointer;
  padding: 0;
  margin: 0;
  position: relative;
  transition: padding 0.3s ease;
}

.item-card::before {
  content: '';
  position: absolute;
  top: -1rem;
  left: -1.5rem;
  right: -1.5rem;
  bottom: -1rem;
  background: var(--color-ui-hover);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
  pointer-events: none;
}

.item-card:hover::before {
  opacity: 1;
}

/* Common Item Wrapper */
.content-item {
  display: block;
  margin-bottom: 2rem;
  width: 100%;
}

/* Two Column Layout */
.item-left-column {
  flex: 0 0 25%;
  position: relative;
  z-index: 1;
  display: flex;
  align-items: flex-start;
}

.item-right-column {
  flex: 1;
  position: relative;
  z-index: 1;
}

.item-header {
  margin-bottom: 0.75rem;
}

/* Common Position/Title Styles */
.item-title {
  font-size: var(--font-size-body);
  font-weight: var(--font-weight-normal);
  color: var(--color-text-primary);
  margin: 0 0 0.25rem 0;
  line-height: var(--line-height-base);
  display: flex;
  align-items: center;
  gap: 0.25rem;
  flex-wrap: wrap;
}

.item-title>span {
  display: inline-block;
  width: 3px;
  height: 3px;
  background-color: var(--color-text-primary);
  border-radius: 50%;
  margin-left: 2px;
}

/* Common Link Styles */
.external-link {
  font-size: var(--font-size-small);
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.item-card:hover .external-link {
  opacity: 1;
}

.item-link {
  color: var(--color-text-secondary);
  text-decoration: none;
  transition: color 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: var(--font-size-body);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-base);
}

.item-link:hover {
  color: var(--color-text-primary);
}

/* Common Description */
.about-text {
  color: var(--text-light);
  line-height: var(--line-height-base);
  margin-bottom: 1rem;
  font-size: var(--font-size-body);
}

/* Item Description */
.item-description {
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  margin-bottom: 1rem;
  font-size: var(--font-size-description);
}

/* HTML Tags inside text content */
.about-text b,
.about-text strong,
.about-text span,
.about-text a,
.item-description b,
.item-description strong,
.item-description span,
.item-description a {
  color: var(--color-text-primary);
  font-weight: var(--font-weight-semibold);
}

.about-text a,
.item-description a {
  text-decoration: none;
  transition: opacity 0.2s ease;
}

.about-text a:hover,
.item-description a:hover {
  opacity: 0.8;
}

/* Common Tags Section */
.tags-section {
  margin-top: 1rem;
}

.tag-list {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.tag-item {
  background: var(--color-ui-bg);
  color: var(--color-text-secondary);
  padding: 2px 0.5rem;
  border-radius: 4px;
  font-size: var(--font-size-small);
  border: 1px solid var(--color-ui-border);
  transition: all 0.3s ease;
}

.tag-item:hover {
  border-color: var(--color-text-secondary);
  color: var(--color-text-primary);
  background: var(--color-ui-hover);
}

.archive-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--color-text-primary);
  text-decoration: none;
  font-weight: var(--font-weight-medium);
  font-size: var(--font-size-body);
  padding: .2rem 0;
  transition: all 0.3s ease;
  cursor: pointer;
}

.archive-button:hover {
  color: var(--color-text-primary);
  border-bottom: 1px solid var(--color-text-secondary);
}

.archive-button:hover .archive-arrow {
  transform: translateX(4px);
}

.archive-text {
  font-size: var(--font-size-body);
}

.archive-arrow {
  font-size: 1.2em;
  transition: transform 0.3s ease;
}

/* Section Layouts */
.content-section {
  background: transparent;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
  display: none;
  /* Hidden on desktop */
}

.section-title {
  font-size: var(--font-size-title);
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
  margin: 0;
}

.content-timeline {
  position: relative;
  overflow: visible;
  /* Allow hover effects to extend beyond boundaries */
}

.empty-state {
  text-align: center;
  padding: 3rem 0;
  color: var(--color-text-secondary);
}

.empty-state p {
  font-size: var(--font-size-body);
  margin: 0;
}

/* Mobile Responsive - Common Patterns */
@media (max-width: 768px) {
  :root {
    /* Standardized Font Sizes */
    --font-size-profile:42px;
  }

  .section-header {
    display: block;
    /* Show on mobile */
    position: sticky;
    top: 0;
    background: var(--color-background-blur-strong);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 1rem 1.5rem;
    margin: 0 -1.5rem 1rem -1.5rem;
    z-index: 10;
  }

  .section-title {
    margin: 0;
  }

  .content-section {
    padding: 0 0.5rem 0 0.5rem;
  }

  .item-card {
    flex-direction: column;
    gap: 1rem;
  }

  .item-card::before {
    top: -1rem;
    left: -1rem;
    right: -1rem;
    bottom: -1rem;
  }

  .item-left-column {
    flex: none;
    width: 100%;
  }

  .item-title {
    font-size: var(--font-size-body);
    flex-direction: column;
    align-items: flex-start;
  }

  .item-title>span {
    display: none;
  }
}
</style>
