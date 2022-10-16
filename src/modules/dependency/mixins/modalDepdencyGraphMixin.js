const modalDepdencyGraphMixin = {
  methods: {
    /**
     *
     * @param {*} response
     */
    parseDataToGraph(response) {
      debugger
      response?.targets?.map((item) => {
        const key = "target_".item?.uid

        this.nodes[key] = {
          name: item?.name,
        }

        return item
      })
    },
  },
}

export default { modalDepdencyGraphMixin }
