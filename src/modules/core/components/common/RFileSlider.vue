<template>
  <EcBox v-bind="$attrs">
    <Carousel :settings="settings" :breakpoints="breakpoints">
      <Slide v-for="(file, idx) in files" :key="file?.uid || idx" class="p-1">
        <!-- Each slide -->
        <EcFlex class="flex flex-col border border-c0-200 rounded w-full max-w-sm h-full">
          <!-- Actions group -->
          <EcFlex class="w-full justify-end mb-4 pt-2 pb-2 bg-c0-100 z-10">
            <!-- Download -->
            <EcButton variant="transparent" class="text-c0-400 hover:text-c0-600" @click="handleClickDownload(file)">
              <EcIcon icon="DocumentDownload" width="18" />
            </EcButton>

            <!-- Delete -->
            <EcButton variant="transparent" class="bg-c3-400 text-c0-400 hover:text-c0-600" @click="handleClickDeleteFile(file)">
              <EcIcon :icon="recordDeleting[file.uid] ? 'Spinner' : 'Trash'" class="mx-0" width="18" />
            </EcButton>
          </EcFlex>

          <!-- Name and date -->
          <EcBox @click="handleOpenFilePreview(file)" class="hover:cursor-pointer">
            <!-- Icon or image -->
            <EcFlex class="justify-center mb-2">
              <EcIcon v-if="!media.isImage(file)" :icon="media.fileIcon(file)" class="w-12 h-16" />
              <EcBox v-else class="w-12 h-16"> <img :src="file.url" /></EcBox>
            </EcFlex>

            <!-- File name -->
            <EcBox class="my-auto mb-1 p-1 text-left" :title="file?.name">
              <EcLabel class="font-semibold text-sm truncate"> {{ ++idx }}. {{ file?.name }} </EcLabel>

              <EcLabel class="text-sm text-ellipsis ml-3">
                {{ formatDateTime(file?.created_at) }}
              </EcLabel>
            </EcBox>
          </EcBox>
        </EcFlex>
      </Slide>

      <template #addons>
        <Navigation v-if="files?.length > 0" />
      </template>
    </Carousel>
  </EcBox>

  <teleport to="#layer1">
    <!-- Modal preview file -->
    <RModalPreview ref="modalFilePreview"></RModalPreview>
  </teleport>
</template>

<script>
import { useGlobalStore } from "@/stores/global"
import { defineComponent } from "vue"
import { Carousel, Navigation, Slide } from "vue3-carousel"
import { apiDeleteFile } from "@/readybc/composables/api/apiUploadFile"
import * as media from "@/readybc/composables/helpers/mediaHelper"

import "vue3-carousel/dist/carousel.css"

export default defineComponent({
  name: "Breakpoints",
  components: { Carousel, Slide, Navigation },
  emits: ["fileDeleted"],
  props: {
    files: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      // Deleting
      recordDeleting: [],

      // carousel settings
      settings: {
        itemsToShow: 1,
        snapAlign: "center",
      },
      // breakpoints are mobile first
      // any settings not specified will fallback to the carousel settings
      breakpoints: {
        // 700px and up
        700: {
          itemsToShow: 3,
          snapAlign: "center",
        },
        // 1024 and up
        1024: {
          itemsToShow: 6,
          snapAlign: "start",
        },
      },
    }
  },

  setup() {
    const globalStore = useGlobalStore()

    return {
      globalStore,
      media,
    }
  },
  methods: {
    /**
     *
     * @param {*} file
     */
    handleClickDownload(file) {
      window.open(file?.url, "_blank")
    },

    /**
     *
     * @param {*} file
     */
    async handleClickDeleteFile(file) {
      this.recordDeleting[file.uid] = true

      try {
        const res = await apiDeleteFile(file.uid)

        if (res) {
          this.$emit("fileDeleted", file.uid)
        }
      } catch (error) {
        this.globalStore.addErrorToastMessage("Delete failed")
      }

      this.recordDeleting[file.uid] = false
    },

    /**
     *
     * @param {*} url
     */
    handleOpenFilePreview(file) {
      this.$refs.modalFilePreview.file = file
      this.$refs.modalFilePreview.isModalPreviewOpen = true
      //  window.open(url, "_blank")
    },

    // Format Datetime
    formatDateTime(str) {
      return this.globalStore.formatDateTime(str)
    },
  },
})
</script>
