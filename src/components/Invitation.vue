<template>
  <div class="container has-text-centered">
  	<p>有收到喜帖的應該會知道（欸</p>
  </div>
</template>

<script>
export default {
  name: 'Invitation',
  mounted () {
    window.fitText(this.$el, false, {
      minFontSize: '14px',
      maxFontSize: '20px'
    })
  }
}
</script>
