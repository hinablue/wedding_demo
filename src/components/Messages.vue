<template>
  <div class="is-clearfix">
    <div class="container">
      <section class="has-text-centered" v-if="!fbLogin">
        <button type="button" class="button is-danger" @click.prevent="loginFB()">我也要留個話</button>
      </section>
      <article class="media" v-bind:class="{ 'is-disabled': sendingMessage }" v-else>
        <figure class="media-left">
          <p class="image is-64x64">
            <img v-bind:src="facebook.avatar">
          </p>
        </figure>
        <div class="media-content">
          <div class="content">
            <p>Hello, <strong>{{ facebook.name }}</strong></p>
          </div>
          <label class="label">留下想說的話給我們</label>
          <p class="control">
            <textarea class="textarea" v-model="messageContent" placeholder="寫點什麼東西吧..."></textarea>
          </p>
          <br>
          <label class="label">然後可以貼個 Youtube / Viemo 影片連結</label>
          <p class="control">
            <div class="container responsive-intrinsic-container" v-if="youtubeId">
              <iframe :src="'https://www.youtube.com/embed/' + youtubeId" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="container responsive-intrinsic-container" v-if="vimeoId">
              <iframe :src="'https://player.vimeo.com/video/' + vimeoId" frameborder="0" allowfullscreen></iframe>
            </div>
            <input class="input" type="text" v-model="messageUrl" placeholder="https://..." @blur="checkMessageUrl()">
          </p>
          <br>
          <label class="label">或是上傳相片</label>
          <p class="control">
            <figure class="image" v-show="isImageFile">
              <img src="http://placekitten.com/g/128/128" id="preview-image-file">
            </figure>
            <div class="notification is-warning has-text-centered" id="file-uploader">
              <p>你可以按這裡選取檔案，或是直接將相片拖曳到這裡上傳。</p>
              <p>目前只有支援 PNG, JPG, GIF 格式，檔案請不要超過 5 MB 感恩。</p>
            </div>
          </p>
          <br>
          <nav class="level">
            <div class="level-left">
              <div class="level-item">
                <a class="button is-info is-danger" v-bind:class="{ 'is-loading': sendingMessage }" @click.prevent="submitMessage()">送出祝福</a>
              </div>
            </div>
          </nav>
        </div>
      </article>
    </div>
    <hr>
    <masonry-cards ref="masonryCards"></masonry-cards>
  </div>
</template>

<script>
const request = require('superagent')

import Flow from '@flowjs/flow.js'
import Cards from './Cards.vue'

let flow = new Flow({
  target: '/api/fileuploader',
  singleFile: true,
  allowDuplicateUploads: true,
  testChunks: false,
  successStatuses: [200, 201, 202, 204]
})

export default {
  name: 'Messages',
  components: {
    'masonry-cards': Cards
  },
  data () {
    return {
      messageUrl: '',
      messageContent: '',
      sendingMessage: false,
      isImageFile: false,
      isVideoFile: false,
      youtubeId: false,
      vimeoId: false,
      fbLogin: false,
      file: '',
      facebook: {
        id: '',
        name: '',
        avatar: 'http://placekitten.com/g/128/128'
      }
    }
  },
  methods: {
    saveMessage () {
      request
        .post('/api/messages')
        .send({
          id: this.facebook.id,
          name: this.facebook.name,
          messages: this.messageContent,
          url: this.messageUrl,
          file: this.file
        }).end((err, res) => {
          this.sendingMessage = false
          if (err) {
            this.$swal({
              title: '喔歐！',
              type: 'error',
              text: '你的祝福好像沒有送到，可以再試一次～'
            })
            return false
          }

          let response = JSON.parse(res.text)
          if (response.status !== 'ok') {
            this.$swal({
              title: '喔歐！',
              type: 'error',
              text: '你的祝福好像沒有送到，可以再試一次～'
            })
            return false
          }

          this.$refs.masonryCards.messages.items.unshift({
            id: response.results.id,
            fbAppId: response.results.fbAppId,
            file: response.results.file,
            url: response.results.url,
            content: response.results.messages,
            createdAt: response.results.createdAt
          })

          this.$nextTick(() => {
            flow.files.forEach((file) => {
              flow.removeFile(file)
            })

            this.messageUrl = ''
            this.messageContent = ''
            this.isImageFile = false
            this.isVideoFile = false
            this.youtubeId = false
            this.vimeoId = false
            this.file = ''

            this.$swal({
              title: '好！',
              type: 'success',
              text: '收到你的祝福了～'
            })

            setTimeout(() => {
              this.$refs.masonryCards.masonry.reloadItems()
              this.$refs.masonryCards.masonry.layout()
            }, 100)
          })
        })
    },
    submitMessage () {
      this.sendingMessage = true
      if (this.messageContent === '') {
        this.$swal({
          title: '喔歐！',
          type: 'error',
          text: '留下一些祝福的話給我們吧～'
        })
        this.sendingMessage = false
        return false
      }
      if (this.fbLogin === false) {
        this.$swal({
          title: '喔歐！',
          type: 'error',
          text: '請先用 FB 登入一下～'
        })
        this.sendingMessage = false
        return false
      }

      if (flow.files.length > 0) {
        flow.upload()
      } else {
        this.saveMessage()
      }
    },
    checkMessageUrl () {
      this.youtubeId = false
      this.vimeoId = false

      let matches = this.messageUrl.match(/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/i)
      if (matches && typeof matches[2] !== 'undefined') {
        this.youtubeId = matches[2]
        this.vimeoId = false
      } else {
        matches = this.messageUrl.match(/^.*(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/i)
        if (matches && typeof matches[3] !== 'undefined') {
          this.vimeoId = matches[3]
          this.youtubeId = false
        }
      }
    },
    prepareFacebookData () {
      let _that = this
      window.FB.api('/me', function (response) {
        if (response && !response.error) {
          _that.fbLogin = true
          _that.facebook.id = response.id
          _that.facebook.name = response.name
          _that.facebook.avatar = '//graph.facebook.com/' + response.id + '/picture?type=large'

          setTimeout(() => {
            _that.$nextTick(() => {
              const zone = document.getElementById('file-uploader')
              flow.assignBrowse(zone)
              flow.assignDrop(zone)
            })
          }, 100)
        } else {
          _that.$swal({
            title: '喔歐！',
            type: 'error',
            text: 'Facebook 登入壞了～'
          })
        }
      })
    },
    loginFB () {
      if ('FB' in window) {
        let _that = this
        window.FB.login(function (response) {
          if (response.authResponse) {
            _that.prepareFacebookData()
          } else {
            _that.$swal({
              title: '喔歐！',
              type: 'error',
              text: 'Facebook 登入壞了～'
            })
          }
        })
      } else {
        this.$swal({
          title: '喔歐！',
          type: 'error',
          text: 'Facebook 登入壞了～'
        })
      }
    }
  },
  mounted () {
    flow.on('fileAdded', (file) => {
      // 5MB File limit.
      if (file.size >= 1024 * 5000) {
        this.$swal({
          title: '喔歐！',
          type: 'error',
          text: '你的檔案太大了！上傳 ' + Math.ceil(file.size / 1000 / 1024) + 'MB 是要逼死誰！'
        })
        flow.removeFile(file)
        return false
      }
      // Extension is not the jpg, png and gif.
      const ext = file.getExtension()
      if (['jpg', 'jpeg', 'png', 'gif'].indexOf(ext) === -1) {
        this.$swal({
          title: '喔歐！',
          type: 'error',
          text: '假的！你的檔案看起來不是圖片！'
        })
        flow.removeFile(file)
        return false
      }
      if ('FileReader' in window) {
        let fileReader = new window.FileReader()
        fileReader.readAsDataURL(file.file)
        fileReader.onload = (event) => {
          this.$nextTick(() => {
            document.getElementById('preview-image-file').src = event.target.result
            this.isImageFile = true
          })
        }
      }
    })
    flow.on('fileSuccess', (file, message) => {
      const res = JSON.parse(message)
      if (res.status === 'ok') {
        this.file = res.files[0]['url']
        this.saveMessage()
      }
    })
    flow.on('error', (message, file) => {
      flow.removeFile(file)
      this.sendingMessage = false
      this.isImageFile = false
      this.file = ''
      this.$swal({
        title: '喔歐！',
        type: 'error',
        text: '暫時的！你的檔案不能上傳！'
      })
    })

    setTimeout(() => {
      if ('FB' in window) {
        let _that = this
        window.FB.getLoginStatus(function (response) {
          if (response.status === 'connected') {
            _that.fbLogin = true
            _that.prepareFacebookData()
          }
        })
      }
    }, 100)
  }
}
</script>

<style scoped>
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
#preview-image-file {
  max-width: 80%;
  margin: 0 auto;
}
</style>
