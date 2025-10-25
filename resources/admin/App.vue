<template>
  <div class="cb-portfolio-admin">
    <h1>{{ title }}</h1>
    
    <div class="settings-section">
      <h2>Portfolio Settings</h2>
      
      <div class="setting-item">
        <label class="toggle-label">
          <input 
            type="checkbox" 
            v-model="portfolioEnabled" 
            @change="saveSettings"
            :disabled="saving"
          />
          <span class="toggle-text">Turn on portfolio</span>
        </label>
        <p class="description">When enabled, the portfolio will take control of your website homepage.</p>
      </div>
      
      <div v-if="saving" class="notice notice-info">
        Saving...
      </div>
      
      <div v-if="success" class="notice notice-success">
        Settings saved successfully!
      </div>
      
      <div v-if="error" class="notice notice-error">
        {{ error }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      title: 'Chinmoy Biswas Portfolio',
      portfolioEnabled: false,
      saving: false,
      success: false,
      error: null
    }
  },
  mounted() {
    this.loadSettings();
  },
  methods: {
    async loadSettings() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/settings', {
          headers: {
            'X-WP-Nonce': cbPortfolioData.nonce
          }
        });
        
        if (response.ok) {
          const data = await response.json();
          this.portfolioEnabled = data.enabled || false;
        }
      } catch (err) {
        console.error('Error loading settings:', err);
      }
    },
    
    async saveSettings() {
      this.saving = true;
      this.success = false;
      this.error = null;
      
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/settings', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': cbPortfolioData.nonce
          },
          body: JSON.stringify({
            enabled: this.portfolioEnabled
          })
        });
        
        if (response.ok) {
          this.success = true;
          setTimeout(() => {
            this.success = false;
          }, 3000);
        } else {
          const data = await response.json();
          this.error = data.message || 'Failed to save settings';
        }
      } catch (err) {
        this.error = 'An error occurred while saving settings';
        console.error('Error saving settings:', err);
      } finally {
        this.saving = false;
      }
    }
  }
}
</script>

<style scoped>
.cb-portfolio-admin {
  padding: 10px 20px 20px 0;
}

h1 {
  margin-bottom: 20px;
}

.settings-section {
  background: #fff;
  padding: 20px;
  border: 1px solid #ccd0d4;
  box-shadow: 0 1px 1px rgba(0,0,0,.04);
}

.setting-item {
  margin-bottom: 20px;
}

.toggle-label {
  display: flex;
  align-items: center;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.toggle-label input[type="checkbox"] {
  width: 20px;
  height: 20px;
  margin-right: 10px;
  cursor: pointer;
}

.toggle-text {
  color: #1d2327;
}

.description {
  margin: 10px 0 0 30px;
  color: #646970;
  font-size: 13px;
  font-style: italic;
}

.notice {
  padding: 10px;
  margin-top: 15px;
  border-radius: 4px;
}

.notice-info {
  background: #d1ecf1;
  border-left: 4px solid #0c5460;
  color: #0c5460;
}

.notice-success {
  background: #d4edda;
  border-left: 4px solid #155724;
  color: #155724;
}

.notice-error {
  background: #f8d7da;
  border-left: 4px solid #721c24;
  color: #721c24;
}
</style>