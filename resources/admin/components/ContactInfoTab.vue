<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Contact Information</h2>
      <p class="tab-description">How people can reach you</p>
    </div>

    <div class="form-section">
      <FormRow :columns="2">
        <FormGroup label="Email">
          <BaseInput 
            v-model="localData.email" 
            type="email"
            placeholder="your.email@example.com"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>

        <FormGroup label="Phone">
          <BaseInput 
            v-model="localData.phone" 
            type="tel"
            placeholder="+1 (555) 123-4567"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>
      </FormRow>

      <FormGroup label="Location">
        <BaseInput 
          v-model="localData.location" 
          type="text"
          placeholder="City, State/Country"
          @update:modelValue="$emit('data-changed', localData)" 
        />
      </FormGroup>

      <FormRow :columns="2">
        <FormGroup label="GitHub URL">
          <BaseInput 
            v-model="localData.github_url" 
            type="url"
            placeholder="https://github.com/username"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>

        <FormGroup label="LinkedIn URL">
          <BaseInput 
            v-model="localData.linkedin_url" 
            type="url"
            placeholder="https://linkedin.com/in/username"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>
      </FormRow>

      <FormRow :columns="2">
        <FormGroup label="Twitter URL">
          <BaseInput 
            v-model="localData.twitter_url" 
            type="url"
            placeholder="https://twitter.com/username"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>

        <FormGroup label="Website URL">
          <BaseInput 
            v-model="localData.website_url" 
            type="url"
            placeholder="https://yourwebsite.com"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>
      </FormRow>

      <FormGroup label="Resume URL">
        <BaseInput 
          v-model="localData.resume_url" 
          type="url"
          placeholder="https://yourwebsite.com/resume.pdf"
          @update:modelValue="$emit('data-changed', localData)" 
        />
      </FormGroup>
    </div>
  </div>
</template>

<script>
import BaseInput from './form/BaseInput.vue'
import FormGroup from './form/FormGroup.vue'
import FormRow from './form/FormRow.vue'

export default {
  name: 'ContactInfoTab',
  components: {
    BaseInput,
    FormGroup,
    FormRow
  },
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