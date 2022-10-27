<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("bcp.title.editBCP") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox v-if="!isLoading" variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("bcp.title.bcpDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="bcp.name"
            componentName="EcInputText"
            :label="$t('bcp.labels.name')"
            :validator="v$"
            field="bcp.name"
            @input="v$.bcp.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Status -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12">
          <!-- select box -->
          <RFormInput
            v-model="bcp.statusObj"
            componentName="EcMultiSelect"
            :label="$t('bcp.labels.status')"
            :allowSelectNothing="false"
            :isSingleSelect="true"
            :options="statuses"
            :validator="v$"
            field="bcp.statusObj"
            @change="v$.bcp.statusObj.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Due Date -->
      <EcFlex class="flex-wrap max-w-md items-center mb-8">
        <EcBox class="w-full sm:w-6/12">
          <!-- select box -->
          <RFormInput
            v-model="bcp.due_date"
            componentName="EcDatePicker"
            :label="$t('bcp.labels.dueDate')"
            :allowSelectNothing="false"
            :validator="v$"
            field="bcp.due_date"
            @change="v$.bcp.due_date.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Reports -->
      <EcFlex class="flex-wrap items-center mb-8">
        <EcBox class="w-full">
          <!-- Title and upload button -->
          <EcFlex class="items-center">
            <EcLabel>{{ $t("bcp.plans.reports") }}</EcLabel>
            <EcButton
              variant="transparent"
              class="ml-4 text-c1-800"
              v-tooltip="{ text: 'Upload BCP Reports' }"
              @click="handleOpenBCPFileUploadModal"
            >
              <EcIcon icon="CloudUpload" />
            </EcButton>
          </EcFlex>

          <!-- Report row -->
          <RFileSlider class="mt-4" :files="bcp.reports" @fileDeleted="handleRemoveUploadedFile"></RFileSlider>
        </EcBox>

        <!-- End -->
      </EcFlex>

      <!-- Activity -->
      <EcFlex class="flex-wrap items-center mb-8">
        <RActivityLog :uid="uid" :fetcher="getBCPLogs" />
      </EcFlex>
      <!-- End Activity -->

      <!-- End body -->
      <!-- Actions -->
      <EcBox class="width-full mt-8 px-4 sm:px-10">
        <!-- Button create -->
        <EcFlex v-if="!isUpdateLoading" class="mt-6">
          <EcButton variant="tertiary-outline" @click="handleClickCancel">
            {{ $t("bcp.buttons.back") }}
          </EcButton>

          <EcButton variant="primary" class="ml-4" @click="handleClickUpdate">
            {{ $t("bcp.buttons.update") }}
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
    <ModalUploadBCPFile
      :isModalUploadBCPFileOpen="isModalUploadBCPFileOpen"
      @handleCloseUploadModal="handleCloseUploadModal"
      @handleUploadCallback="handleUploadCallback"
      ref="modalUploadRef"
    />
  </teleport>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useBCPDetail } from "@/modules/bcp/use/useBCPDetail"
import { useBCPLog } from "@/modules/bcp/use/useBCPLog"
import { useBCPStatusEnum } from "@/modules/bcp/use/useBCPStatusEnum"
import { useGlobalStore } from "@/stores/global"
import ModalUploadBCPFile from "../components/ModalUploadBCPFile.vue"
import { ref } from "vue"

export default {
  name: "ViewBCPNew",
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
      isModalUploadBCPFileOpen: false,
      isLoading: false,
      isUpdateLoading: false,
    }
  },
  mounted() {
    this.fetchBCP()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { bcp, v$, getBCP, updateBCP, deleteBCP } = useBCPDetail()
    const { removeReportFile } = useBCPDetail()
    const { statuses } = useBCPStatusEnum()

    const { getBCPLogs } = useBCPLog()
    const modalUploadRef = ref()

    return {
      getBCP,
      updateBCP,
      deleteBCP,
      getBCPLogs,
      bcp,
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
    async handleClickUpdate() {
      this.v$.$touch()

      if (this.v$.bcp.$invalid) {
        return
      }
      this.isUpdateLoading = true
      this.bcp.status = this.bcp.statusObj?.value
      const res = await this.updateBCP(this.bcp, this.uid)

      if (res) {
        this.transformData(res)
      }

      this.isUpdateLoading = false
    },
    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewBCPList")
    },

    /**
     * Handle upload file
     */
    handleOpenBCPFileUploadModal() {
      this.isModalUploadBCPFileOpen = true
    },

    /**
     * Close upload modal
     */
    handleCloseUploadModal() {
      this.isModalUploadBCPFileOpen = false
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
      this.bcp.reports.forEach((item, idx) => {
        if (item?.uid === uid) {
          this.bcp.reports.splice(idx, 1)
        }
      })
    },

    // Upload file callback
    handleUploadCallback(files) {
      files.forEach((file) => {
        if (this.isFileAddedToReports(file)) {
          return
        }
        this.bcp.reports.push({
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
      return this.bcp.reports?.find((item) => {
        return file?.response?.uid === item?.uid
      })
    },

    /** =========== PRE LOAD  */

    /**
     * Get BCP by UID
     */
    async fetchBCP() {
      this.isLoading = true
      const bcpRes = await this.getBCP(this.uid)

      if (bcpRes) {
        this.transformData(bcpRes)
      }

      this.isLoading = false
    },

    transformData(response) {
      this.bcp = response

      // Status
      this.bcp.statusObj = this.statuses?.find((status) => {
        return status.value === response?.status
      })

      // Files
    },

    // Format Datetime
    formatDateTime(str) {
      return this.globalStore.formatDateTime(str)
    },
  },
  components: { ModalUploadBCPFile },
}
</script>
