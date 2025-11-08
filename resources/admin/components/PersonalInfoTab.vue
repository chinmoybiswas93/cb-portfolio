<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Personal Information</h2>
      <p class="tab-description">Tell visitors about yourself and your expertise</p>
    </div>

    <div class="form-section">
      <FormRow :columns="2">
        <FormGroup label="Full Name">
          <BaseInput 
            v-model="localData.name" 
            type="text"
            placeholder="Enter your full name"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>

        <FormGroup label="Job Title">
          <BaseInput 
            v-model="localData.title" 
            type="text"
            placeholder="e.g., Front End Engineer"
            @update:modelValue="$emit('data-changed', localData)" 
          />
        </FormGroup>
      </FormRow>

      <FormGroup label="Tagline">
        <BaseTextarea 
          v-model="localData.tagline" 
          placeholder="Brief description of what you do" 
          :rows="2"
          @update:modelValue="$emit('data-changed', localData)" 
        />
      </FormGroup>

      <FormGroup label="About">
        <BaseTextarea 
          v-model="localData.about"
          placeholder="Tell us about yourself, your experience, and what you're passionate about" 
          :rows="6"
          @update:modelValue="$emit('data-changed', localData)" 
        />
        <p class="field-description">You can use HTML tags like &lt;b&gt;, &lt;span&gt;, and &lt;a&gt; for formatting. Bold text will be styled prominently.</p>
      </FormGroup>
    </div>
  </div>
</template>

<script>
import BaseInput from './form/BaseInput.vue'
import BaseTextarea from './form/BaseTextarea.vue'
import FormGroup from './form/FormGroup.vue'
import FormRow from './form/FormRow.vue'

export default {
  name: 'PersonalInfoTab',
  components: {
    BaseInput,
    BaseTextarea,
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

<style scoped>
.field-description {
  margin: 8px 0 0 0;
  color: #646970;
  font-size: 13px;
  line-height: 1.5;
  font-style: italic;
}
</style>