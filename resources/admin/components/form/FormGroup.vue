<template>
  <div class="form-group" :class="{ 'has-error': error, 'is-required': required }">
    <label v-if="label" class="form-label" :for="fieldId">
      {{ label }}
      <span v-if="required" class="required-asterisk">*</span>
    </label>
    <div class="form-field">
      <slot></slot>
    </div>
    <div v-if="error && errorMessage" class="error-message">
      {{ errorMessage }}
    </div>
    <div v-if="helpText" class="help-text">
      {{ helpText }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'FormGroup',
  props: {
    label: {
      type: String,
      default: ''
    },
    error: {
      type: Boolean,
      default: false
    },
    errorMessage: {
      type: String,
      default: ''
    },
    helpText: {
      type: String,
      default: ''
    },
    required: {
      type: Boolean,
      default: false
    },
    fieldId: {
      type: String,
      default: ''
    }
  }
}
</script>

<style scoped>
.form-group {
  margin-bottom: 16px;
}

.form-label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: #1d2327;
  font-size: 14px;
  line-height: 1.4;
}

.required-asterisk {
  color: #d63638;
  margin-left: 2px;
}

.form-field {
  position: relative;
}

.error-message {
  margin-top: 4px;
  font-size: 12px;
  color: #d63638;
  line-height: 1.4;
}

.help-text {
  margin-top: 4px;
  font-size: 12px;
  color: #646970;
  line-height: 1.4;
}

.form-group.has-error .form-label {
  color: #d63638;
}
</style>