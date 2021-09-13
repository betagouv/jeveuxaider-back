<template>
  <div class="h-24 mt-6">
    <div class="flex items-center justify-center">
      <div
        v-for="(step, index) in steps"
        :key="step.slug"
        class="relative flex items-center w-8 h-8"
        :class="[
          { 'mr-24': index != steps.length - 1 },
          { 'cursor-pointer': !isLastStep && index < currentStepIndex },
        ]"
        @click="onClick(index)"
      >
        <div
          class="flex-none rounded-full border-2 w-8 h-8 flex items-center justify-center border-[#C7C7C7]"
          :class="[
            {
              '!bg-primary':
                index < currentStepIndex ||
                currentStepIndex == steps.length - 1,
            },
            { '!border-primary': index <= currentStepIndex },
          ]"
        >
          {{ step.icon }}
        </div>

        <div
          v-if="index != steps.length - 1"
          class="w-24 h-[2px] bg-[#C7C7C7] absolute left-8"
          :class="[
            {
              '!bg-primary': index < currentStepIndex,
            },
          ]"
        ></div>

        <div
          class="absolute bottom-0 translate-y-full -translate-x-1/2 left-1/2"
        >
          <div
            class="pt-2 text-[11px] text-[#A7A7B0] font-bold"
            :class="[{ '!text-primary': index <= currentStepIndex }]"
          >
            {{ step.label }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {}
  },
  computed: {
    steps() {
      return this.$store.getters['temoignage/steps']
    },
    currentStep() {
      return this.$store.getters['temoignage/step']
    },
    currentStepIndex() {
      return this.$store.getters['temoignage/stepIndex']
    },
    lastStep() {
      return this.steps[this.steps.length - 1]
    },
    isLastStep() {
      return this.currentStep.slug == this.lastStep.slug
    },
  },
  methods: {
    onClick(index) {
      if (!this.isLastStep && index < this.currentStepIndex) {
        this.$store.commit('temoignage/setStep', this.steps[index])
      }
    },
  },
}
</script>
