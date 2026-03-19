<template>
  <div>
    <DataTable
      title="Projects"
      description="Showcase your work"
      add-button-text="Add Project"
      :columns="columns"
      :rows="sortedItems"
      empty-text="No projects added yet. Click 'Add Project' to get started."
      @add-new="openAddModal"
      @edit-row="openEditModal"
      @delete-row="openDeleteConfirm"
      @move-up="moveUp"
      @move-down="moveDown"
    />

    <DataEditModal
      :show="showEditModal"
      :title="isEditing ? 'Edit Project' : 'Add Project'"
      :save-button-text="isEditing ? 'Update Project' : 'Add Project'"
      :field-layout="fieldLayout"
      :model-value="editFormData"
      @save="handleSave"
      @cancel="showEditModal = false"
    />

    <ConfirmModal
      :show="showDeleteConfirm"
      title="Delete Project"
      message="Are you sure you want to delete this project? This action cannot be undone."
      @confirm="handleDelete"
      @cancel="showDeleteConfirm = false"
    />
  </div>
</template>

<script>
import { onMounted } from 'vue'
import { useDataListTab } from '../composables/useDataListTab'
import DataTable from './shared/DataTable.vue'
import DataEditModal from './shared/DataEditModal.vue'
import ConfirmModal from './shared/ConfirmModal.vue'

const PROJECT_DEFAULTS = {
  title: '',
  description: '',
  image_url: '',
  live_url: '',
  github_url: '',
  technologies: '',
  year: '',
  made_at: '',
  featured: 0
}

export default {
  name: 'ProjectsTab',
  components: { DataTable, DataEditModal, ConfirmModal },
  props: {
    projectsData: { type: Array, required: true }
  },
  emits: ['reload-data', 'update-projects-order'],
  setup(props, { emit }) {
    const list = useDataListTab({
      endpoint: 'projects',
      entityName: 'Project',
      itemDefaults: PROJECT_DEFAULTS,
      orderEvent: 'update-projects-order',
      getItems: () => props.projectsData
    }, emit)

    onMounted(() => list.initializeOrderIndex())

    return { ...list }
  },
  data() {
    return {
      columns: [
        { key: 'title', label: 'Title' },
        { key: 'technologies', label: 'Technologies', truncate: true },
        { key: 'year', label: 'Year', width: '70px' },
        { key: 'made_at', label: 'Made At' },
        {
          key: 'featured',
          label: 'Featured',
          width: '80px',
          formatter: (val) => parseInt(val)
            ? '<span style="color:#00a32a;font-weight:600">Yes</span>'
            : '<span style="color:#8c8f94">No</span>'
        }
      ],

      fieldLayout: [
        {
          columns: 1,
          fields: [
            { key: 'title', label: 'Project Title', type: 'text', placeholder: 'Project Name' }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe the project, technologies used, and your role', rows: 4 }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'image_url', label: 'Project Image', type: 'media', alt: 'Project Image' }
          ]
        },
        {
          columns: 2,
          fields: [
            { key: 'year', label: 'Year', type: 'text', placeholder: '2024' },
            { key: 'made_at', label: 'Made At', type: 'text', placeholder: 'Company Name or Organization' }
          ]
        },
        {
          columns: 2,
          fields: [
            { key: 'live_url', label: 'Live URL', type: 'url', placeholder: 'https://project-demo.com' },
            { key: 'github_url', label: 'GitHub URL', type: 'url', placeholder: 'https://github.com/username/project' }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'technologies', label: 'Technologies', type: 'text', placeholder: 'React, Node.js, MongoDB (comma separated)' }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'featured', label: 'Featured Project', type: 'checkbox', trueValue: 1, falseValue: 0, checkboxLabel: 'Featured Project' }
          ]
        }
      ]
    }
  }
}
</script>
