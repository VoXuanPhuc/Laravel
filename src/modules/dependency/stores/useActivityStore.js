import { defineStore } from "pinia"
import { computed, ref } from "vue"
import useVuelidate from "@vuelidate/core"
import { required, numeric, minValue } from "@vuelidate/validators"

export const useActivityStore = defineStore("activity", () => {
  const form = ref({
    roles: [
      {
        uid: "",
        name: "",
      },
    ],

    alternative_roles: [
      {
        uid: "",
        name: "",
      },
    ],

    utilities: [
      {
        uid: "",
        name: "",
      },
    ],

    remote_access_factors: [
      {
        uid: "",
        name: "",
      },
    ],

    softwares: [
      {
        uid: "",
        name: "",
      },
    ],

    equipments: [
      {
        uid: "",
        name: "",
      },
    ],

    it: {
      data: "",
      location: "",
    },

    min_people: 1,
    is_remote: true,
  })

  const rules = computed(() => ({
    form: {
      name: { required },
      min_people: { required, numeric, minValue: minValue(1) },
      unable_enable_remote: {},
      it: {
        data: { required },
        location: { required },
      },
    },
  }))

  const v$ = useVuelidate(rules, { form })

  return {
    form,
    v$,
  }
})
