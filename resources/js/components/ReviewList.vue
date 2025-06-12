<template>
  <div class="review-list">
    <h3 class="review-list-title">Отзывы ({{ reviews.length }})</h3>
    <div v-if="reviews.length === 0" class="review-list-empty">
      Нет отзывов. Будьте первым, кто оставит отзыв!
    </div>
    <div v-else class="review-list-items">
      <div v-for="review in reviews" :key="review.id" class="review-item">
        <div class="review-item-header">
          <div class="review-item-user">{{ review.user.name }}</div>
          <div class="review-item-rating">
            <span v-for="star in 5" :key="star" class="rating-star" :class="{ 'active': star <= review.rating }">★</span>
          </div>
          <div class="review-item-date">{{ formatDate(review.created_at) }}</div>
        </div>
        <div class="review-item-content">{{ review.content }}</div>
        <div class="review-item-actions">
          <button 
            @click="likeReview(review)" 
            class="review-item-like" 
            :class="{ 'active': review.is_liked_by_user }"
          >
            <i class="fa fa-thumbs-up"></i> {{ review.likes_count }}
          </button>
          <button 
            v-if="canDeleteReview(review)" 
            @click="deleteReview(review)" 
            class="review-item-delete"
          >
            <i class="fa fa-trash"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    gameId: {
      type: Number,
      required: true
    },
    initialReviews: {
      type: Array,
      default: () => []
    },
    userId: {
      type: Number,
      default: null
    },
    isAdmin: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      reviews: this.initialReviews
    }
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('ru-RU');
    },
    likeReview(review) {
      if (!this.userId) {
        // Redirect to login page if not authenticated
        window.location.href = '/login';
        return;
      }
      
      if (review.is_liked_by_user) {
        // Unlike the review
        axios.delete(`/reviews/${review.id}/unlike`)
          .then(response => {
            // Update the review in the list
            review.is_liked_by_user = false;
            review.likes_count--;
          })
          .catch(error => {
            this.$emit('message', {
              type: 'error',
              text: 'Произошла ошибка при удалении лайка'
            });
          });
      } else {
        // Like the review
        axios.post(`/reviews/${review.id}/like`)
          .then(response => {
            // Update the review in the list
            review.is_liked_by_user = true;
            review.likes_count++;
          })
          .catch(error => {
            this.$emit('message', {
              type: 'error',
              text: 'Произошла ошибка при добавлении лайка'
            });
          });
      }
    },
    canDeleteReview(review) {
      return this.isAdmin || review.user_id === this.userId;
    },
    deleteReview(review) {
      if (confirm('Вы уверены, что хотите удалить этот отзыв?')) {
        axios.delete(`/reviews/${review.id}`)
          .then(response => {
            // Remove the review from the list
            this.reviews = this.reviews.filter(r => r.id !== review.id);
            
            this.$emit('message', {
              type: 'success',
              text: 'Отзыв успешно удален'
            });
          })
          .catch(error => {
            this.$emit('message', {
              type: 'error',
              text: 'Произошла ошибка при удалении отзыва'
            });
          });
      }
    },
    loadReviews() {
      axios.get(`/api/games/${this.gameId}/reviews`)
        .then(response => {
          this.reviews = response.data;
        })
        .catch(error => {
          this.$emit('message', {
            type: 'error',
            text: 'Произошла ошибка при загрузке отзывов'
          });
        });
    }
  },
  mounted() {
    // If no initial reviews were provided, load them
    if (this.initialReviews.length === 0) {
      this.loadReviews();
    }
  }
}
</script>
