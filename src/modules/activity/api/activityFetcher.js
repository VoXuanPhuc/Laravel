import fetcher from "@/modules/core/api/fetcher"

export const createNewActivity = async (payload) => {
  return fetcher.post(`identity/api/v1/activities`, payload)
}

export const fetchActivity = async (uid) => {
  return fetcher.get(`identity/api/v1/activities/${uid}`)
}

export const updateActivityRemoteAccess = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}/remote-access-factors`, payload)
}

export const updateApplicationAdnEquipment = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}/applications-and-equipments`, payload)
}
