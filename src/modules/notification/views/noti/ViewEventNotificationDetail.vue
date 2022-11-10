<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("notification.title.updateEvent") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox v-if="!isLoading" variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("notification.title.templateDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="eventNotification.name"
            componentName="EcInputText"
            :label="$t('notification.labels.name')"
            :validator="v$"
            field="eventNotification.name"
            @input="v$.eventNotification.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Title -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="eventNotification.title"
            componentName="EcInputText"
            :label="$t('notification.labels.title')"
            :validator="v$"
            field="eventNotification.title"
            @input="v$.eventNotification.title.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Desc -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="eventNotification.description"
            componentName="EcInputLongText"
            :label="$t('notification.labels.desc')"
            :validator="v$"
            :rows="1"
            field="eventNotification.description"
            @input="v$.eventNotification.description.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Type and Methods -->
      <EcFlex class="flex-wrap max-w-md mb-8 w-full">
        <EcBox class="w-full md:w-5/12">
          <!-- select box -->
          <RFormInput
            v-model="eventNotification.typeObj"
            componentName="EcMultiSelect"
            :label="$t('notification.labels.type')"
            :allowSelectNothing="false"
            :isSingleSelect="true"
            :options="types"
            :validator="v$"
            field="eventNotification.typeObj"
            @change="v$.eventNotification.typeObj.$touch()"
          />
        </EcBox>

        <!-- Methods -->
        <EcBox class="w-full md:ml-6 md:w-5/12">
          <!-- select box -->
          <RFormInput
            v-model="eventNotification.methodObjs"
            componentName="EcMultiSelect"
            :label="$t('notification.labels.methods')"
            :allowSelectNothing="false"
            :options="methods"
            :validator="v$"
            field="eventNotification.methodObjs"
            @change="v$.eventNotification.methodObjs.$touch()"
          />
        </EcBox>

        <EcSpinner v-if="isLoadingConfig" class="ml-2 mt-8" />
      </EcFlex>

      <!-- Action and Model -->
      <EcFlex v-if="isNotificationTypeAuto" class="flex-wrap max-w-md mb-8 w-full">
        <!-- Models -->
        <EcBox class="w-full md:w-5/12">
          <!-- select box -->
          <RFormInput
            v-model="eventNotification.ruleModels"
            componentName="EcMultiSelect"
            :label="$t('notification.labels.onModel')"
            :allowSelectNothing="false"
            :options="ruleModels"
            :isSingleSelect="true"
            :validator="v$"
            field="eventNotification.ruleModels"
            @change="v$.eventNotification.ruleModels.$touch()"
          />
        </EcBox>

        <EcBox class="w-full md:ml-6 md:w-5/12">
          <!-- select box -->
          <RFormInput
            v-model="eventNotification.ruleActions"
            componentName="EcMultiSelect"
            :label="$t('notification.labels.onAction')"
            :allowSelectNothing="false"
            :isSingleSelect="true"
            :options="ruleActions"
            :validator="v$"
            field="eventNotification.ruleActions"
            @change="v$.eventNotification.ruleActions.$touch()"
          />
        </EcBox>

        <EcSpinner v-if="isLoadingConfig" class="ml-2 mt-8" />
      </EcFlex>

      <!-- Pinned and All user -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8 w-full">
        <EcFlex class="w-full md:w-5/12 items-center">
          <EcLabel class="mr-1 text-base font-medium hover:cursor-pointer" v-tooltip="{ text: $t('notification.notes.pinNoti') }">
            {{ $t("notification.labels.pinned") }}
          </EcLabel>

          <EcIcon icon="QuestionMark" width="20" class="mr-2" />

          <!-- select box -->
          <RFormInput
            v-model="eventNotification.pinned"
            componentName="EcCheckbox"
            :validator="v$"
            field="eventNotification.pinned"
            @change="v$.eventNotification.pinned.$touch()"
          />
        </EcFlex>

        <!-- All Users -->
        <EcFlex class="w-full md:ml-6 md:w-5/12 items-center">
          <EcLabel
            class="mr-1 text-base font-medium w-6/12 hover:cursor-pointer"
            v-tooltip="{ text: $t('notification.notes.allUser') }"
          >
            {{ $t("notification.labels.allUser") }}
          </EcLabel>

          <EcIcon icon="QuestionMark" width="20" class="mr-2" />

          <RFormInput
            v-model="eventNotification.all_user"
            componentName="EcCheckbox"
            :validator="v$"
            field="eventNotification.allUser"
            @change="v$.eventNotification.allUser.$touch()"
          />
        </EcFlex>
      </EcFlex>

      <!-- Receivers -->
      <EcFlex v-if="!eventNotification.all_user" class="flex-wrap max-w-md items-center mb-8 w-full">
        <EcBox class="w-full md:w-8/12">
          <!-- select box -->
          <RFormInput
            v-model="eventNotification.receivers"
            componentName="EcMultiSelect"
            :label="$t('notification.labels.receivers')"
            :allowSelectNothing="false"
            :options="filteredReceivers"
            :valueKey="'uid'"
            :validator="v$"
            field="eventNotification.receivers"
            @change="v$.eventNotification.receivers.$touch()"
          />
        </EcBox>

        <EcSpinner v-if="isLoadingReceivers" class="ml-2 mt-6" />
      </EcFlex>

      <!-- Dispatch Time -->
      <EcFlex v-if="!isNotificationTypeAuto" class="flex-wrap max-w-md items-center mb-8 w-full">
        <EcBox class="w-full md:w-8/12">
          <!-- label -->
          <EcFlex>
            <EcLabel class="text-base font-medium" v-tooltip="{ text: $t('notification.notes.dispatchAfter') }">
              {{ $t("notification.labels.dispatchTime") }}
            </EcLabel>
            <EcIcon icon="QuestionMark" width="14" class="ml-2" />
          </EcFlex>
          <!-- Input Date time -->
          <EcFlex>
            <RFormInput v-model="eventNotification.dispatch_after" componentName="EcDateTimePicker" />
          </EcFlex>
        </EcBox>
      </EcFlex>

      <!-- Template -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8 w-full">
        <EcBox class="w-full md:w-8/12">
          <!-- label -->
          <EcFlex>
            <EcLabel class="text-base font-medium" v-tooltip="{ text: $t('notification.notes.templates') }">
              {{ $t("notification.labels.templates") }}
            </EcLabel>
            <EcIcon icon="QuestionMark" width="14" class="ml-2" />
          </EcFlex>
          <!-- select box -->
          <RFormInput
            v-model="template"
            componentName="EcMultiSelect"
            :allowSelectNothing="true"
            :options="filteredTemplates"
            :isSingleSelect="true"
            :valueKey="'uid'"
          />
        </EcBox>

        <EcSpinner v-if="isLoadingTemplateDetail || isLoadingTemplates" class="ml-2 mt-6" />
      </EcFlex>

      <!-- Content -->
      <EcFlex class="flex-wrap w-10/12 items-center mb-8">
        <EcEditor
          v-model="eventNotification.data"
          field="eventNotification.data"
          :validator="v$"
          @change="v$.eventNotification.data.$touch()"
          label="Content"
          :modelValue="eventNotification.data"
          @update:modelValue="onUpdateContent"
        />

        <!-- Errors -->
        <EcLabel v-if="v$.eventNotification.data.$errors?.length > 0" class="mt-1 text-cError-500 text-base">
          {{ $t("notification.errors.contentRequired") }}
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

    <RLoading v-else />

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isUpdating" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("notification.buttons.back") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("notification.buttons.update") }}
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
import { useEventNotificationDetail } from "@/modules/notification/use/noti/useEventNotificationDetail"
import { useGlobalStore } from "@/stores/global"
import { reactive } from "vue"
import { useEventNotificationConfig } from "../../use/noti/useEventNotificationConfig"
import { useEventNotifiactionReceiver } from "../../use/noti/useEventNotificationReceiver"
import { useManagedTemplateList } from "../../use/template/useManagedTemplateList"
import { useManagedTemplateDetail } from "../../use/template/useManagedTemplateDetail"

export default {
  name: "ViewEventNotificationDetail",
  props: {
    uid: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      template: reactive({}),
      reportFilesDeleting: [],
      isModalUploadBCPFileOpen: false,
      isLoading: false,
      isLoadingConfig: false,
      isLoadingReceivers: false,
      isLoadingTemplates: false,
      isLoadingTemplateDetail: false,
      isUpdating: false,

      // Content data
      contentData: reactive(""),
    }
  },
  mounted() {
    this.fetchEventNotification()
    this.fetchNotificationConfig()
    this.fetchReceivers()
    this.fetchManagedTemplates()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { eventNotification, v$, getEventNotification, updateEventNotification } = useEventNotificationDetail()
    const { getNotificationConfigs, ucFirst, buildReplacement, copyValue, configs } = useEventNotificationConfig()
    const { receivers, getReceivers } = useEventNotifiactionReceiver()
    const { templateList, getManagedTemplateListAll } = useManagedTemplateList()
    const { getManagedTemplateDetail } = useManagedTemplateDetail()

    return {
      getEventNotification,
      updateEventNotification,
      eventNotification,
      v$,
      globalStore,

      // Notification configs
      configs,
      ucFirst,
      buildReplacement,
      copyValue,
      getNotificationConfigs,

      // Receivers
      receivers,
      getReceivers,

      // Templates
      templateList,
      getManagedTemplateListAll,
      getManagedTemplateDetail,
    }
  },
  computed: {
    /**
     * Notification methods
     */
    methods() {
      return this.configs?.method?.map((item) => {
        return {
          name: this.ucFirst(item),
          value: item,
        }
      })
    },

    /**
     * Notification types
     */
    types() {
      return this.configs?.type?.map((item) => {
        return {
          name: this.ucFirst(item),
          value: item,
        }
      })
    },

    /**
     * Rule Actions
     */
    ruleActions() {
      return this.configs?.ruleAction?.map((item) => {
        return {
          name: this.ucFirst(item),
          value: item,
        }
      })
    },

    /**
     * Rule Models
     */
    ruleModels() {
      return this.configs?.ruleModel?.map((item) => {
        return {
          name: this.ucFirst(item),
          value: item,
        }
      })
    },
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

    /**
     * Receivers
     */
    filteredReceivers() {
      return this.receivers?.map((item) => {
        return {
          uid: item.id,
          name: item.firstName,
        }
      })
    },

    /**
     * Template
     */
    filteredTemplates() {
      return this.templateList
    },

    /**
     * The type of notification is manual
     */
    isNotificationTypeAuto() {
      return this.eventNotification?.typeObj?.value === "auto"
    },
  },
  methods: {
    /**
     * Create resource
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()
      if (this.v$.eventNotification.$invalid) {
        return
      }
      this.isUpdating = true

      this.mappingPayload()

      const response = await this.updateEventNotification(this.eventNotification, this.uid)

      this.isUpdating = false
      if (response) {
        goto("ViewEventNotificationList")
      }
    },

    /**
     *
     */
    mappingPayload() {
      // Mapping data

      // Type
      this.eventNotification.type = this.eventNotification.typeObj.value

      // Methods
      this.eventNotification.methods = this.eventNotification.methodObjs.map((item) => {
        return item.value
      })

      // Rule for auto

      if (this.isNotificationTypeAuto) {
        const rule = {
          model: this.eventNotification?.ruleModels?.value,
          action: this.eventNotification?.ruleActions?.value,
        }

        this.eventNotification.rules = [rule]
      }
    },

    /**
     *
     * @param {*} content
     */
    onUpdateContent(content) {
      this.contentData = this.eventNotification.data = content
    },

    // === PRE-LOAD === /
    async fetchEventNotification() {
      this.isLoading = true

      const eventNotiRes = await this.getEventNotification(this.uid)

      if (eventNotiRes) {
        this.eventNotification = eventNotiRes

        this.transformData(eventNotiRes)
      }

      this.isLoading = false
    },

    /**
     *
     * @param {*} eventNotiRes
     */
    transformData(eventNotiRes) {
      // Type
      this.eventNotification.typeObj = {
        name: this.ucFirst(eventNotiRes.type),
        value: eventNotiRes.type,
      }

      // Methods
      this.eventNotification.methodObjs = eventNotiRes?.methods?.map((method) => {
        return {
          name: this.ucFirst(method),
          value: method,
        }
      })

      // Model and Action Rules
      if (eventNotiRes?.rules[0]) {
        // Actions
        this.eventNotification.ruleActions = {
          name: this.ucFirst(eventNotiRes?.rules[0].action),
          value: eventNotiRes?.rules[0].action,
        }

        // Models
        this.eventNotification.ruleModels = {
          name: this.ucFirst(eventNotiRes?.rules[0].model),
          value: eventNotiRes?.rules[0].model,
        }
      }

      // Receivers
      this.eventNotification.receivers = eventNotiRes?.users

      // Data
      this.contentData = this.eventNotification.data = eventNotiRes?.data
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
     * Fetch Receivers
     */
    async fetchReceivers() {
      this.isLoadingReceivers = true

      const receiverRes = await this.getReceivers()
      if (receiverRes) {
        this.receivers = receiverRes
      }
      this.isLoadingReceivers = false
    },

    /**
     * Fetch Templates
     */
    async fetchManagedTemplates() {
      this.isLoadingTemplates = true

      const templateRes = await this.getManagedTemplateListAll()
      if (templateRes) {
        this.templateList = templateRes
      }
      this.isLoadingTemplates = false
    },

    /**
     * Fetch Template Detail
     */
    async fetchManagedTemplateDetail(uid) {
      this.isLoadingTemplateDetail = true

      const templateDetailRes = await this.getManagedTemplateDetail(uid)
      if (templateDetailRes) {
        // Mapping data
        this.eventNotification.name = templateDetailRes.name
        this.eventNotification.title = templateDetailRes.title
        this.eventNotification.data = templateDetailRes.data
        this.eventNotification.description = templateDetailRes.description
      }
      this.isLoadingTemplateDetail = false
    },

    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewEventNotificationList")
    },

    /**
     * Click all user
     */
    handleClickAllUser() {
      if (this.eventNotification.all_user) {
        this.eventNotification.receivers = []
      }
    },

    /**
     *
     * @param {*} key
     * @param {*} replacement
     */
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
