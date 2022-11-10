<template>
  <EcBox>
    <EcLabel class="text-base font-medium mb-2">{{ label }}</EcLabel>
    <Editor :apiKey="apiKey" :init="init" v-model="value" @change="handleContentChange" />
  </EcBox>
</template>

<script>
import Editor from "@tinymce/tinymce-vue"
import { reactive, watchEffect } from "vue"

export default {
  name: "EcEditor",
  components: { Editor },
  emits: ["update:modelValue"],
  props: {
    label: {
      type: String,
      default: "",
    },
    modelValue: {
      type: String,
    },
  },
  data() {
    const plugins = [
      "advlist",
      "autolink",
      "lists",
      "link",
      "image",
      "charmap",
      "print",
      "preview",
      "anchor",
      "searchreplace",
      "visualblocks",
      "code",
      "fullscreen",
      "insertdatetime",
      "media",
      "table",
      "paste",
      "code",
      "help",
      "wordcount",
    ]

    const toolbar = [
      "undo",
      "redo",
      "formatselect",
      "fontselect",
      "bold",
      "italic",
      "backcolor",
      "alignleft",
      "aligncenter",
      "alignright",
      "alignjustify",
      "bullist",
      "removeformat",
      "searchreplace",
      "anchor",
      "link",
      "image",
      "media",
      "numlist",
      "outdent",
      "indent",
      "code",
      "table",
      "help",
    ]

    const init = {
      selector: "textarea",
      menubar: false,
      plugins: plugins,
      toolbar: toolbar.join(" | "),
      images_upload_url: process.env.VUE_APP_EDITOR_UPLOAD_URL,
      automatic_uploads: true,
    }

    return {
      init,
    }
  },
  setup(props) {
    let value = reactive("")
    watchEffect(() => {
      value = props.modelValue
    })

    return {
      value,
    }
  },
  computed: {
    apiKey() {
      return process.env.VUE_APP_EDITOR_API_KEY
    },
  },
  methods: {
    /**
     * Content change
     */
    handleContentChange() {
      this.$emit("update:modelValue", this.value)
    },

    handleInsertContent(content) {},
  },

  watch: {
    modelValue(value) {
      this.value = value
    },
  },
}
</script>
