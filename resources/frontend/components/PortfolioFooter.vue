<template>
  <footer class="portfolio-footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-info">
          <p>&copy; {{ currentYear }} {{ portfolioData.name || 'Your Name' }}. All rights reserved.</p>
        </div>
        
        <div v-if="hasSocialLinks" class="social-links">
          <a 
            v-if="portfolioData.github_url" 
            :href="portfolioData.github_url" 
            target="_blank" 
            class="social-link"
            aria-label="GitHub"
          >
            <span class="social-icon">💻</span>
          </a>
          
          <a 
            v-if="portfolioData.linkedin_url" 
            :href="portfolioData.linkedin_url" 
            target="_blank" 
            class="social-link"
            aria-label="LinkedIn"
          >
            <span class="social-icon">💼</span>
          </a>
          
          <a 
            v-if="portfolioData.twitter_url" 
            :href="portfolioData.twitter_url" 
            target="_blank" 
            class="social-link"
            aria-label="Twitter"
          >
            <span class="social-icon">🐦</span>
          </a>
          
          <a 
            v-if="portfolioData.website_url" 
            :href="portfolioData.website_url" 
            target="_blank" 
            class="social-link"
            aria-label="Website"
          >
            <span class="social-icon">🌐</span>
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'PortfolioFooter',
  props: {
    portfolioData: {
      type: Object,
      required: true
    }
  },
  computed: {
    currentYear() {
      return new Date().getFullYear();
    },
    hasSocialLinks() {
      return this.portfolioData.github_url || 
             this.portfolioData.linkedin_url || 
             this.portfolioData.twitter_url || 
             this.portfolioData.website_url;
    }
  }
}
</script>

<style scoped>
.portfolio-footer {
  background: #2c3e50;
  color: white;
  padding: 40px 0;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.footer-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.footer-info p {
  margin: 0;
  font-size: 0.9rem;
  opacity: 0.8;
}

.social-links {
  display: flex;
  gap: 16px;
}

.social-link {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
  text-decoration: none;
  transition: all 0.2s;
}

.social-link:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: translateY(-2px);
}

.social-icon {
  font-size: 1.2rem;
}

@media (max-width: 768px) {
  .footer-content {
    flex-direction: column;
    gap: 20px;
    text-align: center;
  }
  
  .social-links {
    justify-content: center;
  }
}
</style>
