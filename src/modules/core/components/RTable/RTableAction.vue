<template>
  <EcBox v-click-outside="hideShowMore" class="relative w-10 h-10" @click="handleClickMore()">
    <EcButton variant="transparent-rounded" class="h-full">
      <EcIcon icon="DotsHorizontal" />
    </EcButton>
    <EcBox v-if="isShowMore" class="w-32 absolute z-10 right-0 py-2 bg-cWhite rounded-lg shadow" style="top: 2rem">
      <slot></slot>
    </EcBox>
  </EcBox>
</template>

<script>
export default {
  name: "RTableAction",

  data() {
    return {
      isShowMore: false,
    }
  },
  methods: {
    handleClickMore() {
      this.isShowMore = !this.isShowMore
    },
    hideShowMore() {
      this.isShowMore = false
    },
  },
}
</script>
