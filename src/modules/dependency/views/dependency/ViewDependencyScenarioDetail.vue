<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("dependencyScenario.title.editDependency") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox v-if="!isLoading" variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("dependencyScenario.title.dependencyDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="dependencyScenario.name"
            componentName="EcInputText"
            :label="$t('dependencyScenario.labels.name')"
            :validator="valdator$"
            field="dependencyScenario.name"
            @input="valdator$.dependencyScenario.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Desc -->
      <EcFlex class="flex-wrap max-w-full">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="dependencyScenario.description"
            componentName="EcInputLongText"
            :rows="1"
            :label="$t('dependencyScenario.labels.description')"
            :validator="valdator$"
            field="dependencyScenario.description"
            @input="valdator$.dependencyScenario.description.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Status -->
      <EcFlex class="flex-wrap max-w-full">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="dependencyScenario.status"
            componentName="EcSelect"
            :options="statuses"
            :label="$t('dependencyScenario.labels.status')"
            :validator="valdator$"
            field="dependencyScenario.status"
            @input="valdator$.dependencyScenario.status.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Target dependencies -->
      <EcBox>
        <!-- Resource or activity -->
        <EcFlex class="mb-3">
          <EcLabel class="text-sm"> {{ $t("dependencyScenario.target.label") }}</EcLabel>
          <!-- <EcButton
            variant="primary-rounded"
            class="h-6 ml-2"
            v-tooltip="{ text: 'More target dependencies', position: 'right' }"
            @click="handleAddMoreTargetDependency"
          >
            <EcIcon icon="Plus" class="h-6 w-4" />
          </EcButton> -->
        </EcFlex>

        <!-- Target dependencies selection  -->
        <!-- Warpper -->
        <EcBox class="w-full mb-6">
          <!-- Target dependency row -->
          <EcBox class="w-full sm:w-6/12 sm:pr-6">
            <EcFlex class="items-center">
              <RFormInput
                class="w-full lg:w-10/12 sm:pr-6"
                :modelValue="dependencyScenario.targets"
                componentName="EcMultiSelect"
                :options="filteredDependencyFactors"
                :valueKey="'uid'"
                :isGroupOptions="true"
                :validator="valdator$"
                field="dependencyScenario.targets"
              />

              <!-- Loading roles -->
              <EcSpinner v-if="isLoadingTargetDependencies" class="ml-"></EcSpinner>

              <!-- Remove button -->
              <!-- <EcButton
                v-if="index !== 0"
                class="ml-1 w-4 h-"
                variant="tertiary-rounded"
                @click="handleRemoveTargetDependency(index)"
              >
                <EcIcon class="text-c1-400 hover:text-cError-400" icon="X" width="14" height="14" />
              </EcButton> -->
            </EcFlex>
            <!-- Target dependency row -->
          </EcBox>
        </EcBox>
      </EcBox>

      <!-- Up/down stream -->
      <EcBox class="border border-bottom border-c1-100 mb-4" />
      <EcBox>
        <!-- Dependencies stream line -->
        <EcText class="font-bold text-lg mb-4">{{ $t("dependencyScenario.title.linksDependencies") }}</EcText>

        <EcFlex>
          <!-- Upstream -->
          <EcBox class="w-1/2">
            <!-- label and add button -->
            <EcFlex class="mb-3">
              <EcLabel class="text-sm mb-4">{{ $t("dependencyScenario.title.upstreamDependencies") }}</EcLabel>
              <!-- <EcButton
                variant="primary-rounded"
                class="h-6 ml-2"
                v-tooltip="{ text: 'More target', position: 'right' }"
                @click="handleAddMoreUpstreamDependency"
              >
                <EcIcon icon="Plus" class="h-6 w-4" />
              </EcButton> -->
            </EcFlex>

            <!-- Upstream dependencies selection  -->
            <!-- Warpper -->
            <EcBox class="w-full mb-6">
              <!-- upstream dependency row -->
              <EcBox class="w-full">
                <EcFlex class="items-center">
                  <RFormInput
                    class="w-full lg:w-10/12 sm:pr-6"
                    :modelValue="dependencyScenario.upstream"
                    componentName="EcMultiSelect"
                    :options="filteredUpstreamDependencyFactors"
                    :valueKey="'uid'"
                    :isGroupOptions="true"
                    :validator="valdator$"
                    field="dependencyScenario.upstream"
                  />

                  <!-- Loading upstream dependencies -->
                  <EcSpinner v-if="isLoadingUpstreamDependencies" class="ml-"></EcSpinner>

                  <!-- Remove button -->
                  <!-- <EcButton
                    v-if="index !== 0"
                    class="ml-1 w-4 h-"
                    variant="tertiary-rounded"
                    @click="handleRemoveUpstreamDependency(index)"
                  >
                    <EcIcon class="text-c1-400 hover:text-cError-400" icon="X" width="14" height="14" />
                  </EcButton> -->
                </EcFlex>
                <!-- Target upstream dependency row -->
              </EcBox>
            </EcBox>
          </EcBox>

          <!-- Downstream -->
          <EcBox class="w-1/2">
            <!-- label and add button -->
            <EcFlex class="mb-3">
              <EcLabel class="text-sm mb-4">{{ $t("dependencyScenario.title.downstreamDependencies") }}</EcLabel>
              <!-- <EcButton
                variant="primary-rounded"
                class="h-6 ml-2"
                v-tooltip="{ text: 'More target', position: 'right' }"
                @click="handleAddMoreDownstreamDependency"
              >
                <EcIcon icon="Plus" class="h-6 w-4" />
              </EcButton> -->
            </EcFlex>

            <!-- Downstream dependencies selection  -->
            <!-- Warpper -->
            <EcBox class="w-full mb-6">
              <!-- downstream dependency row -->
              <EcBox class="w-full">
                <EcFlex class="items-center">
                  <RFormInput
                    class="w-full lg:w-10/12 sm:pr-6"
                    :modelValue="dependencyScenario.downstream"
                    componentName="EcMultiSelect"
                    :options="filteredDownstreamDependencyFactors"
                    :valueKey="'uid'"
                    :isGroupOptions="true"
                    :validator="valdator$"
                    field="dependencyScenario.downstream"
                  />

                  <!-- Loading downstream dependencies -->
                  <EcSpinner v-if="isLoadingDownstreamDependencies" class="ml-"></EcSpinner>

                  <!-- Remove button -->
                  <!-- <EcButton
                    v-if="index !== 0"
                    class="ml-1 w-4 h-"
                    variant="tertiary-rounded"
                    @click="handleRemoveDownstreamDependency(index)"
                  >
                    <EcIcon class="text-c1-400 hover:text-cError-400" icon="X" width="14" height="14" />
                  </EcButton> -->
                </EcFlex>
                <!-- Target downstream dependency row -->
              </EcBox>
            </EcBox>
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- Actions -->
      <EcBox class="width-full mt-8 px-4 sm:px-10">
        <!-- Button create -->
        <EcFlex v-if="!isUpdateLoading" class="mt-6">
          <EcButton variant="tertiary-outline" @click="handleClickCancel">
            {{ $t("dependencyScenario.buttons.back") }}
          </EcButton>

          <EcButton variant="primary" class="ml-4" @click="handleClickUpdate">
            {{ $t("dependencyScenario.buttons.update") }}
          </EcButton>
        </EcFlex>

        <!-- Loading -->
        <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
      </EcBox>
      <!-- End actions -->
      <!-- End body -->
    </EcBox>
    <RLoading v-else />
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useDependencyScenarioDetail } from "@/modules/dependency/use/dependency/useDependencyScenarioDetail"
import { useDependencyFactor } from "@/modules/dependency/use/dependencyFactor/useDependencyFactor"
import { useDependencyScenarioStatusEnum } from "@/modules/dependency/use/dependency/useDependencyScenarioStatusEnum"
import { useGlobalStore } from "@/stores/global"

// Minxins
import dependencyMixin from "../../mixins/dependencyMixin"

export default {
  name: "ViewDependencyScenarioDetail",
  mixins: [dependencyMixin],
  props: {
    uid: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      isLoadingTargetDependencies: false,
      isLoadingUpstreamDependencies: false,
      isLoadingDownstreamDependencies: false,
      isLoading: false,
      isUpdateLoading: false,
    }
  },
  mounted() {
    this.fetchDependencyScenario()
    this.fetchDependencyFactors()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { dependencyScenario, valdator$, getDependencyScenario, updateDependencyScenario } = useDependencyScenarioDetail()
    const { statuses } = useDependencyScenarioStatusEnum()
    const { dependencyFactors, getDependencyFactors } = useDependencyFactor()

    return {
      getDependencyScenario,
      updateDependencyScenario,
      dependencyScenario,
      valdator$,
      globalStore,
      statuses,

      dependencyFactors,
      getDependencyFactors,
    }
  },
  computed: {},

  methods: {
    // =========== Target dependenciess ================ //
    /**
     * Add more target dependency
     */
    handleAddMoreTargetDependency() {
      this.dependencyScenario.targets.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveTargetDependency(index) {
      this.dependencyScenario.targets.splice(index, 1)
    },

    // =========== Upstream dependenciess ================ //
    /**
     * Add more upstream dependency
     */
    handleAddMoreUpstreamDependency() {
      this.dependencyScenario.upstream.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveUpstreamDependency(index) {
      this.dependencyScenario.upstream.splice(index, 1)
    },

    // =========== Downstream dependenciess ================ //
    /**
     * Add more downstream dependency
     */
    handleAddMoreDownstreamDependency() {
      this.dependencyScenario.downstream.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveDownstreamDependency(index) {
      this.dependencyScenario.downstream.splice(index, 1)
    },

    /**
     * Update dependencies scenario
     *
     */
    async handleClickUpdate() {
      this.valdator$.$touch()
      if (this.valdator$.dependencyScenario.$invalid) {
        return
      }

      this.isUpdateLoading = true

      const response = await this.updateDependencyScenario(this.dependencyScenario, this.uid)

      if (response) {
        this.dependencyScenario = response
      }

      this.isUpdateLoading = false
    },

    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewDependencyScenarioList")
    },

    // =========== PRE-LOAD -------//

    /**
     * Fetch dependency scenario detail
     */
    async fetchDependencyScenario() {
      this.isLoading = true
      const response = await this.getDependencyScenario(this.uid)

      if (response) {
        this.dependencyScenario = response
      }

      this.isLoading = false
    },

    /**
     * Fetch Dependency factors
     */

    async fetchDependencyFactors() {
      this.isLoadingTargetDependencies = true
      this.isLoadingUpstreamDependencies = true
      this.isLoadingDownstreamDependencies = true

      const response = await this.getDependencyFactors()
      if (response) {
        this.dependencyFactors = response
      }

      this.isLoadingTargetDependencies = false
      this.isLoadingUpstreamDependencies = false
      this.isLoadingDownstreamDependencies = false
    },
  },
}
</script>
