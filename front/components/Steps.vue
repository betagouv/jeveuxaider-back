<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <nav aria-label="Progress">
    <ol class="overflow-hidden">
      <li
        v-for="(step, stepIdx) in steps"
        :key="step.name"
        :class="[stepIdx !== steps.length - 1 ? 'pb-10' : '', 'relative']"
      >
        <template v-if="step.status === 'complete'">
          <div
            v-if="stepIdx !== steps.length - 1"
            class="-ml-px absolute mt-0.5 w-0.5 h-full bg-blue-800"
            style="top: 1rem; left: 1rem"
            aria-hidden="true"
          />
          <a :href="step.href" class="relative flex items-center group">
            <span class="h-9 flex items-center">
              <div
                class="relative z-10 w-8 h-8 flex items-center justify-center bg-blue-800 rounded-full group-hover:bg-blue-900"
              >
                <div
                  class="w-5 h-5 text-white"
                  v-html="
                    require('@/assets/images/icones/heroicon/check.svg?include')
                  "
                />
              </div>
            </span>
            <span class="ml-4 min-w-0 flex flex-col">
              <span class="text-xs font-semibold tracking-wide uppercase">{{
                step.name
              }}</span>
              <span class="text-sm text-gray-500">{{ step.description }}</span>
            </span>
          </a>
        </template>
        <template
          v-else-if="step.status === 'current'"
          condition="step.status === 'current'"
        >
          <div
            v-if="stepIdx !== steps.length - 1"
            class="-ml-px absolute mt-0.5 w-0.5 h-full bg-gray-300"
            style="top: 1rem; left: 1rem"
            aria-hidden="true"
          />
          <a
            :href="step.href"
            class="relative flex items-center group"
            aria-current="step"
          >
            <span class="h-9 flex items-center" aria-hidden="true">
              <span
                class="relative z-10 w-8 h-8 flex items-center justify-center bg-white border-2 border-blue-800 rounded-full"
              >
                <span class="h-2.5 w-2.5 bg-blue-800 rounded-full" />
              </span>
            </span>
            <span class="ml-4 min-w-0 flex flex-col">
              <span
                class="text-xs font-semibold tracking-wide uppercase text-blue-800"
                >{{ step.name }}</span
              >
              <span class="text-sm text-gray-500">{{ step.description }}</span>
            </span>
          </a>
        </template>
        <template v-else>
          <div
            v-if="stepIdx !== steps.length - 1"
            class="-ml-px absolute mt-0.5 w-0.5 h-full bg-gray-300"
            style="top: 1rem; left: 1rem"
            aria-hidden="true"
          />
          <a :href="step.href" class="relative flex items-center group">
            <span class="h-9 flex items-center" aria-hidden="true">
              <span
                class="relative z-10 w-8 h-8 flex items-center justify-center bg-white border-2 border-gray-300 rounded-full group-hover:border-gray-400"
              >
                <span
                  class="h-2.5 w-2.5 bg-transparent rounded-full group-hover:bg-gray-300"
                />
              </span>
            </span>
            <span class="ml-4 min-w-0 flex flex-col">
              <span
                class="text-xs font-semibold tracking-wide uppercase text-gray-500"
                >{{ step.name }}</span
              >
              <span class="text-sm text-gray-500">{{ step.description }}</span>
            </span>
          </a>
        </template>
      </li>
    </ol>
  </nav>
</template>

<script>
export default {
  props: {
    steps: {
      type: Array,
      required: true,
    },
  },
}
</script>
