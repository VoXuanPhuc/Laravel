<template>
  <EcBox
    :variant="cardVariant"
    class="mb-4 mr-3 lg:inline-flex lg:flex-grow lg:w-auto hover:cursor-pointer"
    style="min-width: 12rem"
    @click="$emit('handleCardChange', division.uid)"
  >
    <EcFlex class="relative justify-center items-center p-4 rounded-full w-32 h-auto overflow-hidden">
      <EcText class="text-5xl">
        <img :src="divisionAvatar(division.name)" />
      </EcText>
    </EcFlex>
    <EcBox class="mt-4 lg:mt-0 lg:ml-6">
      <EcText class="font-medium text-2xl text-cBlack">
        {{ division.name }}
      </EcText>

      <EcText class="font-medium text-c0-500 text-sm mt-2">
        Status:
        <span :class="statusText(division.isActive)">{{ division.isActive ? "Active" : "Inactive" }}</span>
      </EcText>
      <EcText class="font-medium text-c0-500 text-sm mt-2"> Created at: {{ division.createdAt }}</EcText>

      <!-- Actions -->
      <EcFlex class="items-center mt-2">
        <!-- Edit -->
        <EcBox v-if="division.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickEdit" title="Edit">
            <EcIcon icon="Pencil" width="20" height="20" class="text-cError-500" />
          </EcButton>
        </EcBox>

        <!-- View -->

        <EcBox v-if="division.name" class="ml-2">
          <EcButton variant="transparent-rounded" @click="handleClickManageOrganization" title="Manage Division">
            <EcIcon class="text-c0-500" icon="Eye" width="20" height="20" />
          </EcButton>
        </EcBox>

        <!-- End view -->
      </EcFlex>
    </EcBox>
  </EcBox>
</template>

<script>
import EcBox from "@/components/EcBox/index.vue"
import { goto } from "@/modules/core/composables"
import EcText from "@/components/EcText/index.vue"
export default {
  name: "DivisionListCardItem",
  props: {
    isActive: {
      type: Boolean,
      default: false,
    },
    division: {
      type: Object,
      default: () => {},
    },
  },

  components: { EcBox, EcText },
  computed: {
    cardVariant() {
      return this.isActive ? "card-2" : "card-1"
    },
  },
  methods: {
    statusText(status) {
      return status ? "font-bold text-cSuccess-500" : "font-bold text-cError-500"
    },

    divisionAvatar(divisionName) {
      const avartarLetter = divisionName.substr(0, 2)
      return `https://ui-avatars.com/api/?name=${avartarLetter}&background=random`
    },
    handleClickEdit() {
      goto("ViewOrganizationDetail", {
        params: {
          organizationId: this.organization?.id,
        },
      })
    },
    handleClickManageOrganization() {
      goto("ViewOrganizationManagement", {
        params: {
          organizationId: this.organization?.id,
        },
      })
    },
  },
}
</script>
