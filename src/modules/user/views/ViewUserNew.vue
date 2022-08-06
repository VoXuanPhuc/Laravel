<template>
  <RLayout :title="$t('user.newUser')">
    <EcBox variant="card-1" class="max-w-2xl mt-8 px-4 sm:px-10">
      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="form.englishFirstName"
            componentName="EcInputText"
            :label="$t('user.label.firstName')"
            :validator="v$"
            field="form.englishFirstName"
            @input="v$.form.englishFirstName.$touch()"
          />
        </EcBox>
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pl-6">
          <RFormInput
            v-model="form.englishLastName"
            componentName="EcInputText"
            :label="$t('user.label.lastName')"
            :validator="v$"
            field="form.englishLastName"
            @input="v$.form.englishLastName.$touch()"
          />
        </EcBox>
      </EcFlex>

      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full mb-6">
          <RFormInput
            v-model="form.email"
            componentName="EcInputText"
            :label="$t('user.label.email')"
            :validator="v$"
            field="form.email"
            @input="v$.form.email.$touch()"
          />
        </EcBox>
      </EcFlex>

      <EcFlex class="flex-wrap max-w-md">
        <EcBox class="w-full sm:w-full mb-6">
          <RFormInput
            v-model="form.role"
            componentName="EcSelect"
            :label="$t('user.label.role')"
            placeholder="Please select"
            :options="roleOptions"
            :validator="v$"
            field="form.role"
            @input="v$.form.role.$touch()"
          />
        </EcBox>
      </EcFlex>
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="primary" @click="handleClickCreate()">
          {{ $t("user.button.create") }}
        </EcButton>
        <EcButton class="ml-4" variant="tertiary-outline" @click="handleClickCancel()">
          {{ $t("user.button.cancel") }}
        </EcButton>
      </EcFlex>
      <EcBox v-else class="flex items-center mt-6 h-10">
        <Loading size="2rem" class="text-c1-500" />
      </EcBox>
    </EcBox>
  </RLayout>
</template>

<script>
import Loading from "../components/Loading"
import { mapState } from "vuex"
import { goto } from "@/modules/core/composables"
import { required, email, alpha, maxLength } from "@vuelidate/validators"
// import { apiInviteInternal, apiPermissionGroups, apiAddToPermissionGroup } from "@covergo/cover-composables"

// import { fetcher } from "@/modules/user/api/fetcher.js"
import { handleErrorForUser } from "@/modules/user/api/handleErrorForUser.js"
import useVuelidate from "@vuelidate/core"

export default {
  name: "ViewUserNew",
  components: { Loading },
  data() {
    return {
      isLoading: false,
      form: {
        englishFirstName: "",
        englishLastName: "",
        chineseFirstName: "",
        chineseLastName: "",
        email: "",
        role: "",
      },
      roleOptions: [],
      loginId: null,
    }
  },
  setup(props) {
    const v$ = useVuelidate()

    return { v$ }
  },
  computed: {
    ...mapState({
      clientId: (state) => state.clientId,
    }),
  },
  validations() {
    return {
      form: {
        englishFirstName: { required, alpha },
        englishLastName: { required, alpha },
        email: { required, email, maxLength: maxLength(225) },
        role: { required },
      },
    }
  },
  async mounted() {
    await this.getPermissionGroup()
  },
  methods: {
    async getPermissionGroup() {
      // const { data, error } = await apiPermissionGroups({ fetcher })
      const error = [{}]
      const data = null
      if (error) handleErrorForUser({ error, $t: this.$t })
      else {
        this.roleOptions = data.map((x) => ({
          name: x.name,
          value: x.id,
        }))
      }
    },

    async handleClickCreate() {
      this.v$.form.$touch()
      if (this.v$.form.$invalid) return

      this.isLoading = true

      const { data: dataInviteInternal, error: errorInviteInternal } = await this.inviteInternal()
      if (errorInviteInternal) handleErrorForUser({ error: errorInviteInternal, $t: this.$t })
      else if (dataInviteInternal?.id) {
        this.loginId = dataInviteInternal.id

        const { error: errorAssignRole } = await this.assignRole()
        if (errorAssignRole) handleErrorForUser({ error: errorAssignRole, $t: this.$t })
        else goto("ViewUserList")
      }

      this.isLoading = false
    },

    async inviteInternal() {
      // const variables = {
      //   clientId: this.clientId,
      //   input: {
      //     englishFirstName: this.form.englishFirstName,
      //     englishLastName: this.form.englishLastName,
      //     chineseFirstName: this.form.chineseFirstName,
      //     chineseLastName: this.form.chineseLastName,
      //     email: this.form.email,
      //   },
      // }
      // const { data, error } = await apiInviteInternal({ variables, fetcher })
      const error = null
      const data = null
      return { data, error }
    },

    async assignRole() {
      // const variables = {
      //   loginId: this.loginId,
      //   permissionGroupId: this.form.role,
      // }
      // const { data, error } = await apiAddToPermissionGroup({ variables, fetcher })
      const data = null
      const error = null
      return { data, error }
    },

    handleClickCancel() {
      // Go to User List Page
      goto("ViewUserList")
    },
  },
}
</script>
