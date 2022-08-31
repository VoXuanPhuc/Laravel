import { onMounted, ref } from "vue"
import { useI18n } from "vue-i18n"
// import { fetcher } from "./../api/fetcher"
// import { handleErrorForUser } from "../api/handleErrorForUser"

export function useOrganizationList() {
  // Initial data
  const searchQuery = ref("")
  const organizationHeader = ref([])
  const organizationList = ref([
    {
      id: "dg434-dsc223-3xxx",
      logo: "https://amazbin.com/images/encoda.png",
      name: "Encoda",
      description:
        "Encoda was formed based on the belief that the key to project success is to have the right individuals, in the right roles, at the right points in time.",
      code: "ENCODA",
      isActive: true,
      createdAt: "2022-23-29",
    },
    {
      id: "dg434-dsc223-3xxx",
      logo: "https://amazbin.com/images/amz-logo-light.png",
      name: "AMZ",
      description:
        "Encoda was formed based on the belief that the key to project success is to have the right individuals, in the right roles, at the right points in time.",
      code: "ENCODA",
      isActive: true,
      createdAt: "2022-23-29",
    },
    {
      id: "dg434-dsc223-3xxx",
      logo: "https://www.google.com/logos/doodles/2015/googles-new-logo-5078286822539264.3-hp2x.gif",
      name: "Google",
      description:
        "Encoda was formed based on the belief that the key to project success is to have the right individuals, in the right roles, at the right points in time.",
      code: "ENCODA",
      isActive: false,
      createdAt: "2022-23-29",
    },
    {
      id: "dg434-dsc223-3xxx",
      logo: "https://upload.wikimedia.org/wikipedia/commons/thumb/5/51/Facebook_f_logo_%282019%29.svg/2048px-Facebook_f_logo_%282019%29.svg.png",
      name: "Facebook",
      description:
        "Encoda was formed based on the belief that the key to project success is to have the right individuals, in the right roles, at the right points in time.",
      code: "ENCODA",
      isActive: true,
      createdAt: "2022-23-29",
    },
  ])
  const totalItems = ref(0)
  const skip = ref(0)
  const limit = ref(10)
  const currentPage = ref(1)
  const { t } = useI18n()

  const intialHeader = () => {
    organizationHeader.value = [
      {
        name: "name",
        label: t("organization.name"),
      },
      {
        name: "code",
        label: t("organization.code"),
      },
      {
        name: "active",
        label: t("organization.active"),
      },
      {
        name: "created-at",
        label: t("organization.createdAt"),
      },
    ]
  }

  onMounted(() => {
    intialHeader()
  })

  return {
    searchQuery,
    organizationHeader,
    organizationList,
    totalItems,
    skip,
    limit,
    currentPage,
    t,
  }
}
