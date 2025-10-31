<template>
  <label class="base-checkbox">
    <input 
      type="checkbox"
      :checked="isChecked"
      :disabled="disabled"
      :required="required"
      @change="handleChange"
    />
    <span class="checkbox-custom"></span>
    <span class="checkbox-text">
      <slot>{{ label }}</slot>
    </span>
  </label>
</template>

<script>
export default {
  name: 'BaseCheckbox',
  props: {
    modelValue: {
      type: [Boolean, Number, String],
      default: false
    },
    trueValue: {
      type: [Boolean, Number, String],
      default: true
    },
    falseValue: {
      type: [Boolean, Number, String],
      default: false
    },
    label: {
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
    }
  },
  emits: ['update:modelValue', 'change'],
  computed: {
    isChecked() {
      return this.modelValue === this.trueValue
    }
  },
  methods: {
    handleChange(event) {
      const value = event.target.checked ? this.trueValue : this.falseValue
      this.$emit('update:modelValue', value)
      this.$emit('change', value, event)
    }
  }
}
</script>

<style scoped>
.base-checkbox {
  display: flex !important;
  align-items: center;
  cursor: pointer;
  font-size: 14px;
  font-weight: normal;
  user-select: none;
}

.base-checkbox input[type="checkbox"] {
  display: none;
}

.checkbox-custom {
  display: inline-block;
  width: 16px;
  height: 16px;
  background: #fff;
  border: 2px solid #dcdcde;
  border-radius: 4px;
  margin-right: 8px;
  position: relative;
  transition: all 0.2s ease;
  flex-shrink: 0;
}

.base-checkbox input[type="checkbox"]:checked + .checkbox-custom {
  background: #2271b1;
  border-color: #2271b1;
}

.base-checkbox input[type="checkbox"]:checked + .checkbox-custom::after {
  content: '✓';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 12px;
  font-weight: bold;
}

.base-checkbox input[type="checkbox"]:disabled + .checkbox-custom {
  background: #f6f7f7;
  border-color: #dcdcde;
  cursor: not-allowed;
}

.base-checkbox input[type="checkbox"]:disabled + .checkbox-custom + .checkbox-text {
  color: #8c8f94;
  cursor: not-allowed;
}

.base-checkbox:hover input[type="checkbox"]:not(:disabled) + .checkbox-custom {
  border-color: #2271b1;
}

.checkbox-text {
  color: #1d2327;
  line-height: 1.4;
}
</style>