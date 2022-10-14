<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("dependency.title.newDependency") }}
        </EcHeadline>
      </EcFlex>
    </EcFlex>

    <!-- Body -->
    <EcBox variant="card-1" class="width-full mt-8 px-4 sm:px-10">
      <EcText class="font-bold text-lg mb-4">{{ $t("dependency.title.dependencyDetail") }}</EcText>
      <!-- Name -->
      <EcFlex class="flex-wrap max-w-full mb-8">
        <EcBox class="w-full sm:w-6/12 sm:pr-6">
          <RFormInput
            v-model="dependency.name"
            componentName="EcInputText"
            :label="$t('dependency.labels.name')"
            :validator="valdator$"
            field="dependency.name"
            @input="valdator$.resource.name.$touch()"
          />
        </EcBox>
      </EcFlex>

      <!-- Desc -->
      <!-- <EcFlex class="flex-wrap max-w-full">
        <EcBox class="w-full sm:w-6/12 mb-6 sm:pr-6">
          <RFormInput
            v-model="dependency.description"
            componentName="EcInputLongText"
            :rows="1"
            :label="$t('dependency.labels.description')"
            :validator="valdator$"
            field="dependency.description"
            @input="valdator$.resource.description.$touch()"
          />
        </EcBox>
      </EcFlex> -->

      <!-- Target dependencies -->
      <EcBox>
        <!-- Resource or activity -->
        <EcFlex class="mb-3">
          <EcLabel class="text-sm"> {{ $t("dependency.target.label") }}</EcLabel>
          <EcButton
            variant="primary-rounded"
            class="h-6 ml-2"
            v-tooltip="{ text: 'More target dependencies', position: 'right' }"
            @click="handleAddMoreTargetDependency"
          >
            <EcIcon icon="Plus" class="h-6 w-4" />
          </EcButton>
        </EcFlex>

        <!-- Target dependencies selection  -->
        <!-- Warpper -->
        <EcBox class="w-full mb-6">
          <!-- Target dependency row -->
          <EcBox class="w-full sm:w-6/12 sm:pr-6" v-for="(targetDependency, index) in dependency.targetDependencies" :key="index">
            <EcFlex class="items-center">
              <RFormInput
                class="w-full lg:w-10/12 sm:pr-6"
                :modelValue="[dependency.targetDependencies[index]]"
                componentName="EcMultiSelect"
                :options="groupedCategories"
                :valueKey="'uid'"
                :isGroupOptions="true"
                :isSingleSelect="true"
                :validator="valdator$"
                field="dependency.targetDependencies[index]"
              />

              <!-- Loading roles -->
              <EcSpinner v-if="isLoadingTargetDependencies" class="ml-"></EcSpinner>

              <!-- Remove button -->
              <EcButton
                v-if="index !== 0"
                class="ml-1 w-4 h-"
                variant="tertiary-rounded"
                @click="handleRemoveTargetDependency(index)"
              >
                <EcIcon class="text-c1-400 hover:text-cError-400" icon="X" width="14" height="14" />
              </EcButton>
            </EcFlex>
            <!-- Target dependency row -->
          </EcBox>
        </EcBox>
      </EcBox>

      <!-- Up/down stream -->
      <EcBox class="border border-bottom border-c1-100 mb-4" />
      <EcBox>
        <!-- Dependencies stream line -->
        <EcText class="font-bold text-lg mb-4">{{ $t("dependency.title.linkedDependencies") }}</EcText>

        <EcFlex>
          <!-- Upstream -->
          <EcBox class="w-1/2">
            <!-- label and add button -->
            <EcFlex class="mb-3">
              <EcLabel class="text-sm mb-4">{{ $t("dependency.title.upstreamDependencies") }}</EcLabel>
              <EcButton
                variant="primary-rounded"
                class="h-6 ml-2"
                v-tooltip="{ text: 'More target', position: 'right' }"
                @click="handleAddMoreUpstreamDependency"
              >
                <EcIcon icon="Plus" class="h-6 w-4" />
              </EcButton>
            </EcFlex>

            <!-- Upstream dependencies selection  -->
            <!-- Warpper -->
            <EcBox class="w-full mb-6">
              <!-- upstream dependency row -->
              <EcBox class="w-full" v-for="(upstreamDependency, index) in dependency.upstreamDependencies" :key="index">
                <EcFlex class="items-center">
                  <RFormInput
                    class="w-full lg:w-10/12 sm:pr-6"
                    :modelValue="[dependency.upstreamDependencies[index]]"
                    componentName="EcMultiSelect"
                    :options="groupedCategories"
                    :valueKey="'uid'"
                    :isGroupOptions="true"
                    :isSingleSelect="true"
                    :validator="valdator$"
                    field="dependency.upstreamDependencies[index].uid"
                  />

                  <!-- Loading upstream dependencies -->
                  <EcSpinner v-if="isLoadingUpstreamDependencies" class="ml-"></EcSpinner>

                  <!-- Remove button -->
                  <EcButton
                    v-if="index !== 0"
                    class="ml-1 w-4 h-"
                    variant="tertiary-rounded"
                    @click="handleRemoveUpstreamDependency(index)"
                  >
                    <EcIcon class="text-c1-400 hover:text-cError-400" icon="X" width="14" height="14" />
                  </EcButton>
                </EcFlex>
                <!-- Target upstream dependency row -->
              </EcBox>
            </EcBox>
          </EcBox>

          <!-- Downstream -->
          <EcBox class="w-1/2">
            <!-- label and add button -->
            <EcFlex class="mb-3">
              <EcLabel class="text-sm mb-4">{{ $t("dependency.title.downstreamDependencies") }}</EcLabel>
              <EcButton
                variant="primary-rounded"
                class="h-6 ml-2"
                v-tooltip="{ text: 'More target', position: 'right' }"
                @click="handleAddMoreDownstreamDependency"
              >
                <EcIcon icon="Plus" class="h-6 w-4" />
              </EcButton>
            </EcFlex>

            <!-- Downstream dependencies selection  -->
            <!-- Warpper -->
            <EcBox class="w-full mb-6">
              <!-- downstream dependency row -->
              <EcBox class="w-full" v-for="(downstreamDependency, index) in dependency.downstreamDependencies" :key="index">
                <EcFlex class="items-center">
                  <RFormInput
                    class="w-full lg:w-10/12 sm:pr-6"
                    :modelValue="[dependency.downstreamDependencies[index]]"
                    componentName="EcMultiSelect"
                    :options="groupedCategories"
                    :valueKey="'uid'"
                    :isGroupOptions="true"
                    :isSingleSelect="true"
                    :validator="valdator$"
                    field="dependency.downstreamDependencies[index].uid"
                  />

                  <!-- Loading downstream dependencies -->
                  <EcSpinner v-if="isLoadingDownstreamDependencies" class="ml-"></EcSpinner>

                  <!-- Remove button -->
                  <EcButton
                    v-if="index !== 0"
                    class="ml-1 w-4 h-"
                    variant="tertiary-rounded"
                    @click="handleRemoveDownstreamDependency(index)"
                  >
                    <EcIcon class="text-c1-400 hover:text-cError-400" icon="X" width="14" height="14" />
                  </EcButton>
                </EcFlex>
                <!-- Target downstream dependency row -->
              </EcBox>
            </EcBox>
          </EcBox>
        </EcFlex>
      </EcBox>

      <!-- End body -->
    </EcBox>

    <!-- Actions -->
    <EcBox class="width-full mt-8 px-4 sm:px-10">
      <!-- Button create -->
      <EcFlex v-if="!isLoading" class="mt-6">
        <EcButton variant="tertiary-outline" @click="handleClickCancel">
          {{ $t("dependency.buttons.cancel") }}
        </EcButton>

        <EcButton variant="primary" class="ml-4" @click="handleClickConfirm">
          {{ $t("dependency.buttons.confirm") }}
        </EcButton>
      </EcFlex>

      <!-- Loading -->
      <EcBox v-else class="flex items-center mt-6 h-10"> <EcSpinner variant="secondary" type="dots" /> </EcBox>
    </EcBox>
    <!-- End actions -->
  </RLayout>
</template>
<script>
import { goto } from "@/modules/core/composables"
import { useDependencyNew } from "@/modules/dependency/use/dependency/useDependencyNew"
import { useCategoryList } from "@/modules/dependency/use/category/useCategoryList"
import { useResourceStatusEnum } from "@/modules/dependency/use/dependency/useResourceStatusEnum"
import { useOwnerList } from "@/modules/dependency/use/owner/useOwnerList"
import { useGlobalStore } from "@/stores/global"

export default {
  name: "ViewDependencyNew",
  data() {
    return {
      isLoadingTargetDependencies: false,
      isLoadingUpstreamDependencies: false,
      isLoadingDownstreamDependencies: false,
      isLoading: false,
      isLoadingOwners: false,
      isLoadingCategories: false,
      categories: [],
      owners: [],
    }
  },
  mounted() {
    this.fetchResourceCategories()
    this.fetchOwners()
  },
  setup() {
    const globalStore = useGlobalStore()
    // Pre-loaded
    const { getOwnerListAll } = useOwnerList()
    const { getResourceCategoryList } = useCategoryList()
    const { dependency, valdator$, createNewDependency } = useDependencyNew()
    const { statuses } = useResourceStatusEnum()

    return {
      createNewDependency,
      getOwnerListAll,
      getResourceCategoryList,
      dependency,
      valdator$,
      globalStore,
      statuses,
    }
  },
  computed: {
    groupedCategories() {
      return [
        {
          name: "Resources Notes",
          data: this.categories,
        },
        {
          name: "Activities",
          data: this.categories,
        },
      ]
    },
  },
  methods: {
    // =========== Target dependenciess ================ //
    /**
     * Add more target dependency
     */
    handleAddMoreTargetDependency() {
      this.dependency.targetDependencies.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveTargetDependency(index) {
      this.dependency.targetDependencies.splice(index, 1)
    },

    // =========== Upstream dependenciess ================ //
    /**
     * Add more upstream dependency
     */
    handleAddMoreUpstreamDependency() {
      this.dependency.upstreamDependencies.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveUpstreamDependency(index) {
      this.dependency.upstreamDependencies.splice(index, 1)
    },

    // =========== Upstream dependenciess ================ //
    /**
     * Add more upstream dependency
     */
    handleAddMoreDownstreamDependency() {
      this.dependency.upstreamDependencies.push({ uid: "" })
    },

    /**
     * Remove item in array
     * @param {*} index
     */
    handleRemoveDownstreamDependency(index) {
      this.dependency.upstreamDependencies.splice(index, 1)
    },

    /**
     * Create dependencies scenario
     *
     */
    async handleClickConfirm() {
      this.valdator$.$touch()
      if (this.valdator$.resource.$invalid) {
        return
      }

      this.isLoading = true

      const response = await this.createNewResource(this.resource)

      this.isLoading = false
      if (response) {
        goto("ViewResourceList")
      }
    },

    /**
     * Cancel add new resource
     */
    handleClickCancel() {
      goto("ViewResourceList")
    },

    // =========== PRE-LOAD -------//
    /**
     * Fetch roles
     */
    async fetchOwners() {
      this.isLoadingOwners = true
      const response = await this.getOwnerListAll()
      if (response) {
        this.owners = response
      }
      this.isLoadingOwners = false
    },
    /**
     * Fetch Categories
     */
    async fetchResourceCategories() {
      this.isLoadingCategories = true
      const response = await this.getResourceCategoryList()
      if (response) {
        this.categories = response
      }
      this.isLoadingCategories = false
    },
  },
}
</script>
