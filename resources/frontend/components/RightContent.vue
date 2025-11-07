<template>
  <main class="right-content">
    <div class="content-container">
      <!-- About Section -->
      <section id="about" class="content-section">
        <AboutSection :portfolio-data="portfolioData" />
      </section>

      <!-- Experience Section -->
      <section id="experience" class="content-section">
        <ExperienceSection :experience-data="experienceData" />
      </section>

      <!-- Projects Section -->
      <section id="projects" class="content-section">
        <ProjectsSection :projects-data="projectsData" />
      </section>
    </div>
  </main>
</template>

<script>
import AboutSection from './AboutSection.vue'
import ExperienceSection from './ExperienceSection.vue'
import ProjectsSection from './ProjectsSection.vue'

export default {
  name: 'RightContent',
  components: {
    AboutSection,
    ExperienceSection,
    ProjectsSection
  },
  props: {
    portfolioData: {
      type: Object,
      required: true
    },
    experienceData: {
      type: Array,
      required: true
    },
    projectsData: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
.right-content {
  flex: 1;
  min-height: 100vh;
  /* Remove margin since we're not using fixed positioning */
}

.content-container {
  max-width: 700px;
  margin: 0 auto;
  padding: 6rem 2rem;
}

.content-section {
  margin-bottom: 5rem;
  scroll-margin-top: 2rem;
}

.content-section:last-child {
  margin-bottom: 3rem;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .right-content {
    height: auto;
    min-height: auto;
    flex: none;
    width: 100%;
  }

  .content-container {
    padding: 2rem 0;
  }

  .content-section {
    margin-bottom: 3rem;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .content-container {
    padding: 1.5rem;
  }
}

/* Smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Custom scrollbar for webkit browsers */
.right-content::-webkit-scrollbar {
  width: 8px;
}

.right-content::-webkit-scrollbar-track {
  background: #f1f5f9;
}

.right-content::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.right-content::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>