<template>
  <div class="project-item">
    <div class="item-header">
      <h3>Project {{ projectNumber }}</h3>
      <button @click="$emit('remove')" class="remove-btn">
        <span class="btn-icon">×</span>
      </button>
    </div>

    <FormGroup label="Project Title">
      <BaseInput 
        v-model="localData.title" 
        type="text"
        placeholder="Project Name" 
        @update:modelValue="$emit('update', localData)" 
      />
    </FormGroup>

    <FormGroup label="Description">
      <BaseTextarea 
        v-model="localData.description" 
        placeholder="Describe the project, technologies used, and your role"
        :rows="4" 
        @update:modelValue="$emit('update', localData)" 
      />
    </FormGroup>

    <FormRow :columns="2">
      <FormGroup label="Live URL">
        <BaseInput 
          v-model="localData.live_url" 
          type="url"
          placeholder="https://project-demo.com"
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>

      <FormGroup label="GitHub URL">
        <BaseInput 
          v-model="localData.github_url" 
          type="url"
          placeholder="https://github.com/username/project"
          @update:modelValue="$emit('update', localData)" 
        />
      </FormGroup>
    </FormRow>

    <FormGroup label="Technologies">
      <BaseInput 
        v-model="localData.technologies" 
        type="text"
        placeholder="React, Node.js, MongoDB (comma separated)"
        @update:modelValue="$emit('update', localData)" 
      />
    </FormGroup>

    <FormGroup label="">
      <BaseCheckbox 
        v-model="localData.featured" 
        :true-value="1" 
        :false-value="0"
        label="Featured Project"
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
  name: 'ProjectItem',
  components: {
    BaseInput,
    BaseTextarea,
    BaseCheckbox,
    FormGroup,
    FormRow
  },
  props: {
    project: {
      type: Object,
      required: true
    },
    projectNumber: {
      type: Number,
      required: true
    }
  },
  emits: ['update', 'remove'],
  data() {
    return {
      localData: { ...this.project }
    }
  },
  watch: {
    project: {
      handler(newData) {
        this.localData = { ...newData };
      },
      deep: true
    }
  }
}
</script>

<style scoped>
/* Items */
.project-item {
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