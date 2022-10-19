import { ref } from "vue"
import { required, email } from "@vuelidate/validators"
import * as api from "../../api/organizationFetcher"
import useVuelidate from "@vuelidate/core"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export function useOrganizationDetail() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()
  // Initial data

  const organization = ref({
    owner: {},
  })

  const rules = {
    organization: {
      name: { required },
      friendly_url: {},
      description: {},
      address: {},
      logo: {},

      owner: {
        first_name: { required },
        last_name: { required },
        email: { required, email },
        phone: { required },
      },
    },
  }

  const v$ = useVuelidate(rules, { organization })

  /**
   *
   * @returns
   */
  const getOrganization = async (organizationId) => {
    try {
      const { data } = await api.fetchOrganization(organizationId)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
      return false
    }
  }

  const deleteOrganization = async (organizationId) => {
    try {
      const { data } = await api.deleteOrganization(organizationId)
      globalStore.addSuccessToastMessage(t("organization.deleted"))
      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  const updateOrganization = async (organizationId, payload) => {
    try {
      const { data } = await api.updateOrganization(payload, organizationId)

      globalStore.addSuccessToastMessage(t("organization.updated"))

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    organization,
    v$,
    getOrganization,
    updateOrganization,
    deleteOrganization,
  }
}
