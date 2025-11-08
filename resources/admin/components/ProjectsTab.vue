<template>
  <div class="tab-content">
    <div class="tab-header">
      <h2>Projects</h2>
      <p class="tab-description">Showcase your work</p>
      <button @click="$emit('add-project')" class="add-btn">
        <span class="btn-icon">+</span>
        Add Project
      </button>
    </div>

    <div class="form-section">
      <transition-group name="project-list" tag="div" class="project-list">
        <div 
          v-for="(project, index) in sortedProjects" 
          :key="project.id || `project-${index}`"
          :ref="`project-${project.id || index}`"
          class="project-item-wrapper"
        >
          <ProjectItem 
            :project="project"
            :project-number="index + 1"
            :can-move-up="index > 0"
            :can-move-down="index < sortedProjects.length - 1"
            @update="(updatedData) => $emit('update-project', getOriginalIndex(project), updatedData)"
            @remove="$emit('remove-project', getOriginalIndex(project))"
            @move-up="moveUp(index)"
            @move-down="moveDown(index)"
          />
        </div>
      </transition-group>
    </div>
  </div>
</template>

<script>
import ProjectItem from './ProjectItem.vue'

export default {
  name: 'ProjectsTab',
  components: {
    ProjectItem
  },
  props: {
    projectsData: {
      type: Array,
      required: true
    }
  },
  computed: {
    sortedProjects() {
      return [...this.projectsData].sort((a, b) => (a.order_index || 0) - (b.order_index || 0))
    }
  },
  mounted() {
    this.initializeOrderIndex()
  },
  emits: ['add-project', 'update-project', 'remove-project', 'update-projects-order'],
  methods: {
    initializeOrderIndex() {
      // Ensure all items have order_index values
      this.projectsData.forEach((project, index) => {
        if (project.order_index === undefined || project.order_index === null) {
          project.order_index = index + 1
        }
      })
    },
    
    async moveUp(currentIndex) {
      if (currentIndex === 0) return

      const items = [...this.sortedProjects]
      const currentItem = items[currentIndex]
      const previousItem = items[currentIndex - 1]

      const tempOrder = currentItem.order_index || currentIndex + 1
      currentItem.order_index = previousItem.order_index || currentIndex
      previousItem.order_index = tempOrder

      await this.updateOrder([currentItem, previousItem])
      
      // Scroll to the moved item after animation
      this.$nextTick(() => {
        this.scrollToItem(currentItem.id, currentIndex - 1)
      })
    },

    async moveDown(currentIndex) {
      if (currentIndex === this.sortedProjects.length - 1) return

      const items = [...this.sortedProjects]
      const currentItem = items[currentIndex]
      const nextItem = items[currentIndex + 1]

      const tempOrder = currentItem.order_index || currentIndex + 1
      currentItem.order_index = nextItem.order_index || currentIndex + 2
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
        
        const response = await fetch(`/wp-json/cb-portfolio/v1/projects/reorder`, {
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
        
        // Update the original projectsData array with new order values
        const updatedProjectsData = [...this.projectsData]
        itemsToUpdate.forEach(updatedItem => {
          const originalIndex = updatedProjectsData.findIndex(p => p.id === updatedItem.id)
          if (originalIndex !== -1) {
            updatedProjectsData[originalIndex] = { ...updatedProjectsData[originalIndex], order_index: updatedItem.order_index }
          }
        })
        
        // Emit the updated data to the parent component
        this.$emit('update-projects-order', updatedProjectsData)
        
      } catch (error) {
        alert(`Error updating project order: ${error.message}`)
      }
    },

    getOriginalIndex(project) {
      return this.projectsData.findIndex(p => p.id === project.id)
    },

    scrollToItem(itemId, newIndex) {
      // Wait for the animation to complete, then scroll
      setTimeout(() => {
        const element = this.$refs[`project-${itemId}`]?.[0]
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
.project-item-wrapper {
  margin-bottom: 20px;
}

/* Animation styles for list transitions */
.project-list {
  position: relative;
}

.project-list-move {
  transition: transform 0.3s ease;
}

.project-list-enter-active,
.project-list-leave-active {
  transition: all 0.3s ease;
}

.project-list-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.project-list-leave-to {
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