<template>
  <div class="container has-text-centered">
    <p>身為一個阿宅，寫個關於婚禮的網站，應該不是很稀奇。</p>
    <p>所以我就寫了一個。</p>
  </div>
</template>

<script>
export default {
  name: 'Homepage',
  mounted () {
    window.fitText(this.$el, false, {
      minFontSize: '14px',
      maxFontSize: '20px'
    })
  }
}
</script>