import fetcher from "@/modules/core/api/fetcher"

export const createNewActivity = async (payload) => {
  return fetcher.post(`identity/api/v1/activities`, payload)
}

export const fetchActivity = async (uid) => {
  return fetcher.get(`identity/api/v1/activities/${uid}`)
}

export const updateActivity = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}`, payload)
}

export const permanentDelete = async (uid) => {
  return fetcher.delete(`identity/api/v1/activities/${uid}/permanent`)
}

export const updateActivityRemoteAccess = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}/remote-access-factors`, payload)
}

export const updateApplicationAdnEquipment = async (payload, uid) => {
  return fetcher.put(`identity/api/v1/activities/${uid}/applications-and-equipments`, payload)
}

export const fetchActivities = async () => {
  return fetcher.get(`/identity/api/v1/activities`)
}

export const fetchActivityListByOrganizationUid = async (organizationUid) => {
  return fetcher.get(`/identity/api/v1/organizations/${organizationUid}/activities`)
}

export const fetchActivityListByDivisionUid = async (organizationUid, divisionUid) => {
  return fetcher.get(`/identity/api/v1/organizations/${organizationUid}/divisions/${divisionUid}/activities`)
}
