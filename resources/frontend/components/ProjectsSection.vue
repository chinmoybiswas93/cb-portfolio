<template>
  <section class="projects-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Projects</h2>
    </div>

    <div class="content-timeline slide-up">
      <ProjectItem v-for="(project, index) in featuredProjects" :key="project.id" :project="project" :index="index" />

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
/* Component-specific styles for Projects Section */
.projects-section {
  background: transparent;
  padding-bottom: 4rem;
  margin-bottom: 0;
}

.section-title {
  font-weight: var(--font-weight-medium);
  text-transform: uppercase;
  letter-spacing: .5px;
}

.archive-button {
  border-bottom: 1px solid transparent;
}
</style>
