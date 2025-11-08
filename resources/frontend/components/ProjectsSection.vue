<template>
  <section class="projects-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Projects</h2>
    </div>

    <div class="content-timeline slide-up">
      <ProjectItem v-for="(project, index) in featuredProjects" :key="project.id" :project="project" :index="index" />

      <!-- Loading Skeletons -->
      <div v-if="isLoading" class="loading-items">
        <div v-for="n in 3" :key="`loading-proj-${n}`" class="loading-item" :style="{ animationDelay: `${(n - 1) * 0.1}s` }">
          <div class="item-card">
            <div class="item-left-column">
              <div class="loading-skeleton image-skeleton"></div>
            </div>
            <div class="item-right-column">
              <div class="loading-skeleton title-skeleton"></div>
              <div class="loading-skeleton description-skeleton"></div>
              <div class="loading-skeleton description-skeleton short"></div>
              <div class="tags-skeleton">
                <div class="loading-skeleton tag-skeleton"></div>
                <div class="loading-skeleton tag-skeleton"></div>
                <div class="loading-skeleton tag-skeleton"></div>
                <div class="loading-skeleton tag-skeleton"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state - only show if not loading and no data -->
      <div v-if="!isLoading && featuredProjects.length === 0" class="empty-state">
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
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    featuredProjects() {
      // Filter featured projects and sort by order_index in ascending order
      const featured = this.projectsData.filter(project => project.featured === 1 || project.featured === '1');
      
      // Sort by order_index in ascending order
      const sortedFeatured = featured.sort((a, b) => {
        const orderA = a.order_index || 999;
        const orderB = b.order_index || 999;
        return orderA - orderB;
      });
      
      // Get the first 5 featured projects (in order_index order)
      return sortedFeatured.slice(0, 5);
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
  margin-bottom: 0;
}

.archive-button {
  border-bottom: 1px solid transparent;
}

/* Loading States */
.loading-items {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.loading-item {
  display: block;
  margin-bottom: 2rem;
  width: 100%;
  opacity: 0;
  animation: fadeIn 0.6s ease-out forwards;
}

.loading-skeleton {
  background: linear-gradient(90deg,
      transparent 25%,
      rgba(255, 255, 255, 0.02) 50%,
      transparent 75%);
  background-size: 200% 100%;
  border-radius: 8px;
  animation: skeleton-loading 1.5s infinite;
}

.image-skeleton {
  height: 80px;
  width: 100%;
  border-radius: 8px;
}

.title-skeleton {
  height: 20px;
  width: 70%;
  margin-bottom: 0.75rem;
}

.description-skeleton {
  height: 16px;
  width: 100%;
  margin-bottom: 0.5rem;
}

.description-skeleton.short {
  width: 60%;
  margin-bottom: 1rem;
}

.tags-skeleton {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}

.tag-skeleton {
  height: 24px;
  width: 60px;
}

@keyframes skeleton-loading {
  0% {
    background-position: 200% 0;
  }

  100% {
    background-position: -200% 0;
  }
}

@media (max-width: 768px) {
  .loading-item .item-card {
    flex-direction: column;
    gap: 1rem;
  }

  .loading-item .item-left-column {
    flex: none;
    width: 100%;
  }

  .image-skeleton {
    height: 60px;
  }
}
</style>
