<template>
  <RLayout :title="$t('setting.labels.mail')">
    <!-- Modal form -->

    <EcBox variant="card-1" class="wrapper">
      <!-- Enabled -->
      <EcBox class="w-6/12 mt-10 mb-10">
        <RFormInput
          v-model="form.enabled"
          componentName="EcToggle"
          :label="$t('setting.mail.enabled')"
          showLabel
          :labelTrue="$t('setting.mail.yes')"
          :labelFalse="$t('setting.mail.no')"
        />
      </EcBox>

      <!-- Host -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput v-model="form.host" componentName="EcInputText" :label="$t('setting.mail.host')" />
      </EcBox>

      <!-- Port -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput v-model="form.port" componentName="EcInputText" :label="$t('setting.mail.port')" />
      </EcBox>

      <!-- Display name -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput v-model="form.from_display_name" componentName="EcInputText" :label="$t('setting.mail.displayName')" />
      </EcBox>

      <!-- From Address -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput v-model="form.from_address" componentName="EcInputText" :label="$t('setting.mail.fromAddress')" />
      </EcBox>

      <!-- To Email -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput v-model="form.to_address" componentName="EcInputText" :label="$t('setting.mail.toAddress')" />
      </EcBox>

      <!-- Enabled -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput
          v-model="form.tls"
          componentName="EcToggle"
          :label="$t('setting.mail.tls')"
          showLabel
          :labelTrue="$t('setting.mail.yes')"
          :labelFalse="$t('setting.mail.no')"
        />
      </EcBox>

      <!-- Enabled -->
      <EcBox class="w-6/12 mb-6">
        <RFormInput
          v-model="form.auth"
          componentName="EcToggle"
          :label="$t('setting.mail.auth')"
          showLabel
          :labelTrue="$t('setting.mail.yes')"
          :labelFalse="$t('setting.mail.no')"
        />
      </EcBox>
      <!-- End -->

      <!-- Actions -->
      <EcFlex class="mt-8">
        <EcButton class="mr-4" variant="tertiary-outline" @click="handleClickCancel()">
          {{ $t("setting.cancel") }}
        </EcButton>
        <EcButton variant="primary" @click="handleUpdateMailSettings()">
          {{ $t("setting.update") }}
        </EcButton>
      </EcFlex>
    </EcBox>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { ref } from "vue"

export default {
  name: "ViewMailSetting",

  data() {},
  setup() {
    const form = ref({})

    return {
      form,
    }
  },

  methods: {
    /**
     * Update
     */
    handleUpdateMailSettings() {},

    /**
     * Cancel update
     */
    handleClickCancel() {
      goto("ViewSettingList")
    },
  },
}
</script>
