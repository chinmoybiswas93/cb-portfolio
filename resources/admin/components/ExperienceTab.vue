<template>
  <div>
    <DataTable
      title="Work Experience"
      description="Your professional journey"
      add-button-text="Add Experience"
      :columns="columns"
      :rows="sortedItems"
      empty-text="No experience added yet. Click 'Add Experience' to get started."
      @add-new="openAddModal"
      @edit-row="openEditModal"
      @delete-row="openDeleteConfirm"
      @move-up="moveUp"
      @move-down="moveDown"
    />

    <DataEditModal
      :show="showEditModal"
      :title="isEditing ? 'Edit Experience' : 'Add Experience'"
      :save-button-text="isEditing ? 'Update Experience' : 'Add Experience'"
      :field-layout="fieldLayout"
      :model-value="editFormData"
      @save="handleSave"
      @cancel="showEditModal = false"
    />

    <ConfirmModal
      :show="showDeleteConfirm"
      title="Delete Experience"
      message="Are you sure you want to delete this experience? This action cannot be undone."
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

const EXPERIENCE_DEFAULTS = {
  company: '',
  company_website: '',
  position: '',
  start_date: '',
  end_date: '',
  current: 0,
  description: '',
  skills: ''
}

export default {
  name: 'ExperienceTab',
  components: { DataTable, DataEditModal, ConfirmModal },
  props: {
    experienceData: { type: Array, required: true }
  },
  emits: ['reload-data', 'update-experience-order'],
  setup(props, { emit }) {
    const list = useDataListTab({
      endpoint: 'experience',
      entityName: 'Experience',
      itemDefaults: EXPERIENCE_DEFAULTS,
      orderEvent: 'update-experience-order',
      getItems: () => props.experienceData
    }, emit)

    onMounted(() => list.initializeOrderIndex())

    return { ...list }
  },
  data() {
    return {
      columns: [
        { key: 'company', label: 'Company' },
        { key: 'position', label: 'Position' },
        {
          key: 'start_date',
          label: 'Period',
          formatter: (_val, row) => {
            const start = row.start_date || '—'
            const end = (parseInt(row.current) === 1) ? 'Present' : (row.end_date || '—')
            return `${start} – ${end}`
          }
        },
        { key: 'skills', label: 'Skills', truncate: true }
      ],

      fieldLayout: [
        {
          columns: 2,
          fields: [
            { key: 'company', label: 'Company', type: 'text', placeholder: 'Company Name' },
            { key: 'company_website', label: 'Company Website', type: 'url', placeholder: 'https://company.com' }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'position', label: 'Position', type: 'text', placeholder: 'Job Title' }
          ]
        },
        {
          columns: 3,
          fields: [
            { key: 'start_date', label: 'Start Date', type: 'text', placeholder: '2020' },
            {
              key: 'end_date',
              label: 'End Date',
              type: 'text',
              placeholder: (data) => parseInt(data.current) === 1 ? 'Present' : '2024 or Present',
              disabled: (data) => parseInt(data.current) === 1
            },
            {
              key: 'current',
              label: 'Currently working here',
              type: 'checkbox',
              trueValue: 1,
              falseValue: 0,
              checkboxLabel: 'Currently working here',
              onChange(value, data) {
                if (parseInt(value) === 1) {
                  data.end_date = ''
                }
              }
            }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'description', label: 'Description', type: 'textarea', placeholder: 'Describe your role and achievements', rows: 3 }
          ]
        },
        {
          columns: 1,
          fields: [
            { key: 'skills', label: 'Skills / Technologies', type: 'text', placeholder: 'JavaScript, React, Node.js (comma separated)' }
          ]
        }
      ]
    }
  }
}
</script>
