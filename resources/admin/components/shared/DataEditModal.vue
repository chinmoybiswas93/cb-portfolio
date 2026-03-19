<template>
  <teleport to="body">
    <transition name="modal-fade">
      <div v-if="show" class="cb-modal-overlay" @click.self="$emit('cancel')">
        <transition name="modal-slide">
          <div v-if="show" class="cb-edit-modal">
            <div class="edit-modal-header">
              <h3>{{ title }}</h3>
              <button @click="$emit('cancel')" class="modal-close-btn" type="button">&times;</button>
            </div>

            <div class="edit-modal-body">
              <template v-for="(row, rowIdx) in fieldLayout" :key="rowIdx">
                <FormRow :columns="row.columns || 1">
                  <template v-for="field in row.fields" :key="field.key">
                    <FormGroup
                      v-if="field.type !== 'checkbox'"
                      :label="field.label"
                    >
                      <BaseInput
                        v-if="isInputType(field.type)"
                        v-model="localData[field.key]"
                        :type="field.type || 'text'"
                        :placeholder="getPlaceholder(field)"
                        :disabled="isFieldDisabled(field)"
                        @update:modelValue="onFieldChange(field, $event)"
                      />
                      <BaseTextarea
                        v-else-if="field.type === 'textarea'"
                        v-model="localData[field.key]"
                        :placeholder="field.placeholder || ''"
                        :rows="field.rows || 3"
                        @update:modelValue="onFieldChange(field, $event)"
                      />
                      <MediaUploader
                        v-else-if="field.type === 'media'"
                        v-model="localData[field.key]"
                        :alt="field.alt || ''"
                        @update:modelValue="onFieldChange(field, $event)"
                      />
                    </FormGroup>

                    <FormGroup v-else :label="''">
                      <BaseCheckbox
                        v-model="localData[field.key]"
                        :true-value="field.trueValue ?? 1"
                        :false-value="field.falseValue ?? 0"
                        :label="field.checkboxLabel || field.label"
                        @update:modelValue="onFieldChange(field, $event)"
                      />
                    </FormGroup>
                  </template>
                </FormRow>
              </template>
            </div>

            <div class="edit-modal-footer">
              <button @click="$emit('cancel')" class="btn btn-secondary" type="button">Cancel</button>
              <button @click="handleSave" class="btn btn-primary" type="button">{{ saveButtonText }}</button>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<script>
import BaseInput from '../form/BaseInput.vue'
import BaseTextarea from '../form/BaseTextarea.vue'
import BaseCheckbox from '../form/BaseCheckbox.vue'
import FormGroup from '../form/FormGroup.vue'
import FormRow from '../form/FormRow.vue'
import MediaUploader from '../form/MediaUploader.vue'

export default {
  name: 'DataEditModal',
  components: { BaseInput, BaseTextarea, BaseCheckbox, FormGroup, FormRow, MediaUploader },
  props: {
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Edit Item' },
    saveButtonText: { type: String, default: 'Save' },
    fieldLayout: { type: Array, required: true },
    modelValue: { type: Object, default: () => ({}) }
  },
  emits: ['save', 'cancel'],
  data() {
    return {
      localData: {}
    }
  },
  watch: {
    show(val) {
      if (val) {
        this.localData = JSON.parse(JSON.stringify(this.modelValue || {}))
      }
      document.body.style.overflow = val ? 'hidden' : ''
    }
  },
  beforeUnmount() {
    document.body.style.overflow = ''
  },
  methods: {
    isInputType(type) {
      return ['text', 'url', 'email', 'tel', 'number'].includes(type || 'text')
    },

    isFieldDisabled(field) {
      if (typeof field.disabled === 'function') {
        return field.disabled(this.localData)
      }
      return !!field.disabled
    },

    getPlaceholder(field) {
      if (typeof field.placeholder === 'function') {
        return field.placeholder(this.localData)
      }
      return field.placeholder || ''
    },

    onFieldChange(field, value) {
      if (typeof field.onChange === 'function') {
        field.onChange(value, this.localData)
      }
    },

    handleSave() {
      this.$emit('save', { ...this.localData })
    }
  }
}
</script>

<style>
.cb-edit-modal {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
  width: 100%;
  max-width: 720px;
  max-height: 90vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.edit-modal-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px;
  border-bottom: 1px solid #e1e5e9;
  flex-shrink: 0;
}

.edit-modal-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: #1d2327;
}

.edit-modal-body {
  padding: 24px;
  overflow-y: auto;
  flex: 1;
}

.edit-modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 16px 24px;
  border-top: 1px solid #e1e5e9;
  flex-shrink: 0;
}
</style>
