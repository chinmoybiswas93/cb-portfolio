<template>
  <div class="projects-page">
    <div class="projects-container">
      <header class="projects-header">
        <div class="projects-header-top">
          <a href="/" class="back-link" aria-label="Back to home">
            <span class="back-arrow">→</span>
          </a>
          <a v-if="portfolioData && portfolioData.name" href="/" class="projects-name">{{ portfolioData.name }}</a>
        </div>
        <h1 class="projects-title">All Projects</h1>
      </header>

      <div v-if="isLoading" class="loading-state">
        <p>Loading projects...</p>
      </div>

      <div v-else-if="projects.length === 0" class="empty-state">
        <p>No projects available yet.</p>
      </div>

      <div v-else class="projects-table-wrapper">
        <table class="projects-table">
          <thead>
            <tr>
              <th class="col-year">Year</th>
              <th class="col-project">Project</th>
              <th class="col-made-at desktop-only">Made at</th>
              <th class="col-built-with desktop-only">Built with</th>
              <th class="col-link desktop-only">Link</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="project in sortedProjects" :key="project.id" class="project-row">
              <td class="col-year">
                <span class="year-text">{{ getProjectYear(project) }}</span>
              </td>
              <td class="col-project">
                <div class="project-content">
                  <!-- Desktop: Just title, no link -->
                  <span class="project-name desktop-only">{{ project.title }}</span>
                  <!-- Mobile: Link integrated with title -->
                  <a 
                    v-if="project.live_url || project.github_url"
                    :href="project.live_url || project.github_url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="project-name project-name-link mobile-only"
                  >
                    {{ project.title }}
                    <span class="external-icon">↗</span>
                  </a>
                  <!-- Mobile fallback if no link -->
                  <span v-if="!(project.live_url || project.github_url)" class="project-name mobile-only">{{ project.title }}</span>
                </div>
              </td>
              <td class="col-made-at desktop-only">
                <span class="made-at-text">{{ getMadeAt(project) }}</span>
              </td>
              <td class="col-built-with desktop-only">
                <div class="tech-tags">
                  <span 
                    v-for="(tech, index) in getTechnologies(project)" 
                    :key="index" 
                    class="tech-tag"
                  >
                    {{ tech }}
                  </span>
                </div>
              </td>
              <td class="col-link desktop-only">
                <div class="project-links">
                  <a 
                    v-if="project.live_url" 
                    :href="project.live_url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="project-link"
                  >
                    {{ getDomainFromUrl(project.live_url) }}
                    <span class="external-icon">↗</span>
                  </a>
                  <a 
                    v-else-if="project.github_url" 
                    :href="project.github_url" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="project-link"
                  >
                    {{ getDomainFromUrl(project.github_url) }}
                    <span class="external-icon">↗</span>
                  </a>
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
  name: 'ProjectsApp',
  data() {
    return {
      portfolioData: null,
      projects: [],
      isLoading: false
    };
  },
  computed: {
    sortedProjects() {
      // Sort by order_index ascending, then by created_at descending
      return [...this.projects].sort((a, b) => {
        const orderA = a.order_index || 999;
        const orderB = b.order_index || 999;
        if (orderA !== orderB) {
          return orderA - orderB;
        }
        // If order_index is same, sort by created_at descending
        const dateA = new Date(a.created_at || 0);
        const dateB = new Date(b.created_at || 0);
        return dateB - dateA;
      });
    }
  },
  mounted() {
    // Check if data is available from server-side rendering
    if (typeof cbPortfolioData !== 'undefined') {
      this.portfolioData = cbPortfolioData;
    } else {
      this.loadPortfolioData();
    }

    if (typeof cbProjectsData !== 'undefined') {
      this.projects = cbProjectsData || [];
    } else {
      this.isLoading = true;
      this.loadProjectsData();
    }
  },
  methods: {
    async loadPortfolioData() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/portfolio');
        if (response.ok) {
          const data = await response.json();
          if (data && Object.keys(data).length > 0) {
            this.portfolioData = data;
          }
        }
      } catch (err) {
        console.error('Error loading portfolio data:', err);
      }
    },
    async loadProjectsData() {
      try {
        const response = await fetch('/wp-json/cb-portfolio/v1/projects');
        if (response.ok) {
          const data = await response.json();
          this.projects = data || [];
        }
      } catch (err) {
        console.error('Error loading projects data:', err);
      } finally {
        this.isLoading = false;
      }
    },
    getProjectYear(project) {
      // Use the year field if available, otherwise fallback to created_at year
      if (project.year) {
        return project.year;
      }
      if (project.created_at) {
        const date = new Date(project.created_at);
        return date.getFullYear();
      }
      return new Date().getFullYear();
    },
    getMadeAt(project) {
      return project.made_at || '';
    },
    getTechnologies(project) {
      if (!project.technologies) {
        return [];
      }
      // Split by comma and trim
      return project.technologies
        .split(',')
        .map(tech => tech.trim())
        .filter(tech => tech.length > 0);
    },
    getDomainFromUrl(url) {
      try {
        const urlObj = new URL(url);
        return urlObj.hostname.replace('www.', '');
      } catch (e) {
        // If URL parsing fails, try to extract domain manually
        const match = url.match(/https?:\/\/(?:www\.)?([^\/]+)/);
        return match ? match[1] : url;
      }
    }
  }
};
</script>

<style scoped>
.projects-page {
  min-height: 100vh;
  background: #0f172a;
  color: rgb(148, 163, 184);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
  padding: 60px 20px;
}

.projects-container {
  max-width: 1200px;
  margin: 0 auto;
}

.projects-header {
  margin-bottom: 60px;
}

.projects-header-top {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 16px;
}

.back-link {
  display: inline-flex;
  align-items: center;
  color: rgb(148, 163, 184);
  text-decoration: none;
  transition: color 0.2s ease;
  margin-right: 4px;
}

.back-link:hover {
  color: rgb(226, 232, 240);
}

.back-arrow {
  font-size: 20px;
  line-height: 1;
  display: inline-block;
  transform: rotate(180deg);
}

.projects-name {
  font-size: 16px;
  font-weight: 500;
  color: rgb(226, 232, 240);
  margin: 0;
  text-decoration: none;
  transition: color 0.2s ease;
}

.projects-name:hover {
  color: rgb(148, 163, 184);
}

.projects-title {
  font-size: 32px;
  font-weight: 600;
  color: rgb(226, 232, 240);
  margin: 0;
  letter-spacing: -0.5px;
}

.projects-table-wrapper {
  overflow-x: auto;
}

.projects-table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
}

.projects-table thead {
  border-bottom: 1px solid rgb(46, 60, 83);
}

.projects-table th {
  text-align: left;
  padding: 16px 20px;
  font-size: 14px;
  font-weight: 500;
  color: rgb(148, 163, 184);
  text-transform: capitalize;
  letter-spacing: 0.5px;
}

.projects-table th.col-year {
  padding-left: 20px;
  padding-right: 20px;
}

.projects-table th.col-project {
  padding-left: 20px;
}

.projects-table tbody tr {
  border-bottom: 1px solid rgba(46, 60, 83, 0.5);
  transition: background-color 0.2s ease;
}

.projects-table tbody tr:hover {
  background-color: rgba(30, 41, 59, 0.3);
}

.projects-table td {
  padding: 20px;
  vertical-align: top;
}

.col-year {
  width: 80px;
  font-size: 14px;
  font-weight: 300;
  color: rgb(148, 163, 184);
  text-align: left;
  padding-left: 20px;
  padding-right: 20px;
}

.col-project {
  min-width: 200px;
}

.project-content {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.project-name {
  font-size: 16px;
  font-weight: 500;
  color: rgb(226, 232, 240);
}

.project-name-link {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: rgb(226, 232, 240);
  text-decoration: none;
  transition: color 0.2s ease;
}

.project-name-link:hover {
  color: rgb(148, 163, 184);
}

.project-name-link .external-icon {
  font-size: 14px;
  opacity: 0.7;
}

.col-made-at {
  min-width: 150px;
  font-size: 14px;
  color: rgb(148, 163, 184);
}

.col-built-with {
  min-width: 300px;
}

.tech-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.tech-tag {
  display: inline-block;
  padding: 4px 12px;
  background-color: rgba(30, 41, 59, 0.6);
  border: 1px solid rgb(46, 60, 83);
  border-radius: 4px;
  font-size: 12px;
  color: rgb(148, 163, 184);
  white-space: nowrap;
}

.col-link {
  min-width: 150px;
}

.project-links {
  display: flex;
  align-items: center;
  gap: 8px;
}

.project-link {
  display: inline-flex;
  align-items: center;
  gap: 4px;
  color: rgb(148, 163, 184);
  text-decoration: none;
  font-size: 14px;
  transition: color 0.2s ease;
}

.project-link:hover {
  color: rgb(226, 232, 240);
}

.external-icon {
  font-size: 14px;
  opacity: 0.7;
}

/* Desktop only columns */
.desktop-only {
  display: table-cell;
}

.mobile-only {
  display: none;
}

.loading-state,
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: rgb(148, 163, 184);
}

/* Desktop only columns */
.desktop-only {
  display: table-cell;
}

.mobile-only {
  display: none;
}

/* Mobile responsive */
@media (max-width: 768px) {
  .projects-page {
    padding: 40px 16px;
  }

  .projects-title {
    font-size: 24px;
  }

  .projects-header-top {
    margin-bottom: 12px;
  }

  .projects-table-wrapper {
    overflow-x: auto;
  }

  .projects-table {
    font-size: 14px;
    display: table;
    width: 100%;
  }

  .projects-table thead {
    display: table-header-group;
  }

  .projects-table th {
    padding: 12px 8px;
  }

  .projects-table th.col-year {
    padding-left: 16px;
    padding-right: 8px;
    text-align: left;
  }

  .projects-table th.col-project {
    padding-left: 20px;
  }

  .projects-table tbody {
    display: table-row-group;
  }

  .projects-table tbody tr {
    display: table-row;
    border-bottom: 1px solid rgba(46, 60, 83, 0.5);
  }

  .projects-table td {
    display: table-cell;
    padding: 12px 8px;
    vertical-align: top;
  }

  .projects-table td.col-year {
    padding-left: 16px;
    padding-right: 8px;
    text-align: left;
  }

  .projects-table td.col-project {
    padding-left: 20px;
  }

  /* Hide desktop-only columns on mobile */
  .projects-table td.desktop-only,
  .projects-table th.desktop-only {
    display: none !important;
  }

  .col-year {
    width: 80px;
  }

  .year-text {
    font-size: 14px;
    font-weight: 300;
    color: rgb(148, 163, 184);
  }

  .col-project {
    min-width: auto;
  }

  .project-content {
    gap: 0;
  }

  /* Show mobile-only elements */
  .mobile-only {
    display: inline-flex !important;
  }

  /* Hide desktop project name, show mobile linked version */
  .project-content .project-name.desktop-only {
    display: none !important;
  }

  .project-content .project-name-link.mobile-only {
    display: inline-flex !important;
  }

  .project-content .project-name.mobile-only {
    display: inline !important;
  }
}
</style>

