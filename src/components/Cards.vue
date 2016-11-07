<template>
<div class="masonry is-clearfix" id="masonry">
  <div class="card" v-bind:id="entry.id"
    v-bind:class="{ 'card--video': isVideo(entry.url) }"
    v-for="entry in messages.items"
    @click.prevent.stop="toggleCard(entry.id)">
    <div class="card-image" v-if="isPhotoOrVideo(entry)">
      <div class="container responsive-intrinsic-container" v-if="isYoutubeId(entry.url)">
        <iframe :src="'https://www.youtube.com/embed/' + isYoutubeId(entry.url)" frameborder="0" allowfullscreen></iframe>
      </div>
      <div class="container responsive-intrinsic-container" v-if="isVimeoId(entry.url)">
        <iframe :src="'https://player.vimeo.com/video/' + isVimeoId(entry.url)" frameborder="0" allowfullscreen></iframe>
      </div>
      <figure class="image" v-if="isFile(entry.file)">
        <img v-bind:src="entry.file">
      </figure>
    </div>
    <div class="card-content">
      <div class="media">
        <div class="media-left">
          <figure class="image is-32x32">
            <img v-bind:src="fbAvatar(entry.fbAppId)" alt="">
          </figure>
        </div>
        <div class="media-content">
          <p class="title is-5">{{ entry.name }}</p>
        </div>
      </div>

      <div class="content">
        <p v-html="entry.content"></p>
        <br>
        <small>{{ entry.createdAt | distanceInWordsToNow }}</small>
      </div>
    </div>
  </div>
</div>
</template>

<script>
const request = require('superagent')
import Vue from 'vue'
import Masonry from 'masonry-layout'
import { distanceInWordsToNow } from 'date-fns'

Vue.filter('distanceInWordsToNow', (date) => {
  return distanceInWordsToNow(date)
})

export default {
  name: 'Cards',
  data () {
    return {
      messages: {
        items: []
      },
      masonry: null
    }
  },
  methods: {
    toggleCard (id) {
      this.$nextTick(() => {
        document.getElementById(id).classList.toggle('expand')
        this.masonry.layout()
      })
    },
    fbAvatar (id) {
      return '//graph.facebook.com/' + id + '/picture?type=large'
    },
    isFile (file) {
      return file !== '' && file !== '/content/'
    },
    isYoutubeId (url) {
      const matches = url.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/i)
      if (matches && typeof matches[2] !== 'undefined') {
        return matches[2]
      }
      return false
    },
    isVimeoId (url) {
      const matches = url.match(/^.*(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/i)
      if (matches && typeof matches[3] !== 'undefined') {
        return matches[3]
      }
      return false
    },
    isVideo (url) {
      return !!(this.isYoutubeId(url) || this.isVimeoId(url))
    },
    isPhotoOrVideo (entry) {
      return (entry.file !== '' && entry.file !== '/content/') || this.isVideo(entry.url)
    }
  },
  mounted () {
    request.get('/api/messages')
      .end((err, res) => {
        if (err) {
          // Loading failed.
        } else {
          const response = JSON.parse(res.text)
          if (response.status === 'ok') {
            this.messages = response.results
            setTimeout(() => {
              this.$nextTick(() => {
                this.masonry = new Masonry(
                  document.getElementById('masonry'),
                  {
                    itemSelector: '.card',
                    percentPosition: true,
                    fitWidth: false,
                    gutter: 10
                  }
                )
              })
            }, 500)
          }
        }
      })
  }
}
</script>

<style scoped>
.card {
  width: 375px;
  max-width: 100%;
  margin: 0 auto 10px auto;
  float: left;
}
.card.card--video {
  width: 600px;
  max-width: 100%;
}
.card.expand {
  width: 900px;
  max-width: 100%;
}
.responsive-intrinsic-container {
  position: relative;
  height: 0;
  overflow: hidden;
  padding-bottom: 56.25%;
  width: 100%;
  max-width: 100%;
  margin-bottom: 1rem;
}
.responsive-intrinsic-container iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}
</style>
