<template>
  <div v-if="visible" class="message-toast" :class="message.type">
    <div class="message-toast-content">{{ message.text }}</div>
    <button @click="close" class="message-toast-close">×</button>
  </div>
</template>

<script>
export default {
  props: {
    message: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      visible: false,
      timer: null
    }
  },
  watch: {
    message(newVal) {
      if (newVal && newVal.text) {
        this.show();
      }
    }
  },
  methods: {
    show() {
      this.visible = true;
      
      // Clear any existing timer
      clearTimeout(this.timer);
      
      // Auto-hide after 5 seconds
      this.timer = setTimeout(() => {
        this.close();
      }, 5000);
    },
    close() {
      this.visible = false;
      this.$emit('close');
    }
  },
  beforeUnmount() {
    clearTimeout(this.timer);
  }
}
</script>
