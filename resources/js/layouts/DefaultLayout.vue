<template>
  <div class="bg-gray-100">
    <AppHeader />
    <router-view :key="$route.fullPath" />
    <AppFooter />
  </div>
</template>

<script>
export default {
  name: 'DefaultLayout',
}
</script>

<script>
window.axeptioSettings = {
  clientId: '5fc77c519875a6702af31184',
  cookiesVersion: 'ga_fb',
}
;(function (d, s) {
  var t = d.getElementsByTagName(s)[0],
    e = d.createElement(s)
  e.async = true
  e.src = '//static.axept.io/sdk.js'
  t.parentNode.insertBefore(e, t)
})(document, 'script')
</script>
