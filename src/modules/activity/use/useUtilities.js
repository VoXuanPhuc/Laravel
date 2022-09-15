import * as api from "@/readybc/composables/api/apiUtilities"

export function useUtilities() {
  /**
   *
   * @returns
   */
  const getUtilities = async () => {
    try {
      const { datax } = await api.fetchUtilities()
      const data = [
        {
          uid: "12343",
          name: "Electricity",
        },
        {
          uid: "1234e33",
          name: "Internet",
        },
      ]
      console.log(datax)
      return data
    } catch (error) {
      return error
    }
  }

  return {
    getUtilities,
  }
}
