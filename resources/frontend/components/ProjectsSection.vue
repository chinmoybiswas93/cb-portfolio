<template>
  <section class="projects-section">
    <div class="container">
      <div class="section-header">
        <h2>Projects</h2>
        <div class="section-divider"></div>
      </div>
      
      <div class="projects-grid">
        <ProjectItem
          v-for="project in projectsData"
          :key="project.id"
          :project="project"
        />
      </div>
    </div>
  </section>
</template>

<script>
import ProjectItem from './ProjectItem.vue'

export default {
  name: 'ProjectsSection',
  components: {
    ProjectItem
  },
  props: {
    projectsData: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
.projects-section {
  padding: 80px 0;
  background: #f8f9fa;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-header h2 {
  font-size: 2.5rem;
  font-weight: 600;
  color: #2c3e50;
  margin: 0 0 10px 0;
}

.section-divider {
  width: 60px;
  height: 4px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  margin: 0 auto;
  border-radius: 2px;
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 24px;
}

@media (max-width: 768px) {
  .projects-section {
    padding: 60px 0;
  }
  
  .section-header h2 {
    font-size: 2rem;
  }
  
  .projects-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
}
</style>
