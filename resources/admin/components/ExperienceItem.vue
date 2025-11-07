<template>
  <div class="experience-item">
    <div class="item-header">
      <h3>Experience {{ experienceNumber }}</h3>
      <button @click="$emit('remove')" class="remove-btn">
        <span class="btn-icon">×</span>
      </button>
    </div>

    <FormRow :columns="2">
      <FormGroup label="Company">
        <BaseInput 
          v-model="localData.company" 
          type="text"
          placeholder="Company Name" 
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>

      <FormGroup label="Position">
        <BaseInput 
          v-model="localData.position" 
          type="text"
          placeholder="Job Title" 
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>
    </FormRow>

    <FormRow :columns="3">
      <FormGroup label="Start Date">
        <BaseInput 
          v-model="localData.start_date" 
          type="text"
          placeholder="2020" 
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>

      <FormGroup label="End Date">
        <BaseInput 
          v-model="localData.end_date" 
          type="text"
          :placeholder="isCurrentJob ? 'Present' : '2024 or Present'" 
          :disabled="isCurrentJob"
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>

      <FormGroup label="">
        <BaseCheckbox 
          v-model="localData.current" 
          :true-value="1" 
          :false-value="0"
          label="Currently working here"
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>
    </FormRow>

    <FormGroup label="Description">
      <BaseTextarea 
        v-model="localData.description" 
        placeholder="Describe your role and achievements" 
        :rows="3"
        @update:modelValue="$emit('update', localData)" 
      />
    </FormGroup>

    <FormGroup label="Skills/Technologies">
      <BaseInput 
        v-model="localData.skills" 
        type="text"
        placeholder="JavaScript, React, Node.js (comma separated)"
        @update:modelValue="$emit('update', localData)" 
      />
    </FormGroup>
  </div>
</template>

<script>
import BaseInput from './form/BaseInput.vue'
import BaseTextarea from './form/BaseTextarea.vue'
import BaseCheckbox from './form/BaseCheckbox.vue'
import FormGroup from './form/FormGroup.vue'
import FormRow from './form/FormRow.vue'

export default {
  name: 'ExperienceItem',
  components: {
    BaseInput,
    BaseTextarea,
    BaseCheckbox,
    FormGroup,
    FormRow
  },
  props: {
    experience: {
      type: Object,
      required: true
    },
    experienceNumber: {
      type: Number,
      required: true
    }
  },
  emits: ['update', 'remove'],
  data() {
    return {
      localData: { ...this.experience }
    }
  },
  computed: {
    isCurrentJob() {
      // Handle both boolean and numeric values (0/1) from database, including strings
      const current = this.localData.current;
      return current === true || current === 1 || current === '1' || current == 1;
    }
  },
  watch: {
    experience: {
      handler(newData) {
        this.localData = { ...newData };
      },
      deep: true
    },
    'localData.current': {
      handler(newValue) {
        // When "current" is checked, clear the end_date
        if (newValue === 1 || newValue === true || newValue === '1' || newValue == 1) {
          this.localData.end_date = '';
        }
        this.$emit('update', this.localData);
      }
    }
  }
}
</script>


<style scoped>
/* Items */
.experience-item {
  background: #f8f9fa;
  border: 1px solid #e1e5e9;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 10px;
}

.item-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
}

.item-header h3 {
  margin: 0;
  font-size: 16px;
  font-weight: 600;
  color: #1d2327;
}


.remove-btn {
  background: #d63638;
  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
}

.remove-btn:hover {
  background: #b32d2e;
  transform: translateY(-1px);
}

.btn-icon {
  font-size: 14px;
  font-weight: bold;
}
</style>