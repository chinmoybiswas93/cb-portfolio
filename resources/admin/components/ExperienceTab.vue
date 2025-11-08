<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Work Experience</h2>
      <p class="tab-description">Your professional journey</p>
      <button @click="$emit('add-experience')" class="add-btn">
        <span class="btn-icon">+</span>
        Add Experience
      </button>
    </div>

    <div class="form-section">
      <transition-group name="experience-list" tag="div" class="experience-list">
        <div 
          v-for="(exp, index) in sortedExperience" 
          :key="exp.id || `exp-${index}`"
          :ref="`experience-${exp.id || index}`"
          class="experience-item-wrapper"
        >
          <ExperienceItem 
            :experience="exp"
            :experience-number="index + 1"
            :can-move-up="index > 0"
            :can-move-down="index < sortedExperience.length - 1"
            @update="(updatedData) => $emit('update-experience', getOriginalIndex(exp), updatedData)"
            @remove="$emit('remove-experience', getOriginalIndex(exp))"
            @move-up="moveUp(index)"
            @move-down="moveDown(index)"
          />
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script>
import ExperienceItem from './ExperienceItem.vue'

export default {
  name: 'ExperienceTab',
  components: {
    ExperienceItem
  },
  props: {
    experienceData: {
      type: Array,
      required: true
    }
  },
  computed: {
    sortedExperience() {
      return [...this.experienceData].sort((a, b) => (a.order_index || 0) - (b.order_index || 0))
    }
  },
  emits: ['add-experience', 'update-experience', 'remove-experience', 'update-experience-order'],
  mounted() {
    this.initializeOrderIndex()
  },
  methods: {
    initializeOrderIndex() {
      // Ensure all items have order_index values
      this.experienceData.forEach((experience, index) => {
        if (experience.order_index === undefined || experience.order_index === null) {
          experience.order_index = index + 1
        }
      })
    },

    async moveUp(currentIndex) {
      if (currentIndex === 0) return

      const items = [...this.sortedExperience]
      const currentItem = items[currentIndex]
      const previousItem = items[currentIndex - 1]

      const tempOrder = currentItem.order_index
      currentItem.order_index = previousItem.order_index
      previousItem.order_index = tempOrder

      await this.updateOrder([currentItem, previousItem])
      
      // Scroll to the moved item after animation
      this.$nextTick(() => {
        this.scrollToItem(currentItem.id, currentIndex - 1)
      })
    },

    async moveDown(currentIndex) {
      if (currentIndex === this.sortedExperience.length - 1) return

      const items = [...this.sortedExperience]
      const currentItem = items[currentIndex]
      const nextItem = items[currentIndex + 1]

      const tempOrder = currentItem.order_index
      currentItem.order_index = nextItem.order_index
      nextItem.order_index = tempOrder

      await this.updateOrder([currentItem, nextItem])
      
      // Scroll to the moved item after animation
      this.$nextTick(() => {
        this.scrollToItem(currentItem.id, currentIndex + 1)
      })
    },

    async updateOrder(itemsToUpdate) {
      try {
        const nonce = window.cbPortfolioAdmin?.nonce || window.wpApiSettings?.nonce || '';
        
        const response = await fetch(`/wp-json/cb-portfolio/v1/experience/reorder`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': nonce
          },
          body: JSON.stringify({ 
            items: itemsToUpdate.map(item => ({
              id: item.id,
              order_index: item.order_index
            }))
          })
        })

        if (!response.ok) {
          const errorText = await response.text()
          console.error('Response error:', response.status, errorText)
          throw new Error(`Server responded with ${response.status}: ${errorText}`)
        }

        const result = await response.json()
        
        // Update the original experienceData array with new order values
        const updatedExperienceData = [...this.experienceData]
        itemsToUpdate.forEach(updatedItem => {
          const originalIndex = updatedExperienceData.findIndex(exp => exp.id === updatedItem.id)
          if (originalIndex !== -1) {
            updatedExperienceData[originalIndex] = { ...updatedExperienceData[originalIndex], order_index: updatedItem.order_index }
          }
        })
        
        // Emit the updated data to the parent component
        this.$emit('update-experience-order', updatedExperienceData)
        
      } catch (error) {
        alert(`Error updating experience order: ${error.message}`)
      }
    },

    getOriginalIndex(experience) {
      return this.experienceData.findIndex(exp => exp.id === experience.id)
    },

    scrollToItem(itemId, newIndex) {
      // Wait for the animation to complete, then scroll
      setTimeout(() => {
        const element = this.$refs[`experience-${itemId}`]?.[0]
        if (element) {
          element.scrollIntoView({ 
            behavior: 'smooth', 
            block: 'center',
            inline: 'nearest'
          })
          
          // Add a brief highlight animation
          element.classList.add('highlight-moved')
          setTimeout(() => {
            element.classList.remove('highlight-moved')
          }, 1500)
        }
      }, 300)
    }
  }
}
</script>

<style scoped>
.experience-item-wrapper {
  margin-bottom: 20px;
}

/* Animation styles for list transitions */
.experience-list {
  position: relative;
}

.experience-list-move {
  transition: transform 0.3s ease;
}

.experience-list-enter-active,
.experience-list-leave-active {
  transition: all 0.3s ease;
}

.experience-list-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.experience-list-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

/* Highlight animation for moved items */
.highlight-moved {
  animation: highlightPulse 1.5s ease-in-out;
  transform: scale(1.02);
  transition: transform 0.2s ease;
  border-radius: 8px;
}

@keyframes highlightPulse {
  0% {
    box-shadow: 0 0 0 0 rgba(0, 115, 170, 0.4);
    border-radius: 8px;
  }
  50% {
    box-shadow: 0 0 0 10px rgba(0, 115, 170, 0.2);
    border-radius: 8px;
  }
  100% {
    box-shadow: 0 0 0 0 rgba(0, 115, 170, 0);
    border-radius: 8px;
  }
}
</style>