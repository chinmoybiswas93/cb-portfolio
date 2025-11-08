<template>
  <div class="media-uploader">
    <div v-if="imageUrl" class="image-preview">
      <img :src="imageUrl" :alt="alt" />
      <button @click="removeImage" class="remove-image-btn" type="button">
        <span>×</span>
      </button>
    </div>
    
    <div v-else class="upload-placeholder">
      <div class="upload-icon">📷</div>
      <p>No image selected</p>
    </div>
    
    <div class="upload-actions">
      <button @click="openMediaLibrary" class="upload-btn" type="button">
        {{ imageUrl ? 'Change Image' : 'Select Image' }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MediaUploader',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    alt: {
      type: String,
      default: 'Project Image'
    }
  },
  emits: ['update:modelValue'],
  computed: {
    imageUrl() {
      return this.modelValue;
    }
  },
  methods: {
    openMediaLibrary() {
      // Check if wp.media is available
      if (typeof wp === 'undefined' || !wp.media) {
        console.error('WordPress media library is not available');
        alert('WordPress media library is not available. Please refresh the page.');
        return;
      }

      // Create the media frame
      const mediaFrame = wp.media({
        title: 'Select Project Image',
        button: {
          text: 'Use this image'
        },
        multiple: false,
        library: {
          type: 'image'
        }
      });

      // Handle image selection
      mediaFrame.on('select', () => {
        const attachment = mediaFrame.state().get('selection').first().toJSON();
        this.$emit('update:modelValue', attachment.url);
      });

      // Open the media frame
      mediaFrame.open();
    },
    
    removeImage() {
      this.$emit('update:modelValue', '');
    }
  }
}
</script>

<style scoped>
.media-uploader {
  border: 2px dashed #e1e5e9;
  border-radius: 8px;
  padding: 15px;
  text-align: center;
  background: #fafbfc;
  transition: border-color 0.2s;
}

.media-uploader:hover {
  border-color: #c3c4c7;
}

.image-preview {
  position: relative;
  margin-bottom: 10px;
}

.image-preview img {
  max-width: 100%;
  max-height: 150px;
  border-radius: 6px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.remove-image-btn {
  position: absolute;
  top: -8px;
  right: -8px;
  background: #d63638;
  color: white;
  border: none;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 14px;
  font-weight: bold;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
}

.remove-image-btn:hover {
  background: #b32d2e;
  transform: scale(1.1);
}

.upload-placeholder {
  margin-bottom: 10px;
}

.upload-icon {
  font-size: 24px;
  margin-bottom: 6px;
  opacity: 0.6;
}

.upload-placeholder p {
  margin: 0;
  color: #646970;
  font-style: italic;
  font-size: 13px;
}

.upload-actions {
  margin-top: 8px;
}

.upload-btn {
  background: #2271b1;
  color: white;
  border: none;
  padding: 6px 12px;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
  transition: background 0.2s;
}

.upload-btn:hover {
  background: #135e96;
}

.upload-btn:focus {
  outline: 2px solid #2271b1;
  outline-offset: 2px;
}
</style>