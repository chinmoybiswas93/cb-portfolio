<template>
  <teleport to="body">
    <transition name="modal-fade">
      <div v-if="show" class="cb-modal-overlay" @click.self="$emit('cancel')">
        <transition name="modal-slide">
          <div v-if="show" class="cb-confirm-modal">
            <div class="confirm-header">
              <h3>{{ title }}</h3>
              <button @click="$emit('cancel')" class="modal-close-btn" type="button">&times;</button>
            </div>
            <div class="confirm-body">
              <p>{{ message }}</p>
            </div>
            <div class="confirm-footer">
              <button @click="$emit('cancel')" class="btn btn-secondary" type="button">{{ cancelText }}</button>
              <button @click="$emit('confirm')" class="btn btn-danger" type="button">{{ confirmText }}</button>
            </div>
          </div>
        </transition>
      </div>
    </transition>
  </teleport>
</template>

<script>
export default {
  name: 'ConfirmModal',
  props: {
    show: { type: Boolean, default: false },
    title: { type: String, default: 'Confirm Action' },
    message: { type: String, default: 'Are you sure?' },
    confirmText: { type: String, default: 'Delete' },
    cancelText: { type: String, default: 'Cancel' }
  },
  emits: ['confirm', 'cancel'],
  watch: {
    show(val) {
      document.body.style.overflow = val ? 'hidden' : ''
    }
  },
  beforeUnmount() {
    document.body.style.overflow = ''
  }
}
</script>

<style>
.cb-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 100000;
  padding: 20px;
}

.cb-confirm-modal {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
  width: 100%;
  max-width: 440px;
  overflow: hidden;
}

.cb-confirm-modal .confirm-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 20px 24px 0;
}

.cb-confirm-modal .confirm-header h3 {
  margin: 0;
  font-size: 18px;
  font-weight: 600;
  color: #1d2327;
}

.cb-confirm-modal .confirm-body {
  padding: 16px 24px;
}

.cb-confirm-modal .confirm-body p {
  margin: 0;
  font-size: 14px;
  color: #646970;
  line-height: 1.6;
}

.cb-confirm-modal .confirm-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 16px 24px 20px;
}

.modal-close-btn {
  background: none;
  border: none;
  font-size: 24px;
  color: #646970;
  cursor: pointer;
  padding: 0;
  line-height: 1;
  transition: color 0.2s;
}

.modal-close-btn:hover {
  color: #1d2327;
}

.btn {
  padding: 8px 18px;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-secondary {
  background: #f0f0f1;
  color: #1d2327;
}

.btn-secondary:hover {
  background: #dcdcde;
}

.btn-danger {
  background: #d63638;
  color: #fff;
}

.btn-danger:hover {
  background: #b32d2e;
}

.btn-primary {
  background: #2271b1;
  color: #fff;
}

.btn-primary:hover {
  background: #135e96;
}

/* Transitions */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}

.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}

.modal-slide-enter-active,
.modal-slide-leave-active {
  transition: all 0.25s ease;
}

.modal-slide-enter-from {
  opacity: 0;
  transform: translateY(-20px) scale(0.96);
}

.modal-slide-leave-to {
  opacity: 0;
  transform: translateY(10px) scale(0.96);
}
</style>
