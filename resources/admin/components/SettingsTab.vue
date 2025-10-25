<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Portfolio Settings</h2>
      <p class="tab-description">Configure your portfolio display settings</p>
    </div>

    <div class="form-section">
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
  </div>
</template>

<script>
export default {
  name: 'SettingsTab',
  props: {
    portfolioEnabled: {
      type: Boolean,
      default: false
    },
    saving: {
      type: Boolean,
      default: false
    }
  },
  emits: ['settings-changed']
}
</script>


<style scoped>
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