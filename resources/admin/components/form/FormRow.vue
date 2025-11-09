<template>
  <div class="form-row" :class="{ [`columns-${columns}`]: columns }">
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'FormRow',
  props: {
    columns: {
      type: [Number, String],
      default: 2,
      validator: (value) => [1, 2, 3, 4].includes(Number(value))
    },
    gap: {
      type: String,
      default: '16px'
    }
  }
}
</script>

<style scoped>
.form-row {
  display: grid;
  gap: v-bind(gap);
}

.form-row.columns-1 {
  grid-template-columns: 1fr;
}

.form-row.columns-2 {
  grid-template-columns: 1fr 1fr;
}

.form-row.columns-3 {
  grid-template-columns: 1fr 1fr 1fr;
}

.form-row.columns-4 {
  grid-template-columns: 1fr 1fr 1fr 1fr;
}

/* Responsive behavior */
@media (max-width: 768px) {
  .form-row.columns-2,
  .form-row.columns-3,
  .form-row.columns-4 {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 1024px) {
  .form-row.columns-3,
  .form-row.columns-4 {
    grid-template-columns: 1fr 1fr;
  }
}
</style>