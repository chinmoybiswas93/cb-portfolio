<template>
  <section class="experience-section">
    <div class="section-header fade-in">
      <h2 class="section-title">Experience</h2>
    </div>

    <div class="content-timeline slide-up">
      <ExperienceItem v-for="(experience, index) in sortedExperience" :key="experience.id" :experience="experience"
        :index="index" />

      <!-- Loading Skeletons -->
      <div v-if="isLoading" class="loading-items">
        <div v-for="n in 3" :key="`loading-exp-${n}`" class="loading-item" :style="{ animationDelay: `${(n - 1) * 0.1}s` }">
          <div class="item-card">
            <div class="item-left-column">
              <div class="loading-skeleton date-skeleton"></div>
            </div>
            <div class="item-right-column">
              <div class="loading-skeleton title-skeleton"></div>
              <div class="loading-skeleton description-skeleton"></div>
              <div class="loading-skeleton description-skeleton short"></div>
              <div class="tags-skeleton">
                <div class="loading-skeleton tag-skeleton"></div>
                <div class="loading-skeleton tag-skeleton"></div>
                <div class="loading-skeleton tag-skeleton"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty state - only show if not loading and no data -->
      <div v-if="!isLoading && sortedExperience.length === 0" class="empty-state">
        <p>No experience data available yet.</p>
      </div>

      <!-- View Full Archive Button -->
      <div v-if="sortedExperience.length > 0" class="archive-section">
        <a 
          v-if="portfolioData && portfolioData.resume_url" 
          :href="portfolioData.resume_url" 
          target="_blank" 
          rel="noopener noreferrer"
          class="archive-button"
        >
          <span class="archive-text">View Full Resume</span>
          <span class="archive-arrow">→</span>
        </a>
        <a 
          v-else
          href="#" 
          class="archive-button" 
          @click.prevent="viewFullArchive"
        >
          <span class="archive-text">View Full Resume</span>
          <span class="archive-arrow">→</span>
        </a>
      </div>
    </div>
  </section>
</template>

<script>
import ExperienceItem from './ExperienceItem.vue'

export default {
  name: 'ExperienceSection',
  components: {
    ExperienceItem
  },
  props: {
    experienceData: {
      type: Array,
      required: true
    },
    portfolioData: {
      type: [Object, null],
      required: true
    },
    isLoading: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    sortedExperience() {
      // Sort experience by order_index in ascending order
      return [...this.experienceData].sort((a, b) => {
        const orderA = a.order_index || 999;
        const orderB = b.order_index || 999;
        return orderA - orderB;
      });
    }
  },
  methods: {
    viewFullArchive() {
      // Add your archive view logic here
      console.log('View full experience archive');
      // You can emit an event or navigate to an archive page
      this.$emit('view-archive');
    }
  }
}
</script>

<style scoped>
.experience-section {
  margin-bottom: 0;
}

.archive-button {
  border-bottom: 1px solid transparent;
}

/* Loading States */
.loading-items {
  display: flex;
  flex-direction: column;
  gap: 2rem;
}

.loading-item {
  display: block;
  margin-bottom: 2rem;
  width: 100%;
  opacity: 0;
  animation: fadeIn 0.6s ease-out forwards;
}

.loading-skeleton {
  background: linear-gradient(90deg,
      transparent 25%,
      rgba(255, 255, 255, 0.02) 50%,
      transparent 75%);
  background-size: 200% 100%;
  border-radius: 8px;
  animation: skeleton-loading 1.5s infinite;
}

.date-skeleton {
  height: 16px;
  width: 100%;
  max-width: 120px;
}

.title-skeleton {
  height: 20px;
  width: 70%;
  margin-bottom: 0.75rem;
}

.description-skeleton {
  height: 16px;
  width: 100%;
  margin-bottom: 0.5rem;
}

.description-skeleton.short {
  width: 60%;
  margin-bottom: 1rem;
}

.tags-skeleton {
  display: flex;
  gap: 0.5rem;
  margin-top: 1rem;
}

.tag-skeleton {
  height: 24px;
  width: 60px;
}

@keyframes skeleton-loading {
  0% {
    background-position: 200% 0;
  }

  100% {
    background-position: -200% 0;
  }
}

@media (max-width: 768px) {
  .loading-item .item-card {
    flex-direction: column;
    gap: 1rem;
  }

  .loading-item .item-left-column {
    flex: none;
    width: 100%;
  }
}

</style>
