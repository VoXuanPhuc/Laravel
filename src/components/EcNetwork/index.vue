<template>
  <VNetworkGraph :nodes="nodes" :edges="edges" :layouts="layouts" :configs="configData" ref="graph">
    <slot></slot>
  </VNetworkGraph>
</template>

<script>
import * as VNetworkGraph from "v-network-graph"
import "v-network-graph/lib/style.css"
import { reactive, ref } from "vue"

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

    configData() {
      return reactive(VNetworkGraph.defineConfigs(this.configs))
    },

    layoutData() {
      return {
        nodes: {
          node1: { x: 0, y: 0 },
          node2: { x: 80, y: 80 },
          node3: { x: 160, y: 0 },
          node4: { x: 240, y: 80 },
          node5: { x: 320, y: 0 },
        },
      }
    },
  },

  setup() {
    const graph = ref()

    return {
      graph,
    }
  },

  created() {},
  methods: {
    /**
     * Download SVG
     */
    downloadSvg() {
      if (!this.graph) {
        return
      }

      console.log(this.graph)
      const data = this.graph.getAsSvg()

      const url = URL.createObjectURL(new Blob([data], { type: "octet/stream" }))
      const a = document.createElement("a")
      a.href = url
      a.download = "graph.svg" // filename to download
      a.click()
      window.URL.revokeObjectURL(url)
    },
  },
}
</script>
