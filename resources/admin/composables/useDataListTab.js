import { ref, computed, inject } from 'vue'
import api from '../utils/api'

export function useDataListTab(config, emit) {
  const showToast = inject('showToast')
  const showEditModal = ref(false)
  const showDeleteConfirm = ref(false)
  const editFormData = ref({ ...config.itemDefaults })
  const editingOriginalIndex = ref(-1)
  const deletingRow = ref(null)
  const saving = ref(false)

  const isEditing = computed(() => editingOriginalIndex.value >= 0)

  function getItems() {
    return config.getItems()
  }

  const sortedItems = computed(() => {
    return [...getItems()].sort((a, b) => (a.order_index || 0) - (b.order_index || 0))
  })

  function initializeOrderIndex() {
    getItems().forEach((item, index) => {
      if (item.order_index === undefined || item.order_index === null) {
        item.order_index = index + 1
      }
    })
  }

  function getOriginalIndex(row) {
    if (row.id) {
      return getItems().findIndex(item => item.id === row.id)
    }
    return getItems().indexOf(row)
  }

  function openAddModal() {
    editingOriginalIndex.value = -1
    editFormData.value = { ...config.itemDefaults }
    showEditModal.value = true
  }

  function openEditModal(row) {
    editingOriginalIndex.value = getOriginalIndex(row)
    editFormData.value = { ...row }
    showEditModal.value = true
  }

  function openDeleteConfirm(row) {
    deletingRow.value = row
    showDeleteConfirm.value = true
  }

  async function handleSave(data) {
    if (saving.value) return
    saving.value = true

    try {
      await api.post(config.endpoint, data)
      showEditModal.value = false
      const action = isEditing.value ? 'updated' : 'added'
      showToast('success', `${config.entityName} ${action} successfully!`)
      emit('reload-data')
    } catch (err) {
      console.error(`Error saving ${config.entityName.toLowerCase()}:`, err)
      showToast('error', `Failed to save ${config.entityName.toLowerCase()}. Please try again.`)
    } finally {
      saving.value = false
    }
  }

  async function handleDelete() {
    const row = deletingRow.value
    showDeleteConfirm.value = false

    if (!row || !row.id) return

    try {
      await api.del(`${config.endpoint}/${row.id}`)
      showToast('success', `${config.entityName} deleted successfully!`)
      emit('reload-data')
    } catch (err) {
      console.error(`Error deleting ${config.entityName.toLowerCase()}:`, err)
      showToast('error', `Failed to delete ${config.entityName.toLowerCase()}. Please try again.`)
    }
  }

  async function moveUp(sortedIndex) {
    if (sortedIndex === 0) return

    const items = [...sortedItems.value]
    const current = items[sortedIndex]
    const previous = items[sortedIndex - 1]

    const tmp = current.order_index || sortedIndex + 1
    current.order_index = previous.order_index || sortedIndex
    previous.order_index = tmp

    await persistOrder([current, previous])
  }

  async function moveDown(sortedIndex) {
    if (sortedIndex >= sortedItems.value.length - 1) return

    const items = [...sortedItems.value]
    const current = items[sortedIndex]
    const next = items[sortedIndex + 1]

    const tmp = current.order_index || sortedIndex + 1
    current.order_index = next.order_index || sortedIndex + 2
    next.order_index = tmp

    await persistOrder([current, next])
  }

  async function persistOrder(itemsToUpdate) {
    try {
      await api.post(`${config.endpoint}/reorder`, {
        items: itemsToUpdate.map(i => ({ id: i.id, order_index: i.order_index }))
      })

      const updated = [...getItems()]
      itemsToUpdate.forEach(item => {
        const idx = updated.findIndex(e => e.id === item.id)
        if (idx !== -1) {
          updated[idx] = { ...updated[idx], order_index: item.order_index }
        }
      })
      emit(config.orderEvent, updated)
    } catch (err) {
      console.error(`Error reordering ${config.entityName.toLowerCase()}:`, err)
      showToast('error', 'Failed to reorder. Please try again.')
    }
  }

  return {
    showEditModal,
    showDeleteConfirm,
    editFormData,
    editingOriginalIndex,
    deletingRow,
    saving,
    isEditing,
    sortedItems,
    initializeOrderIndex,
    openAddModal,
    openEditModal,
    openDeleteConfirm,
    handleSave,
    handleDelete,
    moveUp,
    moveDown
  }
}
