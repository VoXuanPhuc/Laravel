<template>
  <!-- Log summary -->
  <EcFlex class="items-center mb-5">
    <Editor apiKey="4tdmwi7qg1ejhjnnjagsa63gbt2tqb8apbdusikvkaiymd6l" />
  </EcFlex>
</template>

<script>
import Editor from "@tinymce/tinymce-vue"
export default {
  name: "RCommentRow",

  components: { Editor },
  props: {
    log: {
      type: Object,
      default: () => {},
    },
  },

  methods: {
    getFromFormat(value) {
      return value === "None" ? "text-c0-400" : ""
    },
  },
}
</script>
