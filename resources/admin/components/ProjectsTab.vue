<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Projects</h2>
      <p class="tab-description">Showcase your work</p>
      <button @click="$emit('add-project')" class="add-btn">
        <span class="btn-icon">+</span>
        Add Project
      </button>
    </div>

    <div class="form-section">
      <ProjectItem v-for="(project, index) in projectsData" :key="project.id || index" :project="project"
        :project-number="projectsData.length - index"
        @update="(updatedData) => $emit('update-project', index, updatedData)"
        @remove="$emit('remove-project', index)" />
    </div>
  </div>
</template>

<script>
import ProjectItem from './ProjectItem.vue'

export default {
  name: 'ProjectsTab',
  components: {
    ProjectItem
  },
  props: {
    projectsData: {
      type: Array,
      required: true
    }
  },
  emits: ['add-project', 'update-project', 'remove-project']
}
</script>
