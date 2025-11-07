<template>
  <section class="experience-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Experience</h2>
      <div class="section-divider"></div>
    </div>
    
    <div class="experience-timeline slide-up">
      <ExperienceItem
        v-for="(experience, index) in experienceData"
        :key="experience.id"
        :experience="experience"
        :index="index"
      />
      
      <div v-if="experienceData.length === 0" class="empty-state">
        <p>No experience data available yet.</p>
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
  background: transparent;
  padding: 0 3rem 4rem 3rem;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
}

.section-title {
  font-size: 2rem;
  font-weight: 700;
  color: rgb(248, 250, 252);
  margin: 0 0 1rem 0;
}

.section-divider {
  width: 60px;
  height: 2px;
  background: rgba(148, 163, 184, 0.3);
  border-radius: 1px;
}

.experience-timeline {
  position: relative;
}

.empty-state {
  text-align: center;
  padding: 3rem 0;
  color: rgb(148, 163, 184);
}

.empty-state p {
  font-size: 1.125rem;
  margin: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .experience-section {
    padding: 0 1.5rem 3rem 1.5rem;
  }
  
  .section-title {
    font-size: 1.75rem;
  }
  

}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .experience-section {
    padding: 0 2rem 3.5rem 2rem;
  }
  
  .section-title {
    font-size: 1.875rem;
  }
}
</style>
