<template>
  <div class="portfolio-app">
    <!-- Mouse spotlight effect -->
    <div class="spotlight-effect" :style="spotlightStyle"></div>

    <LeftSidebar :portfolio-data="portfolioData" :active-section="activeSection" @navigate-to="navigateToSection" />
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
      activeSection: 'about',
      mouseX: 0,
      mouseY: 0
    }
  },
  computed: {
    spotlightStyle() {
      return {
        background: `radial-gradient(400px at ${this.mouseX}px ${this.mouseY}px, rgba(29, 78, 216, 0.15), transparent 80%)`
      }
    }
  },
  mounted() {
    this.loadPortfolioData();
    this.loadExperienceData();
    this.loadProjectsData();
    this.setupScrollHandler();
    this.setupMouseTracking();
  },
  methods: {
    async loadPortfolioData() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/portfolio');
        if (response.ok) {
          const data = await response.json();
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

          const sectionElements = sections.map(id => ({
            id,
            element: document.getElementById(id)
          })).filter(item => item.element);

          if (sectionElements.length === 0) return;

          let currentSection = 'about';
          let minDistance = Infinity;

          for (let i = 0; i < sectionElements.length; i++) {
            const section = sectionElements[i];
            const rect = section.element.getBoundingClientRect();

            const sectionTop = rect.top;
            const distanceFromTop = Math.abs(sectionTop);

            if (sectionTop <= 200 && distanceFromTop < minDistance) {
              minDistance = distanceFromTop;
              currentSection = section.id;
            }
          }

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

          if (scrollTop < 50) {
            currentSection = 'about';
          }

          if (this.activeSection !== currentSection) {
            this.activeSection = currentSection;
          }
        };

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

        handleScroll();

        this.$once('hook:beforeDestroy', () => {
          window.removeEventListener('scroll', throttledScroll);
        });
      });
    },

    setupMouseTracking() {
      const updateMousePosition = (e) => {
        this.mouseX = e.clientX;
        this.mouseY = e.clientY;
      };

      // Only enable on desktop
      const isDesktop = window.innerWidth >= 1024;

      if (isDesktop) {
        // Set initial position to top-left corner
        this.mouseX = 0;
        this.mouseY = 0;
        
        document.addEventListener('mousemove', updateMousePosition);
      }

      // Handle resize to enable/disable based on screen size
      const handleResize = () => {
        const nowDesktop = window.innerWidth >= 1024;
        if (nowDesktop && !isDesktop) {
          // Reset to top-left when enabling on desktop
          this.mouseX = 0;
          this.mouseY = 0;
          document.addEventListener('mousemove', updateMousePosition);
        } else if (!nowDesktop && isDesktop) {
          document.removeEventListener('mousemove', updateMousePosition);
        }
      };

      window.addEventListener('resize', handleResize);

      this.$once('hook:beforeDestroy', () => {
        document.removeEventListener('mousemove', updateMousePosition);
        window.removeEventListener('resize', handleResize);
      });
    }
  }
}
</script>

<style scoped>
.spotlight-effect {
  pointer-events: none;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
  transition: background 300ms ease;
}

.portfolio-app {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  line-height: var(--line-height-base);
  font-weight: var(--font-weight-light);
  color: var(--color-text-secondary);
  display: flex;
  min-height: 100vh;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 2rem;
  position: relative;
}

@media (max-width: 768px) {
  .portfolio-app {
    flex-direction: column;
    min-height: 100vh;
    padding: 20px;
  }
}

@media (min-width: 1920px) {
  .portfolio-app {
    max-width: 1400px;
    margin: 0 auto;
  }
}
</style>

<style>
:root {
  --font-size-small: 12px;
  --font-size-medium: 14px;
  --font-size-base: 16px;
  --font-size-large: 20px;

  --font-size-profile: 48px;
  --font-size-title: var(--font-size-large);
  --font-size-body: var(--font-size-base);
  --font-size-description: var(--font-size-medium);

  --line-height-base: 1.6;

  --color-text-primary: rgb(226, 232, 240);
  --color-text-secondary: rgb(148, 163, 184);

  --color-background: rgb(15, 23, 42);
  --color-background-blur: rgba(15, 23, 42, 0.762);
  --color-background-blur-strong: rgba(15, 23, 42, 0.9);

  --color-ui-border: rgb(46, 60, 83);
  --color-ui-bg: rgba(30, 41, 59, 0.6);
  --color-ui-hover: rgba(30, 41, 59, 0.6);

  --font-weight-light: 300;
  --font-weight-normal: 400;
  --font-weight-medium: 500;
  --font-weight-semibold: 600;
  --font-weight-bold: 700;
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
  -webkit-overflow-scrolling: touch;
}

html {
  scroll-behavior: smooth;
  background: var(--color-background);
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
}

h1,
h2,
h3,
h4,
h5,
h6 {
  color: var(--color-text-primary);
  font-weight: var(--font-weight-medium);
  line-height: var(--line-height-base);
  margin: 0;
}

p {
  margin: 0;
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  font-weight: var(--font-weight-light);
}

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
  border: 1px solid var(--color-ui-border);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
  pointer-events: none;
}

.item-card:hover::before {
  opacity: 1;
}

.content-item {
  display: block;
  margin-bottom: 2rem;
  width: 100%;
}

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
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-base);
}

.item-link:hover {
  color: var(--color-text-primary);
}

.about-text {
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  margin-bottom: 1rem;
  font-size: var(--font-size-body);
}

.item-description {
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  margin-bottom: 1rem;
  font-size: var(--font-size-description);
}

.about-text b,
.about-text strong,
.about-text span,
.about-text a,
.item-description b,
.item-description strong,
.item-description span,
.item-description a {
  color: var(--color-text-primary);
  font-weight: var(--font-weight-medium);
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
  padding: 0 0.5rem;
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
  font-weight: var(--font-weight-normal);
}

.archive-arrow {
  font-size: 1.2em;
  transition: transform 0.3s ease;
}

.content-section {
  background: transparent;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
  display: none;
}

.section-title {
  font-size: 18px;
  color: var(--color-text-primary);
  margin: 0;
  font-weight: var(--font-weight-medium);
  text-transform: uppercase;
  letter-spacing: .5px;
}

.content-timeline {
  position: relative;
  overflow: visible;
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

@media (max-width: 768px) {
  :root {
    --font-size-profile: 42px;
  }

  .section-header {
    display: block;
    position: sticky;
    top: 0;
    background: var(--color-background-blur-strong);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 1rem 1.5rem;
    margin: 0 -1.5rem 1rem -1.5rem;
    z-index: 10;
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
