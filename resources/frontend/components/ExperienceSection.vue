<template>
  <section class="experience-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Experience</h2>
    </div>

    <div class="content-timeline slide-up">
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
/* Component-specific styles for Experience Section */
.experience-section {
  background: transparent;
  margin-bottom: 0;
}

.section-title {
  font-weight: var(--font-weight-medium);
  text-transform: uppercase;
  letter-spacing: .5px;
}

.archive-button {
  border-bottom: 1px solid transparent;
}

</style>
