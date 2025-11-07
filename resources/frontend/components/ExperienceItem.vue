<template>
  <div class="content-item slide-left" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="item-card">
      <!-- Left Column: Duration (25%) -->
      <div class="item-left-column">
        <span class="date-range" :class="{ current: experience.current }">
          {{ experience.start_date }} <span>-</span> {{ experience.current ? 'PRESENT' : experience.end_date }}
        </span>
      </div>

      <!-- Right Column: Content (75%) -->
      <div class="item-right-column">
        <div class="item-header">
          <h3 class="item-title">
            {{ experience.position }} <span></span>
            <a v-if="experience.company_website" :href="experience.company_website" target="_blank"
              rel="noopener noreferrer" class="item-link">
              {{ experience.company }} <span class="external-link">↗</span>
            </a>
            <span v-else class="company-text">
              {{ experience.company }}
            </span>
          </h3>
        </div>

        <p class="item-description" v-if="experience.description">{{ experience.description }}</p>

        <div v-if="experience.skills" class="tags-section">
          <div class="tag-list">
            <span v-for="skill in skillList" :key="skill" class="tag-item">
              {{ skill.trim() }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ExperienceItem',
  props: {
    experience: {
      type: Object,
      required: true
    },
    index: {
      type: Number,
      default: 0
    }
  },
  computed: {
    skillList() {
      if (!this.experience.skills) return [];
      return this.experience.skills.split(',').filter(skill => skill.trim());
    }
  }
}
</script>

<style scoped>
.content-item {
  padding: 5px 0;
}

/* Experience-specific styles */
.date-range {
  font-size: var(--font-size-xs);
  color: var(--color-white-60);
  font-weight: var(--font-weight-normal);
  text-transform: uppercase;
  display: block;
  line-height: var(--line-height-base);
  padding-top: 0.25rem;
}

.date-range.current {
  color: var(--color-white-80);
  font-weight: var(--font-weight-medium);
}

.company-text {
  color: var(--color-white-80);
  font-size: var(--font-size-sm);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-base);
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .item-card {
    flex-direction: column-reverse;
    gap: 0.5rem;
  }

  .item-card::before {
    top: -0.5rem;
    left: -1rem;
    right: -1rem;
    bottom: -0.9rem;
  }

  .item-left-column {
    width: 100%;
    order: 2;
  }

  .item-right-column {
    order: 1;
  }

  .date-range {
    font-size: 11px;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .item-card {
    gap: 1.5rem;
    padding: 0;
    margin: 0;
  }

  .item-card:hover {
    padding: 1rem;
    margin: 0 -1rem;
  }

  .item-left-column {
    width: 30%;
  }
}
</style>
