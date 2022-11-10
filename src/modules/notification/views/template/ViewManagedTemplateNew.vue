<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("notification.template.newTemplate") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("notification.template.templateDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="managedTemplate.name"
            componentName="EcInputText"
            :label="$t('notification.template.labels.name')"
            :validator="v$"
            field="managedTemplate.name"
            @input="v$.managedTemplate.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Title -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="managedTemplate.title"
            componentName="EcInputText"
            :label="$t('notification.template.labels.title')"
            :validator="v$"
            field="managedTemplate.title"
            @input="v$.managedTemplate.title.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Desc -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="managedTemplate.description"
            componentName="EcInputLongText"
            :label="$t('notification.template.labels.desc')"
            :validator="v$"
            :rows="1"
            field="managedTemplate.description"
            @input="v$.managedTemplate.description.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Content -->
      <EcFlex class="flex-wrap w-10/12 items-center mb-8">
        <EcEditor
          v-model="managedTemplate.data"
          field="managedTemplate.data"
          :validator="v$"
          @change="v$.managedTemplate.data.$touch()"
          label="Content"
          :modelValue="managedTemplate.data"
          @update:modelValue="onUpdateContent"
        />
        <EcLabel v-if="v$.managedTemplate.data.$errors?.length > 0" class="mt-1 text-cError-500 text-base">
          {{ $t("notification.template.errors.contentRequired") }}
        </EcLabel>
      </EcFlex>

      <!-- Replacements - Manual -->
      <EcBox class="grid grid-rows gap-2">
        <EcLabel class="text-base font-medium"> Available Replacements</EcLabel>
        <EcLabel class="text-xs"> (Click on item to copy)</EcLabel>

        <!-- List replacements -->
        <EcBox v-if="!isLoadingConfig">
          <EcBox v-for="(replacements, key) in manualReplacements" :key="key" class="mt-4 ml-4">
            <!-- Object name -->
            <EcLabel class="text-base font-medium">
              {{ ucFirst(key) }}
            </EcLabel>

            <!-- Replacements -->
            <EcFlex class="ml-4 mt-3 grid grid-cols-5 gap-2">
              <EcLabel
                class="text-base italic hover:cursor-pointer"
                v-for="(replacement, idx) in replacements"
                :key="idx"
                @click="handleClickReplacementItem(key, replacement)"
              >
                {{ buildReplacement(key, replacement) }}
              </EcLabel>
            </EcFlex>
            <!-- End replacements -->
          </EcBox>
        </EcBox>
        <EcSpinner v-else />
      </EcBox>

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("notification.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("notification.buttons.confirm") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10">
        <EcSpinner type="dots" />
      </EcBox>
    </EcBox>
    <!-- End actions -->
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useGlobalStore } from "@/stores/global"
import { reactive } from "vue"
import { useEventNotificationConfig } from "../../use/noti/useEventNotificationConfig"
import { useManagedTemplateNew } from "../../use/template/useManagedTemplateNew"

export default {
  name: "ViewManagedTemplateNew",
  data() {
    return {
      template: reactive(),
      isLoading: false,
      isLoadingConfig: false,
    }
  },
  mounted() {
    this.fetchNotificationConfig()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { managedTemplate, v$, createManagedTemplate } = useManagedTemplateNew()
    const { getNotificationConfigs, ucFirst, buildReplacement, copyValue, configs } = useEventNotificationConfig()
    return {
      // Notification configs
      configs,
      ucFirst,
      buildReplacement,
      copyValue,
      getNotificationConfigs,

      // Managed templates
      createManagedTemplate,
      managedTemplate,
      v$,
      globalStore,

      // Templates
    }
  },
  computed: {
    /**
     * Manual Replacements
     */
    manualReplacements() {
      return this.configs?.replacements?.manual
    },
    /**
     * Auto replacements
     */
    autoReplacements() {
      return this.configs?.replacements?.auto
    },
  },
  methods: {
    /**
     * Create resource
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()
      if (this.v$.managedTemplate.$invalid) {
        return
      }
      this.isLoading = true

      const response = await this.createManagedTemplate(this.managedTemplate)
      this.isLoading = false
      if (response) {
        goto("ViewManagedTemplateList")
      }
    },

    /**
     *
     * @param {*} content
     */
    onUpdateContent(content) {
      this.managedTemplate.data = content
    },

    /**
     * Fetch configs
     */
    async fetchNotificationConfig() {
      this.isLoadingConfig = true
      const configRes = await this.getNotificationConfigs()
      if (configRes) {
        this.configs = configRes
      }
      this.isLoadingConfig = false
    },

    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewBCPList")
    },

    handleClickReplacementItem(key, replacement) {
      const replacementStr = this.buildReplacement(key, replacement)
      this.copyValue(replacementStr)
    },
  },

  watch: {
    template(data) {
      if (data?.uid) {
        this.fetchManagedTemplateDetail(data.uid)
      }
    },
  },
}
</script>
