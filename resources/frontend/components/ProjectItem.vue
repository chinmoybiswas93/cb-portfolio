<template>
  <div 
    class="project-item" 
    :class="{ featured: project.featured }"
    :style="{ '--index': index }"
  >
    <div class="project-header">
      <h3>{{ project.title }}</h3>
      <div v-if="project.featured" class="featured-badge">Featured</div>
    </div>
    
    <div class="project-content">
      <p class="description">{{ project.description }}</p>
      
      <div v-if="project.technologies" class="technologies">
        <div class="tech-tags">
          <span 
            v-for="tech in techList" 
            :key="tech" 
            class="tech-tag"
          >
            {{ tech.trim() }}
          </span>
        </div>
      </div>
      
      <div class="project-links">
        <a 
          v-if="project.live_url" 
          :href="project.live_url" 
          target="_blank" 
          class="project-link live-link"
        >
          <span class="link-icon">🌐</span>
          Live Demo
        </a>
        
        <a 
          v-if="project.github_url" 
          :href="project.github_url" 
          target="_blank" 
          class="project-link github-link"
        >
          <span class="link-icon">💻</span>
          GitHub
        </a>
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
  background: transparent;
  padding: 1.5rem 0;
  border-bottom: 1px solid rgba(148, 163, 184, 0.1);
  transition: all 0.3s ease;
  animation: fadeInUp 0.6s ease-out;
  animation-delay: calc(var(--index, 0) * 0.1s);
  animation-fill-mode: both;
  height: fit-content;
}

.project-item:hover {
  border-bottom-color: rgba(148, 163, 184, 0.3);
}

.project-item.featured {
  border-left: 3px solid rgba(148, 163, 184, 0.4);
  padding-left: 1rem;
  position: relative;
}

.project-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.project-title {
  font-size: 1.25rem;
  font-weight: 700;
  color: rgb(248, 250, 252);
  margin: 0;
  line-height: var(--line-height-base);
}

.featured-badge {
  background: transparent;
  color: rgb(203, 213, 225);
  padding: 0;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.project-content {
  margin-top: 20px;
}

.project-description {
  font-size: 0.875rem;
  color: rgb(148, 163, 184);
  margin: 0.5rem 0 1rem;
  line-height: var(--line-height-base);
}

.technologies {
  margin-bottom: 1.5rem;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.tech-tag {
  background: transparent;
  color: rgb(148, 163, 184);
  padding: 0.25rem 0.5rem;
  font-size: 0.75rem;
  font-weight: 500;
  border: 1px solid rgba(148, 163, 184, 0.3);
  transition: all 0.2s ease;
}

.tech-tag:hover {
  border-color: rgba(148, 163, 184, 0.6);
  color: rgb(203, 213, 225);
}

.project-links {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.project-link {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.625rem 1rem;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.875rem;
  transition: all 0.3s ease;
  flex: 1;
  justify-content: center;
  min-width: 100px;
}

.live-link {
  background: transparent;
  color: rgb(148, 163, 184);
  border: 1px solid rgba(148, 163, 184, 0.5);
}

.live-link:hover {
  border-color: rgba(148, 163, 184, 0.8);
  color: rgb(203, 213, 225);
}

.github-link {
  background: transparent;
  color: rgb(148, 163, 184);
  border: 1px solid rgba(148, 163, 184, 0.3);
}

.github-link:hover {
  border-color: rgba(148, 163, 184, 0.6);
  color: rgb(203, 213, 225);
}

.link-icon {
  font-size: 1rem;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .project-item {
    padding: 1.5rem 0;
  }
  
  .project-item.featured {
    padding-left: 1rem;
  }
  
  .project-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  
  .project-links {
    flex-direction: column;
  }
  
  .project-link {
    justify-content: center;
  }
}
</style>
