<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("bia.title.editBIA") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox v-if="!isLoading" variant="card-1" class="width-full mt-8 px-4 sm:px-10">
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
            <EcLabel class="ml-2" v-if="bia.reports?.length > 0">({{ bia.reports?.length }})</EcLabel>
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
      </EcFlex>
      <!-- End Report-->

      <!-- Activity -->
      <EcFlex class="flex-wrap items-center mb-8">
        <RActivityLog :uid="uid" :fetcher="getBIALogs" />
      </EcFlex>
      <!-- End Activity -->

      <!-- End body -->
      <!-- Actions -->
      <EcBox class="width-full mt-8 px-4 sm:px-10">
        <!-- Button create -->
        <EcFlex v-if="!isUpdateLoading" class="mt-6">
          <EcButton variant="tertiary-outline" @click="handleClickCancel">
            {{ $t("bia.buttons.back") }}
          </EcButton>

          <EcButton variant="primary" class="ml-4" @click="handleClickUpdate">
            {{ $t("bia.buttons.update") }}
          </EcButton>
        </EcFlex>

        <!-- Loading -->
        <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
      </EcBox>
      <!-- End actions -->
    </EcBox>

    <RLoading v-else />
  </RLayout>

  <teleport to="#layer1">
    <ModalUploadAssessmentFile
      :isModalUploadBIAFileOpen="isModalUploadBIAFileOpen"
      @handleCloseUploadModal="handleCloseUploadModal"
      @handleUploadCallback="handleUploadCallback"
      ref="modalUploadRef"
    />
  </teleport>

  <teleport to="#layer2">
    <!-- Modal preview file -->
    <RModalPreview ref="modalFilePreview" :file="fileX"></RModalPreview>
  </teleport>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useBIADetail } from "@/modules/assessment/use/useBIADetail"
import { useBIALog } from "@/modules/assessment/use/useBIALog"
import { useBIAStatusEnum } from "@/modules/assessment/use/useBIAStatusEnum"
import { useGlobalStore } from "@/stores/global"
import ModalUploadAssessmentFile from "../components/ModalUploadAssessmentFile.vue"
import { ref } from "vue"
import { downloadFromUrl } from "@/readybc/composables/helpers/downloadHelper"
import RFileSlider from "@/modules/core/components/common/RFileSlider.vue"

export default {
  name: "ViewBIADetail",
  props: {
    uid: {
      type: String,
      required: true,
      default: "",
    },
  },
  data() {
    return {
      reportFilesDeleting: [],
      isModalUploadBIAFileOpen: false,
      isModalFilePreviewOpen: false,
      isLoading: false,
      isUpdateLoading: false,
    }
  },
  mounted() {
    this.fetchBIA()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { bia, v$, getBIA, updateBIA, deleteBIA } = useBIADetail()
    const { removeReportFile } = useBIADetail()
    const { statuses } = useBIAStatusEnum()

    const modalUploadRef = ref()

    // Log getter
    const { getBIALogs } = useBIALog()

    return {
      getBIA,
      updateBIA,
      deleteBIA,
      getBIALogs,
      bia,
      v$,
      globalStore,
      statuses,
      modalUploadRef,
      removeReportFile,
    }
  },
  computed: {
    fileX() {
      return this.bia.reports[0]
    },
  },
  methods: {
    /**
     * Create resource
     *
     */
    async handleClickUpdate() {
      this.v$.$touch()

      if (this.v$.bia.$invalid) {
        return
      }
      this.isUpdateLoading = true
      this.bia.status = this.bia.statusObj?.value
      const res = await this.updateBIA(this.bia, this.uid)

      if (res) {
        this.transformData(res)
      }
      this.isUpdateLoading = false
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
    handleOpenFilePreview(file) {
      this.$refs.modalFilePreview.file = file
      this.$refs.modalFilePreview.isModalPreviewOpen = true
      //  window.open(url, "_blank")
    },

    /**
     *
     * @param {*} url
     */
    async handleRemoveUploadedFile(uid) {
      this.bia.reports.forEach((item, idx) => {
        if (item?.uid === uid) {
          this.bia.reports.splice(idx, 1)
        }
      })
    },

    /**
     *
     * @param {*} file
     */
    handleDownloadFileItem(file) {
      downloadFromUrl(file?.url)
    },

    /**
     * Upload file callback
     */
    handleUploadCallback(files) {
      files.forEach((file) => {
        if (this.isFileAddedToReports(file)) {
          return
        }
        this.bia.reports.push({
          uid: file?.response?.uid,
          name: file?.response?.name,
          url: file?.response?.url,
          mime_type: file?.response?.mime_type,
        })
      })
    },

    /**
     * Check to see the file already add to report
     * @param {*} file
     */
    isFileAddedToReports(file) {
      return this.bia.reports?.find((item) => {
        return file?.response?.uid === item?.uid
      })
    },

    /** =========== PRE LOAD  */

    /**
     * Get BIA by UID
     */
    async fetchBIA() {
      this.isLoading = true
      const biaRes = await this.getBIA(this.uid)

      if (biaRes) {
        this.transformData(biaRes)
      }

      this.isLoading = false
    },

    async logFetcher() {},

    transformData(response) {
      this.bia = response

      // Status
      this.bia.statusObj = this.statuses?.find((status) => {
        return status.value === response?.status
      })

      // Files
    },
  },
  components: { ModalUploadAssessmentFile, RFileSlider },
}
</script>
