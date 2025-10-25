<template>
  <div class="portfolio-container">
    <header class="portfolio-header">
      <div class="container">
        <h1 class="portfolio-title">{{ title }}</h1>
        <p class="portfolio-subtitle">{{ subtitle }}</p>
      </div>
    </header>

    <main class="portfolio-main">
      <div class="container">
        <div class="portfolio-grid">
          <div v-for="project in projects" :key="project.id" class="portfolio-item">
            <div class="portfolio-card">
              <h3>{{ project.title }}</h3>
              <p>{{ project.description }}</p>
              <a v-if="project.link" :href="project.link" target="_blank" class="btn">View Project</a>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer class="portfolio-footer">
      <div class="container">
        <p>&copy; {{ new Date().getFullYear() }} {{ authorName }}</p>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'PortfolioApp',
  data() {
    return {
      title: 'Welcome to My Portfolio',
      subtitle: 'Showcasing my latest work and projects',
      authorName: 'Chinmoy Biswas',
      projects: [
        {
          id: 1,
          title: 'Project One',
          description: 'This is a sample project description. Replace it with your actual project details.',
          link: '#'
        },
        {
          id: 2,
          title: 'Project Two',
          description: 'Another amazing project that demonstrates my skills and expertise.',
          link: '#'
        },
        {
          id: 3,
          title: 'Project Three',
          description: 'A third project that showcases my versatility and creativity.',
          link: '#'
        }
      ]
    }
  },
  mounted() {
    // Load portfolio data from API if needed
    this.loadPortfolioData();
  },
  methods: {
    async loadPortfolioData() {
      // Future: Load data from WordPress REST API
      // const response = await fetch('/wp-json/cb-portfolio/v1/items');
      // this.projects = await response.json();
    }
  }
}
</script>

<style scoped>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.portfolio-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.portfolio-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 80px 0;
  text-align: center;
}

.portfolio-title {
  font-size: 3rem;
  margin-bottom: 1rem;
  font-weight: 700;
}

.portfolio-subtitle {
  font-size: 1.5rem;
  opacity: 0.9;
}

.portfolio-main {
  flex: 1;
  padding: 80px 0;
  background: #f8f9fa;
}

.portfolio-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  margin-top: 40px;
}

.portfolio-item {
  animation: fadeIn 0.6s ease-in;
}

.portfolio-card {
  background: white;
  border-radius: 10px;
  padding: 30px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.portfolio-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
}

.portfolio-card h3 {
  color: #333;
  margin-bottom: 15px;
  font-size: 1.5rem;
}

.portfolio-card p {
  color: #666;
  line-height: 1.6;
  margin-bottom: 20px;
}

.btn {
  display: inline-block;
  padding: 10px 20px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-decoration: none;
  border-radius: 5px;
  transition: opacity 0.3s ease;
}

.btn:hover {
  opacity: 0.9;
}

.portfolio-footer {
  background: #2c3e50;
  color: white;
  padding: 30px 0;
  text-align: center;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@media (max-width: 768px) {
  .portfolio-title {
    font-size: 2rem;
  }
  
  .portfolio-subtitle {
    font-size: 1.2rem;
  }
  
  .portfolio-grid {
    grid-template-columns: 1fr;
  }
}
</style>
