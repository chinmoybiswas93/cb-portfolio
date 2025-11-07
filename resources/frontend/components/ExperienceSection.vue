<template>
  <section class="experience-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Experience</h2>
    </div>

    <div class="experience-timeline slide-up">
      <ExperienceItem v-for="(experience, index) in experienceData" :key="experience.id" :experience="experience"
        :index="index" />

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
  display: none;
  /* Hidden on desktop */
}

@media (max-width: 768px) {
  .section-header {
    display: block;
    /* Show on mobile */
    position: sticky;
    top: 0;
    background: var(--color-background-blur-strong);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 1rem 1.5rem;
    margin: 0 -1.5rem 2rem -1.5rem;
    z-index: 10;
  }

  .section-title {
    margin: 0;
  }
}

.section-title {
  font-size: var(--section-title-size);
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
  margin: 0;
}

.experience-timeline {
  position: relative;
}

.empty-state {
  text-align: center;
  padding: 3rem 0;
  color: var(--color-text-secondary);
}

.empty-state p {
  font-size: var(--highlight-text-size);
  margin: 0;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .experience-section {
    padding: 0 1.5rem 3rem 1.5rem;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .experience-section {
    padding: 0 2rem 3.5rem 2rem;
  }
}
</style>
