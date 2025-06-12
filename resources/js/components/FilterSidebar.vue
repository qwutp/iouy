<template>
  <div class="filter-sidebar">
    <h3 class="filter-sidebar-title">Фильтры</h3>
    <form @submit.prevent="applyFilters">
      <div class="filter-section">
        <h4 class="filter-section-title">Жанры</h4>
        <div class="filter-section-content">
          <div v-for="genre in genres" :key="genre.id" class="filter-checkbox">
            <input 
              type="checkbox" 
              :id="'genre-' + genre.id" 
              :value="genre.id" 
              v-model="selectedGenres"
            >
            <label :for="'genre-' + genre.id">{{ genre.name }}</label>
          </div>
        </div>
      </div>
      
      <div class="filter-section">
        <h4 class="filter-section-title">Цена</h4>
        <div class="filter-section-content">
          <div class="filter-price-range">
            <div class="filter-price-input">
              <label for="min-price">От:</label>
              <input 
                type="number" 
                id="min-price" 
                v-model.number="minPrice" 
                min="0" 
                step="100"
              >
            </div>
            <div class="filter-price-input">
              <label for="max-price">До:</label>
              <input 
                type="number" 
                id="max-price" 
                v-model.number="maxPrice" 
                min="0" 
                step="100"
              >
            </div>
          </div>
        </div>
      </div>
      
      <div class="filter-section">
        <h4 class="filter-section-title">Дополнительно</h4>
        <div class="filter-section-content">
          <div class="filter-checkbox">
            <input type="checkbox" id="on-sale" v-model="onSale">
            <label for="on-sale">Со скидкой</label>
          </div>
        </div>
      </div>
      
      <div class="filter-actions">
        <button type="submit" class="filter-apply-btn">Применить</button>
        <button type="button" @click="resetFilters" class="filter-reset-btn">Сбросить</button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    genres: {
      type: Array,
      required: true
    },
    initialFilters: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      selectedGenres: this.initialFilters.genre ? [parseInt(this.initialFilters.genre)] : [],
      minPrice: this.initialFilters.min_price || '',
      maxPrice: this.initialFilters.max_price || '',
      onSale: this.initialFilters.on_sale === '1'
    }
  },
  methods: {
    applyFilters() {
      const filters = {};
      
      if (this.selectedGenres.length > 0) {
        filters.genre = this.selectedGenres[0]; // For simplicity, we'll use only the first selected genre
      }
      
      if (this.minPrice) {
        filters.min_price = this.minPrice;
      }
      
      if (this.maxPrice) {
        filters.max_price = this.maxPrice;
      }
      
      if (this.onSale) {
        filters.on_sale = 1;
      }
      
      this.$emit('filters-applied', filters);
    },
    resetFilters() {
      this.selectedGenres = [];
      this.minPrice = '';
      this.maxPrice = '';
      this.onSale = false;
      
      this.$emit('filters-applied', {});
    }
  }
}
</script>
