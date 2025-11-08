<template>
  <footer class="portfolio-footer">
    <div class="container">
      <div class="footer-content">
        <!-- Custom Footer Text -->
        <div v-if="portfolioData && portfolioData.footer_text" class="footer-text" v-html="portfolioData.footer_text">
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
      type: [Object, null],
      required: true
    }
  }
}
</script>

<style scoped>

.container {
  max-width: none;
  margin: 0;
  padding: 0;
}

.footer-content {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  text-align: left;
}

.footer-text {
  max-width: 100%;
  margin: 0;
  font-size: var(--font-size-description);
}

.footer-text p {
  margin: 0;
  font-size: var(--font-size-description);
  line-height: 1.6;
  color: var(--color-text-secondary);
}

/* Specific HTML Tag Styles for Footer */
.footer-text :deep(b),
.footer-text :deep(strong) {
  font-weight: var(--font-weight-medium);
  color: var(--color-text-primary);
  font-size: inherit;
}

.footer-text :deep(span) {
  font-weight: var(--font-weight-medium);
  color: var(--color-text-primary);
  font-size: inherit;
}

.footer-text :deep(a) {
  color: var(--color-text-primary);
  text-decoration: none;
  font-weight: var(--font-weight-medium);
  border-bottom: 1px solid transparent;
  transition: all 0.2s ease;
  font-size: inherit;
}

.footer-text :deep(a:hover) {
  color: var(--color-text-primary);
  border-bottom-color: var(--color-text-secondary);
}

@media (max-width: 768px) {
  .footer-text,
  .footer-text p {
    font-size: var(--font-size-description);
  }
  
  .footer-text :deep(b),
  .footer-text :deep(strong),
  .footer-text :deep(span),
  .footer-text :deep(a) {
    font-size: inherit;
  }
}
</style>
