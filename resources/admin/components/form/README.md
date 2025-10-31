# Form Components Documentation

This directory contains reusable form components for the CB Portfolio admin interface. These components provide consistent styling, behavior, and reduce code duplication across the application.

## Components

### BaseInput.vue
Reusable input component for text, email, tel, url, password, and number inputs.

**Props:**
- `modelValue` (String|Number): The input value
- `type` (String): Input type - text, email, tel, url, password, number
- `placeholder` (String): Placeholder text
- `disabled` (Boolean): Whether input is disabled
- `required` (Boolean): Whether input is required
- `error` (Boolean): Whether to show error state

**Events:**
- `@update:modelValue`: Emitted when value changes
- `@blur`: Emitted when input loses focus
- `@focus`: Emitted when input gains focus

### BaseTextarea.vue
Reusable textarea component for multi-line text input.

**Props:**
- `modelValue` (String): The textarea value
- `placeholder` (String): Placeholder text
- `disabled` (Boolean): Whether textarea is disabled
- `required` (Boolean): Whether textarea is required
- `error` (Boolean): Whether to show error state
- `rows` (String|Number): Number of rows (default: 3)

**Events:**
- `@update:modelValue`: Emitted when value changes
- `@blur`: Emitted when textarea loses focus
- `@focus`: Emitted when textarea gains focus

### BaseCheckbox.vue
Reusable checkbox component with custom styling.

**Props:**
- `modelValue` (Boolean|Number|String): The checkbox value
- `trueValue` (Boolean|Number|String): Value when checked (default: true)
- `falseValue` (Boolean|Number|String): Value when unchecked (default: false)
- `label` (String): Checkbox label text
- `disabled` (Boolean): Whether checkbox is disabled
- `required` (Boolean): Whether checkbox is required

**Events:**
- `@update:modelValue`: Emitted when value changes
- `@change`: Emitted when checkbox state changes

### FormGroup.vue
Wrapper component for form fields with label, error message, and help text.

**Props:**
- `label` (String): Label text
- `error` (Boolean): Whether to show error state
- `errorMessage` (String): Error message to display
- `helpText` (String): Help text to display
- `required` (Boolean): Whether field is required
- `fieldId` (String): ID for label association

**Slots:**
- Default slot: Form field content

### FormRow.vue
Container component for creating responsive form layouts.

**Props:**
- `columns` (Number|String): Number of columns (1-4, default: 2)
- `gap` (String): Gap between columns (default: '16px')

**Slots:**
- Default slot: Form groups or fields

## Usage Examples

```vue
<template>
  <!-- Simple input with FormGroup -->
  <FormGroup label="Full Name" required>
    <BaseInput 
      v-model="name" 
      type="text"
      placeholder="Enter your full name"
      required
    />
  </FormGroup>

  <!-- Two-column layout -->
  <FormRow :columns="2">
    <FormGroup label="Email">
      <BaseInput v-model="email" type="email" />
    </FormGroup>
    <FormGroup label="Phone">
      <BaseInput v-model="phone" type="tel" />
    </FormGroup>
  </FormRow>

  <!-- Textarea with custom rows -->
  <FormGroup label="Description">
    <BaseTextarea 
      v-model="description" 
      :rows="4"
      placeholder="Enter description..."
    />
  </FormGroup>

  <!-- Checkbox -->
  <FormGroup>
    <BaseCheckbox 
      v-model="featured" 
      :true-value="1" 
      :false-value="0"
      label="Featured item"
    />
  </FormGroup>
</template>

<script>
import { BaseInput, BaseTextarea, BaseCheckbox, FormGroup, FormRow } from './form'
// or import individually:
// import BaseInput from './form/BaseInput.vue'

export default {
  components: {
    BaseInput,
    BaseTextarea,
    BaseCheckbox,
    FormGroup,
    FormRow
  },
  // ... rest of component
}
</script>
```

## Benefits

1. **Consistency**: All form fields have the same styling and behavior
2. **Reusability**: Components can be used across different forms
3. **Maintainability**: Changes to styling or behavior only need to be made in one place
4. **Accessibility**: Built-in focus states, proper labeling, and keyboard navigation
5. **Validation**: Built-in error state styling and error message display
6. **Responsive**: FormRow automatically adapts to smaller screen sizes