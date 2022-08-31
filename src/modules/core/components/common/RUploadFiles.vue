<template>
  <EcFlex class="flex-wrap mt-4 -m-4 md:mt-10 lg:max-w-6xl">
    <EcBox class="w-full p-4 md:w-6/12">
      <RDroppableZone
        :dropZoneCls="dropZoneCls"
        :activeDropZoneCls="activeDropZoneCls"
        :dropZoneStyles="dropZoneStyles"
        :files="fileList"
        @change="handleInputFiles($event)"
      >
        <template v-slot="props">
          <EcFlex class="flex-col items-center justify-center">
            <EcIcon class="hidden text-c1-500 md:block" width="32" height="32" icon="CloudUpload" />
            <EcIcon class="text-c1-500 md:hidden" width="32" height="32" icon="CloudUpload" />
            <EcText class="mt-1 text-lg font-medium text-center text-c0-500">
              {{ $t("core.dragDropHere") }}
            </EcText>
            <EcText class="mt-1 font-medium text-c0-500"> {{ $t("core.or") }} </EcText>
            <EcButton variant="secondary" class="mt-4" @click="props.handleClickBrowse()">
              {{ $t("core.browse") }}
            </EcButton>
          </EcFlex>
        </template>
      </RDroppableZone>
    </EcBox>

    <!-- File lists -->
    <EcFlex class="flex-col justify-between w-full p-4 md:w-6/12">
      <EcBox>
        <EcHeadline variant="h2">{{ $t(documentTitle) }}</EcHeadline>
        <EcBox>
          <EcText v-if="!fileList.length" class="mt-4 text-c0-500">
            {{ $t("core.noFiles") }}
          </EcText>
          <RFileRow
            v-for="(fileObj, index) in fileList"
            :key="index"
            class="mt-8"
            :file="fileObj"
            @re-upload="handleReupload(fileObj)"
            @remove="handleRemoveFile(fileObj)"
            @replace="handleReplace(fileObj)"
          />
        </EcBox>
      </EcBox>
      <EcBox v-if="fileList && fileList.length" class="mt-10">
        <EcText v-if="error" class="mb-4 text-cError-500">
          {{ error }}
        </EcText>
        <EcButton class="justify-center w-full" @click="handleClickUpload()">
          {{ uploading ? $t("core.uploading") : $t("core.upload") }}
        </EcButton>
      </EcBox>
    </EcFlex>
    <!-- End file lists -->
  </EcFlex>
</template>

<script>
export default {
  name: "RUploadFiles",
  props: {
    dropZoneStyles: {
      type: String,
      default: "border-radius: 3rem",
    },
    dropZoneCls: {
      type: String,
      default: "border-c0-500 border-dashed border-2 bg-cWhite md:py-12 p-4 ",
    },
    activeDropZoneCls: {
      type: String,
      default: "border-c0-500 border-2 bg-cWhite opacity-50 md:py-12 p-4",
    },
    uploadedFiles: {
      type: Array,
      default: () => [],
    },
    filesOf: {
      validator(val) {
        return val === "user" || val === "organization"
      },
      required: true,
    },
    entityId: {
      type: String,
      required: true,
    },
    customPath: {
      type: String,
      default: "",
    },
    documentTitle: {
      type: String,
      default: "core.documents",
    },
  },
  data() {
    return {
      error: "",
      FILE_STATUS: {
        FAILED: this.$t("core.failed"),
        IN_PROGRESS: this.$t("core.inProgress"),
        READY: this.$t("core.ready"),
        UPLOADED: this.$t("core.uploaded"),
        EXISTED: this.$t("core.existed"),
      },
      fileList: [],
      uploading: false,
    }
  },
  computed: {
    getUrl() {
      let path = ""

      switch (this.filesOf) {
        case "user":
          path = encodeURIComponent(`entities/${this.entityId}/attachments`)
          break
        case "organization":
          path = encodeURIComponent(`claims/${this.entityId}/admin`) // default only admin see files, not share to client
          break
        case "custom":
          path = this.customPath
          break
      }

      return path
    },
    filesToBeUploaded() {
      return this.fileList.filter((file) => file.status !== this.FILE_STATUS.UPLOADED)
    },
    filesUploadedName() {
      return this.uploadedFiles.map((f) => f.fileName)
    },
  },
  methods: {
    handleInputFiles(files) {
      this.fileList = [...this.fileList, ...this.buildFileObject(files)]
    },
    handleClickUpload() {
      if (this.uploading) return
      this.error = ""
      if (this.filesToBeUploaded.length === 0) {
        this.error = this.$t("core.noFiles_upload")
        return
      }
      this.uploading = true
      const uploadQueue = []
      this.fileList.forEach((fileObj) => {
        if (fileObj.status !== this.FILE_STATUS.UPLOADED) {
          uploadQueue.push(this.uploadFile(fileObj))
        }
      })
      Promise.all(uploadQueue).finally(() => {
        this.uploading = false
        this.$emit("files:uploaded")
      })
    },
    handleReupload(fileObj) {
      this.error = ""
      this.uploadFile(fileObj)
    },
    handleReplace(fileObj) {
      const file = this.fileList[this.fileList.indexOf(fileObj)]
      file.status = this.FILE_STATUS.READY
      file.error = null
    },
    handleRemoveFile(fileObj) {
      this.fileList = this.fileList.filter((file) => file.name !== fileObj.name)
    },
    /* eslint-disable */
    async uploadFile(fileObj) {
      if (fileObj.status === this.FILE_STATUS.EXISTED) {
        this.error = this.$t("core.fileExisted")
        return
      }
      fileObj.status = this.FILE_STATUS.IN_PROGRESS
      fileObj.percentage = 0
      fileObj.error = ""
      const formData = new FormData()
      formData.append("file", fileObj.file)
      const options = {
        onUploadProgress(progressEvent) {
          fileObj.percentage = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        },
      }
      // const { error } = await apiUploadFile({ url: this.getUrl, data: formData, options, fetcher: restFetcher })
      this.error = null
      if (error) {
        fileObj.status = this.FILE_STATUS.FAILED
        fileObj.error = error?.message || this.$t("errors.general")
        // handleErrorForUser({ error, $t: this.$t })
      } else {
        fileObj.status = this.FILE_STATUS.UPLOADED
      }
    },
    /* eslint-enable */
    buildFileObject(fileList) {
      return [...fileList].map((f) => {
        let status = this.FILE_STATUS.READY
        let error = null
        if (this.filesUploadedName.includes(f.name)) {
          status = this.FILE_STATUS.EXISTED
          error = this.$t("core.existedFileName")
        }
        return {
          status,
          error,
          percentage: 0,
          name: f.name,
          file: f,
        }
      })
    },
  },
}
</script>
