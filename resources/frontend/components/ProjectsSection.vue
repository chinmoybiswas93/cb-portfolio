<template>
  <section class="projects-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Projects</h2>
    </div>
    
    <div class="projects-grid slide-up">
      <ProjectItem
        v-for="(project, index) in projectsData"
        :key="project.id"
        :project="project"
        :index="index"
      />
      
      <div v-if="projectsData.length === 0" class="empty-state">
        <p>No projects available yet.</p>
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
  background: transparent;
  padding: 0 3rem 4rem 3rem;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
  display: none; /* Hidden on desktop */
}

@media (max-width: 768px) {
  .section-header {
    display: block; /* Show on mobile */
    position: sticky;
    top: 0;
    background: var(--color-background-blur-strong);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 1rem 1.5rem;
    margin: 0 -1.5rem 2rem -1.5rem;
    z-index: 10;
  }
  
  .section-title {
    margin: 0;
  }
}

.section-title {
  font-size: var(--section-title-size);
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
  margin: 0;
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1.5rem;
}

.empty-state {
  text-align: center;
  padding: 3rem 0;
  color: var(--color-text-secondary);
  grid-column: 1 / -1;
}

.empty-state p {
  font-size: var(--highlight-text-size);
  margin: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .projects-section {
    padding: 0 1.5rem 1rem 1.5rem;
  }
  
  .projects-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .projects-section {
    padding: 0 2rem 3.5rem 2rem;
  }
  
  .projects-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  }
}
</style>
