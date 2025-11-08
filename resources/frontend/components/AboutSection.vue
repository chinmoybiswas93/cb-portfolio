<template>
  <section class="about-section">
    <div class="section-header fade-in">
      <h2 class="section-title">About</h2>
    </div>

    <div class="about-content slide-up" v-if="hasData">
      <div class="about-text">
        <div v-html="aboutText"></div>
      </div>
    </div>

    <div class="loading-placeholder" v-else>
      <div class="loading-skeleton"></div>
    </div>
  </section>
</template>

<script>
export default {
  name: 'AboutSection',
  props: {
    portfolioData: {
      type: [Object, null],
      required: true
    }
  },
  computed: {
    aboutText() {
      // Only process text when data is fully loaded
      if (!this.hasData) {
        return null;
      }

      const text = this.portfolioData.about;

      // Wrap in paragraph if it doesn't contain HTML tags
      if (!text.includes('<')) {
        return `<p>${text}</p>`;
      }

      return text;
    },
    hasData() {
      // Check if portfolioData is loaded with an ID (indicating real data)
      return this.portfolioData &&
        this.portfolioData.id &&
        this.portfolioData.about !== undefined;
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

/* Loading States */
.loading-placeholder {
  margin-top: 1rem;
}

.loading-skeleton {
  height: 120px;
  background: linear-gradient(90deg,
      transparent 25%,
      rgba(255, 255, 255, 0.02) 50%,
      transparent 75%);
  background-size: 200% 100%;
  border-radius: 8px;
  animation: skeleton-loading 1.5s infinite;
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
