<template>
  <RLayout>
    <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
      {{ $t("industry.new.name") }}
    </EcHeadline>
    <RLayoutTwoCol>
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
              <!-- error message name has been used -->
              <EcBox v-if="isNameUnique" class="mt-2">
                <EcText class="text-cError-600 text-sm mt-1"> {{ $t("industry.nameUnique") }} </EcText>
              </EcBox>
            </EcBox>
          </EcFlex>
          <!-- description -->
          <EcFlex class="flex-wrap max-w-full">
            <EcBox class="w-full 2xl:w-6/12 mb-6 sm:pr-6">
              <RFormInput
                v-model="industry.description"
                :label="$t('industry.desc')"
                :validator="v$"
                componentName="EcInputText"
                field="industry.description"
                @input="v$.industry.description.$touch()"
              />
            </EcBox>
          </EcFlex>

          <!-- Actions -->
          <EcFlex v-if="!isCreating" class="justify-center mt-10">
            <EcButton variant="primary" @click="handleClickCreate">
              {{ $t("industry.create") }}
            </EcButton>
            <EcButton class="ml-3" variant="tertiary-outline" @click="handleClickCancel">
              {{ $t("industry.cancel") }}
            </EcButton>
          </EcFlex>
          <EcFlex v-else class="items-center justify-center mt-10 h-10">
            <EcSpinner type="dots" />
          </EcFlex>
        </EcBox>
      </template>
    </RLayoutTwoCol>

    <!-- Modals create success and move to portal -->
    <teleport to="#layer2">
      <EcModalSimple :isVisible="isCreatedIndustrySuccess" variant="center-3xl">
        <EcBox class="text-center">
          <EcBox>
            <EcHeadline as="h3" class="text-cSuccess-500 text-md" variant="h3">
              <EcText class="text-lg"> {{ $t("industry.createIndustrySuccess") }} </EcText>
              <EcText class="text-lg"> {{ $t("industry.visitNewIndustry") }} </EcText>
            </EcHeadline>

            <EcText class="text-2xl text-cError-500"> {{ this.industry.name }} </EcText>
          </EcBox>

          <!-- Actions -->
          <EcFlex class="justify-center mt-10">
            <EcButton class="mr-3" variant="tertiary-outline" @click="handleClickCancelViewNewIndustry">
              {{ $t("industry.no") }}
            </EcButton>
            <EcButton variant="primary" @click="handleClickVisitNewIndustry">
              {{ $t("industry.yes") }}
            </EcButton>
          </EcFlex>
        </EcBox>
      </EcModalSimple>
    </teleport>
  </RLayout>
</template>

<script>
import { goto } from "@/modules/core/composables"
import { useIndustryCreate } from "@/modules/industry/use/industry/useIndustryNew"

export default {
  name: "ViewIndustryNew",
  data() {
    return {
      isCreatedIndustrySuccess: false,
      isCreating: false,
      isNameUnique: false,
    }
  },

  setup() {
    const { industry, v$, createIndustry } = useIndustryCreate()
    // error code for unique name
    const NAME_UNIQUE = "NAME_UNIQUE"

    return {
      industry,
      v$,
      createIndustry,
      NAME_UNIQUE,
    }
  },

  methods: {
    // Handle create new industry
    async handleClickCreate() {
      this.v$.$touch()

      if (this.v$.$invalid) {
        return
      }

      this.isCreating = true

      const industryRes = await this.createIndustry(this.industry)
      if (industryRes && industryRes.uid) {
        this.industry = industryRes
        this.isCreatedIndustrySuccess = true
      } else {
        // show message when name has been used
        if (industryRes.message === this.NAME_UNIQUE) {
          this.isNameUnique = true
        }
      }
      this.isCreating = false
    },

    // go to view industry list
    handleClickCancel() {
      goto("ViewIndustryList")
    },

    // go to industry detail
    handleClickVisitNewIndustry() {
      goto("ViewIndustryDetail", {
        params: {
          industryUid: this.industry?.uid,
        },
      })
    },

    // back to view industry list
    handleClickCancelViewNewIndustry() {
      goto("ViewIndustryList")
    },
  },
}
</script>
