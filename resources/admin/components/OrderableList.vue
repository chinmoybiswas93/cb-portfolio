<template>
  <div class="orderable-list">
    <div class="list-header">
      <h3>{{ title }}</h3>
      <button @click="addNewItem" class="btn-add">Add New {{ itemType }}</button>
    </div>
    
    <div class="list-items">
      <div 
        v-for="(item, index) in sortedItems" 
        :key="item.id"
        class="list-item"
      >
        <div class="item-content">
          <slot :item="item" :index="index"></slot>
        </div>
        
        <div class="item-controls">
          <div class="order-controls">
            <button 
              @click="moveUp(index)"
              :disabled="index === 0"
              class="btn-order"
              title="Move Up"
            >
              ↑
            </button>
            <span class="order-number">{{ index + 1 }}</span>
            <button 
              @click="moveDown(index)"
              :disabled="index === sortedItems.length - 1"
              class="btn-order"
              title="Move Down"
            >
              ↓
            </button>
          </div>
          
          <div class="action-controls">
            <button @click="editItem(item)" class="btn-edit">Edit</button>
            <button @click="deleteItem(item.id)" class="btn-delete">Delete</button>
          </div>
        </div>
      </div>
      
      <div v-if="sortedItems.length === 0" class="empty-state">
        <p>No {{ itemType.toLowerCase() }} items found. Add your first one!</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderableList',
  props: {
    items: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    itemType: {
      type: String,
      required: true
    }
  },
  computed: {
    sortedItems() {
      return [...this.items].sort((a, b) => (a.order_index || 0) - (b.order_index || 0))
    }
  },
  methods: {
    async moveUp(currentIndex) {
      if (currentIndex === 0) return

      const items = [...this.sortedItems]
      const currentItem = items[currentIndex]
      const previousItem = items[currentIndex - 1]

      const tempOrder = currentItem.order_index
      currentItem.order_index = previousItem.order_index
      previousItem.order_index = tempOrder

      await this.updateOrder([currentItem, previousItem])
    },

    async moveDown(currentIndex) {
      if (currentIndex === this.sortedItems.length - 1) return

      const items = [...this.sortedItems]
      const currentItem = items[currentIndex]
      const nextItem = items[currentIndex + 1]

      const tempOrder = currentItem.order_index
      currentItem.order_index = nextItem.order_index
      nextItem.order_index = tempOrder

      await this.updateOrder([currentItem, nextItem])
    },

    async updateOrder(itemsToUpdate) {
      try {
        const endpoint = this.itemType.toLowerCase() === 'experience' ? 'experience' : 'projects'
        const response = await fetch(`/wp-json/cb-portfolio/v1/${endpoint}/reorder`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-WP-Nonce': window.cbPortfolioAdmin?.nonce || ''
          },
          body: JSON.stringify({ 
            items: itemsToUpdate.map(item => ({
              id: item.id,
              order_index: item.order_index
            }))
          })
        })

        if (response.ok) {
          this.$emit('items-reordered', this.sortedItems)
          this.showNotification('Order updated successfully', 'success')
        } else {
          throw new Error('Failed to update order')
        }
      } catch (error) {
        this.showNotification('Error updating order', 'error')
        console.error('Order update error:', error)
      }
    },

    editItem(item) {
      this.$emit('edit-item', item)
    },

    deleteItem(itemId) {
      if (confirm('Are you sure you want to delete this item?')) {
        this.$emit('delete-item', itemId)
      }
    },

    addNewItem() {
      this.$emit('add-item')
    },

    showNotification(message, type) {
      this.$emit('notification', { message, type })
    }
  }
}
</script>

<style scoped>
.orderable-list {
  background: white;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  margin-bottom: 20px;
}

.list-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  border-bottom: 1px solid #ddd;
  padding-bottom: 15px;
}

.list-header h3 {
  margin: 0;
  color: #23282d;
  font-size: 18px;
}

.list-items {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.list-item {
  display: flex;
  align-items: flex-start;
  padding: 15px;
  border: 1px solid #e1e1e1;
  border-radius: 6px;
  background: #fafafa;
  transition: background-color 0.2s ease;
}

.list-item:hover {
  background: #f0f0f1;
}

.item-content {
  flex: 1;
  margin-right: 15px;
}

.item-controls {
  display: flex;
  flex-direction: column;
  gap: 10px;
  align-items: flex-end;
}

.order-controls {
  display: flex;
  align-items: center;
  gap: 5px;
  background: white;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 5px;
}

.btn-order {
  width: 30px;
  height: 30px;
  border: none;
  background: #0073aa;
  color: white;
  border-radius: 3px;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s ease;
}

.btn-order:hover:not(:disabled) {
  background: #005a87;
}

.btn-order:disabled {
  background: #ddd;
  color: #999;
  cursor: not-allowed;
}

.order-number {
  min-width: 25px;
  text-align: center;
  font-size: 12px;
  font-weight: bold;
  color: #666;
}

.action-controls {
  display: flex;
  gap: 8px;
}

.btn-add, .btn-edit, .btn-delete {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
  font-weight: 500;
  transition: background-color 0.2s ease;
}

.btn-add {
  background: #00a32a;
  color: white;
}

.btn-add:hover {
  background: #008a20;
}

.btn-edit {
  background: #0073aa;
  color: white;
}

.btn-edit:hover {
  background: #005a87;
}

.btn-delete {
  background: #d63638;
  color: white;
}

.btn-delete:hover {
  background: #b32d2e;
}

.empty-state {
  text-align: center;
  padding: 40px 20px;
  color: #666;
  background: #f9f9f9;
  border-radius: 6px;
  border: 2px dashed #ddd;
}

.empty-state p {
  margin: 0;
  font-size: 14px;
}

@media (max-width: 768px) {
  .list-item {
    flex-direction: column;
    gap: 15px;
  }

  .item-controls {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    width: 100%;
  }

  .action-controls {
    flex-direction: column;
  }
}
</style>