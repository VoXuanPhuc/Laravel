<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("bcp.title.newBCP") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("bcp.title.bcpDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="bcp.name"
            componentName="EcInputText"
            :label="$t('bcp.labels.name')"
            :validator="vldator$"
            field="bcp.name"
            @input="vldator$.bcp.name.$touch()"
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
            :validator="vldator$"
            field="bcp.statusObj"
            @change="vldator$.bcp.statusObj.$touch()"
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
            :validator="vldator$"
            field="bcp.due_date"
            @change="vldator$.bcp.due_date.$touch()"
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

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("bcp.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("bcp.buttons.confirm") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->
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
import { useBCPNew } from "@/modules/bcp/use/useBCPNew"
import { useBCPDetail } from "@/modules/bcp/use/useBCPDetail"
import { useBCPStatusEnum } from "@/modules/bcp/use/useBCPStatusEnum"
import { useGlobalStore } from "@/stores/global"
import ModalUploadBCPFile from "../components/ModalUploadBCPFile.vue"
import { ref } from "vue"

export default {
  name: "ViewBCPDetail",
  data() {
    return {
      reportFilesDeleting: [],
      isModalUploadBCPFileOpen: false,
      isLoading: false,
    }
  },
  mounted() {},
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { bcp, vldator$, createNewBCP } = useBCPNew()
    const { removeReportFile } = useBCPDetail()
    const { statuses } = useBCPStatusEnum()

    const modalUploadRef = ref()

    return {
      createNewBCP,
      bcp,
      vldator$,
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
      this.vldator$.$touch()

      if (this.vldator$.bcp.$invalid) {
        return
      }
      this.isLoading = true
      this.bcp.status = this.bcp.statusObj?.value
      const response = await this.createNewBCP(this.bcp)
      this.isLoading = false
      if (response) {
        goto("ViewBCPList")
      }
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
          size: file?.response?.size,
          mime_type: file?.response?.mime_type,
        })
      })
    },

    isFileAddedToReports(file) {
      return this.bcp.reports?.find((item) => {
        return file?.response?.uid === item?.uid
      })
    },
  },
  components: { ModalUploadBCPFile },
}
</script>
