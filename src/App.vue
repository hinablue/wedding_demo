<template>
  <div id="app" class="container is-fluid">
    <love-header></love-header>
    <section class="section main">
      <navigation class="is-hidden-425"></navigation>
      <router-view></router-view>
    </section>
    <love-footer></love-footer>
  </div>
</template>

<script>
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'
import Navigation from './components/Navigation.vue'

export default {
  name: 'app',
  components: {
    'love-header': Header,
    'love-footer': Footer,
    'navigation': Navigation
  }
}
</script>

<style>
.main {
  padding-top: 0.5rem;
}
@media screen and (max-width: 425px) {
  .is-hidden-425 {
    display: none !important;
  }
}
@media screen and (min-width: 426px) {
  .is-visible-425 {
    display: none !important;
  }
}
</style>
