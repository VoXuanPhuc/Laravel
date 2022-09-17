import fetcher from "@/modules/core/api/fetcher"

const fetchEquipments = async function () {
  return fetcher.get("/identity/api/v1/equipments")
}

export { fetchEquipments }
