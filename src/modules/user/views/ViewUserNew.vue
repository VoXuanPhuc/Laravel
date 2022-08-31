<template>
  <RLayout :title="$t('user.newUser')">
    <EcBox variant="card-1" class="max-w-2xl mt-8 px-4 sm:px-10">
      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="form.firstName"
            componentName="EcInputText"
            :label="$t('user.label.firstName')"
            :validator="v$"
            field="form.firstName"
            @input="v$.form.firstName.$touch()"
          />
        </EcBox>
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pl-6">
          <RFormInput
            v-model="form.lastName"
            componentName="EcInputText"
            :label="$t('user.label.lastName')"
            :validator="v$"
            field="form.lastName"
            @input="v$.form.lastName.$touch()"
          />
        </EcBox>
      </EcFlex>

      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full mb-6">
          <RFormInput
            v-model="form.username"
            componentName="EcInputText"
            :label="$t('user.label.email')"
            :validator="v$"
            field="form.username"
            @input="v$.form.username.$touch()"
          />
        </EcBox>
      </EcFlex>

      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full sm:w-full mb-6">
          <RFormInput
            v-model="form.roleId"
            componentName="EcSelect"
            :label="$t('user.label.role')"
            placeholder="Please select"
            :options="roleOptions"
            :validator="v$"
            field="form.roleId"
            @input="v$.form.roleId.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Buttons -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="primary" @click="handleClickCreate()">
          {{ $t("user.button.create") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel()">
          {{ $t("user.button.cancel") }}
        </EcButton>
      </EcFlex>

      <!-- Loading box -->
      <EcBox v-else class="flex items-center mt-6 h-10">
        <Loading size="2rem" class="text-c1-500" />
      </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import Loading from "../components/Loading"
import { goto } from "@/modules/core/composables"
import { useUserNew } from "../use/useUserNew"
import { useRoleList } from "../use/useRoleList"

export default {
  name: "ViewUserNew",
  components: { Loading },
  data() {
    return {
      isLoading: false,
      roleOptions: [],
      loginId: null,
    }
  },
  setup(props) {
    const { form, v$, createNewUser } = useUserNew()
    const { getRoles } = useRoleList()

    return {
      form,
      v$,
      createNewUser,
      getRoles,
    }
  },
  computed: {},
  async mounted() {
    await this.fetchRoles()
  },

  methods: {
    async fetchRoles() {
      this.isLoading = true
      const roles = await this.getRoles()

      this.roleOptions = roles.map((role) => {
        return {
          name: role.label,
          value: role.uid,
        }
      })

      this.isLoading = false
    },

    async handleClickCreate() {
      // Validate
      this.v$.form.$touch()
      if (this.v$.form.$invalid) {
        return
      }

      this.isLoading = true

      await this.createNewUser(this.form)

      this.isLoading = false
    },

    async inviteInternal() {
      const error = null
      const data = null
      return { data, error }
    },

    handleClickCancel() {
      // Go to User List Page
      goto("ViewUserList")
    },
  },
}
</script>
