<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" class="mb-6 lg:text-4xl">
      {{ computedTitle }}
    </EcHeadline>
    <EcText class="text-c1-200 mb-12 leading-tight">
      {{ computedLabel }}
    </EcText>
    <EcBox v-if="!isLoading" class="w-full max-w-md">
      <RFormInput
        v-show="!isFinish"
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
      <EcFlex>
        <EcButton v-show="!isFinish" variant="primary" class="mr-5" @click="handleClickSend">
          {{ $t("auth.send") }}
        </EcButton>
        <EcButton :variant="isFinish ? 'primary' : 'transparent'" @click="handleClickBackToLogin">
          {{ $t("auth.backToLogin") }}
        </EcButton>
      </EcFlex>
    </EcBox>
    <EcFlex v-else class="items-center">
      <EcSpinner type="dots" />
    </EcFlex>
  </LayoutAuth>
</template>

<script>
import LayoutAuth from "@/modules/auth/components/LayoutAuth.vue"
import { useForgotPassword } from "../stores/useForgotPassword"
import { storeToRefs } from "pinia"

export default {
  name: "ViewForgotPassword",
  components: {
    LayoutAuth,
  },
  setup() {
    const forgotPasswordStore = useForgotPassword()

    const { v$, formEmail } = storeToRefs(forgotPasswordStore)

    return {
      forgotPasswordStore,
      v$,
      formEmail,
    }
  },
  computed: {
    isLoading() {
      return false
    },
    isFinish() {
      return false
    },
    isSuccess() {
      return false
    },
    computedTitle() {
      return this.isFinish ? (this.isSuccess ? this.$t("auth.success") : this.$t("auth.fail")) : this.$t("auth.forgotPassword")
    },
    computedLabel() {
      return this.isFinish
        ? this.isSuccess
          ? this.$t("auth.sendMailSuccessNote")
          : this.$t("auth.sendMailFailNote")
        : this.$t("auth.forgotPasswordNote")
    },
  },
  methods: {
    handleClickSend() {
      this.v.$touch()
      if (this.v.$invalid) return
      this.forgotPasswordStore.sendForgotEmail()
    },
    handleClickBackToLogin() {
      this.forgotPasswordStore.toLoginPage()
    },
  },
}
</script>
