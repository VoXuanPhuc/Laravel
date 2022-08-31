<template>
  <EcBox
    :class="onDragging ? activeDropZoneCls : dropZoneCls"
    :style="dropZoneStyles"
    class="relative"
    @dragenter="handleDragEnter"
    @dragleave="handleDragLeave"
    @drop.prevent="handleDrop($event)"
    @dragover.prevent
  >
    <input
      :id="inputId"
      ref="fileInput"
      class="absolute top-0 left-0 invisible w-full h-full"
      type="file"
      :accept="accept.map((ext) => `.${ext}`).join(', ')"
      :multiple="multiple && html5"
      @input="handleInputChange"
    />
    <slot :handleClickBrowse="handleClickBrowse">
      <EcFlex class="flex-col items-center justify-center">
        <EcText class="font-semibold">{{ $t("core.dragDropHere") }}</EcText>
        <EcText class="mt-4">{{ $t("core.or") }}</EcText>
        <EcButton class="mt-4" @click="handleClickBrowse()">{{ $t("core.browse") }}</EcButton>
      </EcFlex>
    </slot>
  </EcBox>
</template>

<script>
export default {
  name: "RDroppableZone",
  emits: ["change"],
  props: {
    dropZoneCls: {
      type: String,
      default: "p-10",
    },
    activeDropZoneCls: {
      type: String,
      default: "",
    },
    dropZoneStyles: {
      type: String,
      default: "",
    },
    inputId: {
      type: String,
      default: "files-input",
    },
    accept: {
      type: Array,
      default: () => {
        return ["jpg", "jpeg", "png", "gif", "tiff", "webp", "pdf", "doc", "xdoc", "csv", "xls", "xlsx", "txt"]
      },
    },
    multiple: {
      type: Boolean,
      default: true,
    },
    size: {
      type: Number,
      default: 0,
    },
    files: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      onDragging: 0,
    }
  },
  computed: {
    html5() {
      const inputEl = document.createElement("input")
      inputEl.type = "file"
      inputEl.multiple = true
      return window.FormData && inputEl.files
    },
  },

  watch: {
    files(val, oldVal) {
      // Reset input file if any files have been removed
      if (val.length < oldVal.length) {
        this.$refs.fileInput.value = ""
      }
    },
  },

  methods: {
    handleInputChange(e) {
      this.onDragging = 0
      const acceptedFiles = this.filterValidFiles(e.target.files)
      this.$emit("change", acceptedFiles)
    },
    handleDragEnter() {
      this.onDragging++
    },
    handleDragLeave() {
      this.onDragging--
    },
    handleDrop(e) {
      this.onDragging = 0
      const acceptedFiles = this.filterValidFiles(e.dataTransfer.files)
      this.$emit("change", acceptedFiles)
    },
    handleClickBrowse() {
      this.$refs.fileInput.click()
    },
    filterValidFiles(fileList) {
      const acceptedFiles = []
      Array.prototype.slice.call(fileList).forEach((file) => {
        // Exclude files that not in accept type list
        const extension = file.name.match(/\.([\w]+)$/)?.[1]
        if (!this.accept.includes(extension.toLowerCase())) {
          this.$store.dispatch("addToastMessage", {
            type: "error",
            content: {
              type: "message",
              text: this.$t("core.fileNotSupported", { type: extension }),
            },
          })
          return
        }

        // Exclude duplicate files
        if (this.files.some((item) => item.name === file.name)) {
          this.$store.dispatch("addToastMessage", {
            type: "error",
            content: {
              type: "message",
              text: this.$t("core.fileHasExisted", { name: file.name }),
            },
          })
          return
        }

        // Valid
        acceptedFiles.push(file)
      })
      return acceptedFiles
    },
  },
}
</script>
