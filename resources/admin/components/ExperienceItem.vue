<template>
  <div class="experience-item">
    <div class="item-header">
      <h3>Experience {{ experienceNumber }}</h3>
      <div class="header-actions">
        <button 
          @click="$emit('move-up')" 
          :disabled="!canMoveUp"
          class="order-btn" 
          title="Move Up"
        >
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 14L12 9L17 14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button 
          @click="$emit('move-down')" 
          :disabled="!canMoveDown"
          class="order-btn" 
          title="Move Down"
        >
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <button @click="$emit('remove')" class="remove-btn" title="Delete">
          <span class="btn-icon">×</span>
        </button>
      </div>
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

      <FormGroup label="Company Website">
        <BaseInput 
          v-model="localData.company_website" 
          type="url"
          placeholder="https://company.com" 
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>
    </FormRow>

    <FormRow :columns="1">
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
    },
    canMoveUp: {
      type: Boolean,
      default: false
    },
    canMoveDown: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update', 'remove', 'move-up', 'move-down'],
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

.header-actions {
  display: flex;
  align-items: center;
  gap: 5px;
}

.order-btn {
  background: #0073aa;
  color: white;
  border: none;
  padding: 4px;
  border-radius: 3px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  width: 24px;
  height: 24px;
  transition: all 0.2s;
}

.order-btn:hover:not(:disabled) {
  background: #005a87;
}

.order-btn:disabled {
  background: #ddd;
  color: #999;
  cursor: not-allowed;
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