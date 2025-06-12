<template>
  <div class="game-card">
    <div class="game-card-image">
      <img :src="'/storage/' + game.primary_image.image_path" :alt="game.title">
      <div v-if="game.discount_price" class="game-card-discount">
        -{{ discountPercentage }}%
      </div>
    </div>
    <div class="game-card-content">
      <h3 class="game-card-title">
        <a :href="'/games/' + game.id">{{ game.title }}</a>
      </h3>
      <div class="game-card-genres">
        <span v-for="genre in game.genres" :key="genre.id" class="game-card-genre">
          {{ genre.name }}
        </span>
      </div>
      <div class="game-card-price">
        <span v-if="game.discount_price" class="game-card-old-price">{{ formatPrice(game.price) }}</span>
        <span class="game-card-current-price">{{ formatPrice(game.discount_price || game.price) }}</span>
      </div>
      <div class="game-card-actions">
        <button @click="addToCart" class="game-card-cart-btn">В корзину</button>
        <button @click="addToWishlist" class="game-card-wishlist-btn">
          <i class="fa fa-heart"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    game: {
      type: Object,
      required: true
    }
  },
  computed: {
    discountPercentage() {
      if (!this.game.discount_price) return 0;
      return Math.round((1 - (this.game.discount_price / this.game.price)) * 100);
    }
  },
  methods: {
    formatPrice(price) {
      return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0
      }).format(price);
    },
    addToCart() {
      // Send a POST request to add the game to the cart
      axios.post(`/cart/add/${this.game.id}`)
        .then(response => {
          // Show a success message
          this.$emit('message', {
            type: 'success',
            text: 'Игра добавлена в корзину'
          });
        })
        .catch(error => {
          if (error.response && error.response.status === 401) {
            // Redirect to login page if not authenticated
            window.location.href = '/login';
          } else {
            // Show an error message
            this.$emit('message', {
              type: 'error',
              text: 'Произошла ошибка при добавлении игры в корзину'
            });
          }
        });
    },
    addToWishlist() {
      // Send a POST request to add the game to the wishlist
      axios.post(`/wishlist/add/${this.game.id}`)
        .then(response => {
          // Show a success message
          this.$emit('message', {
            type: 'success',
            text: 'Игра добавлена в список желаемого'
          });
        })
        .catch(error => {
          if (error.response && error.response.status === 401) {
            // Redirect to login page if not authenticated
            window.location.href = '/login';
          } else {
            // Show an error message
            this.$emit('message', {
              type: 'error',
              text: 'Произошла ошибка при добавлении игры в список желаемого'
            });
          }
        });
    }
  }
}
</script>
