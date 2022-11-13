import fetcher from "@/modules/core/api/fetcher"

// MTDP
export const getMTDPTimePeriodOptions = async () => {
  return fetcher.get(`/identity/api/v1/tolerable-time-periods/all`)
}

export const updateActivityMTDP = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}/tolerable-period-disruptions`, payload)
}
