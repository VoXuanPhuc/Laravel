<template>
  <!-- Modal Delete -->
  <EcModalSimple :isVisible="isModalUploadBCPFileOpen" variant="center-5xl" class="p-5">
    <EcFlex class="justify-end">
      <EcButton variant="transparent" class="text-cError-400" @click="handleCloseUploadModal">
        <EcIcon icon="X" />
      </EcButton>
    </EcFlex>

    <!-- Drag and drop zone -->
    <EcBox class="width-full mt-4">
      <EcBox class="w-full sm:pr-6">
        <RUploadFiles
          :dir="'bcp/plan'"
          :documentTitle="'bcp.plans.title'"
          :maxFileNum="10"
          dropZoneCls="border-c0-500 border-dashed border-1 bg-cWhite p-2 md:py-4"
          @files:uploaded="handleUploadCallback"
        />
      </EcBox>
    </EcBox>
  </EcModalSimple>
</template>
<script>
export default {
  name: "ModalDeleteResource",

  emits: ["handleCloseUploadModal", "handleUploadCallback"],
  data() {
    return {
      isDeleteLoading: false,
      uploadedFiles: [],
    }
  },
  props: {
    isModalUploadBCPFileOpen: {
      type: Boolean,
      default: false,
    },

    resourceUid: {
      type: String,
      default: null,
    },

    resourceName: {
      type: String,
      default: "",
    },
  },

  mounted() {},
  setup() {},
  methods: {
    /**
     * Close cancel modal
     */
    handleCloseUploadModal() {
      this.$emit("handleCloseUploadModal")
    },

    /**
     * Upload callback
     */
    handleUploadCallback(files) {
      this.$emit("handleUploadCallback", files)
    },
  },
}
</script>
