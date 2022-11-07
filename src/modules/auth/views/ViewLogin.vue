<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" :class="variantCls.title">
      <EcFlex>
        <EcBox>{{ $t("auth.loginTitle") }} </EcBox> &nbsp;
        <EcBox :class="variantCls.readyTitle">{{ $t("auth.ready") }}</EcBox>
        <EcBox :class="variantCls.bcTitle">{{ $t("auth.bc") }}</EcBox>
      </EcFlex>
    </EcHeadline>
    <EcBox></EcBox>
    <EcBox :class="variantCls.subtitle.class">
      <EcText> {{ $t("auth.loginSubtitle") }} </EcText>
    </EcBox>
    <EcBox :class="variantCls.form">
      <RFormInput
        v-model="form.username"
        :class="variantCls.email.class"
        componentName="EcInputText"
        :label="$t('auth.username')"
        type="email"
        required="true"
        :variant="variantCls.email.variant"
        :dark="variantCls.email.isDark"
        :validator="v"
        field="form.username"
        @input="v.form.username.$touch()"
        @keypress.enter="handleClickLogin()"
        data-test="inputEmail"
      />
      <RFormInput
        v-model="form.password"
        :class="variantCls.password.class"
        componentName="EcInputText"
        :label="$t('auth.password')"
        :type="passwordFieldType"
        :variant="variantCls.password.variant"
        :dark="variantCls.password.isDark"
        :validator="v"
        :iconSuffix="passwordIconSuffix"
        field="form.password"
        @input="v.form.password.$touch()"
        @keypress.enter="handleClickLogin()"
        @suffixEvent="handleShowPassword"
        data-test="inputPassword"
      />

      <!-- Forgot password -->
      <EcFlex :class="variantCls.forgotPassword.wrapper">
        <EcText :class="variantCls.forgotPassword.class" @click="handleClickForgotPassword()">
          {{ $t("auth.forgotPassword") }}
        </EcText>
      </EcFlex>
      <EcFlex v-if="!isLoading">
        <EcButton
          id="login"
          :variant="variantCls.login.variant"
          :class="variantCls.login.class"
          @click="handleClickLogin()"
          data-test="buttonLogin"
        >
          {{ $t("auth.login") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center h-12">
        <EcSpinner type="dots" />
      </EcFlex>
    </EcBox>
  </LayoutAuth>
</template>

<script>
import LayoutAuth from "@/modules/auth/components/LayoutAuth"
import EcSpinner from "@/components/EcSpinner/index.vue"
import { useGlobalStore } from "@/stores/global"
import { useLoginStore } from "../stores/useLogin"
import { useNewPasswordStore } from "../stores/useNewPassword"
import { storeToRefs } from "pinia"
import { goto } from "@/modules/core/composables"

export default {
  name: "ViewLogin",
  inject: ["getComponentVariants"],
  components: {
    LayoutAuth,
    EcSpinner,
  },
  data() {
    return {
      passwordFieldType: "password",
      isLoading: false,
      error: null,
    }
  },

  setup() {
    const globalStore = useGlobalStore()
    const loginStore = useLoginStore()
    const newPasswordStore = useNewPasswordStore()
    const { form, v, CHALLENGE_CHANGE_PASSWORD } = storeToRefs(loginStore)

    return {
      globalStore,
      loginStore,
      newPasswordStore,
      form,
      v,
      CHALLENGE_CHANGE_PASSWORD,
    }
  },
  computed: {
    tenantId() {
      return this.globalStore.getTenantId
    },
    clientId() {
      return this.globalStore.getClientId
    },

    variants() {
      return (
        this.getComponentVariants({
          componentName: "ViewLogin",
          variant: "default",
        }) ?? {}
      )
    },
    variantCls() {
      return this.variants?.el || {}
    },

    passwordIconSuffix() {
      if (this.form.password?.length <= 0) {
        return ""
      }
      return this.passwordFieldType === "password" ? "Eye" : "EyeSlashed"
    },
  },
  methods: {
    async handleClickLogin() {
      this.v.form.$touch()
      if (this.v.form.$invalid) {
        return
      }

      try {
        // Show loading indicator
        this.isLoading = true
        // Get token
        const data = await this.loginStore.login()

        // Check to see if there has nay challenge

        if (data && data.challengeName) {
          switch (data.challengeName) {
            case this.loginStore.CHALLENGE_CHANGE_PASSWORD:
              // Add to Store to pass to Vue New Password
              this.newPasswordStore.setNewPasswordChallenge({
                username: data.userUid,
                session: data.session,
                firstName: data.firstName,
              })

              goto("ViewNewPassword")
              return
          }
        }
        // Hide loading indicator
        this.isLoading = false
        // Get previous path that was entered while user was logged in
        // If exists, go to previous path after log in
        // Else go to Dashboard

        if (window.PATH) {
          this.$router.push(window.PATH)
        } else {
          this.$router.push({
            name: data?.landing,
          })
        }
      } catch (error) {
        // Hide loading indicator
        this.isLoading = false
        this.error = error?.message
      }
    },

    /**
     * Forgot Password
     */
    handleClickForgotPassword() {
      this.$router.push({
        name: "ViewForgotPassword",
      })
    },

    handleShowPassword() {
      this.passwordFieldType = this.passwordFieldType === "password" ? "input" : "password"
    },
  },
}
</script>
