<template>
  <div class="experience-item">
    <div class="experience-header">
      <div class="experience-title">
        <h3>{{ experience.position }}</h3>
        <p class="company">{{ experience.company }}</p>
      </div>
      <div class="experience-dates">
        <span class="date-range">
          {{ experience.start_date }} - {{ experience.current ? 'Present' : experience.end_date }}
        </span>
      </div>
    </div>
    
    <div class="experience-content">
      <p class="description">{{ experience.description }}</p>
      
      <div v-if="experience.skills" class="skills">
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
</template>

<script>
export default {
  name: 'ExperienceItem',
  props: {
    experience: {
      type: Object,
      required: true
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
  background: white;
  border-radius: 12px;
  padding: 30px;
  margin-bottom: 24px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
  transition: transform 0.2s, box-shadow 0.2s;
}

.experience-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.experience-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.experience-title h3 {
  font-size: 1.4rem;
  font-weight: 600;
  color: #2c3e50;
  margin: 0 0 8px 0;
}

.company {
  font-size: 1.1rem;
  color: #667eea;
  font-weight: 500;
  margin: 0;
}

.experience-dates {
  text-align: right;
}

.date-range {
  background: #f8f9fa;
  color: #666;
  padding: 8px 16px;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 500;
}

.experience-content {
  margin-top: 20px;
}

.description {
  font-size: 1rem;
  line-height: 1.6;
  color: #555;
  margin: 0 0 20px 0;
}

.skills {
  margin-top: 20px;
}

.skill-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.skill-tag {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 6px 12px;
  border-radius: 16px;
  font-size: 0.85rem;
  font-weight: 500;
}

@media (max-width: 768px) {
  .experience-header {
    flex-direction: column;
    gap: 12px;
  }
  
  .experience-dates {
    text-align: left;
  }
  
  .experience-item {
    padding: 20px;
  }
}
</style>
