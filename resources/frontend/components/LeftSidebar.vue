<template>
  <aside class="left-sidebar">
    <div class="sidebar-content">
      <!-- Profile Section -->
      <div class="profile-section">
        <div class="profile-info" v-if="hasData">
          <h1 class="profile-name">{{ portfolioData.name }}</h1>
          <p class="profile-title">{{ portfolioData.title }}</p>
          <p class="profile-tagline">{{ portfolioData.tagline }}</p>
        </div>
        
        <div class="profile-loading" v-else>
          <div class="loading-skeleton name-skeleton"></div>
          <div class="loading-skeleton title-skeleton"></div>
          <div class="loading-skeleton tagline-skeleton"></div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="sidebar-nav" v-if="showNavigation">
        <ul class="nav-list">
          <li class="nav-item">
            <a 
              href="#about" 
              class="nav-link" 
              :class="{ active: activeSection === 'about' }"
              @click="scrollToSection('about')"
            >
              ABOUT
            </a>
          </li>
          <li class="nav-item">
            <a 
              href="#experience" 
              class="nav-link" 
              :class="{ active: activeSection === 'experience' }"
              @click="scrollToSection('experience')"
            >
              EXPERIENCE
            </a>
          </li>
          <li class="nav-item">
            <a 
              href="#projects" 
              class="nav-link" 
              :class="{ active: activeSection === 'projects' }"
              @click="scrollToSection('projects')"
            >
              PROJECTS
            </a>
          </li>
        </ul>
      </nav>

      <!-- Navigation Loading Skeleton -->
      <div class="sidebar-nav-loading" v-else>
        <div class="nav-loading">
          <div class="loading-skeleton nav-skeleton" v-for="n in 3" :key="`nav-skeleton-${n}`" :style="{ animationDelay: `${(n - 1) * 0.1}s` }">
            <span class="nav-skeleton-text">SECTION</span>
          </div>
        </div>
      </div>

      <!-- Social Links -->
      <div class="contact-section">
        <div class="social-links" v-if="hasData">
          <a 
            v-if="portfolioData.github_url" 
            :href="portfolioData.github_url" 
            target="_blank" 
            class="social-link"
            aria-label="GitHub"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
            </svg>
          </a>
          <a 
            v-if="portfolioData.linkedin_url" 
            :href="portfolioData.linkedin_url" 
            target="_blank" 
            class="social-link"
            aria-label="LinkedIn"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
            </svg>
          </a>
          <a 
            v-if="portfolioData.twitter_url" 
            :href="portfolioData.twitter_url" 
            target="_blank" 
            class="social-link"
            aria-label="Twitter"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
            </svg>
          </a>
          <a 
            v-if="portfolioData.email" 
            :href="`mailto:${portfolioData.email}`" 
            class="social-link"
            aria-label="Email"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-.904.732-1.636 1.636-1.636h.245l10.119 7.587 10.119-7.587h.245c.904 0 1.636.732 1.636 1.636z"/>
            </svg>
          </a>
          <a 
            v-if="portfolioData.phone" 
            :href="`tel:${portfolioData.phone}`" 
            class="social-link"
            aria-label="Phone"
          >
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
              <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
          </a>
        </div>
        
        <div class="social-loading" v-else>
          <div class="loading-skeleton social-skeleton"></div>
        </div>
      </div>
    </div>
  </aside>
</template>

<script>
export default {
  name: 'LeftSidebar',
  props: {
    portfolioData: {
      type: [Object, null],
      required: true
    },
    activeSection: {
      type: String,
      default: 'about'
    },
    isLoadingExperience: {
      type: Boolean,
      default: false
    },
    isLoadingProjects: {
      type: Boolean,
      default: false
    }
  },
  emits: ['navigate-to'],
  computed: {
    hasData() {
      // Check if portfolioData is loaded with real data (has an ID indicating it's from database)
      return this.portfolioData &&
        this.portfolioData.id !== null &&
        this.portfolioData.id !== undefined;
    },
    showNavigation() {
      // Show navigation only when:
      // 1. Profile data is loaded (for About section)
      // 2. Experience data is not loading (Experience section is ready)
      // 3. Projects data is not loading (Projects section is ready)
      return this.hasData && !this.isLoadingExperience && !this.isLoadingProjects;
    }
  },
  methods: {
    scrollToSection(sectionId) {
      // Only emit navigation if all content is loaded
      if (this.showNavigation) {
        this.$emit('navigate-to', sectionId)
      }
    }
  }
}
</script>

<style scoped>
.left-sidebar {
  width: 45%;
  position: sticky;
  top: 0;
  height: 100vh;
  min-height: 100vh;
  overflow: hidden;
  z-index: 1000;
  padding: 0 2rem;
  flex-shrink: 0;
}

.sidebar-content {
  padding: 6rem 0;
  height: 100%;
  display: flex;
  flex-direction: column;
}

/* Profile Section */
.profile-section {
  text-align: left;
  margin-bottom: 3rem;
  padding-bottom: 2rem;
}

.profile-name {
  font-size: var(--font-size-profile);
  font-weight: var(--font-weight-semibold);
  margin: 0 0 0.5rem 0;
  color: var(--color-text-primary);
  line-height: 1;
}

.profile-title {
  font-size: var(--font-size-title);
  font-weight: var(--font-weight-normal);
  margin: 0 0 1.5rem 0;
  color: var(--color-text-secondary);
}

.profile-tagline {
  font-size: var(--font-size-body);
  margin: 0;
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  font-weight: var(--font-weight-light);
  max-width: 80%;
}

/* Navigation */
.sidebar-nav {
  margin-bottom: 3rem;
}

.nav-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.nav-item {
  margin-bottom: 0;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0;
  color: var(--color-text-secondary);
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: var(--font-weight-medium);
  font-size: var(--font-size-small);
  letter-spacing: 0.02em;
  position: relative;
  gap: 0.5rem;
}

.nav-link::before {
  content: '';
  width: 40px;
  height: 1px;
  background: var(--color-text-secondary);
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.nav-link:hover {
  color: var(--color-text-primary);
}

.nav-link:hover::before {
  width: 60px;
  background: var(--color-text-primary);
}

.nav-link.active {
  color: var(--color-text-primary);
  font-weight: var(--font-weight-bold);
}

.nav-link.active::before {
  width: 80px;
  background: var(--color-text-primary);
}

/* Navigation styling is handled in .nav-link */

/* Contact Section */
.contact-section {
  margin-top: auto;
}

/* Social Links */
.social-links {
  display: flex;
  gap: 1.5rem;
  margin: 0;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-text-secondary);
  text-decoration: none;
  transition: all 0.3s ease;
  border-radius: 8px;
}

.social-link:hover {
  color: var(--color-text-primary);
}

/* Social links are the main CTA */

/* Mobile Responsive */
@media (max-width: 768px) {
  .left-sidebar {
    position: relative;
    width: 100%;
    min-width: auto;
    height: auto;
    min-height: auto;
    padding: 0;
  }

  .sidebar-content {
    padding: 3.5rem .5rem 0 .5rem;
  }

  .sidebar-nav {
    display: none;
  }
  
  .sidebar-nav-loading {
    display: none;
  }
  
  .nav-loading {
    display: none;
  }
  
  .profile-section {
  margin-bottom: 1.5rem;
  padding-bottom: 0;
}

.profile-tagline {
  max-width: 95%;
}
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .left-sidebar {
    width: 380px;
    min-width: 350px;
    padding: 0 1.5rem;
  }
  
  .sidebar-content {
    padding: 4rem 0;
  }
  
  .profile-name {
    font-size: 1.5rem;
  }
}

/* Scrollbar Styling */
.left-sidebar::-webkit-scrollbar {
  width: 4px;
}

.left-sidebar::-webkit-scrollbar-track {
  background: var(--color-ui-bg);
}

.left-sidebar::-webkit-scrollbar-thumb {
  background: var(--color-text-secondary);
  border-radius: 2px;
}

.left-sidebar::-webkit-scrollbar-thumb:hover {
  background: var(--color-text-primary);
}

/* Loading States */
.profile-loading {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.nav-loading {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.sidebar-nav-loading {
  margin-bottom: 3rem;
}

.loading-skeleton {
  background: linear-gradient(90deg,
      transparent 25%,
      rgba(255, 255, 255, 0.02) 50%,
      transparent 75%);
  background-size: 200% 100%;
  border-radius: 8px;
  animation: skeleton-loading 1.5s infinite;
}

.name-skeleton {
  height: 48px;
  width: 80%;
  margin-bottom: 0.5rem;
}

.title-skeleton {
  height: 20px;
  width: 60%;
  margin-bottom: 1.5rem;
}

.tagline-skeleton {
  height: 16px;
  width: 90%;
}

.social-skeleton {
  height: 24px;
  width: 150px;
}

.nav-skeleton {
  height: 20px;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  opacity: 0;
  animation: fadeIn 0.6s ease-out forwards;
}

.nav-skeleton::before {
  content: '';
  width: 40px;
  height: 1px;
  background: linear-gradient(90deg,
      transparent 25%,
      rgba(255, 255, 255, 0.02) 50%,
      transparent 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  flex-shrink: 0;
}

.nav-skeleton-text {
  color: transparent;
  background: linear-gradient(90deg,
      transparent 25%,
      rgba(255, 255, 255, 0.02) 50%,
      transparent 75%);
  background-size: 200% 100%;
  animation: skeleton-loading 1.5s infinite;
  border-radius: 4px;
  font-size: var(--font-size-small);
  font-weight: var(--font-weight-medium);
  letter-spacing: 0.02em;
}

@keyframes skeleton-loading {
  0% {
    background-position: 200% 0;
  }

  100% {
    background-position: -200% 0;
  }
}
</style>