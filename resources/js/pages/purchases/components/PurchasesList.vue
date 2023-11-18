<template>
  <v-card
    rounded="lg"
  >
    <v-card-title>Purchases</v-card-title>
    <v-card-text
      class="d-flex align-center flex-wrap pb-0"
    >
      <v-text-field
        v-model="searchText"
        outlined
        dense
        hide-details
        placeholder="Search"
        class="search-input me-3 mb-4"
        @keyup.enter="loadData"
      />
      <user-lookup
        v-if="!supplierId"
        user-type="Supplier"
        class="search-input me-3 mb-4"
        @selected="selectSupplier"
      />
      <v-spacer />
      <div class="d-flex align-center flex-wrap">
        <v-btn
          color="primary"
          :to="{ name : 'purchases.add' }"
          class="me-3 mb-4"
        >
          <v-icon>
            mdi-plus
          </v-icon>
          New Purchase
        </v-btn>
      </div>
    </v-card-text>
    <v-data-table
      :headers="visibleHeaders"
      :items="items"
      :options.sync="options"
      :server-items-length="totalItems"
      :loading="loading"
      item-key="name"
    >
      <template #item.supplier="{ item }">
        {{ item.supplier?.id }} -- {{ item.supplier?.name }}
      </template>
      <template #item.total_amount="{ item }">
        {{ item.total_amount | currency }}
      </template>
      <template #item.actions="{ item }">
        <v-icon
          small
          @click="$router.push({ name : 'purchases.view', params: { purchaseId: item.id } })"
        >
          mdi-eye
        </v-icon>
        <v-icon
          small
          @click="$router.push({ name : 'purchases.edit', params: { purchaseId: item.id } })"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          color="red"
          @click="deletePurchase(item.id)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
export default {
  middleware: 'auth',
  props: {
    supplierId: {
      type: [String, Number],
      default: 0
    }
  },
  data () {
    return {
      totalItems: 0,
      loading: true,
      options: {},
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Date', value: 'purchaseTime' },
        { text: 'Supplier', value: 'supplier' },
        { text: 'Qty', value: 'totalQty' },
        { text: 'Total', value: 'totalAmount' },
        { text: 'Balance', value: 'balance' },
        { text: 'Created At', value: 'createdAt' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      items: [],
      searchText: '',
      supplier: 0
    }
  },
  computed: {
    visibleHeaders () {
      const hideHeaders = []
      if (this.supplierId) {
        hideHeaders.push('supplier')
      }
      return this.headers.filter(h => !hideHeaders.includes(h.value))
    }
  },
  watch: {
    options: {
      handler () {
        this.loadData()
      },
      deep: true
    }
  },
  mounted () {
    if (this.supplierId) {
      this.supplier = this.supplierId
    }
  },
  methods: {
    selectSupplier: function (supplier) {
      this.supplier = supplier.id || 0
      this.loadData()
    },
    loadData: function () {
      this.loading = true
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      this.$http.get(`/api/purchases?per_page=${itemsPerPage}&page=${page}&orderBy=${sortBy}&orderDesc=${sortDesc}&q=${this.searchText}&supplier=${this.supplierId}`)
        .then(response => {
          const { meta, data } = response.data
          this.totalItems = meta && meta.total
          this.items = data || []
          this.loading = false
        })
        .catch(error => {
          this.totalItems = 0
          this.loading = false
          if (error.response && error.response.data) {
            this.$set(this.form, 'errorMessage', error.response.data.message)
          }
          console.log(error)
        })
    },
    deletePurchase: function (purchaseId) {
      this.$confirm.fire({
        icon: 'warning',
        title: 'Delete Confirm',
        text: 'Are you sure you want to delete this sale?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$http.delete(`/api/sales/${purchaseId}`).then(({ data }) => {
            this.$store.dispatch('snackbar/showMessage', data.message)
            this.loadData()
          })
        }
      })
    }
  }
}
</script>

<style lang="scss" scoped>
.search-input{
  max-width: 200px;
}
</style>
