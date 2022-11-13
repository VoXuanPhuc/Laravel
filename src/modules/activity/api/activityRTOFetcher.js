import fetcher from "@/modules/core/api/fetcher"

// Recovery times
export const fetchRecoveryTimeOptions = async () => {
  return fetcher.get(`/identity/api/v1/recovery-times/all`)
}

export const updateActivityRTO = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}/recovery-time-and-disruption-scenario`, payload)
}

// Disruption scenarios
export const fetchDisruptionScenarios = async () => {
  return fetcher.get(`/identity/api/v1/disruption-scenarios/all`)
}
