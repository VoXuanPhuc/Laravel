<template>
  <EcBox>
    <EcFlex variant="basic">
      <!-- file title and status -->
      <EcFlex class="flex-col flex-grow text-sm mr-10">
        <EcText class="font-semibold text-cBlack">{{ file.name }}</EcText>
        <EcBox v-if="file.status === FILE_STATUS.IN_PROGRESS" class="mt-4">
          <RProgressBar :percentage="file.percentage" />
        </EcBox>
        <EcText v-else class="mt-2 text-xs" :class="statusCls">
          {{ file.status }}
          {{ file.error ? ` - ${file.error}` : "" }}
        </EcText>
      </EcFlex>
      <EcBox>
        <!-- Actions -->
        <EcButton
          v-if="file.status === FILE_STATUS.FAILED"
          variant="tertiary-rounded-inv"
          class="bg-c0-300 p-1"
          @click="handleReupload()"
        >
          <EcIcon class="text-cWhite" width="12px" height="12px" icon="Refresh" />
        </EcButton>
        <EcButton
          v-if="file.status === FILE_STATUS.EXISTED"
          variant="tertiary-rounded-inv"
          class="bg-c0-300 p-1"
          @click="handleReplace()"
        >
          <EcIcon class="text-cWhite" width="12px" height="12px" icon="CloudUpload" />
        </EcButton>
        <EcButton
          v-else-if="file.status !== FILE_STATUS.UPLOADED"
          variant="tertiary-rounded-inv"
          class="bg-c0-300 p-1"
          @click="handleRemove()"
        >
          <EcIcon class="text-cWhite" width="12px" height="12px" icon="X" />
        </EcButton>
      </EcBox>
    </EcFlex>
  </EcBox>
</template>

<script>
export default {
  name: "CFileRow",
  props: {
    file: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      FILE_STATUS: {
        FAILED: this.$t("core.failed"),
        IN_PROGRESS: this.$t("core.inProgress"),
        READY: this.$t("core.ready"),
        UPLOADED: this.$t("core.uploaded"),
        EXISTED: this.$t("core.existed"),
      },
    }
  },
  computed: {
    statusCls() {
      return {
        "text-cSuccess-500": this.file.status === this.FILE_STATUS.UPLOADED,
        "text-cWarning-500": this.file.status === this.FILE_STATUS.READY,
        "text-cError-500": this.file.status === this.FILE_STATUS.FAILED || this.file.status === this.FILE_STATUS.EXISTED,
      }
    },
  },
  methods: {
    handleReupload() {
      this.$emit("re-upload", this.file)
    },
    handleRemove() {
      this.$emit("remove", this.file)
    },
    handleReplace() {
      this.$emit("replace", this.file)
    },
  },
}
</script>
