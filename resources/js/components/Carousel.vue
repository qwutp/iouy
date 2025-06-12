<template>
  <div class="carousel">
    <div class="carousel-inner" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
      <div v-for="(slide, index) in slides" :key="index" class="carousel-slide">
        <img :src="slide.image" :alt="slide.title" class="carousel-image">
        <div class="carousel-caption">
          <h3>{{ slide.title }}</h3>
          <p>{{ slide.description }}</p>
          <a v-if="slide.link" :href="slide.link" class="carousel-btn">Подробнее</a>
        </div>
      </div>
    </div>
    <button @click="prevSlide" class="carousel-control carousel-control-prev">
      <i class="fa fa-chevron-left"></i>
    </button>
    <button @click="nextSlide" class="carousel-control carousel-control-next">
      <i class="fa fa-chevron-right"></i>
    </button>
    <div class="carousel-indicators">
      <button 
        v-for="(slide, index) in slides" 
        :key="index" 
        @click="goToSlide(index)" 
        class="carousel-indicator" 
        :class="{ 'active': index === currentIndex }"
      ></button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    slides: {
      type: Array,
      required: true
    },
    autoplay: {
      type: Boolean,
      default: true
    },
    interval: {
      type: Number,
      default: 5000
    }
  },
  data() {
    return {
      currentIndex: 0,
      timer: null
    }
  },
  methods: {
    nextSlide() {
      this.currentIndex = (this.currentIndex + 1) % this.slides.length;
    },
    prevSlide() {
      this.currentIndex = (this.currentIndex - 1 + this.slides.length) % this.slides.length;
    },
    goToSlide(index) {
      this.currentIndex = index;
    },
    startAutoplay() {
      if (this.autoplay && this.slides.length > 1) {
        this.timer = setInterval(this.nextSlide, this.interval);
      }
    },
    stopAutoplay() {
      clearInterval(this.timer);
    }
  },
  mounted() {
    this.startAutoplay();
  },
  beforeUnmount() {
    this.stopAutoplay();
  }
}
</script>
