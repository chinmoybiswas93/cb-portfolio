<template>
  <div class="portfolio-container">
    <PortfolioHeader :portfolio-data="portfolioData" />
    
    <AboutSection :portfolio-data="portfolioData" />
    
    <ExperienceSection :experience-data="experienceData" />
    
    <ProjectsSection :projects-data="projectsData" />
    
    <PortfolioFooter :portfolio-data="portfolioData" />
  </div>
</template>

<script>
import PortfolioHeader from './components/PortfolioHeader.vue'
import AboutSection from './components/AboutSection.vue'
import ExperienceSection from './components/ExperienceSection.vue'
import ProjectsSection from './components/ProjectsSection.vue'
import PortfolioFooter from './components/PortfolioFooter.vue'

export default {
  name: 'PortfolioApp',
  components: {
    PortfolioHeader,
    AboutSection,
    ExperienceSection,
    ProjectsSection,
    PortfolioFooter
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
      projectsData: []
    }
  },
  mounted() {
    this.loadPortfolioData();
    this.loadExperienceData();
    this.loadProjectsData();
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
    }
  }
}
</script>

<style scoped>
.portfolio-container {
  min-height: 100vh;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  line-height: 1.6;
  color: #333;
}

/* Global styles */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
}

/* Responsive container */
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
}

</style>
