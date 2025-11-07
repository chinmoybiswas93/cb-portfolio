<template>
  <section class="about-section">
    <div class="section-header fade-in">
      <h2 class="section-title">About</h2>
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
/* About section specific styles */
.about-section {
  background: transparent;
  margin-bottom: 0;
}

.section-title {
  font-weight: var(--font-weight-medium);
  text-transform: uppercase;
  letter-spacing: .5px;
}

.about-content {
  display: grid;
  gap: 2rem;
}

.about-text p {
  font-size: var(--body-text-size);
  line-height: var(--line-height-base);
  color: var(--color-text-secondary);
  margin: 0;
  font-weight: var(--font-weight-light);
}

.about-highlights {
  margin-top: 1.5rem;
}

.highlight-card {
  background: transparent;
  color: var(--color-text-secondary);
  padding: 2rem 0;
  text-align: left;
}

.highlight-card h3 {
  font-size: var(--highlight-title-size);
  font-weight: var(--font-weight-medium);
  margin: 0 0 1rem 0;
  color: var(--color-text-primary);
  letter-spacing: 0.02em;
}

.highlight-card p {
  font-size: var(--highlight-text-size);
  margin: 0;
  color: var(--color-text-secondary);
  line-height: var(--line-height-base);
  font-weight: var(--font-weight-normal);
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .section-header {
    background: var(--color-background-blur);
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
  }
}
</style>
