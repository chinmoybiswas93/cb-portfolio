<template>
  <div class="experience-item">
    <div class="item-header">
      <h3>Experience {{ experienceNumber }}</h3>
      <button @click="$emit('remove')" class="remove-btn">
        <span class="btn-icon">×</span>
      </button>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Company</label>
        <input type="text" v-model="localData.company" placeholder="Company Name" @input="$emit('update', localData)" />
      </div>

      <div class="form-group">
        <label>Position</label>
        <input type="text" v-model="localData.position" placeholder="Job Title" @input="$emit('update', localData)" />
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label>Start Date</label>
        <input type="text" v-model="localData.start_date" placeholder="2020" @input="$emit('update', localData)" />
      </div>

      <div class="form-group">
        <label>End Date</label>
        <input type="text" v-model="localData.end_date" placeholder="2024 or Present" :disabled="localData.current"
          @input="$emit('update', localData)" />
      </div>

      <div class="form-group checkbox-group">
        <label class="checkbox-label">
          <input type="checkbox" v-model="localData.current" @change="$emit('update', localData)" />
          <span class="checkbox-custom"></span>
          <span>Currently working here</span>
        </label>
      </div>
    </div>

    <div class="form-group">
      <label>Description</label>
      <textarea v-model="localData.description" placeholder="Describe your role and achievements" rows="3"
        @input="$emit('update', localData)"></textarea>
    </div>

    <div class="form-group">
      <label>Skills/Technologies</label>
      <input type="text" v-model="localData.skills" placeholder="JavaScript, React, Node.js (comma separated)"
        @input="$emit('update', localData)" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'ExperienceItem',
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
  watch: {
    experience: {
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