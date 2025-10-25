<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Contact Information</h2>
      <p class="tab-description">How people can reach you</p>
    </div>

    <div class="form-section">
      <div class="form-row">
        <div class="form-group">
          <label>Email</label>
          <input type="email" v-model="localData.email" placeholder="your.email@example.com"
            @input="$emit('data-changed', localData)" />
        </div>

        <div class="form-group">
          <label>Phone</label>
          <input type="tel" v-model="localData.phone" placeholder="+1 (555) 123-4567"
            @input="$emit('data-changed', localData)" />
        </div>
      </div>

      <div class="form-group">
        <label>Location</label>
        <input type="text" v-model="localData.location" placeholder="City, State/Country"
          @input="$emit('data-changed', localData)" />
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>GitHub URL</label>
          <input type="url" v-model="localData.github_url" placeholder="https://github.com/username"
            @input="$emit('data-changed', localData)" />
        </div>

        <div class="form-group">
          <label>LinkedIn URL</label>
          <input type="url" v-model="localData.linkedin_url" placeholder="https://linkedin.com/in/username"
            @input="$emit('data-changed', localData)" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label>Twitter URL</label>
          <input type="url" v-model="localData.twitter_url" placeholder="https://twitter.com/username"
            @input="$emit('data-changed', localData)" />
        </div>

        <div class="form-group">
          <label>Website URL</label>
          <input type="url" v-model="localData.website_url" placeholder="https://yourwebsite.com"
            @input="$emit('data-changed', localData)" />
        </div>
      </div>

      <div class="form-group">
        <label>Resume URL</label>
        <input type="url" v-model="localData.resume_url" placeholder="https://yourwebsite.com/resume.pdf"
          @input="$emit('data-changed', localData)" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ContactInfoTab',
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