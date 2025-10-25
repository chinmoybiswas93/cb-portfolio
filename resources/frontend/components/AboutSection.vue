<template>
  <section class="about-section">
    <div class="container">
      <div class="section-header">
        <h2>About Me</h2>
        <div class="section-divider"></div>
      </div>
      
      <div class="about-content">
        <div class="about-text">
          <p>{{ portfolioData.about || 'Tell your story here...' }}</p>
        </div>
        
        <div class="contact-info" v-if="hasContactInfo">
          <h3>Get In Touch</h3>
          <div class="contact-grid">
            <div v-if="portfolioData.email" class="contact-item">
              <span class="contact-icon">📧</span>
              <a :href="`mailto:${portfolioData.email}`">{{ portfolioData.email }}</a>
            </div>
            
            <div v-if="portfolioData.phone" class="contact-item">
              <span class="contact-icon">📱</span>
              <a :href="`tel:${portfolioData.phone}`">{{ portfolioData.phone }}</a>
            </div>
            
            <div v-if="portfolioData.location" class="contact-item">
              <span class="contact-icon">📍</span>
              <span>{{ portfolioData.location }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AboutSection',
  props: {
    portfolioData: {
      type: Object,
      required: true
    }
  },
  computed: {
    hasContactInfo() {
      return this.portfolioData.email || this.portfolioData.phone || this.portfolioData.location;
    }
  }
}
</script>

<style scoped>
.about-section {
  padding: 80px 0;
  background: #f8f9fa;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.section-header {
  text-align: center;
  margin-bottom: 60px;
}

.section-header h2 {
  font-size: 2.5rem;
  font-weight: 600;
  color: #2c3e50;
  margin: 0 0 10px 0;
}

.section-divider {
  width: 60px;
  height: 4px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  margin: 0 auto;
  border-radius: 2px;
}

.about-content {
  display: grid;
  grid-template-columns: 2fr 1fr;
  gap: 60px;
  align-items: start;
}

.about-text p {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #555;
  margin: 0;
}

.contact-info {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

.contact-info h3 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #2c3e50;
  margin: 0 0 24px 0;
}

.contact-grid {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1rem;
}

.contact-icon {
  font-size: 1.2rem;
  width: 24px;
  text-align: center;
}

.contact-item a {
  color: #667eea;
  text-decoration: none;
  transition: color 0.2s;
}

.contact-item a:hover {
  color: #764ba2;
}

@media (max-width: 768px) {
  .about-content {
    grid-template-columns: 1fr;
    gap: 40px;
  }
  
  .about-section {
    padding: 60px 0;
  }
  
  .section-header h2 {
    font-size: 2rem;
  }
}
</style>
