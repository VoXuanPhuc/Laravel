<template>
  <EcBox class="mb-4 mr-3 lg:inline-flex lg:flex-grow lg:w-auto" variant="card-2">
    <EcBox class="mt-4 mb-2 lg:mt-0 lg:ml-6 w-full">
      <EcText class="font-medium text-2xl text-cBlack min-h-[50%]">
        {{ industry.name }}
      </EcText>
      <EcFlex class="w-full mt-3">
        <EcBox class="w-full">
          <EcText class="font-medium text-c0-500 text-sm mt-2">
            Status:
            <span :class="statusText(industry.is_active)">{{ industry.is_active ? "Active" : "Inactive" }}</span>
          </EcText>
          <EcText class="font-medium text-c0-500 text-sm mt-2">
            Created at: {{ globalStore.formatDate(industry.created_at) }}
          </EcText>
        </EcBox>
        <!-- Actions -->
        <EcFlex class="items-center mt-2">
          <!-- Edit -->
          <EcBox v-if="industry.name" class="ml-2">
            <EcButton title="Edit" variant="transparent-rounded" @click="handleClickUpdate">
              <EcIcon class="text-cError-500" height="20" icon="Pencil" width="20" />
            </EcButton>
          </EcBox>
          <!-- Delete -->
          <EcBox class="ml-2">
            <EcButton title="Delete" variant="transparent-rounded" @click="handleOpenDeleteModal">
              <EcIcon class="text-cError-500" height="20" icon="TrashAlt" width="20" />
            </EcButton>
          </EcBox>

          <!-- End view -->
        </EcFlex>
      </EcFlex>
    </EcBox>
  </EcBox>

  <!-- Modals -->
  <teleport to="#layer2">
    <!-- Modal Delete -->
    <EcModalSimple :isVisible="isModalDeleteOpen" variant="center-3xl" @overlay-click="handleCloseDeleteModal">
      <EcBox class="text-center">
        <EcFlex class="justify-center">
          <EcIcon class="text-cError-500" width="4rem" height="4rem" icon="TrashAlt" />
        </EcFlex>

        <!-- Messages -->
        <EcBox>
          <EcHeadline as="h2" variant="h2" class="text-cError-500 text-4xl">
            {{ $t("industry.confirmToDelete") }}
          </EcHeadline>
          <!-- Industry name -->
          <EcText class="text-lg"> {{ industry?.name }} </EcText>
          <EcText class="text-c0-500 mt-4">
            {{ $t("industry.confirmDeleteQuestion") }}
          </EcText>
        </EcBox>

        <!-- Actions -->
        <EcFlex v-if="!isDeleteLoading" class="justify-center mt-10">
          <EcButton variant="warning" @click="handleClickDeleteBtn">
            {{ $t("industry.delete") }}
          </EcButton>
          <EcButton class="ml-3" variant="tertiary-outline" @click="handleCloseDeleteModal">
            {{ $t("industry.cancel") }}
          </EcButton>
        </EcFlex>
        <EcFlex v-else class="items-center justify-center mt-10 h-10">
          <EcSpinner type="dots" />
        </EcFlex>
      </EcBox>
    </EcModalSimple>
  </teleport>
</template>

<script>
import { useGlobalStore } from "@/stores/global"
import { goto } from "@/modules/core/composables"
import { useIndustryDetail } from "./../../use/industry/useIndustryDetail"

export default {
  name: "IndustryListCardItem",
  setup() {
    const globalStore = useGlobalStore()
    const { industries, deleteIndustry } = useIndustryDetail()
    return {
      industries,
      globalStore,
      deleteIndustry,
    }
  },

  props: {
    industry: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      isModalDeleteOpen: false,
      isDeleteLoading: false,
    }
  },

  methods: {
    statusText(status) {
      return status ? "font-bold text-cSuccess-500" : "font-bold text-cError-500"
    },

    handleClickUpdate() {
      goto("ViewIndustryDetail", {
        params: {
          industryUid: this.industry?.uid,
        },
      })
    },
    /** Open delete modal */
    handleOpenDeleteModal() {
      this.isModalDeleteOpen = true
    },
    /** Close delete modal */
    handleCloseDeleteModal() {
      this.isModalDeleteOpen = false
      this.confirmedOrganizationName = ""
    },
    /**
     * Delete Industry
     */
    async handleClickDeleteBtn() {
      this.isDeleteLoading = true
      await this.deleteIndustry(this.industry.uid)
      this.isDeleteLoading = false
      this.$emit("handleDeletedIndustry")
      this.handleCloseDeleteModal()
    },
  },
}
</script>
