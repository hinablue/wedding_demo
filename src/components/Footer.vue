<template>
  <footer class="footer">
    <div class="container">
      <div class="content has-text-centered">
        <p>
          <strong>Hina <i class="fa fa-heart" aria-hidden="true"></i> Caline</strong> by <a href="https://blog.hinablue.me">Hina Chen</a>. The source code is licensed
          <a href="http://opensource.org/licenses/mit-license.php">MIT</a>. The website content
          is licensed <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC ANS 4.0</a>.
        </p>
        <p>
          <a class="icon" @click.prevent.stop="forkMe()">
            <i class="fa fa-github"></i>
          </a>
        </p>
      </div>
    </div>
    <fork-me-modal ref="forkMeModal"></fork-me-modal>
  </footer>
</template>

<script>
import ForkMeModal from './ForkMeModal.vue'

export default {
  name: 'Footer',
  components: {
    'fork-me-modal': ForkMeModal
  },
  methods: {
    forkMe () {
      this.$refs.forkMeModal.open = true
    }
  }
}
</script>
