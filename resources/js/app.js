import "./bootstrap"
import { createApp } from "vue"

import GameCard from "./components/GameCard.vue"
import GameGallery from "./components/GameGallery.vue"
import ReviewForm from "./components/ReviewForm.vue"
import ReviewList from "./components/ReviewList.vue"
import FilterSidebar from "./components/FilterSidebar.vue"
import Carousel from "./components/Carousel.vue"
import MessageToast from "./components/MessageToast.vue"

const app = createApp({
  data() {
    return {
      message: null,
    }
  },
  methods: {
    showMessage(message) {
      this.message = message
    },
    clearMessage() {
      this.message = null
    },
  },
})

app.component("game-card", GameCard)
app.component("game-gallery", GameGallery)
app.component("review-form", ReviewForm)
app.component("review-list", ReviewList)
app.component("filter-sidebar", FilterSidebar)
app.component("carousel", Carousel)
app.component("message-toast", MessageToast)

app.mount("#app")
