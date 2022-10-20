<template>
  <EcFlex>
    <EcFlex v-for="(item, index) in items" :key="index" class="items-center">
      <EcText class="text-c1-500 mr-3 ml-3 cursor-pointer hover:underline" @click="navigateTo(item)">
        {{ item.label }}
      </EcText>
      <EcIcon v-if="index < items?.length - 1" icon="ChevronRight" width="14" />
    </EcFlex>
  </EcFlex>
</template>

<script>
import { goto } from "../../composables"

export default {
  name: "RBreadcrumb",
  props: {
    items: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  methods: {
    navigateTo(item) {
      goto(item.navigation, {
        params: item.params,
      })
    },
  },
}
</script>
