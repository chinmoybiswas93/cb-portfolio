<template>
  <div class="content-item slide-left" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="item-card">
      <!-- Left Column: Project Image (25%) -->
      <div class="item-left-column">
        <div class="project-image-placeholder">
          <div class="image-icon">🖼️</div>
          <span class="image-text">Project Image</span>
        </div>
      </div>

      <!-- Right Column: Content (75%) -->
      <div class="item-right-column">
        <div class="item-header">
          <h3 class="item-title">
            {{ project.title }}
            <span v-if="project.github_url || project.live_url"></span>
            <a v-if="project.github_url" :href="project.github_url" target="_blank"
              rel="noopener noreferrer" class="item-link">
              GitHub <span class="external-link">↗</span>
            </a>
            <span v-if="project.github_url && project.live_url"></span>
            <a v-if="project.live_url" :href="project.live_url" target="_blank"
              rel="noopener noreferrer" class="item-link">
              Live Demo <span class="external-link">↗</span>
            </a>
          </h3>
        </div>

        <p class="item-description" v-if="project.description">{{ project.description }}</p>

        <div v-if="project.technologies" class="tags-section">
          <div class="tag-list">
            <span v-for="tech in techList" :key="tech" class="tag-item">
              {{ tech.trim() }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProjectItem',
  props: {
    project: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      default: 0
    }
  },
  computed: {
    techList() {
      if (!this.project.technologies) return [];
      return this.project.technologies.split(',').filter(tech => tech.trim());
    }
  }
}
</script>

<style scoped>
/* Project-specific styles */
.content-item {
  padding: 5px 0;
}

.project-image-placeholder {
  background: rgba(148, 163, 184, 0.1);
  border: 2px dashed rgba(148, 163, 184, 0.3);
  border-radius: 8px;
  width: 100%;
  height: 120px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
}

.project-image-placeholder:hover {
  border-color: rgba(148, 163, 184, 0.5);
  background: rgba(148, 163, 184, 0.15);
}

.image-icon {
  font-size: 2rem;
  opacity: 0.6;
}

.image-text {
  font-size: 0.75rem;
  color: var(--color-text-secondary);
  opacity: 0.8;
  text-align: center;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .project-image-placeholder {
    height: 80px;
    max-width: 200px;
  }
}
</style>
