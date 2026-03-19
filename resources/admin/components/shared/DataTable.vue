<template>
  <div class="tab-content">
    <div class="tab-header">
      <div class="tab-header-info">
        <h2>{{ title }}</h2>
        <p class="tab-description">{{ description }}</p>
      </div>
      <div class="tab-header-actions">
        <button @click="$emit('add-new')" class="add-btn" type="button">
          <span class="btn-icon">+</span>
          {{ addButtonText }}
        </button>
      </div>
    </div>

    <div class="form-section">
      <div v-if="rows.length === 0" class="dt-empty-state">
        <p>{{ emptyText }}</p>
      </div>

      <div v-else class="dt-table-wrapper">
        <table class="dt-table">
          <thead>
            <tr>
              <th class="dt-col-index">#</th>
              <th
                v-for="col in columns"
                :key="col.key"
                :style="col.width ? { width: col.width } : {}"
              >
                {{ col.label }}
              </th>
              <th class="dt-col-actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(row, index) in rows"
              :key="row[rowKey] || `row-${index}`"
              class="dt-row"
            >
              <td class="dt-col-index">{{ index + 1 }}</td>
              <td
                v-for="col in columns"
                :key="col.key"
                :class="{ 'dt-cell-truncate': col.truncate }"
              >
                <span v-if="col.formatter" v-html="col.formatter(row[col.key], row)"></span>
                <span v-else>{{ row[col.key] || '—' }}</span>
              </td>
              <td class="dt-col-actions">
                <div class="dt-action-buttons">
                  <button
                    @click="$emit('move-up', index)"
                    :disabled="index === 0"
                    class="dt-action-btn dt-move-btn"
                    title="Move Up"
                    type="button"
                  >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 15l-6-6-6 6"/></svg>
                  </button>
                  <button
                    @click="$emit('move-down', index)"
                    :disabled="index === rows.length - 1"
                    class="dt-action-btn dt-move-btn"
                    title="Move Down"
                    type="button"
                  >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
                  </button>
                  <button
                    @click="$emit('edit-row', row, index)"
                    class="dt-action-btn dt-edit-btn"
                    title="Edit"
                    type="button"
                  >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button
                    @click="$emit('delete-row', row, index)"
                    class="dt-action-btn dt-delete-btn"
                    title="Delete"
                    type="button"
                  >
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'DataTable',
  props: {
    title: { type: String, required: true },
    description: { type: String, default: '' },
    addButtonText: { type: String, default: 'Add New' },
    columns: { type: Array, required: true },
    rows: { type: Array, required: true },
    rowKey: { type: String, default: 'id' },
    emptyText: { type: String, default: 'No items yet. Click the button above to add one.' }
  },
  emits: ['add-new', 'edit-row', 'delete-row', 'move-up', 'move-down']
}
</script>

<style scoped>
.dt-empty-state {
  text-align: center;
  padding: 48px 24px;
  color: #646970;
}

.dt-empty-state p {
  margin: 8px 0 0;
  font-size: 14px;
}

.dt-table-wrapper {
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.dt-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.dt-table thead th {
  text-align: left;
  padding: 10px 14px;
  font-weight: 600;
  font-size: 13px;
  color: #1d2327;
  background: #f8f9fa;
  border-bottom: 2px solid #e1e5e9;
  white-space: nowrap;
}

.dt-table tbody td {
  padding: 10px 14px;
  border-bottom: 1px solid #f0f0f1;
  color: #1d2327;
  vertical-align: middle;
}

.dt-row {
  transition: background-color 0.15s;
}

.dt-row:hover {
  background-color: #f6f7f7;
}

.dt-col-index {
  width: 44px;
  text-align: center;
  color: #8c8f94;
  font-weight: 500;
}

.dt-col-actions {
  width: 160px;
  text-align: right;
}

.dt-cell-truncate {
  max-width: 200px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.dt-action-buttons {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 4px;
}

.dt-action-btn {
  background: none;
  border: 1px solid #dcdcde;
  border-radius: 4px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 30px;
  height: 30px;
  padding: 0;
  color: #50575e;
  transition: all 0.15s;
}

.dt-action-btn:hover:not(:disabled) {
  border-color: #b4b9be;
  background: #f0f0f1;
}

.dt-action-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

.dt-move-btn:hover:not(:disabled) {
  color: #2271b1;
  border-color: #2271b1;
  background: #f0f6fc;
}

.dt-edit-btn:hover {
  color: #2271b1;
  border-color: #2271b1;
  background: #f0f6fc;
}

.dt-delete-btn:hover {
  color: #d63638;
  border-color: #d63638;
  background: #fcf0f1;
}

@media (max-width: 768px) {
  .dt-table thead th,
  .dt-table tbody td {
    padding: 8px 10px;
    font-size: 13px;
  }
}
</style>
