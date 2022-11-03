<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" class="mb-6 lg:text-4xl">
      {{ $t("auth.confirmationCodeTitle") }}
    </EcHeadline>
    <EcText class="text-c1-200 mb-12 leading-tight">
      {{ $t("auth.confirmForgotPasswordNote") }}
    </EcText>
    <EcBox class="w-full max-w-md">
      <template v-if="!isFinish">
        <RFormInput
          v-model.trim="form.confirmation_code"
          componentName="EcInputText"
          class="mb-5"
          type="number"
          :label="$t('auth.confirmationCode')"
          variant="primary-lg"
          dark
          iconPrefix="Key"
          :validator="v$"
          field="form.confirmation_code"
          @input="v$.form.confirmation_code.$touch()"
        />
        <RFormInput
          v-model.trim="form.new_password"
          componentName="EcInputText"
          class="mb-5"
          type="password"
          :label="$t('auth.newPassword')"
          variant="primary-lg"
          dark
          iconPrefix="LockClosed"
          :validator="v$"
          field="form.new_password"
          @input="v$.form.new_password.$touch()"
        />
        <RFormInput
          v-model.trim="form.confirm_password"
          componentName="EcInputText"
          class="mb-12"
          type="password"
          :label="$t('auth.confirmNewPassword')"
          variant="primary-lg"
          dark
          iconPrefix="LockClosed"
          :validator="v$"
          field="form.confirm_password"
          @input="v$.form.confirm_password.$touch()"
        />
        <EcFlex v-if="!isLoading">
          <EcButton class="hover:bg-cWhite hover:text-c4-600 mr-5" variant="primary" @click="handleClickConfirmChangePassword">
            {{ $t("auth.confirm") }}
          </EcButton>
          <EcButton class="hover:bg-cWhite hover:text-c4-600" variant="primary" @click="handleClickBackToLogin">
            {{ $t("auth.backToLogin") }}
          </EcButton>
        </EcFlex>
        <EcFlex v-else class="items-center">
          <EcSpinner type="dots" />
        </EcFlex>
      </template>
      <template v-else>
        <EcButton variant="primary" @click="handleClickBackToLogin">
          {{ $t("auth.goToLogin") }}
        </EcButton>
      </template>
    </EcBox>
  </LayoutAuth>
</template>

<script>
import { useConfirmForgotPassword } from "@/modules/auth/use/useConfirmForgotPassword"
import { goto } from "@/modules/core/composables"
import { useNewPasswordStore } from "@/modules/auth/stores/useNewPassword"
import { useGlobalStore } from "@/stores/global"
import LayoutAuth from "@/modules/auth/components/LayoutAuth.vue"

export default {
  name: "ViewConfirmForgotPassword",
  components: {
    LayoutAuth,
  },
  setup() {
    const { submitChangePassword, form, v$ } = useConfirmForgotPassword()
    const newPasswordStore = useNewPasswordStore()
    const globalStore = useGlobalStore()
    return {
      submitChangePassword,
      newPasswordStore,
      form,
      v$,
      globalStore,
    }
  },

  data() {
    return {
      isLoading: false,
      isFinish: false,
    }
  },

  mounted() {
    this.form.email = this.newPasswordStore?.getNewPasswordChallenge?.email
    if (!this.form.email) {
      this.globalStore.addErrorToastMessage(this.$t("auth.errors.invalidSession"))

      goto("ViewLogin")
    }
  },

  methods: {
    async handleClickConfirmChangePassword() {
      this.v$.$touch()

      if (this.v$.$invalid) {
        return
      }

      this.isLoading = true

      this.isFinish = await this.submitChangePassword(this.form)
      if (this.isFinish) {
        goto("ViewLogin")
      }
      this.isLoading = false
    },
    handleClickBackToLogin() {
      goto("DefaultLogin")
    },
  },
}
</script>
