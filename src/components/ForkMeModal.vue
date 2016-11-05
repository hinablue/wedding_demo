<template>
  <div class="modal" v-bind:class="isActive">
    <div class="modal-background" @click.prevent="open = false"></div>
    <div class="modal-content">
      <article class="message is-danger">
        <div class="message-header">
          Fork Me?
        </div>
        <div class="message-body">
          <p>認真？畢竟是婚禮，還是自己寫一個比較好啦 XD</p>
          <p><small><strike>或是你私底下問我好了 www</strike></small></p>
        </div>
      </article>
    </div>
    <button class="modal-close" @click.prevent="open = false"></button>
  </div>
</template>

<script>
export default {
  name: 'ForkMeModal',
  data () {
    return {
      open: false
    }
  },
  computed: {
    isActive () {
      return { 'is-active': this.open }
    }
  }
}
</script>
