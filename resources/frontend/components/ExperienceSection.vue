<template>
  <section class="experience-section">
    <div class="container">
      <div class="section-header">
        <h2>Experience</h2>
        <div class="section-divider"></div>
      </div>
      
      <div class="experience-list">
        <ExperienceItem
          v-for="experience in experienceData"
          :key="experience.id"
          :experience="experience"
        />
      </div>
    </div>
  </section>
</template>

<script>
import ExperienceItem from './ExperienceItem.vue'

export default {
  name: 'ExperienceSection',
  components: {
    ExperienceItem
  },
  props: {
    experienceData: {
      type: Array,
      required: true
    }
  }
}
</script>

<style scoped>
.experience-section {
  padding: 80px 0;
  background: white;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-header h2 {
  font-size: 2.5rem;
  font-weight: 600;
  color: #2c3e50;
  margin: 0 0 10px 0;
}

.section-divider {
  width: 60px;
  height: 4px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  margin: 0 auto;
  border-radius: 2px;
}

.experience-list {
  max-width: 800px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .experience-section {
    padding: 60px 0;
  }
  
  .section-header h2 {
    font-size: 2rem;
  }
}
</style>
