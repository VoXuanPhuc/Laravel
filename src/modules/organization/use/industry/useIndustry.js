import * as api from "@/readybc/composables/api/apiIndustries"

export function useIndustry() {
  const getIndustries = async () => {
    try {
      const { data } = await api.fetchIndustries()

      return data
    } catch (error) {
      return error
    }
  }

  const getTransformedIndustries = (industries) => {
    if (!industries) {
      return []
    }

    return industries.map((industry) => {
      return {
        value: industry.uid,
        name: industry.name,
      }
    })
  }

  return {
    getIndustries,
    getTransformedIndustries,
  }
}
