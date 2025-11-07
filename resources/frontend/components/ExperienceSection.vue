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

      <!-- View Full Archive Button -->
      <div v-if="experienceData.length > 0" class="archive-section">
        <a href="#" class="archive-button" @click.prevent="viewFullArchive">
          <span class="archive-text">View Full Resume</span>
          <span class="archive-arrow">→</span>
        </a>
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
  },
  methods: {
    viewFullArchive() {
      // Add your archive view logic here
      console.log('View full experience archive');
      // You can emit an event or navigate to an archive page
      this.$emit('view-archive');
    }
  }
}
</script>

<style scoped>
.experience-section {
  background: transparent;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
  display: none;
  /* Hidden on desktop */
}


.section-title {
  font-size: var(--section-title-size);
  font-weight: var(--font-weight-medium);
  text-transform: uppercase;
  letter-spacing: .5px;
  color: var(--color-text-primary);
  margin: 0;
}

.experience-timeline {
  position: relative;
  overflow: visible;
  /* Allow hover effects to extend beyond boundaries */
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

.archive-button {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--color-text-primary);
  text-decoration: none;
  font-weight: var(--font-weight-medium);
  font-size: var(--body-text-size);
  padding: .2rem 0;
  transition: all 0.3s ease;
  cursor: pointer;
  border-bottom: 1px solid transparent;
}

.archive-section {
  padding-top: 2rem;
}

.archive-button:hover {
  color: var(--color-white);
  border-bottom: 1px solid var(--color-white-60);
}

.archive-button:hover .archive-arrow {
  transform: translateX(4px);
}

.archive-text {
  font-size: var(--body-text-size);
}

.archive-arrow {
  font-size: 1.2em;
  transition: transform 0.3s ease;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .experience-section {
    padding: 0 1.5rem 1rem 1.5rem;
  }

  .section-header {
    display: block;
    position: sticky;
    top: 0;
    background: var(--color-background-blur-strong);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 1rem 1.5rem;
    margin: 0 -1.5rem 1rem -1.5rem;
    z-index: 10;
  }

  .section-title {
    margin: 0;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .experience-section {
    padding: 0 2rem 3.5rem 2rem;
  }
}
</style>
