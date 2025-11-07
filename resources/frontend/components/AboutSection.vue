<template>
  <section class="about-section">
    <div class="section-header fade-in">
      <h2 class="section-title">About Me</h2>
      <div class="section-divider"></div>
    </div>
    
    <div class="about-content slide-up">
      <div class="about-text">
        <p>{{ portfolioData.about || 'A journey that began with curiosity and self-learning in web development has transformed into a rewarding career in the WordPress ecosystem. With hands-on experience in WordPress development, technical support, and project management, the path has included roles at organizations like WPManageNinja LLC, SEOPage1. Day-to-day work involves building custom WordPress websites, debugging complex issues, developing themes and plugins, and guiding junior developers. Experience spans frontend and backend development, API integration, and website optimization, always with a focus on clean code and user-friendly solutions. Along the way, technical support for flagship WordPress plugins has helped over 700,000 users worldwide.' }}</p>
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
  }
}
</script>

<style scoped>
.about-section {
  background: transparent;
  padding: 0 3rem 4rem 3rem;
  margin-bottom: 0;
}

.section-header {
  margin-bottom: 2rem;
}

.section-title {
  font-size: 2rem;
  font-weight: 700;
  color: rgb(248, 250, 252);
  margin: 0 0 1rem 0;
  position: relative;
}

.section-divider {
  width: 60px;
  height: 2px;
  background: rgba(148, 163, 184, 0.3);
  border-radius: 1px;
}

.about-content {
  display: grid;
  gap: 2rem;
}

.about-text p {
  font-size: 1.125rem;
  line-height: 1.7;
  color: rgb(148, 163, 184);
  margin: 0;
}

.about-highlights {
  margin-top: 1.5rem;
}

.highlight-card {
  background: transparent;
  color: rgb(148, 163, 184);
  padding: 2rem 0;
  text-align: left;
}

.highlight-card h3 {
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 1rem 0;
  color: rgb(248, 250, 252);
  letter-spacing: 0.02em;
}

.highlight-card p {
  font-size: 1.125rem;
  margin: 0;
  color: rgb(148, 163, 184);
  line-height: 1.6;
  font-weight: 400;
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .about-section {
    padding: 0 1.5rem 3rem 1.5rem;
  }
  
  .section-title {
    font-size: 1.75rem;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .about-section {
    padding: 0 2rem 3.5rem 2rem;
  }
  
  .section-title {
    font-size: 1.875rem;
  }
}
</style>
