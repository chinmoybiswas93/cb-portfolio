<template>
  <div class="project-item">
    <div class="item-header">
      <h3>Project {{ projectNumber }}</h3>
      <button @click="$emit('remove')" class="remove-btn">
        <span class="btn-icon">×</span>
      </button>
    </div>

    <div class="form-group">
      <label>Project Title</label>
      <input type="text" v-model="localData.title" placeholder="Project Name" @input="$emit('update', localData)" />
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea v-model="localData.description" placeholder="Describe the project, technologies used, and your role"
        rows="4" @input="$emit('update', localData)"></textarea>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Live URL</label>
        <input type="url" v-model="localData.live_url" placeholder="https://project-demo.com"
          @input="$emit('update', localData)" />
      </div>

      <div class="form-group">
        <label>GitHub URL</label>
        <input type="url" v-model="localData.github_url" placeholder="https://github.com/username/project"
          @input="$emit('update', localData)" />
      </div>
    </div>

    <div class="form-group">
      <label>Technologies</label>
      <input type="text" v-model="localData.technologies" placeholder="React, Node.js, MongoDB (comma separated)"
        @input="$emit('update', localData)" />
    </div>

    <div class="form-group checkbox-group">
      <label class="checkbox-label">
        <input type="checkbox" v-model="localData.featured" @change="$emit('update', localData)" />
        <span class="checkbox-custom"></span>
        <span>Featured Project</span>
      </label>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProjectItem',
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