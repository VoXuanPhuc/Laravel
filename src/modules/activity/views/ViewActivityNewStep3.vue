<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("activity.title.newActivity") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <!-- Title and cancel button -->
      <EcFlex>
        <EcText class="w-11/12 font-bold text-lg mb-4">{{ $t("activity.title.software") }}</EcText>
        <EcButton class="mx-auto mr-0 my-auto mt-0" variant="tertiary-rounded" title="Cancel" @click="handleOpenCancelModal">
          <EcIcon class="text-sm text-cError-500" icon="X" />
        </EcButton>
      </EcFlex>

      <!-- Softwares -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.software") }}</EcLabel>

          <!-- Softwares select -->
          <EcFlex class="items-center mb-2 w-full" v-for="(role, index) in form.softwares" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                v-model="form.softwares[index]"
                componentName="EcSelect"
                :options="filteredSoftwares"
                :valueKey="'uid'"
                :validator="v$"
                field="form.softwares[index]"
              />
              <!-- Add new Software slot -->
            </EcBox>

            <!-- Loading software -->
            <EcSpinner v-if="isLoadingSoftwares" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.softwares.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveSoftware(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add button -->
            <EcButton
              v-if="index == form.softwares.length - 1 && form.softwares.length < softwares.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreSoftware"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End Softwares select -->
        </EcBox>

        <!-- End Softwares select -->
      </EcFlex>
      <!-- End add more role -->

      <!-- IT data and storage -->
      <EcBox>
        <EcLabel class="text-sm mb-3">{{ $t("activity.labels.dataStorage") }}</EcLabel>

        <EcFlex class="flex-wrap max-w-full mb-8">
          <EcBox class="w-full sm:w-4/12 sm:pr-6">
            <RFormInput
              v-model="form.it.data"
              componentName="EcInputText"
              :label="$t('activity.labels.data')"
              :validator="v$"
              field="form.it.data"
              @input="v$.form.it.data.$touch()"
            />
          </EcBox>

          <EcBox class="w-full sm:w-4/12 sm:pr-6">
            <RFormInput
              v-model="form.it.location"
              componentName="EcInputText"
              :label="$t('activity.labels.storageLocation')"
              :validator="v$"
              field="form.it.location"
              @input="v$.form.it.location.$touch()"
            />
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Equipments -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full">
          <EcLabel class="text-sm"> {{ $t("activity.labels.equipments") }}</EcLabel>

          <!-- Equipments select -->
          <EcFlex class="items-center mb-2 w-full" v-for="(role, index) in form.equipments" :key="index">
            <EcBox class="w-full sm:w-6/12 sm:pr-6">
              <RFormInput
                v-model="form.equipments[index]"
                componentName="EcSelect"
                :options="filteredEquipments"
                :valueKey="'uid'"
                :validator="v$"
                field="form.equipments[index]"
              />
            </EcBox>

            <!-- Loading Equipment -->
            <EcSpinner v-if="isLoadingEquipments" class="ml-2"></EcSpinner>

            <!-- Remove button -->
            <EcButton
              v-if="index !== form.equipments.length - 1"
              class="ml-2"
              variant="tertiary-rounded"
              @click="handleRemoveEquipment(index)"
            >
              <EcIcon class="text-c1-300" icon="X" />
            </EcButton>

            <!-- Add new Equipment slot -->
            <EcButton
              v-if="index == form.equipments.length - 1 && form.equipments.length < equipments.length"
              class="ml-2"
              variant="primary-rounded"
              @click="handleAddMoreEquipment"
            >
              <EcIcon icon="Plus" />
            </EcButton>
          </EcFlex>
          <!-- End Equipment select -->
        </EcBox>

        <!-- End Equipments  -->
      </EcFlex>
      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button back -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickBack">
          {{ $t("activity.buttons.back") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickCreate">
          {{ $t("activity.buttons.finish") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->

    <!-- Modals -->
    <teleport to="#layer2">
      <ModalCancelAddActivity :isModalCancelOpen="isModalCancelOpen" @handleCloseCancelModal="handleCloseCancelModal" />
    </teleport>
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { ref } from "vue"
import { useActivityNewStep3 } from "../use/useActivityNewStep3"
import EcBox from "@/components/EcBox/index.vue"
import EcLabel from "@/components/EcLabel/index.vue"
import ModalCancelAddActivity from "../components/ModalCancelAddActivity.vue"

export default {
  name: "ViewActivityNewStep3",
  data() {
    return {
      isModalCancelOpen: false,
      isLoading: false,
      isLoadingSoftwares: false,
      isLoadingEquipments: false,
    }
  },
  setup() {
    const { form, v$ } = useActivityNewStep3()

    const softwares = ref([])
    const equipments = ref([])

    return {
      form,
      v$,
      softwares,
      equipments,
    }
  },

  mounted() {
    this.fetchSoftwares()
    this.fetchEquipments()
  },

  computed: {
    /**
     * Filter software
     */
    filteredSoftwares() {
      return this.softwares.map((software) => {
        software.disabled = this.form.softwares.includes(software.uid)

        return software
      })
    },

    /**
     * Filter equipments
     */
    filteredEquipments() {
      return this.equipments.map((equipment) => {
        equipment.disabled = this.form.equipments.includes(equipment.uid)

        return equipment
      })
    },
  },
  methods: {
    /**
     * Creaate Activity
     */
    async handleClickCreate() {
      this.v$.$touch()
      if (this.v$.$invalid) {
        return
      }
      this.isLoading = true
      // const response = await this.createActivity(this.form)
      this.isLoading = false
    },
    // =========== SOFTWARE ================ //
    /**
     * Add more alternative role
     */
    handleAddMoreSoftware() {
      this.form.softwares.push("")
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveSoftware(index) {
      this.form.softwares.splice(index, 1)
    },

    // =========== SOFTWARE ================ //

    /**
     * Add equipment
     */
    handleAddMoreEquipment() {
      this.form.equipments.push("")
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveEquipment(index) {
      this.form.equipments.splice(index, 1)
    },
    /**
     * Back to Activity list
     */
    handleClickBack() {
      goto("ViewActivityNewStep2")
    },

    /**
     * Open cancel modal
     */
    handleOpenCancelModal() {
      this.isModalCancelOpen = true
    },

    /**
     * Open cancel modal
     */
    handleCloseCancelModal() {
      this.isModalCancelOpen = false
    },

    // ======== Pre-load =======//

    /**
     * Software
     */
    async fetchSoftwares() {
      this.softwares = [
        {
          uid: "234343",
          name: "NAB Internet Banking",
        },
        {
          uid: "12334",
          name: "Software package #2",
        },
      ]
    },

    /**
     * Equipment
     */
    async fetchEquipments() {
      this.equipments = [
        {
          uid: "234343",
          name: "Laptop/PC",
        },
        {
          uid: "12334",
          name: "SIM Card",
        },
      ]
    },
  },
  components: { EcBox, EcLabel, ModalCancelAddActivity },
}
</script>
