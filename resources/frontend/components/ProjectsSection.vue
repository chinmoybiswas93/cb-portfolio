<template>
  <section class="projects-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Projects</h2>
      <div class="section-divider"></div>
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
}

.section-title {
  font-size: 2rem;
  font-weight: 700;
  color: rgb(248, 250, 252);
  margin: 0 0 1rem 0;
}

.section-divider {
  width: 60px;
  height: 2px;
  background: rgba(148, 163, 184, 0.3);
  border-radius: 1px;
}

.projects-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1.5rem;
}

.empty-state {
  text-align: center;
  padding: 3rem 0;
  color: rgb(148, 163, 184);
  grid-column: 1 / -1;
}

.empty-state p {
  font-size: 1.125rem;
  margin: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .projects-section {
    padding: 0 1.5rem 3rem 1.5rem;
  }
  
  .section-title {
    font-size: 1.75rem;
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
  
  .section-title {
    font-size: 1.875rem;
  }
  
  .projects-grid {
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  }
}
</style>
