<template>
  <textarea 
    :value="modelValue"
    :placeholder="placeholder"
    :disabled="disabled"
    :required="required"
    :rows="rows"
    :class="['base-textarea', { 'is-disabled': disabled, 'has-error': error }]"
    @input="$emit('update:modelValue', $event.target.value)"
    @blur="$emit('blur', $event)"
    @focus="$emit('focus', $event)"
  ></textarea>
</template>

<script>
export default {
  name: 'BaseTextarea',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    placeholder: {
      type: String,
      default: ''
    },
    disabled: {
      type: Boolean,
      default: false
    },
    required: {
      type: Boolean,
      default: false
    },
    error: {
      type: Boolean,
      default: false
    },
    rows: {
      type: [String, Number],
      default: 3
    }
  },
  emits: ['update:modelValue', 'blur', 'focus']
}
</script>

<style scoped>
.base-textarea {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #dcdcde;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  background: #fff;
  transition: all 0.2s ease;
  box-sizing: border-box;
  resize: vertical;
  min-height: 80px;
}

.base-textarea:focus {
  border-color: #2271b1;
  outline: none;
  box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

.base-textarea.is-disabled {
  background: #f6f7f7;
  color: #8c8f94;
  cursor: not-allowed;
  resize: none;
}

.base-textarea.has-error {
  border-color: #d63638;
}

.base-textarea.has-error:focus {
  border-color: #d63638;
  box-shadow: 0 0 0 3px rgba(214, 54, 56, 0.1);
}

.base-textarea::placeholder {
  color: #8c8f94;
}
</style>