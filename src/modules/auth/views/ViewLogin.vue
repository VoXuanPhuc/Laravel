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
        v-model="form.email"
        :class="variantCls.email.class"
        componentName="EcInputText"
        :label="$t('auth.email')"
        type="email"
        required="true"
        :variant="variantCls.email.variant"
        :dark="variantCls.email.isDark"
        iconPrefix="Mail"
        :validator="$v"
        field="form.email"
        @input="$v.form.email.$touch()"
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
        :validator="$v"
        field="form.password"
        @input="$v.form.password.$touch()"
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
          :variant="variantCls.forgotPassword.variant"
          :class="variantCls.forgotPassword.class"
          @click="handleClickForgotPassword()"
        >
          {{ $t("auth.forgotPassword") }}
        </EcButton>
      </EcFlex>
      <EcFlex v-else class="h-12 items-center">
        <EcSpinner variant="primary" type="dots" />
      </EcFlex>
    </EcBox>
  </LayoutAuth>
</template>

<script>
import { required, email } from "@vuelidate/validators"
import { mapState } from "vuex"
import LayoutAuth from "./../components/LayoutAuth"
import { apiToken } from "@/readybc/composables/login/apiToken"
import { fetcher } from "../api/fetcher"
import { handleErrorForUser } from "../api/handleErrorForUser"
import dayjs from "dayjs"
import useVuelidate from "@vuelidate/core"

export default {
  name: "ViewLogin",
  inject: ["getComponentVariants"],
  components: {
    LayoutAuth,
  },
  data() {
    return {
      $v: useVuelidate(),
      form: {
        email: "",
        password: "",
      },
      isLoading: false,
    }
  },
  computed: {
    ...mapState({
      tenantId: (state) => state.tenantId,
      clientId: (state) => state.clientId,
    }),

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
  validations() {
    return {
      form: {
        email: {
          required,
          email,
        },
        password: {
          required,
        },
      },
    }
  },
  methods: {
    async handleClickLogin() {
      // this.$v.form.$touch()
      // if (this.$v.form.$invalid) return

      // Show loading indicator
      this.isLoading = true
      // Get token
      await this.login()
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
    },
    async login() {
      const variables = {
        tenantId: this.tenantId,
        clientId: this.clientId,
        username: this.form.email,
        password: this.form.password,
      }

      const { data, error } = await apiToken({
        variables,
        fetcher,
      })
      if (!error) {
        const { accessToken, error: tokenError, expiresIn } = data
        if (!tokenError) {
          // Save token to localStorage
          localStorage.setItem(process.env.VUE_APP_TOKEN_KEY, accessToken)
          localStorage.setItem(process.env.VUE_APP_TOKEN_EXPIRES, dayjs().add(expiresIn, "second"))
        } else {
          this.$store.dispatch("addToastMessage", {
            type: "error",
            content: {
              type: "message",
              text: this.$t(`auth.${tokenError}`),
            },
          })
        }
      } else {
        handleErrorForUser({ error, $t: this.$t })
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
