<template>
  <div class="project-item" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="project-card">
      <!-- Left Column: Project Image (25%) -->
      <div class="project-duration">
        <div class="project-image-placeholder">
          <div class="image-icon">🖼️</div>
          <span class="image-text">Project Image</span>
        </div>
      </div>

      <!-- Right Column: Content (75%) -->
      <div class="project-content">
        <div class="project-header">
          <h3 class="position">
            {{ project.title }}
            <span v-if="project.github_url || project.live_url"></span>
            <a v-if="project.github_url" :href="project.github_url" target="_blank"
              rel="noopener noreferrer" class="project-link">
              GitHub <span class="external-link">↗</span>
            </a>
            <span v-if="project.github_url && project.live_url"></span>
            <a v-if="project.live_url" :href="project.live_url" target="_blank"
              rel="noopener noreferrer" class="project-link">
              Live Demo <span class="external-link">↗</span>
            </a>
          </h3>
        </div>

        <p class="description" v-if="project.description">{{ project.description }}</p>

        <div v-if="project.technologies" class="skills-section">
          <div class="skill-tags">
            <span v-for="tech in techList" :key="tech" class="skill-tag">
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
.project-item {
  display: block;
  margin-bottom: 2rem;
  /* Increased spacing between items */
  animation: slideInLeft 0.6s ease-out;
  animation-fill-mode: both;
  width: 100%;
  padding: 5px 0;
}

.project-card {
  display: flex;
  gap: 1rem;
  border-radius: 8px;
  cursor: pointer;
  /* No padding by default - content takes full width */
  padding: 0;
  margin: 0;
  position: relative;
  transition: padding 0.3s ease;
}

.project-card::before {
  content: '';
  position: absolute;
  top: -1rem;
  left: -1.5rem;
  right: -1.5rem;
  bottom: -1rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
  pointer-events: none;
}

.project-card:hover::before {
  opacity: 1;
}

/* Left Column - Project Image Placeholder (25%) */
.project-duration {
  flex: 0 0 25%;
  position: relative;
  z-index: 1;
  display: flex;
  align-items: flex-start;
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

/* Right Column - Content (75%) */
.project-content {
  flex: 1;
  position: relative;
  z-index: 1;
}

.project-header {
  margin-bottom: 0.75rem;
}

.position {
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-normal);
  color: var(--color-text-primary);
  margin: 0 0 0.25rem 0;
  line-height: var(--line-height-base);
  display: flex;
  align-items: center;
  gap: 0.25rem;
  flex-wrap: wrap;
}

.position > span {
  display: inline-block;
  width: 3px;
  height: 3px;
  background-color: var(--color-text-primary);
  border-radius: 50%;
  margin-left: 2px;
}

.project-link {
  color: var(--color-white-80);
  text-decoration: none;
  transition: color 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: var(--font-size-sm);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-base);
}

.project-link:hover {
  color: var(--color-white-90);
}

.external-link {
  font-size: 14px;
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.project-card:hover .external-link {
  opacity: 1;
}

.description {
  color: var(--text-light);
  line-height: var(--line-height-base);
  margin-bottom: 1rem;
  font-size: var(--font-size-sm);
}

.skills-section {
  margin-top: 1rem;
}

.skill-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.skill-tag {
  background: rgba(255, 255, 255, 0.05);
  color: var(--text-light);
  padding: 2px 0.5rem;
  border-radius: 4px;
  font-size: var(--font-size-xs);
  border: 1px solid rgba(255, 255, 255, 0.1);
  transition: all 0.3s ease;
}

.skill-tag:hover {
  border-color: var(--color-white-40);
  color: var(--color-white-90);
  background: var(--color-white-10);
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .project-item {
    margin-bottom: 1.5rem;
  }

  .project-card {
    flex-direction: column;
    gap: 1rem;
  }

  /* .project-card:hover {
    padding: 1rem;
  } */

  .project-card::before {
    top: -1rem;
    left: -1rem;
    right: -1rem;
    bottom: -1rem;
  }

  .project-duration {
    flex: none;
    width: 100%;
  }

  .project-image-placeholder {
    height: 80px;
    max-width: 200px;
  }

  .position {
    font-size: var(--body-text-size);
    flex-direction: column;
    align-items: flex-start;
  }

  .position > span {
    display: none;
  }

  .project-link {
      font-size: var(--font-size-sm);
  }
}
</style>
