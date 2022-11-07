<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" class="mb-6 lg:text-4xl">
      {{ $t("auth.forgotPassword") }}
    </EcHeadline>
    <EcText class="text-c1-800 mb-12 mr-8 leading-tight">
      {{ $t("auth.forgotPasswordNote") }}
    </EcText>
    <EcBox class="w-full max-w-md">
      <RFormInput
        v-model="formEmail"
        class="mb-12"
        componentName="EcInputText"
        :label="$t('auth.email')"
        type="email"
        variant="primary-lg"
        dark
        iconPrefix="Mail"
        :validator="v$"
        field="formEmail"
        @input="v$.formEmail.$touch()"
      />
      <EcFlex v-if="!isLoading" class="items-center">
        <EcButton v-show="!isFinish" variant="primary" class="hover:bg-cWhite hover:text-c4-600 mr-5" @click="handleClickSend">
          {{ $t("auth.send") }}
        </EcButton>
        <EcLabel class="text-c1-800 text-base hover:cursor-pointer" @click="handleClickBackToLogin">
          {{ $t("auth.backToLogin") }}
        </EcLabel>
      </EcFlex>

      <EcFlex v-else class="items-center">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </LayoutAuth>
</template>

<script>
import LayoutAuth from "@/modules/auth/components/LayoutAuth.vue"
import { useForgotPassword } from "../stores/useForgotPassword"
import { storeToRefs } from "pinia"
import { useNewPasswordStore } from "../stores/useNewPassword"
import { goto } from "@/modules/core/composables"

export default {
  name: "ViewForgotPassword",
  components: {
    LayoutAuth,
  },
  setup() {
    const forgotPasswordStore = useForgotPassword()
    const newPasswordStore = useNewPasswordStore()
    const { v$, formEmail } = storeToRefs(forgotPasswordStore)

    return {
      newPasswordStore,
      forgotPasswordStore,
      v$,
      formEmail,
    }
  },

  data() {
    return {
      isLoading: false,
      isFinish: false,
      isSuccess: false,
    }
  },
  methods: {
    async handleClickSend() {
      this.v$.$touch()
      if (this.v$.$invalid) return

      this.isLoading = true
      this.isSuccess = await this.forgotPasswordStore.sendForgotEmail(this.formEmail)

      if (this.isSuccess) {
        this.newPasswordStore.setNewPasswordChallenge({
          email: this.formEmail,
        })

        goto("ViewConfirmForgotPassword")
      }

      this.isLoading = false
    },

    handleClickBackToLogin() {
      this.forgotPasswordStore.toLoginPage()
    },
  },
}
</script>
