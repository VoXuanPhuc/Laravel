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
          v-model.trim="newPassword"
          componentName="EcInputText"
          class="mb-5"
          type="password"
          :label="$t('auth.password')"
          variant="primary-lg"
          dark
          iconPrefix="LockClosed"
          :validator="v"
          field="newPassword"
          @input="v.newPassword.$touch()"
          @keypress.enter="handleClickConfirm"
        />
        <RFormInput
          v-model.trim="confirmPassword"
          componentName="EcInputText"
          class="mb-12"
          type="password"
          :label="$t('auth.confirmPassword')"
          variant="primary-lg"
          dark
          iconPrefix="LockClosed"
          :validator="v"
          field="confirmPassword"
          @input="v.confirmPassword.$touch()"
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
import LayoutAuth from "./../components/LayoutAuth"

export default {
  name: "ViewNewPassword",
  components: {
    LayoutAuth,
  },
  setup() {},
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
    handleClickConfirm() {
      this.v.$touch()
      if (this.v.$invalid) return
      this.send("CONFIRM_CHANGE")
    },
    handleClickBackToLogin() {
      this.send("BACK_TO_LOGIN")
    },
  },
}
</script>
