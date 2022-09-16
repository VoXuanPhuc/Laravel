import useVuelidate from "@vuelidate/core"
import { ref } from "vue"
import { helpers, required } from "@vuelidate/validators"
import * as api from "../api/activityFetcher"
import { useGlobalStore } from "@/stores/global"
import { useI18n } from "vue-i18n"

export function useActivityApplicationsAndEquipments() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const form = ref({
    applications: [{ uid: "" }],
    equipments: [{ uid: "" }],

    it_solution: {
      data: "",
      location: "",
    },
  })

  const rules = {
    form: {
      it_solution: {
        data: { required },
        location: { required },
      },
      applications: {
        required,
        $each: helpers.forEach({
          uid: { required },
        }),
      },
      equipments: {
        required,
        $each: helpers.forEach({
          uid: { required },
        }),
      },
    },
  }

  const v$ = useVuelidate(rules, { form })

  /**
   *
   * @param {*} payload
   * @param {*} activityUid
   * @returns
   */
  const updateApplicationAnEquipments = async (payload, activityUid) => {
    try {
      const { data } = await api.updateApplicationAdnEquipment(payload, activityUid)

      if (!data) {
        globalStore.addErrorToastMessage(t("activity.errors.updateActivity"))
      } else {
        globalStore.addSuccessToastMessage(t("activity.messages.updatedActivity"))
      }

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error?.message)
    }
  }

  return {
    form,
    v$,
    updateApplicationAnEquipments,
  }
}
