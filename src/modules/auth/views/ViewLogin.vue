<template>
  <LayoutAuth>
    <EcHeadline variant="h1" as="h1" :class="variantCls.title">
      {{ $t("auth.loginTitle") }}
    </EcHeadline>
    <EcBox :class="variantCls.subtitle.class">
      <EcText> {{ $t("auth.loginSubtitle") }} </EcText>
      <EcText v-if="tenantId" :class="variantCls.subtitle.tenantId"> ( {{ $t("auth.tenantId") }}: {{ tenantId }} ) </EcText>
      <EcText v-else :class="variantCls.subtitle.warning">
        {{ $t("auth.missingTenantId") }}
      </EcText>
    </EcBox>
    <EcBox :class="variantCls.form">
      <RFormInput
        v-model="form.username"
        :class="variantCls.email.class"
        componentName="EcInputText"
        :label="$t('auth.email')"
        type="email"
        required="true"
        :variant="variantCls.email.variant"
        :dark="variantCls.email.isDark"
        iconPrefix="Mail"
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
        type="password"
        :variant="variantCls.password.variant"
        :dark="variantCls.password.isDark"
        iconPrefix="LockClosed"
        :validator="v"
        field="form.password"
        @input="v.form.password.$touch()"
        @keypress.enter="handleClickLogin()"
        data-test="inputPassword"
      />
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
        <EcButton
          class="!hover:text-c0-50"
          :variant="variantCls.forgotPassword.variant"
          :class="variantCls.forgotPassword.class"
          @click="handleClickForgotPassword()"
        >
          {{ $t("auth.forgotPassword") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="items-center h-12">
        <EcSpinner variant="primary" type="dots" />
      </EcFlex>
    </EcBox>
  </LayoutAuth>
</template>

<script>
import LayoutAuth from "@/modules/auth/components/LayoutAuth"
import EcSpinner from "@/components/EcSpinner/index.vue"
import { useGlobalStore } from "@/stores/global"
import { useLoginStore } from "../stores/useLogin"
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
      isLoading: false,
      error: null,
    }
  },

  setup() {
    const globalStore = useGlobalStore()
    const loginStore = useLoginStore()
    const { form, v, CHALLENGE_CHANGE_PASSWORD } = storeToRefs(loginStore)

    return {
      globalStore,
      loginStore,
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
        if (data && data.challenge_name) {
          switch (data.challenge_name) {
            case this.loginStore.CHALLENGE_CHANGE_PASSWORD:
              goto("ViewNewPassword", {
                params: {
                  username: data.user_uid,
                  session: data.session,
                  firstName: data.first_name,
                },
              })
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
            name: "ViewDashboard",
          })
        }
      } catch (error) {
        // Hide loading indicator
        this.isLoading = false
        this.error = error?.message
      }
    },
    handleClickForgotPassword() {
      this.$router.push({
        name: "ViewForgotPassword",
      })
    },
  },
}
</script>
