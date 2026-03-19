<template>
  <div class="tab-content">
    <div class="tab-header">
      <div class="tab-header-info">
        <h2>{{ title }}</h2>
        <p class="tab-description">{{ description }}</p>
      </div>
      <div class="tab-header-actions">
        <button @click="saveData" :disabled="saving" class="save-btn" type="button">
          {{ saving ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </div>

    <div class="form-section">
      <template v-for="(row, rowIdx) in fields" :key="rowIdx">
        <FormRow v-if="row.length > 1" :columns="row.length">
          <FormGroup v-for="field in row" :key="field.key" :label="field.label">
            <BaseTextarea
              v-if="field.type === 'textarea'"
              v-model="localData[field.key]"
              :placeholder="field.placeholder"
              :rows="field.rows || 3"
              @update:modelValue="$emit('data-changed', localData)"
            />
            <BaseInput
              v-else
              v-model="localData[field.key]"
              :type="field.type || 'text'"
              :placeholder="field.placeholder"
              @update:modelValue="$emit('data-changed', localData)"
            />
            <p v-if="field.helpText" class="field-description" v-html="field.helpText"></p>
          </FormGroup>
        </FormRow>

        <FormGroup v-else :label="row[0].label">
          <BaseTextarea
            v-if="row[0].type === 'textarea'"
            v-model="localData[row[0].key]"
            :placeholder="row[0].placeholder"
            :rows="row[0].rows || 3"
            @update:modelValue="$emit('data-changed', localData)"
          />
          <BaseInput
            v-else
            v-model="localData[row[0].key]"
            :type="row[0].type || 'text'"
            :placeholder="row[0].placeholder"
            @update:modelValue="$emit('data-changed', localData)"
          />
          <p v-if="row[0].helpText" class="field-description" v-html="row[0].helpText"></p>
        </FormGroup>
      </template>
    </div>
  </div>
</template>

<script>
import api from '../utils/api'
import BaseInput from './form/BaseInput.vue'
import BaseTextarea from './form/BaseTextarea.vue'
import FormGroup from './form/FormGroup.vue'
import FormRow from './form/FormRow.vue'

export default {
  name: 'PortfolioFormTab',
  components: { BaseInput, BaseTextarea, FormGroup, FormRow },
  props: {
    title: { type: String, required: true },
    description: { type: String, default: '' },
    fields: { type: Array, required: true },
    portfolioData: { type: Object, required: true },
    successMessage: { type: String, default: 'Saved successfully!' }
  },
  inject: ['showToast'],
  emits: ['data-changed'],
  data() {
    return {
      localData: { ...this.portfolioData },
      saving: false
    }
  },
  watch: {
    portfolioData: {
      handler(newData) {
        this.localData = { ...newData }
      },
      deep: true
    }
  },
  methods: {
    async saveData() {
      this.saving = true
      try {
        await api.post('portfolio', this.localData)
        this.showToast('success', this.successMessage)
      } catch (err) {
        console.error('Error saving data:', err)
        this.showToast('error', 'Failed to save. Please try again.')
      } finally {
        this.saving = false
      }
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
