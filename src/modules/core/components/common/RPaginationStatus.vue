<template>
  <EcBox v-show="totalCount > 0">
    <EcText>
      {{ $t("core.showing") }} {{ (currentPage - 1) * limit + 1 }} - {{ Math.min(currentPage * limit, totalCount) }}
      {{ $t("core.of") }}
      {{ totalCount }}
    </EcText>
  </EcBox>
</template>

<script>
export default {
  name: "RPaginationStatus",
  props: {
    currentPage: {
      type: Number,
      default: 1,
    },
    limit: {
      type: Number,
      default: 1,
    },
    totalCount: {
      type: Number,
      default: 0,
    },
  },
}
</script>
