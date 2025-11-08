<template>
  <header class="portfolio-header">
    <div class="container">
      <h1 class="portfolio-title">{{ portfolioData.name || 'Your Name' }}</h1>
      <p class="portfolio-subtitle">{{ portfolioData.title || 'Your Title' }}</p>
      <p class="portfolio-tagline">{{ portfolioData.tagline || 'Your tagline' }}</p>
    </div>
  </header>
</template>

<script>
export default {
  name: 'PortfolioHeader',
  props: {
    portfolioData: {
      type: Object,
      required: true
    }
  }
}
</script>

<style scoped>
.portfolio-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 80px 0;
  text-align: center;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.portfolio-title {
  font-size: 3.5rem;
  font-weight: 700;
  margin: 0 0 10px 0;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.portfolio-subtitle {
  font-size: 1.5rem;
  font-weight: 300;
  margin: 0 0 12px 0;
  opacity: 0.9;
}

.portfolio-tagline {
  font-size: 1.1rem;
  font-weight: 400;
  margin: 0;
  opacity: 0.8;
  max-width: 600px;
  margin: 0 auto;
}

@media (max-width: 768px) {
  .portfolio-header {
    padding: 60px 0;
  }
  
  .portfolio-title {
    font-size: 2.5rem;
  }
  
  .portfolio-subtitle {
    font-size: 1.2rem;
  }
  
  .portfolio-tagline {
    font-size: var(--font-size-body);
  }
}
</style>
