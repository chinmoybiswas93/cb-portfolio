<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Import/Export Portfolio Data</h2>
      <p class="tab-description">Export your portfolio data to backup or transfer to another site. Import data will replace all existing content.</p>
    </div>

    <!-- Export Section -->
    <div class="form-section export-section">
      <div class="section-header">
        <h3>📤 Export Data</h3>
        <p>Download all your portfolio data as a JSON file for backup or migration.</p>
      </div>
      
      <div class="export-controls">
        <div class="export-options">
          <label class="checkbox-option">
            <input type="checkbox" v-model="exportOptions.portfolio" />
            <span>Portfolio Information (Personal Info, Contact)</span>
          </label>
          <label class="checkbox-option">
            <input type="checkbox" v-model="exportOptions.experience" />
            <span>Experience Data ({{ experienceData.length }} items)</span>
          </label>
          <label class="checkbox-option">
            <input type="checkbox" v-model="exportOptions.projects" />
            <span>Projects Data ({{ projectsData.length }} items)</span>
          </label>
          <label class="checkbox-option">
            <input type="checkbox" v-model="exportOptions.settings" />
            <span>Settings & Configuration</span>
          </label>
        </div>
        
        <div class="export-actions">
          <button @click="selectAll" class="btn-secondary">Select All</button>
          <button @click="exportData" :disabled="!hasSelection" class="btn-primary">
            <span class="btn-icon">💾</span>
            Export & Download
          </button>
        </div>
      </div>

      <div v-if="exportPreview" class="export-preview">
        <h4>Export Preview</h4>
        <div class="preview-stats">
          <div class="stat-item">
            <span class="stat-label">File Size:</span>
            <span class="stat-value">{{ formatFileSize(exportPreview.size) }}</span>
          </div>
          <div class="stat-item">
            <span class="stat-label">Created:</span>
            <span class="stat-value">{{ exportPreview.timestamp }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Import Section -->
    <div class="form-section import-section">
      <div class="section-header">
        <h3>📥 Import Data</h3>
        <p class="warning-text">⚠️ <strong>Warning:</strong> Importing will replace ALL existing data. Please backup your data first!</p>
      </div>

      <div class="import-controls">
        <div class="file-upload-area" :class="{ 'drag-over': isDragOver }" 
             @dragenter.prevent="handleDragEnter"
             @dragover.prevent="handleDragOver"
             @dragleave.prevent="handleDragLeave"
             @drop.prevent="handleFileDrop">
          <div v-if="!selectedFile" class="upload-prompt">
            <div class="upload-icon">📁</div>
            <p class="upload-text">
              <strong>Drop your JSON file here</strong> or 
              <label for="import-file" class="file-label">choose a file</label>
            </p>
            <p class="upload-hint">Only .json files are supported</p>
            <input 
              type="file" 
              id="import-file" 
              accept=".json,application/json" 
              @change="handleFileSelect" 
              style="display: none;"
            />
          </div>

          <div v-else class="file-selected">
            <div class="file-info">
              <span class="file-icon">📄</span>
              <div class="file-details">
                <div class="file-name">{{ selectedFile.name }}</div>
                <div class="file-size">{{ formatFileSize(selectedFile.size) }}</div>
              </div>
              <button @click="clearSelectedFile" class="btn-remove">×</button>
            </div>
          </div>
        </div>

        <div v-if="importPreview" class="import-preview">
          <h4>Import Preview</h4>
          <div class="preview-content">
            <div class="preview-item" v-if="importPreview.portfolio">
              <span class="preview-icon">👤</span>
              <span>Portfolio Information</span>
            </div>
            <div class="preview-item" v-if="importPreview.experience">
              <span class="preview-icon">💼</span>
              <span>{{ importPreview.experience.length }} Experience Items</span>
            </div>
            <div class="preview-item" v-if="importPreview.projects">
              <span class="preview-icon">🚀</span>
              <span>{{ importPreview.projects.length }} Project Items</span>
            </div>
            <div class="preview-item" v-if="importPreview.settings">
              <span class="preview-icon">⚙️</span>
              <span>Settings & Configuration</span>
            </div>
          </div>
        </div>

        <div class="import-actions">
          <button @click="clearSelectedFile" v-if="selectedFile" class="btn-secondary">Cancel</button>
          <button @click="importData" :disabled="!selectedFile || importing" class="btn-danger">
            <span v-if="importing">⏳ Importing...</span>
            <span v-else>
              <span class="btn-icon">⚠️</span>
              Replace All Data & Import
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ImportExportTab',
  props: {
    portfolioData: {
      type: Object,
      required: true
    },
    experienceData: {
      type: Array,
      required: true
    },
    projectsData: {
      type: Array,
      required: true
    }
  },
  emits: ['import-data', 'show-toast'],
  data() {
    return {
      exportOptions: {
        portfolio: true,
        experience: true,
        projects: true,
        settings: true
      },
      exportPreview: null,
      selectedFile: null,
      importPreview: null,
      importing: false,
      isDragOver: false
    }
  },
  computed: {
    hasSelection() {
      return Object.values(this.exportOptions).some(selected => selected)
    }
  },
  methods: {
    selectAll() {
      const selectAll = !this.hasSelection
      this.exportOptions = {
        portfolio: selectAll,
        experience: selectAll,
        projects: selectAll,
        settings: selectAll
      }
    },

    async exportData() {
      try {
        const exportData = {
          metadata: {
            exportDate: new Date().toISOString(),
            version: '1.0',
            source: 'CB Portfolio Plugin'
          }
        }

        if (this.exportOptions.portfolio) {
          exportData.portfolio = this.portfolioData
        }

        if (this.exportOptions.experience) {
          exportData.experience = this.experienceData
        }

        if (this.exportOptions.projects) {
          exportData.projects = this.projectsData
        }

        if (this.exportOptions.settings) {
          // We'll need to get settings from the API
          const response = await fetch(`${cbPortfolioData.restUrl}settings`, {
            headers: {
              'X-WP-Nonce': cbPortfolioData.nonce
            }
          })
          
          if (response.ok) {
            const settings = await response.json()
            exportData.settings = settings
          }
        }

        // Create and download file
        const jsonString = JSON.stringify(exportData, null, 2)
        const blob = new Blob([jsonString], { type: 'application/json' })
        
        this.exportPreview = {
          size: blob.size,
          timestamp: new Date().toLocaleString()
        }

        // Create download
        const url = URL.createObjectURL(blob)
        const link = document.createElement('a')
        link.href = url
        link.download = `portfolio-export-${new Date().toISOString().split('T')[0]}.json`
        document.body.appendChild(link)
        link.click()
        document.body.removeChild(link)
        URL.revokeObjectURL(url)

        this.$emit('show-toast', 'success', 'Portfolio data exported successfully!')
      } catch (error) {
        console.error('Export error:', error)
        this.$emit('show-toast', 'error', 'Failed to export data. Please try again.')
      }
    },

    handleDragEnter(e) {
      this.isDragOver = true
    },

    handleDragOver(e) {
      this.isDragOver = true
    },

    handleDragLeave(e) {
      if (!e.relatedTarget || !e.currentTarget.contains(e.relatedTarget)) {
        this.isDragOver = false
      }
    },

    handleFileDrop(e) {
      this.isDragOver = false
      const files = e.dataTransfer.files
      if (files.length > 0) {
        this.processFile(files[0])
      }
    },

    handleFileSelect(e) {
      const file = e.target.files[0]
      if (file) {
        this.processFile(file)
      }
    },

    async processFile(file) {
      if (!file.type.includes('json')) {
        this.$emit('show-toast', 'error', 'Please select a JSON file.')
        return
      }

      this.selectedFile = file

      try {
        const text = await file.text()
        const data = JSON.parse(text)
        this.importPreview = {
          portfolio: data.portfolio || null,
          experience: data.experience || null,
          projects: data.projects || null,
          settings: data.settings || null
        }
      } catch (error) {
        console.error('File processing error:', error)
        this.$emit('show-toast', 'error', 'Invalid JSON file format.')
        this.clearSelectedFile()
      }
    },

    clearSelectedFile() {
      this.selectedFile = null
      this.importPreview = null
      // Reset file input
      const fileInput = document.getElementById('import-file')
      if (fileInput) {
        fileInput.value = ''
      }
    },

    async importData() {
      if (!this.selectedFile || !this.importPreview) return

      this.importing = true

      try {
        const text = await this.selectedFile.text()
        const importedData = JSON.parse(text)

        // Call the backend import API
        const response = await fetch(`${cbPortfolioData.restUrl}import`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': cbPortfolioData.nonce
          },
          body: text
        })

        if (!response.ok) {
          const errorData = await response.json()
          throw new Error(errorData.message || 'Import failed')
        }

        const result = await response.json()
        
        // Emit the import data to parent component to update UI
        this.$emit('import-data', importedData)
        
        // Clear the selected file after successful import
        this.clearSelectedFile()
        
        this.$emit('show-toast', 'success', 'Data imported and saved to database successfully!')
      } catch (error) {
        console.error('Import error:', error)
        this.$emit('show-toast', 'error', `Failed to import data: ${error.message}`)
      } finally {
        this.importing = false
      }
    },

    formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes'
      const k = 1024
      const sizes = ['Bytes', 'KB', 'MB', 'GB']
      const i = Math.floor(Math.log(bytes) / Math.log(k))
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
    }
  }
}
</script>

<style scoped>
.tab-content {
  max-width: 1000px;
}

.section-header {
  margin-bottom: 20px;
}

.section-header h3 {
  margin: 0 0 8px 0;
  font-size: 18px;
  font-weight: 600;
  color: #1d2327;
}

.section-header p {
  margin: 0;
  color: #646970;
  line-height: 1.5;
}

.warning-text {
  padding: 12px;
  background: #fff3cd;
  border: 1px solid #ffeaa7;
  border-radius: 6px;
  color: #856404;
}

/* Export Section */
.export-section {
  margin-bottom: 40px;
}

.export-controls {
  display: flex;
  gap: 30px;
  align-items: flex-start;
}

.export-options {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.checkbox-option {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  padding: 8px 0;
}

.checkbox-option input[type="checkbox"] {
  margin: 0;
}

.export-actions {
  display: flex;
  flex-direction: column;
  gap: 10px;
  min-width: 180px;
}

.export-preview {
  margin-top: 20px;
  padding: 16px;
  background: #f8f9fa;
  border-radius: 6px;
  border: 1px solid #e1e5e9;
}

.export-preview h4 {
  margin: 0 0 12px 0;
  font-size: 14px;
  font-weight: 600;
}

.preview-stats {
  display: flex;
  gap: 20px;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-label {
  font-size: 12px;
  color: #646970;
}

.stat-value {
  font-size: 14px;
  font-weight: 500;
  color: #1d2327;
}

/* Import Section */
.import-section {
  margin-top: 20px;
}

.file-upload-area {
  border: 2px dashed #c3c4c7;
  border-radius: 8px;
  padding: 40px;
  text-align: center;
  transition: all 0.2s ease;
  cursor: pointer;
}

.file-upload-area:hover {
  border-color: #2271b1;
  background: #f6f7f7;
}

.file-upload-area.drag-over {
  border-color: #2271b1;
  background: #f0f6ff;
}

.upload-prompt .upload-icon {
  font-size: 48px;
  margin-bottom: 16px;
}

.upload-text {
  margin: 0 0 8px 0;
  font-size: 16px;
  color: #1d2327;
}

.upload-hint {
  margin: 0;
  font-size: 14px;
  color: #646970;
}

.file-label {
  color: #2271b1;
  text-decoration: underline;
  cursor: pointer;
}

.file-selected {
  padding: 20px;
}

.file-info {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px;
  background: #f8f9fa;
  border-radius: 6px;
}

.file-icon {
  font-size: 24px;
}

.file-details {
  flex: 1;
}

.file-name {
  font-weight: 500;
  color: #1d2327;
}

.file-size {
  font-size: 12px;
  color: #646970;
}

.btn-remove {
  background: #d63638;
  color: white;
  border: none;
  border-radius: 50%;
  width: 24px;
  height: 24px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.btn-remove:hover {
  background: #b32d2e;
}

.import-preview {
  margin: 20px 0;
  padding: 16px;
  background: #f8f9fa;
  border-radius: 6px;
  border: 1px solid #e1e5e9;
}

.import-preview h4 {
  margin: 0 0 12px 0;
  font-size: 14px;
  font-weight: 600;
}

.preview-content {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.preview-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 14px;
}

.preview-icon {
  font-size: 16px;
}

.import-actions {
  display: flex;
  gap: 12px;
  justify-content: flex-end;
  margin-top: 20px;
}

/* Buttons */
.btn-primary, .btn-secondary, .btn-danger {
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  border: none;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.btn-primary {
  background: #2271b1;
  color: white;
}

.btn-primary:hover:not(:disabled) {
  background: #135e96;
}

.btn-primary:disabled {
  background: #8c8f94;
  cursor: not-allowed;
}

.btn-secondary {
  background: #f6f7f7;
  color: #1d2327;
  border: 1px solid #c3c4c7;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.btn-danger {
  background: #d63638;
  color: white;
}

.btn-danger:hover:not(:disabled) {
  background: #b32d2e;
}

.btn-danger:disabled {
  background: #8c8f94;
  cursor: not-allowed;
}

.btn-icon {
  font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
  .export-controls {
    flex-direction: column;
    gap: 20px;
  }
  
  .preview-stats {
    flex-direction: column;
    gap: 12px;
  }
  
  .import-actions {
    flex-direction: column;
  }
}
</style>