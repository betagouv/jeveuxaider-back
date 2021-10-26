<template>
  <div
    v-scroll-lock="true"
    role="menu"
    aria-orientation="vertical"
    aria-labelledby="main-menu"
    class="menu-height fixed w-full flex flex-col flex-1 left-0 top-[80px] overflow-y-auto z-[-1]"
  >
    <div class="flex flex-col bg-gray-50 flex-1">
      <HeaderMenu />
    </div>
  </div>
</template>

<script>
export default {}
</script>

<style lang="postcss" scoped>
.menu-height {
  height: calc(100% - 80px);
}
</style>
