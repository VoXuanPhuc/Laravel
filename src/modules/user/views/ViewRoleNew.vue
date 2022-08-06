<template>
  <RLayout title="New Role">
    <EcBox variant="card-1" class="max-w-2xl px-4 mt-8 sm:px-10">
      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full mb-6">
          <RFormInput
            v-model="v.name.$model"
            componentName="EcInputText"
            :label="$t('user.label.name')"
            :validator="v"
            field="name"
          />
        </EcBox>
        <EcBox class="w-full mb-6">
          <RFormInput
            v-model="v.description.$model"
            componentName="EcInputText"
            :label="$t('user.label.description')"
            :validator="v"
            field="description"
          />
        </EcBox>
      </EcFlex>

      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="primary" @click="handleSubmit">
          {{ $t("user.button.create") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleCancel">
          {{ $t("user.button.cancel") }}
        </EcButton>
      </EcFlex>
      <EcBox v-else class="flex items-center h-10 mt-6">
        <EcSpinner variant="secondary" type="dots" />
      </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import { ref } from "vue"
import { useRouter } from "vue-router"
import useVuelidate from "@vuelidate/core"
import { required, alpha } from "@vuelidate/validators"
// import { apiCreatePermissionGroup } from "@covergo/cover-composables"
import { handleErrorForUser } from "../api"

export default {
  name: "ViewPermissionGroupNew",
  setup() {
    const name = ref("")
    const description = ref("")
    const isLoading = ref(false)
    const router = useRouter()

    const rules = {
      name: { required, alpha },
      description: { required, alpha },
    }
    const v = useVuelidate(rules, { name, description })

    const handleSubmit = async () => {
      // const variables = {
      //   createPermissionGroupInput: { name: name.value, description: description.value },
      // }
      isLoading.value = true
      // const { error } = await apiCreatePermissionGroup({ variables, fetcher })
      const error = null
      if (error) {
        handleErrorForUser({ error, $t: this.$t })
        this.isLoading = false
        return
      }

      isLoading.value = false
      router.push({ name: "ViewRoles" })
    }

    const handleCancel = () => {
      router.push({ name: "ViewRoles" })
    }

    return { name, description, isLoading, handleSubmit, handleCancel, v }
  },
}
</script>
