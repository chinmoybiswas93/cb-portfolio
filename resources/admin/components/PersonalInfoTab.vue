<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Personal Information</h2>
      <p class="tab-description">Tell visitors about yourself and your expertise</p>
    </div>

    <div class="form-section">
      <div class="form-row">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" v-model="localData.name" placeholder="Enter your full name"
            @input="$emit('data-changed', localData)" />
        </div>

        <div class="form-group">
          <label>Job Title</label>
          <input type="text" v-model="localData.title" placeholder="e.g., Front End Engineer"
            @input="$emit('data-changed', localData)" />
        </div>
      </div>

      <div class="form-group">
        <label>Tagline</label>
        <textarea v-model="localData.tagline" placeholder="Brief description of what you do" rows="2"
          @input="$emit('data-changed', localData)"></textarea>
      </div>

      <div class="form-group">
        <label>About</label>
        <textarea v-model="localData.about"
          placeholder="Tell us about yourself, your experience, and what you're passionate about" rows="6"
          @input="$emit('data-changed', localData)"></textarea>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PersonalInfoTab',
  props: {
    portfolioData: {
      type: Object,
      required: true
    }
  },
  emits: ['data-changed'],
  data() {
    return {
      localData: { ...this.portfolioData }
    }
  },
  watch: {
    portfolioData: {
      handler(newData) {
        this.localData = { ...newData };
      },
      deep: true
    }
  }
}
</script>