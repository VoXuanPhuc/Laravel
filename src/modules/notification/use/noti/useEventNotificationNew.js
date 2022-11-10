import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/eventNofiticationFetcher"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useI18n } from "vue-i18n"

export function useEventNotificationNew() {
  const globalStore = useGlobalStore()
  const { t } = useI18n()

  const eventNotification = ref({
    pinned: false,
    all_user: false,
  })

  const rules = {
    eventNotification: {
      name: { required },
      title: { required },
      description: { required },
      ruleModels: {
        required: function (model) {
          if (this.eventNotification?.typeObj?.value === "manual") {
            return true
          }

          return model?.value?.length > 0
        },
      },
      ruleActions: {
        required: function (model) {
          if (this.eventNotification?.typeObj?.value === "manual") {
            return true
          }

          return model?.value?.length > 0
        },
      },
      typeObj: { required },
      methodObjs: { required },
      receivers: {},
      data: { required },
    },
  }

  const v$ = useVuelidate(rules, { eventNotification })

  /**
   *
   * @param {*} filters
   * @returns
   */
  const createEventNotification = async (payload) => {
    try {
      const { data } = await api.createEventNotification(payload)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listEventNotifications"))
    }
  }

  return {
    eventNotification,
    v$,
    createEventNotification,
  }
}
