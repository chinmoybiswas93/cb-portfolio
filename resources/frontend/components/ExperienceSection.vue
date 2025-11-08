<template>
  <section class="experience-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Experience</h2>
    </div>

    <div class="content-timeline slide-up">
      <ExperienceItem v-for="(experience, index) in sortedExperience" :key="experience.id" :experience="experience"
        :index="index" />

      <div v-if="sortedExperience.length === 0" class="empty-state">
        <p>No experience data available yet.</p>
      </div>

      <!-- View Full Archive Button -->
      <div v-if="sortedExperience.length > 0" class="archive-section">
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
  computed: {
    sortedExperience() {
      // Sort experience by order_index in ascending order
      return [...this.experienceData].sort((a, b) => {
        const orderA = a.order_index || 999;
        const orderB = b.order_index || 999;
        return orderA - orderB;
      });
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
  margin-bottom: 0;
}

.archive-button {
  border-bottom: 1px solid transparent;
}

</style>
