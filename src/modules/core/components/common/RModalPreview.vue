<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalPreviewOpen" variant="file-preview" @keyup="handleKeyupOnPreview">
    <!-- Name and actions -->
    <EcFlex class="items-center w-full fixed mb-1">
      <!-- File name -->
      <EcBox>
        <EcText class="text-cWhite font-semibold">{{ file?.name || "Unknown" }}</EcText>
        <EcText class="text-cWhite">{{ file?.size / 1000 }} KB</EcText>
      </EcBox>

      <!-- Actions -->
      <EcFlex class="mx-auto mr-20">
        <!-- Download -->
        <EcButton variant="transparent" @click="handleClickDownload">
          <EcIcon icon="DocumentDownload" class="text-cWhite" />
        </EcButton>
        <!-- Close -->
        <EcButton variant="transparent" @click="handleClosePreviewModal">
          <EcIcon icon="X" class="text-cWhite" />
        </EcButton>
      </EcFlex>
    </EcFlex>

    <!-- File -->
    <RLoading v-if="isIFrameLoaded" />
    <EcFlex v-else class="items-center justify-center mt-10 w-full h-full">
      <!-- If file is image then display it -->
      <EcImageViewer v-if="media.isImage(file)" :src="file?.url" />

      <!-- PDF -->
      <EcPdfViewer v-else-if="media.isPDF(file)" :src="file.url" class="w-full md:w-[80%] h-[90%]"></EcPdfViewer>

      <!-- Using office 365 viewer to see the office files -->
      <iframe
        v-else-if="media.isOffice(file) || media.isTxt(file)"
        ref="iframeRef"
        style="width: 99%; height: 99%; color: #fff"
        :src="src"
        frameBorder="0"
      >
      </iframe>

      <!-- Other file -->
      <EcLabel v-else class="text-cWhite">Unable to show preview</EcLabel>

      <!-- End preview -->
    </EcFlex>
  </EcModalSimple>
</template>
<script>
import { ref } from "vue"
import RLoading from "./RLoading.vue"
import * as media from "@/readybc/composables/helpers/mediaHelper"

export default {
  name: "RModalPreview",
  emits: ["handleClosePreviewModal"],
  data() {
    return {
      file: ref(),
      isModalPreviewOpen: false,
    }
  },
  props: {},
  setup() {
    return {
      media,
    }
  },
  computed: {
    src() {
      const encodedUrl = encodeURIComponent(this.file?.url)

      if (this.media.isOffice(this.file)) {
        return `https://view.officeapps.live.com/op/view.aspx?src=${encodedUrl}&wdOrigin=BROWSELINK`
      }
      if (this.media.isTxt(this.file)) {
        return this.file?.url
      }

      // Default PDF viewer

      return this.file?.url
    },

    /**
     *
     */
    isIFrameLoaded() {
      const iframeDoc = this.$refs.iframeRef?.contentDocument || this.$refs.iframeRef?.contentWindow?.document

      if (iframeDoc?.readyState === "complete") {
        return true
      }

      return false
    },
  },
  beforeUnmount() {
    this.file = ref()
  },
  created() {
    window.addEventListener("keyup", this.handleKeyupOnPreview)
  },
  methods: {
    /**
     *
     * @param {*} file
     */
    handleClickDownload() {
      window.open(this.file?.url, "_blank")
    },
    /**
     * Close cancel modal
     */
    handleClosePreviewModal() {
      this.isModalPreviewOpen = false
      this.file = ref()
      this.$emit("handleClosePreviewModal")
    },

    /**
     *
     * @param {*} event
     */
    handleKeyupOnPreview(event) {
      if (event.key === "Escape" && this.isModalPreviewOpen) {
        this.handleClosePreviewModal()
      }
    },
  },

  components: { RLoading },
}
</script>
