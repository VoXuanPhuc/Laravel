<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" class="mb-6 lg:text-4xl">
      {{ computedTitle }}
    </EcHeadline>
    <EcText class="text-c1-200 mb-12 leading-tight">
      {{ computedLabel }}
    </EcText>
    <EcBox v-if="!isLoading" class="w-full max-w-md">
      <template v-if="!isFinish">
        <RFormInput
          v-model.trim="form.new_password"
          componentName="EcInputText"
          class="mb-5"
          type="password"
          :label="$t('auth.newPassword')"
          variant="primary-lg"
          dark
          iconPrefix="Key"
          :validator="v$"
          field="form.new_password"
          @input="v$.form.new_password.$touch()"
          @keypress.enter="handleClickConfirm"
        />
        <RFormInput
          v-model.trim="form.confirm_password"
          componentName="EcInputText"
          class="mb-12"
          type="password"
          :label="$t('auth.confirmPassword')"
          variant="primary-lg"
          dark
          iconPrefix="LockClosed"
          :validator="v$"
          field="form.confirm_password"
          @input="v$.form.confirm_password.$touch()"
          @keypress.enter="handleClickConfirm"
        />
        <EcButton variant="primary" @click="handleClickConfirm">
          {{ $t("auth.confirm") }}
        </EcButton>
      </template>
      <template v-else>
        <EcButton variant="primary" @click="handleClickBackToLogin">
          {{ $t("auth.goToLogin") }}
        </EcButton>
      </template>
    </EcBox>
    <EcFlex v-else class="items-center">
      <EcSpinner type="dots" />
    </EcFlex>
  </LayoutAuth>
</template>

<script>
import LayoutAuth from "@/modules/auth/components/LayoutAuth"
import { useForceChangePassword } from "../use/useForceChangePassword"
import { useNewPasswordStore } from "../stores/useNewPassword"
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewNewPassword",
  components: {
    LayoutAuth,
  },

  props: {
    username: {
      type: String,
    },
    session: {
      type: String,
    },
    firstName: {
      type: String,
    },
  },
  data() {
    return {
      isLoading: false,
      isFinish: false,
      isSuccess: false,
    }
  },
  setup() {
    const globalStore = useGlobalStore()
    const newPasswordStore = useNewPasswordStore()
    const { form, v$, submitChangePassword } = useForceChangePassword()

    return {
      form,
      v$,
      submitChangePassword,
      globalStore,
      newPasswordStore,
    }
  },

  mounted() {
    this.form.username = this.newPasswordStore?.getNewPasswordChallenge?.username
    this.form.first_name = this.newPasswordStore?.getNewPasswordChallenge?.firstName
    this.form.session_value = this.newPasswordStore?.getNewPasswordChallenge?.session

    if (!this.form.username || !this.form.first_name || !this.form.session_value) {
      this.globalStore.addErrorToastMessage(this.$t("auth.errors.invalidSession"))

      goto("ViewLogin")
    }
  },
  computed: {
    computedTitle() {
      return this.isFinish ? (this.isSuccess ? this.$t("auth.success") : this.$t("auth.fail")) : this.$t("auth.newPassword")
    },
    computedLabel() {
      return this.isFinish
        ? this.isSuccess
          ? this.$t("auth.updatePasswordSuccessNote")
          : this.$t("auth.updatePasswordFailNote")
        : this.$t("auth.updatePasswordNote")
    },
  },
  methods: {
    /**
     * New password
     */
    async handleClickConfirm() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true
      const response = await this.submitChangePassword(this.form)

      this.isLoading = false

      if (response) {
        this.isSuccess = true
        setTimeout(this.handleClickBackToLogin, 2000)
      }
    },

    /**
     * Back to login
     */
    handleClickBackToLogin() {
      goto("ViewLogin")
    },
  },
}
</script>
