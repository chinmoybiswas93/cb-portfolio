<template>
  <div class="project-item">
    <div class="item-header">
      <h3>Project {{ projectNumber }}</h3>
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

    <!-- Two Column Layout for Title/Description and Image Upload -->
    <FormRow :columns="2">
      <div class="left-column">
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
            :rows="6" 
            @update:modelValue="$emit('update', localData)" 
          />
        </FormGroup>
      </div>

      <div class="right-column">
        <FormGroup label="Project Image">
          <MediaUploader 
            v-model="localData.image_url" 
            :alt="localData.title + ' Project Image'"
            @update:modelValue="$emit('update', localData)" 
          />
        </FormGroup>
      </div>
    </FormRow>

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
import MediaUploader from './form/MediaUploader.vue'

export default {
  name: 'ProjectItem',
  components: {
    BaseInput,
    BaseTextarea,
    BaseCheckbox,
    FormGroup,
    FormRow,
    MediaUploader
  },
  props: {
    project: {
      type: Object,
      required: true
    },
    projectNumber: {
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
      localData: { 
        title: '',
        description: '',
        image_url: '',
        live_url: '',
        github_url: '',
        technologies: '',
        featured: 0,
        ...this.project 
      }
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

/* Two Column Layout */
.left-column {
  display: flex;
  flex-direction: column;
  gap: 15px;
  padding-right: 15px;
}

.right-column {
  display: flex;
  flex-direction: column;
  padding-left: 15px;
}

/* Mobile responsive - stack columns */
@media (max-width: 768px) {
  .left-column,
  .right-column {
    padding-left: 0;
    padding-right: 0;
  }
  
  .left-column {
    margin-bottom: 15px;
  }
}
</style>