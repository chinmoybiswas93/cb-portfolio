<template>
  <div class="experience-item" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="experience-card">
      <!-- Left Column: Duration (25%) -->
      <div class="experience-duration">
        <span class="date-range" :class="{ current: experience.current }">
          {{ experience.start_date }} — {{ experience.current ? 'PRESENT' : experience.end_date }}
        </span>
      </div>

      <!-- Right Column: Content (75%) -->
      <div class="experience-content">
        <div class="experience-header">
          <h3 class="position">{{ experience.position }}</h3>
          <p class="company">{{ experience.company }} <span class="external-link">↗</span></p>
        </div>

        <p class="description" v-if="experience.description">{{ experience.description }}</p>

        <div v-if="experience.skills" class="skills-section">
          <div class="skill-tags">
            <span v-for="skill in skillList" :key="skill" class="skill-tag">
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
.experience-item {
  position: relative;
  margin-bottom: 1rem;
  animation: slideInLeft 0.6s ease-out;
  animation-fill-mode: both;
}

.experience-card {
  display: flex;
  gap: 2rem;
  padding: 1rem;
  margin: -1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  cursor: pointer;
}

.experience-card:hover {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
}

/* Left Column: Duration (25%) */
.experience-duration {
  width: 25%;
  flex-shrink: 0;
  padding-top: 0.25rem;
}

.date-range {
  font-size: var(--font-size-xs);
  color: var(--color-white-60);
  font-weight: var(--font-weight-normal);
  text-transform: uppercase;
  display: block;
}

.date-range.current {
  color: var(--color-white-80);
  font-weight: var(--font-weight-medium);
}

/* Right Column: Content (75%) */
.experience-content {
  flex: 1;
  min-width: 0;
  /* Allow content to shrink */
}

.experience-header {
  margin-bottom: 0.75rem;
}

.position {
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-semibold);
  color: var(--color-text-primary);
  margin: 0 0 0.25rem 0;
  line-height: 1.3;
}

.company {
  font-size: var(--font-size-sm);
  color: var(--color-white-80);
  font-weight: var(--font-weight-medium);
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.external-link {
  font-size: 1rem;
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.experience-card:hover .external-link {
  opacity: 1;
}

.description {
  color: var(--text-light);
  line-height: 1.3;
  margin-bottom: 1rem;
  font-size: var(--font-size-sm);
}

.skills-section {
  margin-top: 1rem;
}

.skill-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.skill-tag {
  background: rgba(255, 255, 255, 0.05);
  color: var(--text-light);
  padding: 2px 0.5rem;
  border-radius: 4px;
  font-size: var(--font-size-xs);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.skill-tag:hover {
  border-color: var(--color-white-40);
  color: var(--color-white-90);
  background: var(--color-white-10);
}

@keyframes slideInLeft {
  from {
    opacity: 0;
    transform: translateX(-30px);
  }

  to {
    opacity: 1;
    transform: translateX(0);
  }
}

/* Mobile Responsive */
@media (max-width: 768px) {
  .experience-card {
    flex-direction: column;
    gap: 0.75rem;
    padding: 0.75rem;
    margin: -0.75rem;
  }

  .experience-duration {
    width: 100%;
    order: 2;
  }

  .experience-content {
    order: 1;
  }

  .position {
    font-size: var(--body-text-size);
  }

  .date-range {
    font-size: 11px;
  }

  .company {
    font-size: var(--extra-small-size);
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .experience-card {
    gap: 1.5rem;
  }

  .experience-duration {
    width: 30%;
  }
}
</style>
