<template>
  <div class="tab-content">
    <div class="tab-header">
      <div class="tab-header-info">
        <h2>Portfolio Settings</h2>
        <p class="tab-description">Configure your portfolio display settings</p>
      </div>
      <div class="tab-header-actions">
        <button @click="saveSettings" :disabled="saving" class="save-btn" type="button">
          {{ saving ? 'Saving...' : 'Save Changes' }}
        </button>
      </div>
    </div>

    <div class="form-section">
      <h3 class="section-title">Display Settings</h3>
      
      <div class="setting-item">
        <label class="toggle-label">
          <input type="checkbox" :checked="portfolioEnabled" @change="$emit('settings-changed', $event.target.checked)"
            :disabled="saving" />
          <span class="toggle-slider"></span>
          <span class="toggle-text">Enable Portfolio</span>
        </label>
        <p class="description">When enabled, the portfolio will take control of your website homepage.</p>
      </div>
    </div>

    <div class="form-section">
      <h3 class="section-title">Footer Settings</h3>
      
      <FormGroup label="Footer Text">
        <BaseTextarea 
          v-model="localFooterText" 
          placeholder="Loosely designed in Figma and coded in Visual Studio Code by yours truly. Built with Next.js and Tailwind CSS, deployed with Vercel. All text is set in the Inter typeface."
          :rows="4" 
          @update:modelValue="$emit('footer-text-changed', $event)" 
        />
        <p class="field-description">This text will appear at the bottom of your portfolio. You can use HTML tags like &lt;b&gt;, &lt;span&gt;, and &lt;a&gt; for formatting. Bold text will be styled prominently.</p>
      </FormGroup>
    </div>
  </div>
</template>

<script>
import api from '../utils/api'
import BaseTextarea from './form/BaseTextarea.vue'
import FormGroup from './form/FormGroup.vue'

export default {
  name: 'SettingsTab',
  components: {
    BaseTextarea,
    FormGroup
  },
  props: {
    portfolioEnabled: {
      type: Boolean,
      default: false
    },
    footerText: {
      type: String,
      default: ''
    }
  },
  inject: ['showToast'],
  emits: ['settings-changed', 'footer-text-changed'],
  data() {
    return {
      localFooterText: this.footerText,
      saving: false
    }
  },
  watch: {
    footerText: {
      handler(newValue) {
        this.localFooterText = newValue;
      },
      immediate: true
    }
  },
  methods: {
    async saveSettings() {
      this.saving = true
      try {
        await Promise.all([
          api.post('settings', { enabled: this.portfolioEnabled }),
          api.post('portfolio', { footer_text: this.localFooterText })
        ])
        this.showToast('success', 'Settings saved successfully!')
      } catch (err) {
        console.error('Error saving settings:', err)
        this.showToast('error', 'Failed to save settings')
      } finally {
        this.saving = false
      }
    }
  }
}
</script>


<style scoped>
/* Section Styling */
.form-section {
  margin-bottom: 30px;
  padding-bottom: 20px;
  border-bottom: 1px solid #e1e5e9;
}

.form-section:last-child {
  border-bottom: none;
}

.section-title {
  margin: 0 0 20px 0;
  font-size: 16px;
  font-weight: 600;
  color: #1d2327;
}

.setting-item {
  margin-bottom: 20px;
}

.field-description {
  margin: 8px 0 0 0;
  color: #646970;
  font-size: 13px;
  line-height: 1.5;
  font-style: italic;
}

/* Toggle Switch */
.toggle-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
}

.toggle-label input[type="checkbox"] {
  display: none;
}

.toggle-slider {
  width: 44px;
  height: 24px;
  background: #dcdcde;
  border-radius: 12px;
  position: relative;
  margin-right: 12px;
  transition: background 0.2s;
}

.toggle-slider::before {
  content: '';
  position: absolute;
  width: 20px;
  height: 20px;
  background: #fff;
  border-radius: 50%;
  top: 2px;
  left: 2px;
  transition: transform 0.2s;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.toggle-label input[type="checkbox"]:checked+.toggle-slider {
  background: #2271b1;
}

.toggle-label input[type="checkbox"]:checked+.toggle-slider::before {
  transform: translateX(20px);
}

.toggle-text {
  color: #1d2327;
}

.description {
  margin: 8px 0 0 56px;
  color: #646970;
  font-size: 13px;
  line-height: 1.4;
}
</style>