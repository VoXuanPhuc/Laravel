<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" class="mb-6 lg:text-4xl text-c0-50">
      {{ computedTitle }}
    </EcHeadline>
    <EcText class="mb-12 leading-tight text-c0-50">
      {{ computedLabel }}
    </EcText>
    <EcBox v-if="!isLoading" class="w-full max-w-md">
      <RFormInput
        v-show="!isFinish"
        v-model="formEmail"
        class="mb-12 text-c0-50"
        componentName="EcInputText"
        :label="$t('auth.email')"
        type="email"
        variant="primary-lg"
        dark
        iconPrefix="Mail"
        :validator="v"
        field="formEmail"
        @input="v.formEmail.$touch()"
      />
      <EcFlex>
        <EcButton v-show="!isFinish" variant="primary" class="mr-5 hover:text-c0-50 hover:bg-c0-900" @click="handleClickSend">
          {{ $t("auth.send") }}
        </EcButton>
        <EcButton
          :variant="isFinish ? 'primary' : 'transparent'"
          class="text-c0-50 hover:bg-c0-900"
          @click="handleClickBackToLogin"
        >
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
import LayoutAuth from "./../components/LayoutAuth"
import { useForgotPassword } from "./../use/useForgotPassword"

export default {
  name: "ViewForgotPassword",
  components: {
    LayoutAuth,
  },
  setup() {
    const { state, send, v, formEmail } = useForgotPassword()
    return {
      state,
      send,
      v,
      formEmail,
    }
  },
  computed: {
    isLoading() {
      return this.state.matches("fetching")
    },
    isFinish() {
      return !this.state.matches("formFilling") && !this.state.matches("fetching")
    },
    isSuccess() {
      return this.state.matches("success")
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
      this.send("SEND_MAIL")
    },
    handleClickBackToLogin() {
      this.send("BACK_TO_LOGIN")
    },
  },
}
</script>
