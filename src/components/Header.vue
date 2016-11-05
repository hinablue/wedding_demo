<template>
  <header class="header">
    <section class="hero is-medium is-danger is-bold">
      <navigation class="is-visible-425"></navigation>
      <div class="hero-body">
        <div class="container">
          <div class="media">
            <div class="media-left">
              <figure class="image is-128x128">
                <img src="../assets/logo.png">
              </figure>
            </div>
            <div class="media-content">
              <h1 class="title">
                <span>Hina <span class="icon is-medium"><i class="fa fa-heart" aria-hidden="true"></i></span> Caline</span>
              </h1>
              <h2 class="subtitle">
                <span>是的，<br>我們要結婚了！</span>
              </h2>
            </div>
          </div>
        </div>
      </div>
    </section>
  </header>
</template>

<script>
import Navigation from './Navigation.vue'

export default {
  name: 'Header',
  components: {
    'navigation': Navigation
  }
}
</script>
