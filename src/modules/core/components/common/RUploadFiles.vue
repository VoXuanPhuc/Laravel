<template>
  <EcFlex class="flex-wrap mt-2 -m-4 md:mt-4 lg:max-w-6xl">
    <EcBox class="w-full p-4 md:w-6/12">
      <RDroppableZone
        :activeDropZoneCls="activeDropZoneCls"
        :disabled="isExceedMaxFile"
        :dropZoneCls="dropZoneCls"
        :dropZoneStyles="dropZoneStyles"
        :files="fileList"
        @change="handleInputFiles($event)"
      >
        <template v-slot="props">
          <EcFlex class="flex-col items-center justify-center">
            <EcIcon class="hidden text-c1-500 md:block" height="28" icon="CloudUpload" width="28" />
            <EcIcon class="text-c1-500 md:hidden" height="28" icon="CloudUpload" width="28" />
            <EcText class="mt-1 text-sm font-medium text-center text-c0-500">
              {{ $t("core.dragDropHere") }}
            </EcText>
            <EcText class="mt-1 font-medium text-c0-500"> {{ $t("core.or") }} </EcText>
            <EcButton :disabled="isExceedMaxFile" class="text-sm mt-4" variant="secondary" @click="props.handleClickBrowse()">
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
        <EcBox v-if="previewFileUrls.length <= 0">
          <EcText v-if="!fileList.length" class="mt-4 text-c0-500">
            {{ $t("core.noFiles") }}
          </EcText>
          <RFileRow
            v-for="(fileObj, index) in fileList"
            :key="index"
            :file="fileObj"
            :isImage="isImage"
            :isUploadOnSelect="isUploadOnSelect"
            :maxFileNum="maxFileNum"
            class="mt-2"
            @remove="handleRemoveFile(fileObj)"
            @replace="handleReplace(fileObj)"
            @re-upload="handleReupload(fileObj)"
          />
        </EcBox>
        <EcBox v-if="isImage && previewFileUrls.length > 0">
          <img v-for="imgUrl in previewFileUrls" :key="imgUrl" :src="imgUrl" class="w-32 h-auto" />
        </EcBox>
      </EcBox>
      <EcBox v-if="fileList && fileList.length" class="mt-10">
        <EcText v-if="error" class="mb-4 text-cError-500">
          {{ error }}
        </EcText>
        <EcButton v-if="!isUploadOnSelect" class="justify-center w-full" @click="handleClickUpload()">
          {{ uploading ? $t("core.uploading") : $t("core.upload") }}
        </EcButton>
      </EcBox>
    </EcFlex>
    <!-- End file lists -->
  </EcFlex>
</template>

<script>
import { apiUploadFile } from "../../api/fileUploader"

export default {
  name: "RUploadFiles",
  emits: ["handleSingleUploadResult"],
  props: {
    isUploadOnSelect: {
      type: Boolean,
      default: false,
    },
    isImage: {
      type: Boolean,
      default: false,
    },
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
    uploadedFileUrls: {
      type: Array,
      default: () => [],
    },
    customPath: {
      type: String,
      default: "",
    },
    documentTitle: {
      type: String,
      default: "core.documents",
    },
    dir: {
      type: String,
      default: "core.default_dir",
    },
    maxFileNum: {
      type: Number,
      default: -1,
    },
    url: {
      type: String,
      default: "",
    },
    fileListAfterUpload: {
      type: Array,
      default: () => [],
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
      previewFileUrls: [],
      uploading: false,
    }
  },

  watch: {
    uploadedFileUrls(data) {
      this.previewFileUrls = data
    },
    fileListAfterUpload(data) {
      this.fileList = data
    },
  },
  computed: {
    getUrl() {
      return ""
    },

    getDir() {
      return this.dir
    },

    filesToBeUploaded() {
      return this.fileList.filter((file) => file.status !== this.FILE_STATUS.UPLOADED)
    },
    filesUploadedName() {
      return this.uploadedFiles.map((f) => f.fileName)
    },
    isExceedMaxFile() {
      return this.fileList.length >= this.maxFileNum
    },
  },
  methods: {
    /**
     *
     * @param {*} files
     */
    handleInputFiles(files) {
      if (this.isExceedMaxFile) {
        return
      }
      this.previewFileUrls = []

      this.fileList = [...this.fileList, ...this.buildFileObject(files)]

      /**
       * If set to upload on select or drag and drop file
       * Then we trigger upload file
       */
      if (this.isUploadOnSelect) {
        this.handleClickUpload()
      }
    },

    /**
     * Handle click upload
     */
    handleClickUpload() {
      if (this.uploading) {
        return
      }

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

    /**
     *
     * @param {*} fileObj
     */
    handleReupload(fileObj) {
      this.error = ""
      this.uploadFile(fileObj)
    },

    /**
     *
     * @param {*} fileObj
     */
    handleReplace(fileObj) {
      const file = this.fileList[this.fileList.indexOf(fileObj)]
      file.status = this.FILE_STATUS.READY
      file.error = null
    },

    /**
     *
     * @param {*} fileObj
     */
    handleRemoveFile(fileObj) {
      this.fileList = this.fileList.filter((file) => file.name !== fileObj.name)
    },

    /* eslint-disable */
    async uploadFile(fileObj) {
      // If file existed then do nothing
      if (fileObj.status === this.FILE_STATUS.EXISTED) {
        this.error = this.$t("core.fileExisted")
        return
      }

      fileObj.status = this.FILE_STATUS.IN_PROGRESS
      fileObj.percentage = 0
      fileObj.error = ""

      const formData = new FormData()

      formData.append("file", fileObj.file)
      formData.append("dir", this.getDir)

      const options = {
        onUploadProgress(progressEvent) {
          fileObj.percentage = Math.round((progressEvent.loaded * 100) / progressEvent.total)
        },
      }
      const { data, error } = await apiUploadFile({ dir: this.getDir, data: formData, options })

      if (error) {
        fileObj.status = this.FILE_STATUS.FAILED
        fileObj.error = error?.message || this.$t("errors.general")
        // handleErrorForUser({ error, $t: this.$t })
      } else {
        fileObj.status = this.FILE_STATUS.UPLOADED
      }

      fileObj.reponse = data

      //Send event back to parent compent to use the response if needed
      this.$emit("handleSignleFileUploaded", data)

      return data
    },

    /**
     * eslint-enable
     * @param {*} fileList
     */
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
