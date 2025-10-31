<template>
  <div class="cb-portfolio-admin">
    <!-- Minimal Header -->
    <div class="admin-header">
      <div class="header-left">
        <h1>{{ title }}</h1>
      </div>
      <div class="header-right">
        <button @click="saveAll" :disabled="saving" class="save-btn">
          {{ saving ? 'Saving Changes...' : 'Save Changes' }}
        </button>
      </div>
    </div>

    <div class="admin-layout">
      <!-- Left Sidebar Navigation -->
      <div class="sidebar">
        <nav class="nav-menu">
          <div class="nav-item" :class="{ active: activeTab === 'settings' }" @click="setActiveTab('settings')">
            <span class="nav-icon">⚙️</span>
            <span class="nav-text">Settings</span>
          </div>
          <div class="nav-item" :class="{ active: activeTab === 'personal' }" @click="setActiveTab('personal')">
            <span class="nav-icon">👤</span>
            <span class="nav-text">Personal Info</span>
          </div>
          <div class="nav-item" :class="{ active: activeTab === 'contact' }" @click="setActiveTab('contact')">
            <span class="nav-icon">📧</span>
            <span class="nav-text">Contact</span>
          </div>
          <div class="nav-item" :class="{ active: activeTab === 'experience' }" @click="setActiveTab('experience')">
            <span class="nav-icon">💼</span>
            <span class="nav-text">Experience</span>
          </div>
          <div class="nav-item" :class="{ active: activeTab === 'projects' }" @click="setActiveTab('projects')">
            <span class="nav-icon">🚀</span>
            <span class="nav-text">Projects</span>
          </div>
        </nav>
      </div>

      <!-- Main Content Area -->
      <div class="main-content">
        <!-- Settings Tab -->
        <SettingsTab v-if="activeTab === 'settings'" :portfolio-enabled="portfolioEnabled" :saving="saving"
          @settings-changed="handleSettingsChange" />

        <!-- Personal Information Tab -->
        <PersonalInfoTab v-if="activeTab === 'personal'" :portfolio-data="portfolioData"
          @data-changed="handlePortfolioDataChange" />

        <!-- Contact Information Tab -->
        <ContactInfoTab v-if="activeTab === 'contact'" :portfolio-data="portfolioData"
          @data-changed="handlePortfolioDataChange" />

        <!-- Experience Tab -->
        <ExperienceTab v-if="activeTab === 'experience'" :experience-data="experienceData"
          @add-experience="addExperience" @update-experience="updateExperience" @remove-experience="removeExperience" />

        <!-- Projects Tab -->
        <ProjectsTab v-if="activeTab === 'projects'" :projects-data="projectsData" @add-project="addProject"
          @update-project="updateProject" @remove-project="removeProject" />
      </div>
    </div>

    <!-- Floating Toast Notifications -->
    <ToastNotification :show="showToast" :type="toastType" :message="toastMessage" />
  </div>
</template>

<script>
import SettingsTab from './components/SettingsTab.vue'
import PersonalInfoTab from './components/PersonalInfoTab.vue'
import ContactInfoTab from './components/ContactInfoTab.vue'
import ExperienceTab from './components/ExperienceTab.vue'
import ProjectsTab from './components/ProjectsTab.vue'
import ToastNotification from './components/ToastNotification.vue'

export default {
  name: 'App',
  components: {
    SettingsTab,
    PersonalInfoTab,
    ContactInfoTab,
    ExperienceTab,
    ProjectsTab,
    ToastNotification
  },
  data() {
    return {
      title: 'Chinmoy Biswas Portfolio',
      activeTab: 'settings',
      portfolioEnabled: false,
      saving: false,
      success: false,
      error: null,
      showToast: false,
      toastType: 'success',
      toastMessage: '',
      portfolioData: {
        name: '',
        title: '',
        tagline: '',
        about: '',
        email: '',
        phone: '',
        location: '',
        github_url: '',
        linkedin_url: '',
        twitter_url: '',
        website_url: '',
        resume_url: '',
        profile_image: ''
      },
      experienceData: [],
      projectsData: [],
      deletedExperienceIds: [],
      deletedProjectIds: []
    }
  },
  mounted() {
    this.loadSettings();
    this.loadPortfolioData();
    this.loadExperienceData();
    this.loadProjectsData();

    // Restore active tab from localStorage
    const savedTab = localStorage.getItem('cb-portfolio-active-tab');
    if (savedTab && ['settings', 'personal', 'contact', 'experience', 'projects'].includes(savedTab)) {
      this.activeTab = savedTab;
    }
  },
  methods: {
    async loadSettings() {
      console.log(cbPortfolioData);
      try {
        const response = await fetch(`${cbPortfolioData.restUrl}settings`, {
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

    async loadPortfolioData() {
      try {
        const response = await fetch(`${cbPortfolioData.restUrl}portfolio`, {
          headers: {
            'X-WP-Nonce': cbPortfolioData.nonce
          }
        });

        if (response.ok) {
          const data = await response.json();
          if (data && Object.keys(data).length > 0) {
            this.portfolioData = { ...this.portfolioData, ...data };
          }
        }
      } catch (err) {
        console.error('Error loading portfolio data:', err);
      }
    },

    async loadExperienceData() {
      try {
        const response = await fetch(`${cbPortfolioData.restUrl}experience`, {
          headers: {
            'X-WP-Nonce': cbPortfolioData.nonce
          }
        });

        if (response.ok) {
          const data = await response.json();
          this.experienceData = data || [];
          // Reset deleted IDs when loading fresh data
          this.deletedExperienceIds = [];
        }
      } catch (err) {
        console.error('Error loading experience data:', err);
      }
    },

    async loadProjectsData() {
      try {
        const response = await fetch(`${cbPortfolioData.restUrl}projects`, {
          headers: {
            'X-WP-Nonce': cbPortfolioData.nonce
          }
        });

        if (response.ok) {
          const data = await response.json();
          this.projectsData = data || [];
          // Reset deleted IDs when loading fresh data
          this.deletedProjectIds = [];
        }
      } catch (err) {
        console.error('Error loading projects data:', err);
      }
    },

    setActiveTab(tab) {
      this.activeTab = tab;
      // Save active tab to localStorage
      localStorage.setItem('cb-portfolio-active-tab', tab);
    },

    showToastNotification(type, message) {
      this.toastType = type;
      this.toastMessage = message;
      this.showToast = true;

      setTimeout(() => {
        this.showToast = false;
      }, 4000);
    },

    handleSettingsChange(enabled) {
      this.portfolioEnabled = enabled;
      this.saveSettings();
    },

    handlePortfolioDataChange(data) {
      this.portfolioData = { ...data };
    },

    updateExperience(index, updatedData) {
      this.experienceData[index] = { ...updatedData };
    },

    updateProject(index, updatedData) {
      this.projectsData[index] = { ...updatedData };
    },

    async saveSettings() {
      this.saving = true;
      this.success = false;
      this.error = null;

      try {
        const response = await fetch(`${cbPortfolioData.restUrl}settings`, {
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
          this.showToastNotification('success', 'Settings saved successfully!');
        } else {
          const data = await response.json();
          this.showToastNotification('error', data.message || 'Failed to save settings');
        }
      } catch (err) {
        this.showToastNotification('error', 'An error occurred while saving settings');
        console.error('Error saving settings:', err);
      } finally {
        this.saving = false;
      }
    },

    async saveAll() {
      this.saving = true;
      this.success = false;
      this.error = null;

      try {
        // Save portfolio data
        await this.savePortfolioData();

        // Save experience data
        await this.saveExperienceData();

        // Save projects data
        await this.saveProjectsData();

        this.showToastNotification('success', 'All data saved successfully!');
      } catch (err) {
        this.showToastNotification('error', 'An error occurred while saving data');
        console.error('Error saving data:', err);
      } finally {
        this.saving = false;
      }
    },

    async savePortfolioData() {
      const response = await fetch(`${cbPortfolioData.restUrl}portfolio`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': cbPortfolioData.nonce
        },
        body: JSON.stringify(this.portfolioData)
      });

      if (!response.ok) {
        throw new Error('Failed to save portfolio data');
      }
    },

    async saveExperienceData() {
      // First, delete removed experiences
      for (const id of this.deletedExperienceIds) {
        const response = await fetch(`${cbPortfolioData.restUrl}experience/${id}`, {
          method: 'DELETE',
          headers: {
            'X-WP-Nonce': cbPortfolioData.nonce
          }
        });

        if (!response.ok) {
          throw new Error('Failed to delete experience');
        }
      }

      // Clear deleted IDs after successful deletion
      this.deletedExperienceIds = [];

      // Then save/update existing experiences
      for (const exp of this.experienceData) {
        const response = await fetch(`${cbPortfolioData.restUrl}experience`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': cbPortfolioData.nonce
          },
          body: JSON.stringify(exp)
        });

        if (!response.ok) {
          throw new Error('Failed to save experience data');
        }
      }
    },

    async saveProjectsData() {
      // First, delete removed projects
      for (const id of this.deletedProjectIds) {
        const response = await fetch(`${cbPortfolioData.restUrl}projects/${id}`, {
          method: 'DELETE',
          headers: {
            'X-WP-Nonce': cbPortfolioData.nonce
          }
        });

        if (!response.ok) {
          throw new Error('Failed to delete project');
        }
      }

      // Clear deleted IDs after successful deletion
      this.deletedProjectIds = [];

      // Then save/update existing projects
      for (const project of this.projectsData) {
        const response = await fetch(`${cbPortfolioData.restUrl}projects`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': cbPortfolioData.nonce
          },
          body: JSON.stringify(project)
        });

        if (!response.ok) {
          throw new Error('Failed to save projects data');
        }
      }
    },

    addExperience() {
      this.experienceData.unshift({
        company: '',
        position: '',
        start_date: '',
        end_date: '',
        current: false,
        description: '',
        skills: ''
      });
    },

    removeExperience(index) {
      const exp = this.experienceData[index];
      // If it has an ID, track it for deletion
      if (exp.id) {
        this.deletedExperienceIds.push(exp.id);
      }
      this.experienceData.splice(index, 1);
    },

    addProject() {
      this.projectsData.unshift({
        title: '',
        description: '',
        image_url: '',
        live_url: '',
        github_url: '',
        technologies: '',
        featured: false
      });
    },

    removeProject(index) {
      const project = this.projectsData[index];
      // If it has an ID, track it for deletion
      if (project.id) {
        this.deletedProjectIds.push(project.id);
      }
      this.projectsData.splice(index, 1);
    }
  }
}
</script>

<style scoped>
.cb-portfolio-admin {
  height: 100vh;
  display: flex;
  flex-direction: column;
  background: #f8f9fa;
  margin-left: -20px;
}

/* Minimal Header */
.admin-header {
  background: #fff;
  border-bottom: 1px solid #e1e5e9;
  padding: 16px 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  z-index: 100;
}

.header-left h1 {
  margin: 0;
  font-size: 20px;
  font-weight: 600;
  color: #1d2327;
}

.save-btn {
  background: #6237fe;
  color: white;
  border: none;
  padding: 12px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

.save-btn:hover:not(:disabled) {
  background: #135e96;
  transform: translateY(-1px);
}

.save-btn:disabled {
  background: #8c8f94;
  cursor: not-allowed;
  transform: none;
}

/* Main Layout */
.admin-layout {
  display: flex;
  flex: 1;
  overflow: hidden;
}

/* Left Sidebar */
.sidebar {
  width: 240px;
  background: #fff;
  border-right: 1px solid #e1e5e9;
  overflow-y: auto;
}

.nav-menu {
  padding: 16px 0;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 12px 24px;
  cursor: pointer;
  transition: all 0.2s;
  border-left: 3px solid transparent;
}

.nav-item:hover {
  background: #f6f7f7;
}

.nav-item.active {
  background: #f0f6ff;
  border-left-color: #2271b1;
  color: #2271b1;
}

.nav-icon {
  font-size: 16px;
  margin-right: 12px;
  width: 20px;
  text-align: center;
}

.nav-text {
  font-size: 14px;
  font-weight: 500;
}

/* Main Content */
.main-content {
  flex: 1;
  overflow-y: auto;
  padding: 24px;
}

.tab-content {
  max-width: 1200px;
}
</style>


<style>
#cb-portfolio-admin .main-content .tab-header {
  margin-bottom: 24px;
}

#cb-portfolio-admin .main-content .tab-header h2 {
  margin: 0 0 8px 0;
  font-size: 24px;
  font-weight: 600;
  color: #1d2327;
}

#cb-portfolio-admin .main-content .tab-description {
  margin: 0;
  color: #646970;
  font-size: 14px;
}

/* Form Sections */
#cb-portfolio-admin .main-content .form-section {
  background: #fff;
  border-radius: 8px;
  padding: 24px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  border: 1px solid #e1e5e9;
}

/* Form styles are now encapsulated in form components */

#cb-portfolio-admin .main-content .tab-header .add-btn {
  margin-top: 10px;
}

/* Buttons */
#cb-portfolio-admin .main-content .tab-header .add-btn {
  background: #00a32a;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

#cb-portfolio-admin .main-content .tab-header .add-btn:hover {
  background: #008a20;
  transform: translateY(-1px);
}
</style>