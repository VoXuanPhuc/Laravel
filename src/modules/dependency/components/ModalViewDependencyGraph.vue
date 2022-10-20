<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalViewGraphOpen" :variant="modalVariant">
    <!-- Actions -->
    <EcFlex v-if="!isGeneratingGraph" class="items-center justify-end">
      <!-- Explaination -->
      <EcFlex class="items-center">
        <EcBox class="h-3 w-3 bg-c1-800 mr-1"></EcBox>
        <EcText>Target</EcText>
      </EcFlex>

      <EcFlex class="items-center ml-4">
        <EcBox class="h-3 w-3 bg-cError-600 mr-1"></EcBox>
        <EcText>Upstream</EcText>
      </EcFlex>

      <EcFlex class="items-center ml-4">
        <EcBox class="h-3 w-3 bg-cSuccess-600 mr-1"></EcBox>
        <EcText>Downstream</EcText>
      </EcFlex>

      <!-- Download -->
      <EcButton variant="primary-sm" class="ml-2" @click="downloadSVG" iconPrefix="Download">
        {{ $t("dependencyScenario.buttons.downloadSVG") }}
      </EcButton>

      <!-- Close -->
      <EcButton variant="transparent" class="text-cError-400 font-semibold ml-6" @click="handleCloseViewGraphModal">
        {{ $t("dependencyScenario.buttons.close") }}
      </EcButton>
    </EcFlex>

    <!-- View Network -->
    <EcBox class="h-full text-center">
      <EcNetwork
        class="h-[95%]"
        v-if="!isGeneratingGraph"
        :nodes="nodes"
        :edges="edges"
        :configs="configs"
        :layouts="layouts"
        ref="ecNetworkRef"
      />
      <EcSpinner v-else />
    </EcBox>

    <EcFlex>
      <EcBox> </EcBox>
    </EcFlex>
  </EcModalSimple>
</template>
<script>
import { useDependencyScenarioDetail } from "@/modules/dependency/use/dependency/useDependencyScenarioDetail"
import EcNetwork from "@/components/EcNetwork/index.vue"
import EcSpinner from "@/components/EcSpinner/index.vue"
import EcBox from "@/components/EcBox/index.vue"
import EcText from "@/components/EcText/index.vue"
import { ref } from "vue"

export default {
  name: "ModalViewDependencyGraph",
  emits: ["handleCloseViewGraphModal", "handleDeleteCallback"],
  data() {
    return {
      isGeneratingGraph: false,
      colors: {
        target: "#110663",
        upstream: "#e6143a",
        downstream: "#29996a",
      },
      nodes: {},
      edges: {},
      layouts: {
        nodes: {},
      },
      configs: {
        node: {
          selectable: true,
          normal: {
            type: "circle",
            radius: (node) => node.size || 16,
            // for type is "rect" -->
            width: 32,
            height: 32,
            borderRadius: 4,
            // <-- for type is "rect"
            strokeWidth: 0,
            strokeColor: "#000000",
            strokeDasharray: "0",
            color: (node) => node.color,
          },
        },

        edge: {
          selectable: true,
          gap: 3,
          width: 1,
          type: "straight",
          summarize: true,
          normal: {
            width: 3,
            color: (edge) => edge.color || "#4466cc",
            dasharray: (edge) => (edge.dashed ? "4" : "0"),
            linecap: "butt",
            animate: (edge) => !!edge.animate,
            animationSpeed: 15,
          },
          marker: {
            source: {},
            target: { type: ([edge, _stroke]) => edge.type || "none", height: 2, margin: -10 },
          },
        },
      },
    }
  },
  props: {
    isModalViewGraphOpen: {
      type: Boolean,
      default: false,
    },
    dependencyScenarioUid: {
      type: String,
      default: null,
    },
    scenarioName: {
      type: String,
      default: "",
    },
  },
  computed: {
    modalVariant() {
      return this.isGeneratingGraph ? "center-2xl" : "center-full"
    },
  },
  created() {},
  setup() {
    const ecNetworkRef = ref()
    const { getDependencyScenario } = useDependencyScenarioDetail()
    return {
      getDependencyScenario,
      ecNetworkRef,
    }
  },
  methods: {
    /**
     * Get dependency
     */
    async fetchDependencyScenario() {
      this.isGeneratingGraph = true
      const response = await this.getDependencyScenario(this.dependencyScenarioUid)

      if (response) {
        this.parseDataToGraph(response)
      } else {
        this.handleCloseViewGraphModal()
      }

      this.isGeneratingGraph = false
    },

    /**
     * Cancel add new resource
     */
    async handleDeleteDependencyScenario() {},

    /**
     * Close cancel modal
     */
    handleCloseViewGraphModal() {
      this.$emit("handleCloseViewGraphModal")
    },

    /**
     *
     * @param {*} response
     */
    parseDataToGraph(response) {
      response?.targets?.forEach((item, idx) => {
        const key = "target_" + item?.uid

        this.nodes[key] = {
          name: item?.type + ": " + item?.name,
          color: this.colors.target,
          label: true,
          size: 20,
        }

        // Set target layout
        this.layouts.nodes[key] = { x: 0, y: idx * 80 }

        return item
      })

      // Upstream
      response?.upstream?.forEach((item, idx) => {
        const key = "upstream_" + item?.uid
        this.nodes[key] = {
          name: item?.type + ": " + item?.name,
          color: this.colors.upstream,
          size: 16,
        }

        // Make the upstreaam higher than target
        if (idx === 0) {
          idx = -1.5
        }

        // Set upstream layout
        this.layouts.nodes[key] = { x: -200, y: idx * 60 }
        return item
      })

      /** Downstream  */
      response?.downstream?.forEach((item, idx) => {
        const key = "downstream_" + item?.uid

        this.nodes[key] = {
          name: item?.type + ": " + item?.name,
          color: this.colors.downstream,
          label: true,
          size: 16,
        }

        // Set downstream layout
        this.layouts.nodes[key] = { x: 200, y: idx * 60 }

        return item
      })

      // Make Edges
      response?.targets.forEach((item, idx) => {
        const fromKey = "target_" + item?.uid

        if (idx > 0) {
          const toKey = "target_" + response?.targets[idx - 1]?.uid
          this.edges["edge" + Math.random()] = {
            source: fromKey,
            target: toKey,
            color: this.colors.target,
          }
        }
      })

      // Link the last target to upstream and downstream

      // Links stream dependencies
      const targetNodeCnt = response?.targets?.length
      const theMiddleNode = targetNodeCnt > 1 ? Math.floor(targetNodeCnt / 2) : 0

      const targetKey = "target_" + response?.targets[theMiddleNode]?.uid
      // Up stream
      response?.upstream?.forEach((upstreamItem) => {
        const upstreamNodeKey = "upstream_" + upstreamItem?.uid
        this.edges["edge" + Math.random()] = {
          source: upstreamNodeKey,
          target: targetKey,
          color: this.colors.upstream,
          animate: true,
          dashed: true,
          type: "angle",
        }
      })

      // Down stream
      response?.downstream?.forEach((upstreamItem) => {
        const upstreamNodeKey = "downstream_" + upstreamItem?.uid
        this.edges["edge" + Math.random()] = {
          source: targetKey,
          target: upstreamNodeKey,
          color: this.colors.downstream,
          animate: true,
          dashed: true,
          type: "angle",
        }
      })
    },

    /**
     * Download SVG
     */
    downloadSVG() {
      this.ecNetworkRef.downloadSvg()
    },
  },

  watch: {
    async isModalViewGraphOpen(isOpen) {
      if (isOpen) {
        this.nodes = {}
        this.edges = {}
        await this.fetchDependencyScenario()
      }
    },
  },
  components: { EcNetwork, EcSpinner, EcBox, EcText },
}
</script>
