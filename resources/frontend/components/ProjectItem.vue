<template>
  <div class="project-item" :class="{ featured: project.featured }">
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
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 24px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: transform 0.2s, box-shadow 0.2s;
  border: 2px solid transparent;
}

.project-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.project-item.featured {
  border-color: #667eea;
  background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.project-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.project-header h3 {
  font-size: 1.4rem;
  font-weight: 600;
  color: #2c3e50;
  margin: 0;
}

.featured-badge {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 6px 12px;
  border-radius: 16px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.project-content {
  margin-top: 20px;
}

.description {
  font-size: 1rem;
  line-height: 1.6;
  color: #555;
  margin: 0 0 20px 0;
}

.technologies {
  margin-bottom: 24px;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.tech-tag {
  background: #f8f9fa;
  color: #667eea;
  padding: 6px 12px;
  border-radius: 16px;
  font-size: 0.85rem;
  font-weight: 500;
  border: 1px solid #e9ecef;
}

.project-links {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.project-link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.2s;
}

.live-link {
  background: #667eea;
  color: white;
}

.live-link:hover {
  background: #5a6fd8;
  transform: translateY(-1px);
}

.github-link {
  background: #f8f9fa;
  color: #333;
  border: 1px solid #e9ecef;
}

.github-link:hover {
  background: #e9ecef;
  transform: translateY(-1px);
}

.link-icon {
  font-size: 1rem;
}

@media (max-width: 768px) {
  .project-item {
    padding: 20px;
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
