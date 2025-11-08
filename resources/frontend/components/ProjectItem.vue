<template>
  <div class="content-item slide-left" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="item-card">
      <!-- Left Column: Project Image (25%) -->
      <div class="item-left-column">
        <div v-if="project.image_url" class="project-image">
          <img :src="project.image_url" :alt="project.title + ' Project Image'" />
        </div>
        <div v-else class="project-image-placeholder">
          <div class="image-icon">🖼️</div>
          <span class="image-text">No Image</span>
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

.project-image {
  width: 100%;
  height: 120px;
  border-radius: 8px;
  overflow: hidden;
  background: var(--color-ui-bg);
  border: 1px solid var(--color-ui-border);
  display: flex;
  align-items: center;
  justify-content: center;
  transition: border-color 0.3s ease;
}

.project-image:hover {
  border-color: var(--color-text-secondary);
}

.project-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.project-image-placeholder {
  background: var(--color-ui-bg);
  border: 2px dashed var(--color-ui-border);
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
  border-color: var(--color-text-secondary);
  background: var(--color-ui-hover);
}

.image-icon {
  font-size: var(--font-size-profile);
  opacity: 0.6;
}

.image-text {
  font-size: var(--font-size-small);
  color: var(--color-text-secondary);
  opacity: 0.8;
  text-align: center;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .project-image,
  .project-image-placeholder {
    height: 120px;
    max-width: 220px;
  }
}
</style>
