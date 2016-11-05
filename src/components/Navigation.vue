<template>
  <section class="is-fullwidth">
    <div class="menu is-visible-425">
      <ul class="menu-list">
        <li class="menu-hamburger">
          <a @click.prevent.stop="toggleMenu()">
            <div class="hamburger-icon is-pulled-right" v-bind:class="{ open: isOpen }">
              <span></span>
              <span></span>
              <span></span>
              <span></span>
            </div>
          </a>
        </li>
        <li v-if="isOpen">
          <router-link :to="{ name: 'homepage' }" exact>
            <span class="icon"><i class="fa fa-heart-o"></i></span>
            <span>首頁</span>
          </router-link>
        </li>
        <li v-if="isOpen">
          <router-link :to="{ name: 'messages' }">
            <span class="icon"><i class="fa fa-comment-o"></i></span>
            <span>留下祝福</span>
          </router-link>
        </li>
        <li v-if="isOpen">
          <router-link :to="{ name: 'invitation' }">
            <span class="icon"><i class="fa fa-envelope-open-o"></i></span>
            <span>宴客訊息</span>
          </router-link>
        </li>
        <li v-if="isOpen">
          <a @click.prevent.stop="forkMe()">
            <span class="icon"><i class="fa fa-code-fork"></i></span>
            <span>Fork me?</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="tabs is-centered is-hidden-425">
      <ul>
        <router-link tag="li" :to="{ name: 'homepage' }" exact>
          <a href="/">
            <span class="icon"><i class="fa fa-heart-o"></i></span>
            <span>首頁</span>
          </a>
        </router-link>
        <router-link tag="li" :to="{ name: 'messages' }">
          <a href="/messages">
            <span class="icon"><i class="fa fa-comment-o"></i></span>
            <span>留下祝福</span>
          </a>
        </router-link>
        <router-link tag="li" :to="{ name: 'invitation' }">
          <a href="/invitation">
            <span class="icon"><i class="fa fa-envelope-open-o"></i></span>
            <span>宴客訊息</span>
          </a>
        </router-link>
        <li>
          <a @click.prevent.stop="forkMe()">
            <span class="icon"><i class="fa fa-code-fork"></i></span>
            <span>Fork me?</span>
          </a>
        </li>
      </ul>
    </div>
    <fork-me-modal ref="forkMeModal"></fork-me-modal>
  </section>
</template>

<script>
import ForkMeModal from './ForkMeModal.vue'

export default {
  name: 'Navigation',
  components: {
    'fork-me-modal': ForkMeModal
  },
  data () {
    return {
      isOpen: false
    }
  },
  methods: {
    forkMe () {
      this.$refs.forkMeModal.open = true
    },
    toggleMenu () {
      this.isOpen = !this.isOpen
    }
  },
  mounted () {
    this.$el.querySelectorAll('li').forEach((item) => {
      window.fitText(item, false, {
        minFontSize: '14px',
        maxFontSize: '16px'
      })
    })
  }
}
</script>

<style scoped>
.tabs li .fa {
  font-size: 1em;
}
.menu-list li a {
  color: white;
}
.menu-hamburger a,
.menu-hamburger a:hover {
  background: transparent;
  height: 30px;
}
.menu-list a.is-active {
  background: white;
  color: #333333;
}
.hamburger-icon {
  display: inline-block;
  width: 2rem;
  height: 1.5rem;
  position: relative;
  margin: 0 auto;
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  -o-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: .5s ease-in-out;
  -moz-transition: .5s ease-in-out;
  -o-transition: .5s ease-in-out;
  transition: .5s ease-in-out;
  cursor: pointer;
}
.hamburger-icon span {
  display: block;
  position: absolute;
  height: 2px;
  width: 100%;
  background: white;
  border-radius: 2px;
  opacity: 1;
  left: 0;
  -webkit-transform: rotate(0deg);
  -moz-transform: rotate(0deg);
  -o-transform: rotate(0deg);
  transform: rotate(0deg);
  -webkit-transition: .25s ease-in-out;
  -moz-transition: .25s ease-in-out;
  -o-transition: .25s ease-in-out;
  transition: .25s ease-in-out;
}
.hamburger-icon span:nth-child(1) {
  top: 0px;
}

.hamburger-icon span:nth-child(2),
.hamburger-icon span:nth-child(3) {
  top: 9px;
}

.hamburger-icon span:nth-child(4) {
  top: 19px;
}

.hamburger-icon.open span:nth-child(1) {
  top: 9px;
  width: 0%;
  left: 50%;
}

.hamburger-icon.open span:nth-child(2) {
  -webkit-transform: rotate(45deg);
  -moz-transform: rotate(45deg);
  -o-transform: rotate(45deg);
  transform: rotate(45deg);
}

.hamburger-icon.open span:nth-child(3) {
  -webkit-transform: rotate(-45deg);
  -moz-transform: rotate(-45deg);
  -o-transform: rotate(-45deg);
  transform: rotate(-45deg);
}

.hamburger-icon.open span:nth-child(4) {
  top: 9px;
  width: 0%;
  left: 50%;
}
</style>
