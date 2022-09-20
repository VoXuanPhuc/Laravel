<template>
  <RLayout>
    <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
      {{ $t("industry.edit.name") }}
    </EcHeadline>
    <RLayoutTwoCol :isLoading="isLoading">
      <template #left>
        <EcBox class="width-full px-4 sm:px-10 mt-0">
          <!-- Name -->
          <EcFlex class="flex-wrap max-w-full">
            <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
              <RFormInput
                v-model="industry.name"
                :label="$t('industry.name')"
                :validator="v$"
                componentName="EcInputText"
                field="industry.name"
                @input="v$.industry.name.$touch()"
              />
              <!-- error message name unique -->
              <EcBox v-if="isNameUnique" class="mt-2">
                <EcBox>
                  <EcText class="text-cError-600 text-sm mt-1"> {{ $t("industry.nameUnique") }} </EcText>
                </EcBox>
              </EcBox>
            </EcBox>
          </EcFlex>
          <!-- description -->
          <EcFlex class="flex-wrap max-w-full">
            <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
              <RFormInput
                v-model="industry.description"
                :label="$t('industry.desc')"
                componentName="EcInputText"
                field="industry.description"
                @input="v$.industry.description.$touch()"
              />
            </EcBox>
          </EcFlex>

          <!-- Actions -->
          <EcFlex v-if="!isUpdating" class="justify-center mt-10">
            <EcButton variant="primary" @click="handleClickUpdateIndustry">
              {{ $t("industry.update") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleClickCancelUpdateIndustry">
              {{ $t("industry.cancel") }}
            </EcButton>
          </EcFlex>
          <EcFlex v-else class="items-center justify-center mt-10 h-10">
            <EcSpinner type="dots" />
          </EcFlex>
        </EcBox>
      </template>
    </RLayoutTwoCol>
  </RLayout>
</template>

<script>
import { useIndustryDetail } from "@/modules/industry/use/industry/useIndustryDetail"
import { goto } from "@/modules/core/composables"

export default {
  name: "ViewIndustryDetail",
  data() {
    return {
      isLoading: false,
      isNameUnique: false,
      isUpdating: false,
    }
  },
  setup() {
    const { industry, v$, getIndustry, updateIndustry } = useIndustryDetail()
    // error code for unique name
    const NAME_UNIQUE = "NAME_UNIQUE"

    return {
      industry,
      v$,
      getIndustry,
      updateIndustry,
      NAME_UNIQUE,
    }
  },
  mounted() {
    this.fetchIndustry()
  },
  methods: {
    // fetch industry by uid
    async fetchIndustry() {
      this.isLoading = true
      this.isNameUnique = false

      const { industryUid } = this.$route.params
      this.industry = await this.getIndustry(industryUid)

      this.isLoading = false
    },

    // handle update industry
    async handleClickUpdateIndustry() {
      this.isUpdating = true
      const industry = await this.updateIndustry(this.industry.uid, this.industry)

      if (industry && industry.uid) {
        this.industry = industry
        this.isNameUnique = false
      } else {
        // show error if name has been used
        if (industry.message === this.NAME_UNIQUE) {
          this.isNameUnique = true
        }
      }

      this.isUpdating = false
    },

    // cancel update industry
    handleClickCancelUpdateIndustry() {
      goto("ViewIndustryList")
    },
  },
}
</script>
