<template>
  <div class="mx-auto w-full" style="max-width: 420px;">
    <div>
      <router-link to="/">
        <img
          class="h-8 w-auto"
          src="/images/logo-header-dark-small.png"
          alt="Réserve Civique"
        />
      </router-link>
      <h2 class="mt-8 text-3xl leading-tight font-extrabold text-gray-900">
        Site en
        <span class="text-blue-800">maintenance</span>
      </h2>
    </div>
    <div class="mt-8 border-t border-gray-200 pt-8" />
    <div class>
      <p>
        Une opération de maintenance est en cours. Nous faisons au plus vite. 🚀
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Maintenance',
}
</script>
