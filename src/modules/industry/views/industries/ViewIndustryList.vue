<template>
  <RLayout>
    <!-- Header -->
    <EcFlex class="items-center flex-wrap">
      <EcFlex class="items-center justify-between w-full flex-wrap lg:w-auto lg:mr-4">
        <EcHeadline class="text-cBlack mr-4 mb-3 lg:mb-0">
          {{ $t("industry.industry") }}
        </EcHeadline>
        <EcButton class="mb-3 lg:mb-0" iconPrefix="PlusCircle" variant="primary-sm" @click="handleClickCreateIndustry">
          {{ $t("industry.add") }}
        </EcButton>
      </EcFlex>
    </EcFlex>

    <!-- Industry List -->
    <IndustryList v-if="!isLoading" :listData="industryList" @handleDeletedIndustry="fetchIndustry" />

    <RLoading v-else />

    <!--    Pagination-->
    <EcFlex class="mt-8 flex-col sm:mt-12 sm:flex-row" variant="basic">
      <RPaginationStatus :currentPage="currentPage" :limit="limit" :totalCount="totalItems" class="mb-4 sm:mb-0" />
      <RPagination v-model="currentPage" :itemPerPage="limit" :totalItems="totalItems" />
    </EcFlex>
  </RLayout>
</template>

<script>
import IndustryList from "@/modules/industry/components/industries/IndustryList"
import RLoading from "@/modules/core/components/common/RLoading"
import { useIndustryList } from "@/modules/industry/use/industry/useIndustryList"
import { ref } from "vue"

export default {
  name: "ViewIndustryList",
  data() {
    return {
      isLoading: false,
    }
  },

  setup() {
    const { searchQuery, getIndustryList, totalItems, skip, limit, currentPage } = useIndustryList()

    const industryList = ref([])

    return {
      searchQuery,
      getIndustryList,
      industryList,
      totalItems,
      skip,
      limit,
      currentPage,
    }
  },

  mounted() {
    this.fetchIndustry()
  },

  methods: {
    // get industries
    async fetchIndustry() {
      this.isLoading = true
      this.industryList = await this.getIndustryList()
      this.isLoading = false
    },

    handleClickCreateIndustry() {
      this.$router.push({
        name: "AddIndustryNew",
      })
    },
  },

  components: {
    IndustryList,
    RLoading,
  },
}
</script>
