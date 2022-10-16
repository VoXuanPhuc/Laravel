<template>
  <VNetworkGraph :nodes="nodes" :edges="edges" :layouts="layouts" :configs="configs">
    <slot></slot>
  </VNetworkGraph>
</template>

<script>
import VNetworkGraph from "v-network-graph"
import "v-network-graph/lib/style.css"

export default {
  comppnents: [VNetworkGraph],
  props: {
    nodes: {
      type: Object,
      required: true,
      default: () => {},
    },
    edges: {
      type: Object,
      required: true,
      default: () => {},
    },
    layouts: {
      type: Object,
      default: () => {},
    },
    configs: {
      type: Object,
      default: () => {},
    },
    variant: {
      type: String,
      default: "default",
    },
  },

  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcNetwork",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },
}
</script>
