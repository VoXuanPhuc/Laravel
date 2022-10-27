<template>
  <ul class="flex">
    <li v-for="(p, idx) in paginationList" :key="idx">
      <button :class="getPageLinkClass(p)" class="w-8 h-8" @click="handleClickPage(p)">
        <template v-if="p.type == 'icon'">
          <EcIcon class="text-c1-800" :icon="p.icon" width="14" />
        </template>
        <template v-else-if="p.type == 'three-dots'"> ... </template>
        <template v-else>
          {{ p.value }}
        </template>
      </button>
    </li>
  </ul>
</template>

<script>
export default {
  name: "RPagination",
  emits: ["update:modelValue"],
  props: {
    modelValue: {
      type: Number,
      required: true,
      default: 1,
    },
    totalItems: {
      type: Number,
      required: true,
      default: 10,
    },
    itemPerPage: {
      type: Number,
      required: true,
      default: 10,
    },
  },
  data() {
    return {
      visiblePage: 2,
    }
  },
  computed: {
    paginationList() {
      const list = []
      if (this.numberOfPage > 1) {
        let haveMore = false
        // Add go to first and prev item
        list.push({
          type: "icon",
          action: "goToFirst",
          icon: "ChevronDoubleLeft",
        })
        list.push({
          type: "icon",
          action: "prevPage",
          icon: "ChevronLeft",
        })
        if (this.numberOfPage > 6) {
          for (let i = 1; i <= this.numberOfPage; i++) {
            if (i < 1 + this.visiblePage || i > this.numberOfPage - this.visiblePage) {
              list.push({
                action: "goToPage",
                value: i,
              })
            } else {
              if (i === this.modelValue) {
                if (i > 1 + this.visiblePage && !haveMore) {
                  list.push({
                    type: "three-dots",
                  })
                }
                list.push({
                  action: "goToPage",
                  value: i,
                })
                if (i < this.numberOfPage - this.visiblePage) {
                  list.push({
                    type: "three-dots",
                  })
                }
                haveMore = true
              }
              if (!haveMore) {
                list.push({
                  type: "three-dots",
                })
                haveMore = true
              }
            }
          }
        } else {
          for (let i = 1; i <= this.numberOfPage; i++) {
            list.push({
              action: "goToPage",
              value: i,
            })
          }
        }

        // Add go to last and next item
        list.push({
          type: "icon",
          action: "nextPage",
          icon: "ChevronRight",
        })
        list.push({
          type: "icon",
          action: "goToLast",
          icon: "ChevronDoubleRight",
        })
      }

      return list
    },
    numberOfPage() {
      return Math.ceil(this.totalItems / this.itemPerPage)
    },
  },
  methods: {
    handleClickPage(p) {
      let nextValue = this.modelValue
      switch (p.action) {
        case "goToPage":
          nextValue = p.value
          break
        case "goToFirst":
          nextValue = 1
          break
        case "goToLast":
          nextValue = this.numberOfPage
          break
        case "prevPage":
          nextValue = this.modelValue - 1
          break
        case "nextPage":
          nextValue = this.modelValue + 1
          break
        default:
          break
      }

      // Boundary
      if (nextValue < 1) {
        nextValue = 1
      }
      if (nextValue > this.numberOfPage) {
        nextValue = this.numberOfPage
      }

      this.$emit("update:modelValue", nextValue)
    },
    getPageLinkClass(p) {
      return {
        "flex justify-center items-center border focus:outline-none rounded hover:text-c": true,
        "px-2 py-1": false,
        "border-cTransparent": p.value !== this.modelValue,
        "border-c1-800 bg-c1-800 text-cWhite": p.value === this.modelValue,
      }
    },
  },
}
</script>
