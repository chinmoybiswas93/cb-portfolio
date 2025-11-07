<template>
  <div class="experience-item" :style="{ animationDelay: `${index * 0.1}s` }">
    <div class="experience-card">
      <!-- Left Column: Duration (25%) -->
      <div class="experience-duration">
        <span class="date-range" :class="{ current: experience.current }">
          {{ experience.start_date }} <span>-</span> {{ experience.current ? 'PRESENT' : experience.end_date }}
        </span>
      </div>

      <!-- Right Column: Content (75%) -->
      <div class="experience-content">
        <div class="experience-header">
          <h3 class="position">
            {{ experience.position }} <span></span>
            <a v-if="experience.company_website" :href="experience.company_website" target="_blank"
              rel="noopener noreferrer" class="company-link">
              {{ experience.company }} <span class="external-link">↗</span>
            </a>
            <span v-else class="company-text">
              {{ experience.company }}
            </span>
          </h3>
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
  display: block;
  margin-bottom: 2rem;
  /* Increased spacing between items */
  animation: slideInLeft 0.6s ease-out;
  animation-fill-mode: both;
  width: 100%;
}

.experience-card {
  display: flex;
  gap: 1rem;
  border-radius: 8px;
  cursor: pointer;
  /* No padding by default - content takes full width */
  padding: 0;
  margin: 0;
  position: relative;
  transition: padding 0.3s ease;
}

.experience-card::before {
  content: '';
  position: absolute;
  top: -1rem;
  left: -1.5rem;
  right: -1.5rem;
  bottom: -1rem;
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border-radius: 8px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  opacity: 0;
  transition: opacity 0.3s ease;
  z-index: -1;
  pointer-events: none;
}

.experience-card:hover::before {
  opacity: 1;
}

.experience-card:hover {
  position: relative;
  z-index: 10;
}

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
  line-height: var(--line-height-base);
}

.date-range.current {
  color: var(--color-white-80);
  font-weight: var(--font-weight-medium);
}

.experience-content {
  flex: 1;
  min-width: 0;
}

.experience-header {
  margin-bottom: 0.75rem;
}

.position {
  font-size: var(--font-size-base);
  font-weight: var(--font-weight-normal);
  color: var(--color-text-primary);
  margin: 0 0 0.25rem 0;
  line-height: var(--line-height-base);
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.position>span {
  display: inline-block;
  width: 3px;
  height: 3px;
  background-color: var(--color-text-primary);
  border-radius: 50%;
  margin-left: 2px;
}

.company-link {
  color: var(--color-white-80);
  text-decoration: none;
  transition: color 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: var(--font-size-sm);
  font-weight: var(--font-weight-normal);
  line-height: var(--line-height-base);
}

.company-link:hover {
  color: var(--color-white-90);
}

.external-link {
  font-size: 14px;
  opacity: 0.7;
  transition: opacity 0.2s ease;
}

.experience-card:hover .external-link {
  opacity: 1;
}

.description {
  color: var(--text-light);
  line-height: var(--line-height-base);
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
    flex-direction: column-reverse;
    gap: 0.5rem;
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
    flex-direction: column;
    align-items: flex-start;
  }

  .date-range {
    font-size: 11px;
  }

  .company {
    font-size: var(--extra-small-size);
  }

  .position>span {
    display: none;
  }
}

/* Tablet Responsive */
@media (max-width: 1024px) and (min-width: 769px) {
  .experience-card {
    gap: 1.5rem;
    /* No padding by default on tablet */
    padding: 0;
    margin: 0;
  }

  .experience-card:hover {
    /* Add padding and extend beyond container on tablet */
    padding: 1rem;
    margin: 0 -1rem;
  }

  .experience-duration {
    width: 30%;
  }
}
</style>
