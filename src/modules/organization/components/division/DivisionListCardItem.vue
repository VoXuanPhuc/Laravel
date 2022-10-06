<template>
  <EcBox
    :variant="cardVariant"
    class="mb-2 p-2 mr-3 lg:inline-flex lg:flex-grow lg:w-auto hover:cursor-pointer"
    style="min-width: 12rem"
    @click="$emit('handleDivisionCardChange', division)"
  >
    <EcFlex class="relative justify-center items-center rounded-full w-24 h-auto overflow-hidden">
      <EcBox>
        <img :src="this.generateAvatar(division.name, division.avatar_color)" />
      </EcBox>
    </EcFlex>
    <EcBox class="mt-2 lg:mt-0 lg:ml-6">
      <EcText class="font-semibold text-md text-cBlack">
        {{ division.name }}
      </EcText>

      <EcText class="font-medium text-c0-500 text-sm mt-2">
        Status:
        <span :class="statusText(division.is_active)">{{ division.is_active ? "Active" : "Inactive" }}</span>
      </EcText>
      <EcText class="font-medium text-c0-500 text-sm mt-2"> {{ globalStore.formatDate(division.created_at) }}</EcText>

      <!-- Actions -->
      <EcFlex class="items-center mt-2">
        <!-- Edit -->
        <EcBox v-if="division.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickEdit" title="Edit">
            <EcIcon icon="Pencil" width="20" height="20" class="text-c1-800" />
          </EcButton>
        </EcBox>

        <!-- View -->

        <EcBox v-if="division.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickManageDivision" title="Manage Division">
            <EcIcon class="text-c0-500" icon="Folder" width="20" height="20" />
          </EcButton>
        </EcBox>

        <!-- End view -->
      </EcFlex>
    </EcBox>
  </EcBox>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { generateAvatar } from "../../use/division/useDivisionAvatar"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "DivisionListCardItem",
  props: {
    isActive: {
      type: Boolean,
      default: false,
    },
    organization: {
      type: Object,
      default: () => {},
    },
    division: {
      type: Object,
      default: () => {},
    },
  },

  setup() {
    const globalStore = useGlobalStore()

    const organizationUid = ""

    return {
      generateAvatar,
      globalStore,
      organizationUid,
    }
  },

  mounted() {
    const { organizationUid } = this.$route.params
    this.organizationUid = organizationUid
  },
  computed: {
    cardVariant() {
      return this.isActive ? "card-6" : "card-2"
    },
  },
  methods: {
    /**
     *
     * @param {*} status
     */
    statusText(status) {
      return status ? "font-bold text-cSuccess-500" : "font-bold text-cError-500"
    },

    /**
     * Click edit
     */
    handleClickEdit() {
      goto("ViewDivisionDetail", {
        params: {
          divisionUid: this.division?.uid,
        },
      })
    },

    /**
     * Click manage organization
     */
    handleClickManageDivision() {
      goto("ViewBusinessUnitList", {
        params: {
          divisionUid: this.division?.uid,
        },
      })
    },
  },
}
</script>
