<template>
  <section class="about-section">
    <div class="section-header fade-in">
      <h2 class="section-title">About</h2>
    </div>
    
    <div class="about-content slide-up">
      <div class="about-text">
        <div v-html="aboutText"></div>
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
    aboutText() {
      const defaultText = 'A journey that began with curiosity and self-learning in web development has transformed into a rewarding career in the WordPress ecosystem. With hands-on experience in WordPress development, technical support, and project management, the path has included roles at organizations like WPManageNinja LLC, SEOPage1. Day-to-day work involves building custom WordPress websites, debugging complex issues, developing themes and plugins, and guiding junior developers. Experience spans frontend and backend development, API integration, and website optimization, always with a focus on clean code and user-friendly solutions. Along the way, technical support for flagship WordPress plugins has helped over 700,000 users worldwide.';
      
      const text = this.portfolioData.about || defaultText;
      
      // Wrap in paragraph if it doesn't contain HTML tags
      if (!text.includes('<')) {
        return `<p>${text}</p>`;
      }
      
      return text;
    }
  }
}
</script>

<style scoped>

.about-section {
  margin-bottom: 0;
}

.about-content {
  display: grid;
  gap: 2rem;
}

.about-text p {
  font-size: var(--font-size-body);
  line-height: var(--line-height-base);
  color: var(--color-text-secondary);
  margin: 0;
  font-weight: var(--font-weight-light);
}

/* HTML Tag Styles */
.about-text b,
.about-text strong {
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
}

.about-text span {
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
}

.about-text a {
  color: var(--color-text-primary);
  text-decoration: none;
  font-weight: var(--font-weight-semibold);
  border-bottom: 1px solid transparent;
  transition: all 0.2s ease;
}

.about-text a:hover {
  color: var(--color-text-primary);
  border-bottom-color: var(--color-text-secondary);
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
  font-size: var(--font-size-title);
  font-weight: var(--font-weight-medium);
  margin: 0 0 1rem 0;
  color: var(--color-text-primary);
  letter-spacing: 0.02em;
}

.highlight-card p {
  font-size: var(--font-size-body);
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
