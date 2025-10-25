<template>
  <div v-if="show" class="toast" :class="type">
    <div class="toast-content">
      <span class="toast-icon">
        <span v-if="type === 'success'">✓</span>
        <span v-else-if="type === 'error'">✕</span>
        <span v-else>ℹ</span>
      </span>
      <span class="toast-message">{{ message }}</span>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ToastNotification',
  props: {
    show: {
      type: Boolean,
      default: false
    },
    type: {
      type: String,
      default: 'info',
      validator: (value) => ['success', 'error', 'info'].includes(value)
    },
    message: {
      type: String,
      default: ''
    }
  }
}
</script>

<style scoped>
/* Toast Notifications */
.toast {
  position: fixed;
  top: 80px;
  right: 24px;
  background: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border-left: 4px solid;
  z-index: 1000;
  animation: slideInRight 0.3s ease-out;
  max-width: 400px;
}

.toast.success {
  border-left-color: #00a32a;
}

.toast.error {
  border-left-color: #d63638;
}

.toast.info {
  border-left-color: #2271b1;
}

.toast-content {
  display: flex;
  align-items: center;
  padding: 16px 20px;
  gap: 12px;
}

.toast-icon {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
}

.toast.success .toast-icon {
  background: #00a32a;
  color: white;
}

.toast.error .toast-icon {
  background: #d63638;
  color: white;
}

.toast.info .toast-icon {
  background: #2271b1;
  color: white;
}

.toast-message {
  font-size: 14px;
  color: #1d2327;
  font-weight: 500;
}

@keyframes slideInRight {
  from {
    transform: translateX(100%);
    opacity: 0;
  }

  to {
    transform: translateX(0);
    opacity: 1;
  }
}

</style>
