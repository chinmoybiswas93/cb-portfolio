<template>
  <aside class="left-sidebar">
    <div class="sidebar-content">
      <!-- Profile Section -->
      <div class="profile-section">
        <div class="profile-info">
          <h1 class="profile-name">{{ portfolioData.name || 'Chinmoy Biswas' }}</h1>
          <p class="profile-title">{{ portfolioData.title || 'Tier 2 Technical Support Engineer' }}</p>
          <p class="profile-tagline">{{ portfolioData.tagline || 'Very Enthusiast WordPress Plugin Developer and Problem solver with a eye for design.' }}</p>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="sidebar-nav">
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

      <!-- Social Links -->
      <div class="contact-section">
        <div class="social-links">
          <a 
            v-if="portfolioData.github_url" 
            :href="portfolioData.github_url" 
            target="_blank" 
            class="social-link"
            aria-label="GitHub"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
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
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
            </svg>
          </a>
          <a 
            v-if="portfolioData.website_url" 
            :href="portfolioData.website_url" 
            target="_blank" 
            class="social-link"
            aria-label="Website"
          >
            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.94-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
          </a>
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
      type: Object,
      required: true
    },
    activeSection: {
      type: String,
      default: 'about'
    }
  },
  emits: ['navigate-to'],
  methods: {
    scrollToSection(sectionId) {
      this.$emit('navigate-to', sectionId)
    }
  }
}
</script>

<style scoped>
.left-sidebar {
  width: 40%;
  height: 100vh;
  color: var(--color-white);
  overflow-y: hidden;
  z-index: 1000;
  flex-shrink: 0;
}

.sidebar-content {
  padding: 6rem 2rem;
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
  font-size: var(--profile-name-size);
  font-weight: var(--font-weight-bold);
  margin: 0 0 0.5rem 0;
  color: var(--color-white);
  letter-spacing: -0.02em;
  line-height: 1.2;
}

.profile-title {
  font-size: var(--profile-title-size);
  font-weight: var(--font-weight-normal);
  margin: 0 0 1.5rem 0;
  color: var(--color-white-80);
}

.profile-tagline {
  font-size: var(--profile-tagline-size);
  margin: 0;
  color: var(--color-white-70);
  line-height: 1.6;
  font-weight: var(--font-weight-light);
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
  color: var(--color-white-50);
  text-decoration: none;
  transition: all 0.3s ease;
  font-weight: var(--font-weight-light);
  font-size: var(--nav-link-size);
  letter-spacing: 0.02em;
  position: relative;
  gap: 0.5rem;
}

.nav-link::before {
  content: '';
  width: 40px;
  height: 1px;
  background: var(--color-white-30);
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.nav-link:hover {
  color: var(--color-white-80);
}

.nav-link:hover::before {
  width: 60px;
  background: var(--color-white-60);
}

.nav-link.active {
  color: var(--color-white);
  font-weight: var(--font-weight-medium);
}

.nav-link.active::before {
  width: 80px;
  background: var(--color-white);
}

/* Navigation styling is handled in .nav-link */

/* Contact Section */
.contact-section {
  margin-top: auto;
}

/* Social Links */
.social-links {
  display: flex;
  gap: .5rem;
  margin: 0;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  color: var(--color-white-60);
  text-decoration: none;
  transition: all 0.3s ease;
  border-radius: 8px;
}

.social-link:hover {
  color: var(--color-white);
  background: var(--color-white-10);
}

/* Social links are the main CTA */

/* Mobile Responsive */
@media (max-width: 768px) {
  .left-sidebar {
    width: 100%;
    height: auto;
    min-height: auto;
    max-height: none;
    position: relative;
    overflow-y: visible;
    border-right: none;
    flex-shrink: 0;
  }

  .sidebar-content {
    padding: 4rem 1.5rem;
    min-height: auto;
  }

  .profile-section {
    text-align: left; /* Changed from center to left */
    margin-bottom: 1rem;
    padding-bottom: 0;
  }

  .profile-name {
    font-size: var(--profile-name-mobile); /* Smaller font size for mobile */
  }

  .sidebar-nav {
    display: none; /* Hide navigation in mobile */
    margin-bottom: 2rem;
  }

  .nav-list {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 1rem;
  }

  .nav-item {
    margin-bottom: 0;
  }

  .nav-link {
    padding: 0.5rem 1rem;
    border-left: none;
    border-radius: 6px;
    background: var(--color-slate-bg-10);
    text-align: center;
    min-width: 100px;
  }

  .nav-link:hover,
  .nav-link.active {
    background: var(--color-slate-bg-20);
    border-left: none;
  }

  .social-links {
    justify-content: flex-start; /* Changed from center to left aligned */
  }

  .contact-section {
    margin-top: 1rem; /* Reduced margin for better mobile layout */
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .left-sidebar {
    width: 45%;
  }
  
  .sidebar-content {
    padding: 4rem 1.5rem;
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
  background: rgba(255, 255, 255, 0.1);
}

.left-sidebar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.left-sidebar::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}
</style>