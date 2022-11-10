import { useGlobalStore } from "@/stores/global"
import * as api from "@/modules/notification/api/eventNofiticationFetcher"
import { ref } from "vue"
import { required } from "@vuelidate/validators"
import useVuelidate from "@vuelidate/core"
import { useI18n } from "vue-i18n"

export function useEventNotificationDetail() {
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
      data: { required },
      receivers: {},
    },
  }

  const v$ = useVuelidate(rules, { eventNotification })

  /**
   *
   * @param {*} filters
   * @returns
   */
  const getEventNotification = async (uid) => {
    try {
      const { data } = await api.fetchEventNotificationDetail(uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listTemplates"))
    }
  }

  /**
   *
   * @param {*} filters
   * @returns
   */
  const updateEventNotification = async (payload, uid) => {
    try {
      const { data } = await api.createEventNotification(payload, uid)

      return data
    } catch (error) {
      globalStore.addErrorToastMessage(error ? error?.message : t("notification.errors.listTemplates"))
    }
  }

  return {
    eventNotification,
    v$,
    getEventNotification,
    updateEventNotification,
  }
}
