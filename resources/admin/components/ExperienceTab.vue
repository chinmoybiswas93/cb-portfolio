<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Work Experience</h2>
      <p class="tab-description">Your professional journey</p>
      <button @click="$emit('add-experience')" class="add-btn">
        <span class="btn-icon">+</span>
        Add Experience
      </button>
    </div>

    <div class="form-section">
      <ExperienceItem v-for="(exp, index) in experienceData" :key="exp.id || index" :experience="exp"
        :experience-number="experienceData.length - index"
        @update="(updatedData) => $emit('update-experience', index, updatedData)"
        @remove="$emit('remove-experience', index)" />
    </div>
  </div>
</template>

<script>
import ExperienceItem from './ExperienceItem.vue'

export default {
  name: 'ExperienceTab',
  components: {
    ExperienceItem
  },
  props: {
    experienceData: {
      type: Array,
      required: true
    }
  },
  emits: ['add-experience', 'update-experience', 'remove-experience']
}
</script>