<template>
  <div class="experience-item" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="experience-card">
      <div class="experience-header">
        <div class="experience-title">
          <h3>{{ experience.position }}</h3>
          <p class="company">{{ experience.company }}</p>
        </div>
        <div class="experience-dates">
          <span class="date-badge" :class="{ current: experience.current }">
            {{ experience.start_date }} - {{ experience.current ? 'Present' : experience.end_date }}
          </span>
        </div>
      </div>
      
      <div class="experience-content">
        <p class="description" v-if="experience.description">{{ experience.description }}</p>
        
        <div v-if="experience.skills" class="skills-section">
          <h4 class="skills-title">Technologies & Skills</h4>
          <div class="skill-tags">
            <span 
              v-for="skill in skillList" 
              :key="skill" 
              class="skill-tag"
            >
              {{ skill.trim() }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ExperienceItem',
  props: {
    experience: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      default: 0
    }
  },
  computed: {
    skillList() {
      if (!this.experience.skills) return [];
      return this.experience.skills.split(',').filter(skill => skill.trim());
    }
  }
}
</script>

<style scoped>
.experience-item {
  position: relative;
  padding-left: 0;
  margin-bottom: 3rem;
  animation: slideInLeft 0.6s ease-out;
  animation-fill-mode: both;
}

.experience-card {
  background: transparent;
  padding: 1.5rem 0;
  border-left: 3px solid rgba(148, 163, 184, 0.3);
  padding-left: 1.5rem;
  transition: all 0.3s ease;
}

.experience-card:hover {
  border-left-color: rgba(148, 163, 184, 0.6);
}

.experience-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1rem;
}

.experience-title h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: rgb(248, 250, 252);
  margin: 0 0 0.25rem 0;
}

.company {
  font-size: 1rem;
  color: rgb(203, 213, 225);
  font-weight: 600;
  margin: 0;
}

.experience-dates {
  flex-shrink: 0;
}

.date-badge {
  background: transparent;
  color: rgb(148, 163, 184);
  padding: 0;
  font-size: 0.875rem;
  font-weight: 500;
  white-space: nowrap;
}

.date-badge.current {
  color: rgb(203, 213, 225);
  font-weight: 600;
}

.experience-content {
  margin-top: 1rem;
}

.description {
  font-size: 1rem;
  line-height: 1.6;
  color: rgb(148, 163, 184);
  margin: 0 0 1rem 0;
}

.skills-section {
  margin-top: 1rem;
}

.skills-title {
  font-size: 0.875rem;
  font-weight: 600;
  color: rgb(203, 213, 225);
  margin: 0 0 0.5rem 0;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.skill-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.skill-tag {
  background: transparent;
  color: rgb(148, 163, 184);
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  border: 1px solid rgba(148, 163, 184, 0.3);
  transition: all 0.2s ease;
}

.skill-tag:hover {
  border-color: rgba(148, 163, 184, 0.6);
  color: rgb(203, 213, 225);
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
  .experience-item {
    padding-left: 0;
  }
  
  .experience-header {
    flex-direction: column;
    gap: 0.75rem;
  }
  
  .experience-dates {
    align-self: flex-start;
  }
  
  .experience-card {
    padding: 1rem 0;
    padding-left: 1rem;
  }
  
  .experience-title h3 {
    font-size: 1.125rem;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .experience-card {
    padding: 1.25rem 0;
    padding-left: 1.25rem;
  }
}
</style>
