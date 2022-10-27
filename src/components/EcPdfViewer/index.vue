<template>
  <div :class="variantCls.wrapper" v-bind="$attrs">
    <!-- Header -->
    <div :class="variantCls.header">
      <!-- Loading and pages -->

      <div v-if="isLoading" :class="variantCls.loading">Loading...</div>

      <!-- PAGE and CNT buttons -->
      <div v-else :class="variantCls.pageButtonWrapper">
        <!-- Show hide all pages -->
        <div>
          <input id="all-page" type="checkbox" v-model="showAllPages" />
          <label class="ml-1">All pages</label>
        </div>

        <!-- All page count -->
        <div v-if="showAllPages" :class="variantCls.allPages">
          <!-- Page cnt -->
          <span> {{ pageCount }} page(s) </span>
        </div>

        <!-- Each page count -->
        <div v-else :class="variantCls.pageButton.wrapper">
          <button :disabled="page <= 1" @click="page--" :class="variantCls.pageButton.left">❮</button>

          {{ page }} / {{ pageCount }}

          <button :disabled="page >= pageCount" @click="page++" :class="variantCls.pageButton.right">❯</button>
        </div>
      </div>

      <!-- All page -->
    </div>

    <!-- Content -->
    <div :class="variantCls.content">
      <VuePdfEmbed
        ref="pdfRef"
        :source="src"
        :page="page"
        @password-requested="handlePasswordRequest"
        @rendered="handleDocumentRender"
      ></VuePdfEmbed>
    </div>
  </div>
</template>

<style>
.vue-pdf-embed > div {
  margin-bottom: 8px;
  box-shadow: 0 2px 8px 4px rgba(0, 0, 0, 0.1);
}
.vue-pdf-embed > div > canvas {
  width: 100% !important;
  height: 98% !important;
}
</style>
<script>
import VuePdfEmbed from "vue-pdf-embed"

export default {
  name: "EcPdfViewer",
  components: { VuePdfEmbed },
  props: {
    variant: {
      type: String,
      default: "default",
    },
    src: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      page: 1,
      pageCount: 1,
      showAllPages: false,
      isLoading: true,
    }
  },
  computed: {
    variantCls() {
      return (
        this.getComponentVariants({
          componentName: "EcPdfViewer",
          variant: this.variant,
        })?.el ?? {}
      )
    },
  },

  methods: {
    handlePasswordRequest(callback, retry) {
      callback(prompt(retry ? "Enter password again" : "Enter password"))
    },
    handleDocumentRender() {
      this.isLoading = false
      this.pageCount = this.$refs.pdfRef.pageCount
    },
  },

  watch: {
    showAllPages() {
      this.page = this.showAllPages ? null : 1
    },
  },
}
</script>
