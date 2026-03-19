<template>
  <div class="cb-portfolio-admin">
    <div class="admin-layout">
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
          <div class="nav-item" :class="{ active: activeTab === 'import-export' }" @click="setActiveTab('import-export')">
            <span class="nav-icon">📁</span>
            <span class="nav-text">Import/Export</span>
          </div>
        </nav>
      </div>

      <div class="main-content">
        <SettingsTab v-if="activeTab === 'settings'" :portfolio-enabled="portfolioEnabled" :footer-text="portfolioData.footer_text"
          @settings-changed="handleSettingsChange" @footer-text-changed="handleFooterTextChange" />

        <PortfolioFormTab v-if="activeTab === 'personal'"
          title="Personal Information"
          description="Tell visitors about yourself and your expertise"
          success-message="Personal info saved successfully!"
          :fields="personalFields"
          :portfolio-data="portfolioData"
          @data-changed="handlePortfolioDataChange" />

        <PortfolioFormTab v-if="activeTab === 'contact'"
          title="Contact Information"
          description="How people can reach you"
          success-message="Contact info saved successfully!"
          :fields="contactFields"
          :portfolio-data="portfolioData"
          @data-changed="handlePortfolioDataChange" />

        <ExperienceTab v-if="activeTab === 'experience'" :experience-data="experienceData"
          @reload-data="loadExperienceData"
          @update-experience-order="handleExperienceOrderUpdate" />

        <ProjectsTab v-if="activeTab === 'projects'" :projects-data="projectsData"
          @reload-data="loadProjectsData"
          @update-projects-order="handleProjectsOrderUpdate" />

        <ImportExportTab v-if="activeTab === 'import-export'" 
          :portfolio-data="portfolioData"
          :experience-data="experienceData"
          :projects-data="projectsData"
          @import-data="handleImportData"
        />
      </div>
    </div>

    <ToastNotification :show="showToast" :type="toastType" :message="toastMessage" />
  </div>
</template>

<script>
import api from './utils/api'
import SettingsTab from './components/SettingsTab.vue'
import PortfolioFormTab from './components/PortfolioFormTab.vue'
import ExperienceTab from './components/ExperienceTab.vue'
import ProjectsTab from './components/ProjectsTab.vue'
import ImportExportTab from './components/ImportExportTab.vue'
import ToastNotification from './components/ToastNotification.vue'

export default {
  name: 'App',
  components: {
    SettingsTab,
    PortfolioFormTab,
    ExperienceTab,
    ProjectsTab,
    ImportExportTab,
    ToastNotification
  },
  provide() {
    return {
      showToast: (type, message) => this.showToastNotification(type, message)
    }
  },
  data() {
    return {
      title: 'Chinmoy Biswas Portfolio',
      activeTab: 'settings',
      portfolioEnabled: false,
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
        profile_image: '',
        footer_text: ''
      },
      experienceData: [],
      projectsData: [],

      personalFields: [
        [
          { key: 'name', label: 'Full Name', type: 'text', placeholder: 'Enter your full name' },
          { key: 'title', label: 'Job Title', type: 'text', placeholder: 'e.g., Front End Engineer' }
        ],
        [
          { key: 'tagline', label: 'Tagline', type: 'textarea', placeholder: 'Brief description of what you do', rows: 2 }
        ],
        [
          { key: 'about', label: 'About', type: 'textarea', placeholder: 'Tell us about yourself, your experience, and what you\'re passionate about', rows: 6, helpText: 'You can use HTML tags like &lt;b&gt;, &lt;span&gt;, and &lt;a&gt; for formatting. Bold text will be styled prominently.' }
        ]
      ],

      contactFields: [
        [
          { key: 'email', label: 'Email', type: 'email', placeholder: 'your.email@example.com' },
          { key: 'phone', label: 'Phone', type: 'tel', placeholder: '+1 (555) 123-4567' }
        ],
        [
          { key: 'location', label: 'Location', type: 'text', placeholder: 'City, State/Country' }
        ],
        [
          { key: 'github_url', label: 'GitHub URL', type: 'url', placeholder: 'https://github.com/username' },
          { key: 'linkedin_url', label: 'LinkedIn URL', type: 'url', placeholder: 'https://linkedin.com/in/username' }
        ],
        [
          { key: 'twitter_url', label: 'Twitter URL', type: 'url', placeholder: 'https://twitter.com/username' },
          { key: 'website_url', label: 'Website URL', type: 'url', placeholder: 'https://yourwebsite.com' }
        ],
        [
          { key: 'resume_url', label: 'Resume URL', type: 'url', placeholder: 'https://yourwebsite.com/resume.pdf' }
        ]
      ]
    }
  },
  mounted() {
    this.loadSettings();
    this.loadPortfolioData();
    this.loadExperienceData();
    this.loadProjectsData();

    // Restore active tab from localStorage
    const savedTab = localStorage.getItem('cb-portfolio-active-tab');
    if (savedTab && ['settings', 'personal', 'contact', 'experience', 'projects', 'import-export'].includes(savedTab)) {
      this.activeTab = savedTab;
    }
  },
  methods: {
    async loadSettings() {
      try {
        const data = await api.get('settings')
        this.portfolioEnabled = data.enabled || false
      } catch (err) {
        console.error('Error loading settings:', err)
      }
    },

    async loadPortfolioData() {
      try {
        const data = await api.get('portfolio')
        if (data && Object.keys(data).length > 0) {
          this.portfolioData = { ...this.portfolioData, ...data }
        }
      } catch (err) {
        console.error('Error loading portfolio data:', err)
      }
    },

    async loadExperienceData() {
      try {
        const data = await api.get('experience')
        this.experienceData = data || []
      } catch (err) {
        console.error('Error loading experience data:', err)
      }
    },

    async loadProjectsData() {
      try {
        const data = await api.get('projects')
        this.projectsData = (data || []).map(project => ({
          ...project,
          featured: parseInt(project.featured) || 0
        }))
      } catch (err) {
        console.error('Error loading projects data:', err)
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
    },

    handlePortfolioDataChange(data) {
      this.portfolioData = { ...data };
    },

    handleFooterTextChange(footerText) {
      this.portfolioData = { ...this.portfolioData, footer_text: footerText };
    },

    handleExperienceOrderUpdate(updatedExperience) {
      // Replace the entire experience data with the updated order
      this.experienceData = [...updatedExperience];
    },

    handleProjectsOrderUpdate(updatedProjects) {
      // Replace the entire projects data with the updated order
      this.projectsData = [...updatedProjects];
    },

    async handleImportData(importedData) {
      try {
        await this.loadPortfolioData();
        await this.loadExperienceData();
        await this.loadProjectsData();
        await this.loadSettings();
        
        this.showToastNotification('success', 'Data imported and refreshed successfully!');
      } catch (error) {
        console.error('Error refreshing imported data:', error);
        this.showToastNotification('error', 'Data was imported but failed to refresh UI. Please reload the page.');
      }
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
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
}

#cb-portfolio-admin .main-content .tab-header .tab-header-info {
  flex: 1;
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

#cb-portfolio-admin .main-content .tab-header .tab-header-actions {
  flex-shrink: 0;
  margin-left: 16px;
}

#cb-portfolio-admin .main-content .tab-header .tab-header-actions .save-btn {
  background: #6237fe;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
}

#cb-portfolio-admin .main-content .tab-header .tab-header-actions .save-btn:hover:not(:disabled) {
  background: #4f2bcc;
  transform: translateY(-1px);
}

#cb-portfolio-admin .main-content .tab-header .tab-header-actions .save-btn:disabled {
  background: #8c8f94;
  cursor: not-allowed;
  transform: none;
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

#cb-portfolio-admin .main-content .tab-header .tab-header-actions .add-btn {
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

#cb-portfolio-admin .main-content .tab-header .tab-header-actions .add-btn:hover {
  background: #008a20;
  transform: translateY(-1px);
}
</style>