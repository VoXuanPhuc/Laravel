const dependencyMixin = {
  computed: {
    /**
     * Filter target dependencies
     * @returns
     */
    filteredDependencyFactors() {
      return this.dependencyFactors
    },

    /**
     * Filtered UpStream dependencies
     */
    filteredUpstreamDependencyFactors() {
      const selectedTargetDependencyUids = []

      this.dependencyScenario?.targets.map((targetDependency) => {
        selectedTargetDependencyUids.push(targetDependency.uid)

        return targetDependency
      })

      return this.dependencyFactors.map((item) => {
        const clonedItem = { ...item }

        clonedItem.data = clonedItem?.data?.filter((dataItem) => {
          return !selectedTargetDependencyUids.includes(dataItem?.uid)
        })

        return clonedItem
      })
    },

    /**
     * Filtered Downstream dependencies
     */
    filteredDownstreamDependencyFactors() {
      const selectedTargetDependencyUids = []

      this.dependencyScenario?.targets.map((targetDependency) => {
        selectedTargetDependencyUids.push(targetDependency.uid)

        return targetDependency
      })

      this.dependencyScenario?.upstream.map((upstreamDependency) => {
        selectedTargetDependencyUids.push(upstreamDependency.uid)

        return upstreamDependency
      })

      return this.dependencyFactors.map((item) => {
        const clonedItem = { ...item }

        clonedItem.data = clonedItem?.data?.filter((dataItem) => {
          return !selectedTargetDependencyUids.includes(dataItem?.uid)
        })

        return clonedItem
      })
    },
  },
}

export default dependencyMixin
