<template>
  <section class="projects-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Projects</h2>
    </div>

    <div class="projects-timeline slide-up">
      <ProjectItem v-for="(project, index) in featuredProjects" :key="project.id" :project="project"
        :index="index" />

      <div v-if="featuredProjects.length === 0" class="empty-state">
        <p>No projects available yet.</p>
      </div>

      <!-- View Full Archive Button -->
      <div v-if="featuredProjects.length > 0" class="archive-section">
        <a href="#" class="archive-button" @click.prevent="viewFullArchive">
          <span class="archive-text">View Full Project Archive</span>
          <span class="archive-arrow">→</span>
        </a>
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
  },
  computed: {
    featuredProjects() {
      // Filter featured projects and get the last 5
      const featured = this.projectsData.filter(project => project.featured === 1 || project.featured === '1');
      return featured.slice(-5).reverse(); // Get last 5 and reverse to show newest first
    }
  },
  methods: {
    viewFullArchive() {
      // Add your archive view logic here
      console.log('View full project archive');
      // You can emit an event or navigate to an archive page
      this.$emit('view-archive');
    }
  }
}
</script>

<style scoped>
.projects-section {
  background: transparent;
  padding-bottom: 4rem;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
  display: none;
  /* Hidden on desktop */
}

@media (max-width: 768px) {
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
}

.section-title {
  font-size: var(--section-title-size);
  font-weight: var(--font-weight-medium);
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--color-text-primary);
  margin: 0;
}

.projects-timeline {
  position: relative;
  overflow: visible; /* Allow hover effects to extend beyond boundaries */
}

.empty-state {
  text-align: center;
  padding: 3rem 0;
  color: var(--color-text-secondary);
}

.empty-state p {
  font-size: var(--highlight-text-size);
  margin: 0;
}

.archive-section {
  padding-top: 2rem;
}

.archive-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--color-text-primary);
  text-decoration: none;
  font-weight: var(--font-weight-medium);
  font-size: var(--body-text-size);
  padding: .2rem 0;
  transition: all 0.3s ease;
  cursor: pointer;
  border-bottom: 1px solid transparent;
}

.archive-button:hover {
  color: var(--color-white);
  border-bottom: 1px solid var(--color-white-60);
}

.archive-button:hover .archive-arrow {
  transform: translateX(4px);
}

.archive-text {
  font-size: var(--body-text-size);
}

.archive-arrow {
  font-size: 1.2em;
  transition: transform 0.3s ease;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .projects-section {
    padding: 0 1.5rem 3rem 1.5rem;
  }

  .archive-section {
    margin-top: 0rem;
    padding-top: 1.5rem;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .projects-section {
    padding: 0 2rem 3.5rem 2rem;
  }
}
</style>
