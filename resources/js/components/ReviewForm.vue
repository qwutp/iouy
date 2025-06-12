<template>
  <div class="review-form">
    <h3 class="review-form-title">Оставить отзыв</h3>
    <form @submit.prevent="submitReview">
      <div class="review-form-rating">
        <label>Оценка:</label>
        <div class="rating-stars">
          <span 
            v-for="star in 5" 
            :key="star" 
            class="rating-star" 
            :class="{ 'active': star <= rating }"
            @click="setRating(star)"
          >
            ★
          </span>
        </div>
      </div>
      <div class="review-form-content">
        <label for="review-content">Отзыв:</label>
        <textarea 
          id="review-content" 
          v-model="content" 
          rows="4" 
          placeholder="Напишите ваш отзыв здесь..."
          required
        ></textarea>
      </div>
      <div class="review-form-actions">
        <button type="submit" class="review-form-submit">Отправить</button>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    gameId: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      rating: 5,
      content: ''
    }
  },
  methods: {
    setRating(value) {
      this.rating = value;
    },
    submitReview() {
      // Send a POST request to submit the review
      axios.post(`/games/${this.gameId}/reviews`, {
        rating: this.rating,
        content: this.content
      })
        .then(response => {
          // Show a success message
          this.$emit('message', {
            type: 'success',
            text: 'Отзыв успешно отправлен'
          });
          
          // Reset the form
          this.rating = 5;
          this.content = '';
          
          // Emit an event to refresh the reviews
          this.$emit('review-submitted');
        })
        .catch(error => {
          if (error.response && error.response.status === 401) {
            // Redirect to login page if not authenticated
            window.location.href = '/login';
          } else if (error.response && error.response.data.message) {
            // Show the error message from the server
            this.$emit('message', {
              type: 'error',
              text: error.response.data.message
            });
          } else {
            // Show a generic error message
            this.$emit('message', {
              type: 'error',
              text: 'Произошла ошибка при отправке отзыва'
            });
          }
        });
    }
  }
}
</script>
