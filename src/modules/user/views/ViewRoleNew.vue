<template>
  <RLayout title="New Role">
    <EcBox variant="card-1" class="max-w-2xl px-4 mt-8 sm:px-10">
      <EcFlex class="flex-wrap max-w-md">
        <!-- Role key -->
        <EcBox class="w-full mb-6 hidden">
          <RFormInput
            v-model="form.name"
            componentName="EcInputText"
            :label="$t('role.label.key')"
            :validator="v$"
            field="form.name"
            disabled="true"
            placeholder="Auto generate from Name"
          />
        </EcBox>

        <!-- Role label -->
        <EcBox class="w-full mb-6">
          <RFormInput
            v-model="form.label"
            componentName="EcInputText"
            :label="$t('role.label.name')"
            :validator="v$"
            field="form.label"
            @input="handleRoleNameInput"
          />
        </EcBox>

        <!-- Role desc -->
        <EcBox class="w-full mb-6">
          <RFormInput
            v-model="form.description"
            componentName="EcInputText"
            :label="$t('user.label.description')"
            :validator="v$"
            field="form.description"
            @input="v$.form.description.$touch()"
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
import { useRoleNew } from "../use/useRoleNew"

export default {
  name: "ViewRoleNew",
  setup() {
    const { form, v$, createNewRole } = useRoleNew()

    const isLoading = ref(false)
    const router = useRouter()

    return {
      form,
      v$,
      createNewRole,
      isLoading,
      router,
    }
  },

  methods: {
    handleRoleNameInput(e) {
      this.form.name = e.target.value.replace(/\s+/g, "-").toLowerCase()
      this.v$.form.label.$touch()
    },
    async handleSubmit() {
      // Validate first
      this.v$.form.$touch()
      if (this.v$.form.$invalid) {
        return
      }

      this.isLoading = true

      const payload = {
        name: this.form.name,
        label: this.form.label,
        description: this.form.description,
        tenant_id: 1,
      }

      await this.createNewRole(payload)

      this.isLoading = false
      this.router.push({ name: "ViewRoles" })
    },

    // Redirect to role list if cancel
    handleCancel() {
      this.router.push({ name: "ViewRoles" })
    },
  },
}
</script>
