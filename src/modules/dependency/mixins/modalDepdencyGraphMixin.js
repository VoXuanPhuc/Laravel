const modalDepdencyGraphMixin = {
  computed: {
    check() {
      return ""
    },
  },
  methods: {
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
}

export default { modalDepdencyGraphMixin }
