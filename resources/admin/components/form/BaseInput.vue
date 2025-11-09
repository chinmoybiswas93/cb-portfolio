<template>
  <input 
    :type="type"
    :value="modelValue"
    :placeholder="placeholder"
    :disabled="disabled"
    :required="required"
    :class="['base-input', { 'is-disabled': disabled, 'has-error': error }]"
    @input="$emit('update:modelValue', $event.target.value)"
    @blur="$emit('blur', $event)"
    @focus="$emit('focus', $event)"
  />
</template>

<script>
export default {
  name: 'BaseInput',
  props: {
    modelValue: {
      type: [String, Number],
      default: ''
    },
    type: {
      type: String,
      default: 'text',
      validator: (value) => ['text', 'email', 'tel', 'url', 'password', 'number'].includes(value)
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
    }
  },
  emits: ['update:modelValue', 'blur', 'focus']
}
</script>

<style scoped>
.base-input {
  width: 100%;
  padding: 3px 12px;
  border: 1px solid #dcdcde;
  border-radius: 6px;
  font-size: 14px;
  font-family: inherit;
  background: #fff;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.base-input:focus {
  border-color: #2271b1;
  outline: none;
  box-shadow: 0 0 0 3px rgba(34, 113, 177, 0.1);
}

.base-input.is-disabled {
  background: #f6f7f7;
  color: #8c8f94;
  cursor: not-allowed;
}

.base-input.has-error {
  border-color: #d63638;
}

.base-input.has-error:focus {
  border-color: #d63638;
  box-shadow: 0 0 0 3px rgba(214, 54, 56, 0.1);
}

.base-input::placeholder {
  color: #8c8f94;
}
</style>