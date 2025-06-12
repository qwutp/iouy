<template>
  <div class="game-gallery">
    <div class="game-gallery-main">
      <img :src="'/storage/' + currentImage.image_path" :alt="gameTitle" class="game-gallery-main-image">
    </div>
    <div class="game-gallery-thumbnails">
      <div 
        v-for="(image, index) in images" 
        :key="image.id" 
        class="game-gallery-thumbnail" 
        :class="{ 'active': currentIndex === index }"
        @click="setCurrentImage(index)"
      >
        <img :src="'/storage/' + image.image_path" :alt="gameTitle + ' thumbnail ' + (index + 1)">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array,
      required: true
    },
    gameTitle: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      currentIndex: 0
    }
  },
  computed: {
    currentImage() {
      return this.images[this.currentIndex] || this.images[0];
    }
  },
  methods: {
    setCurrentImage(index) {
      this.currentIndex = index;
    }
  }
}
</script>
