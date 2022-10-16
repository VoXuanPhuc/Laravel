<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalViewGraphOpen" variant="center-5xl">
    <EcBox class="text-center">
      <!-- View Network -->
      <EcBox>
        <EcNetwork v-if="!isGeneratingGraph" :nodes="nodes" :edges="edges" :configs="configs" />
        <EcSpinner v-else></EcSpinner>
      </EcBox>
      <!-- Actions -->
      <EcFlex v-if="!isGeneratingGraph" class="justify-center mt-10">
        <EcButton class="ml-3" variant="warning" @click="handleCloseViewGraphModal">
          {{ $t("dependencyScenario.buttons.close") }}
        </EcButton>
      </EcFlex>
    </EcBox>
  </EcModalSimple>
</template>
<script>
import { useDependencyScenarioDetail } from "@/modules/dependency/use/dependency/useDependencyScenarioDetail"
import EcNetwork from "@/components/EcNetwork/index.vue"
import EcSpinner from "@/components/EcSpinner/index.vue"
import EcBox from "@/components/EcBox/index.vue"

export default {
  name: "ModalViewDependencyGraph",
  emits: ["handleCloseViewGraphModal", "handleDeleteCallback"],
  data() {
    return {
      isGeneratingGraph: false,
      nodes: {},
      edges: {},
      configs: {
        normal: {
          type: "circle",
          radius: (node) => node.size, // Use the value of each node object
          color: (node) => node.color,
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
  created() {},
  setup() {
    const { getDependencyScenario } = useDependencyScenarioDetail()
    return {
      getDependencyScenario,
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
      response?.targets?.forEach((item) => {
        const key = "target_" + item?.uid

        this.nodes[key] = {
          name: item?.name,
          color: "hotpink",
          label: true,
        }

        return item
      })

      /** Downstream  */
      response?.downstream?.forEach((item) => {
        const key = "downstream_" + item?.uid

        this.nodes[key] = {
          name: item?.name,
        }
        return item
      })

      response?.upstream?.forEach((item) => {
        const key = "upstream_" + item?.uid
        this.nodes[key] = {
          name: item?.name,
        }
        return item
      })

      // Make Edges
      response?.targets.forEach((item) => {
        const targetKey = "target_" + item?.uid

        // Links stream dependencies
        // Up stream
        response?.upstream?.forEach((upstreamItem) => {
          const upstreamNodeKey = "upstream_" + upstreamItem?.uid
          this.edges["edge" + Math.random()] = {
            source: targetKey,
            target: upstreamNodeKey,
            color: "hotpink",
          }
        })

        // Down stream
        response?.downstream?.forEach((upstreamItem) => {
          const upstreamNodeKey = "downstream_" + upstreamItem?.uid
          this.edges["edge" + Math.random()] = {
            source: targetKey,
            target: upstreamNodeKey,
            color: "gray",
            dashed: true,
          }
        })
      })
    },
  },

  watch: {
    async isModalViewGraphOpen(isOpen) {
      if (isOpen) {
        await this.fetchDependencyScenario()
      }
    },
  },
  components: { EcNetwork, EcSpinner, EcBox },
}
</script>
