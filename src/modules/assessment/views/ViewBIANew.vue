<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("bia.title.newBIA") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("bia.title.biaDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="bia.name"
            componentName="EcInputText"
            :label="$t('bia.labels.name')"
            :validator="v$"
            field="bia.name"
            @input="v$.bia.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Status -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12">
          <!-- select box -->
          <RFormInput
            v-model="bia.statusObj"
            componentName="EcMultiSelect"
            :label="$t('bia.labels.status')"
            :allowSelectNothing="false"
            :isSingleSelect="true"
            :options="statuses"
            :validator="v$"
            field="bia.statusObj"
            @change="v$.bia.statusObj.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Due Date -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12">
          <!-- select box -->
          <RFormInput
            v-model="bia.due_date"
            componentName="EcDatePicker"
            :label="$t('bia.labels.dueDate')"
            :allowSelectNothing="false"
            :validator="v$"
            field="bia.due_date"
            @change="v$.bia.due_date.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Reports -->
      <EcFlex class="flex-wrap items-center mb-8">
        <EcBox class="w-full">
          <!-- Title and upload button -->
          <EcFlex class="items-center">
            <EcLabel>{{ $t("bia.plans.reports") }}</EcLabel>
            <EcButton
              variant="transparent"
              class="ml-4 text-c1-800"
              v-tooltip="{ text: 'Upload BIA Reports' }"
              @click="handleOpenBIAFileUploadModal"
            >
              <EcIcon icon="CloudUpload" />
            </EcButton>
          </EcFlex>

          <!-- Report row -->
          <RFileSlider class="mt-4" :files="bia.reports" @fileDeleted="handleRemoveUploadedFile"></RFileSlider>
        </EcBox>

        <!-- End -->
      </EcFlex>

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("bia.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("bia.buttons.confirm") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->
  </RLayout>

  <teleport to="#layer1">
    <ModalUploadAssessmentFile
      :isModalUploadBIAFileOpen="isModalUploadBIAFileOpen"
      @handleCloseUploadModal="handleCloseUploadModal"
      @handleUploadCallback="handleUploadCallback"
      ref="modalUploadRef"
    />
  </teleport>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useBIANew } from "@/modules/assessment/use/useBIANew"
import { useBIADetail } from "@/modules/assessment/use/useBIADetail"
import { useBIAStatusEnum } from "@/modules/assessment/use/useBIAStatusEnum"
import { useGlobalStore } from "@/stores/global"
import ModalUploadAssessmentFile from "../components/ModalUploadAssessmentFile.vue"
import { ref } from "vue"

export default {
  name: "ViewBIADetail",
  data() {
    return {
      reportFilesDeleting: [],
      isModalUploadBIAFileOpen: false,
      isLoading: false,
    }
  },
  mounted() {},
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { bia, v$, createNewBIA } = useBIANew()
    const { removeReportFile } = useBIADetail()
    const { statuses } = useBIAStatusEnum()

    const modalUploadRef = ref()

    return {
      createNewBIA,
      bia,
      v$,
      globalStore,
      statuses,
      modalUploadRef,
      removeReportFile,
    }
  },
  computed: {},
  methods: {
    /**
     * Create resource
     *
     */
    async handleClickConfirm() {
      this.v$.$touch()

      if (this.v$.bia.$invalid) {
        return
      }
      this.isLoading = true
      this.bia.status = this.bia.statusObj?.value
      const response = await this.createNewBIA(this.bia)
      this.isLoading = false
      if (response) {
        goto("ViewBIAList")
      }
    },
    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewBIAList")
    },

    /**
     * Handle upload file
     */
    handleOpenBIAFileUploadModal() {
      this.isModalUploadBIAFileOpen = true
    },

    /**
     * Close upload modal
     */
    handleCloseUploadModal() {
      this.isModalUploadBIAFileOpen = false
    },

    /**
     *
     * @param {*} url
     */
    handleOpenFileUrl(url) {
      window.open(url, "_blank")
    },

    /**
     *
     * @param {*} url
     */
    async handleRemoveUploadedFile(uid) {
      this.bia?.reports?.forEach((item, idx) => {
        if (item?.uid === uid) {
          this.bia?.reports.splice(idx, 1)
        }
      })
    },

    // Upload file callback
    handleUploadCallback(files) {
      files.forEach((file) => {
        if (this.isFileAddedToReports(file)) {
          return
        }
        this.bia?.reports?.push({
          uid: file?.response?.uid,
          name: file?.response?.name,
          url: file?.response?.url,
          size: file?.response?.size,
          mime_type: file?.response?.mime_type,
        })
      })
    },

    isFileAddedToReports(file) {
      return this.bia?.reports?.find((item) => {
        return file?.response?.uid === item?.uid
      })
    },
  },
  components: { ModalUploadAssessmentFile },
}
</script>
