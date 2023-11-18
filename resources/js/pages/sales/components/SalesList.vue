<template>
  <v-card
    rounded="lg"
  >
    <v-card-title>Sales</v-card-title>
    <v-card-text
      class="d-flex align-center flex-wrap"
    >
      <v-text-field
        v-model="searchText"
        dense
        hide-details
        placeholder="Search"
        class="search-input me-3"
        @keyup.enter="loadData"
      />
      <user-lookup
        v-if="!customerId"
        user-type="Customer"
        class="search-input me-3"
        @selected="selectCustomer"
      />
      <v-spacer />
      <div class="d-flex align-center flex-wrap">
        <v-btn
          color="primary"
          :to="{ name : 'sales.add' }"
        >
          <v-icon>
            mdi-plus
          </v-icon>
          New Sale
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
      <template #item.customer="{ item }">
        {{ item.customer?.id }} -- {{ item.customer?.name }}
      </template>
      <template #item.total_amount="{ item }">
        {{ item.total_amount | currency }}
      </template>
      <template #item.actions="{ item }">
        <v-icon
          small
          @click="$router.push({ name : 'sales.view', params: { saleId: item.id } })"
        >
          mdi-eye
        </v-icon>
        <v-icon
          small
          @click="$router.push({ name : 'sales.edit', params: { saleId: item.id } })"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          color="red"
          @click="deleteSale(item)"
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
    customerId: {
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
        { text: 'Date', value: 'saleTime' },
        { text: 'Customer', value: 'customer' },
        { text: 'Qty', value: 'totalQty' },
        { text: 'Total', value: 'totalAmount' },
        { text: 'Balance', value: 'balance' },
        { text: 'Created At', value: 'createdAt' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      items: [],
      searchText: '',
      customer: 0
    }
  },
  computed: {
    visibleHeaders () {
      const hideHeaders = []
      if (this.customerId) {
        hideHeaders.push('customer')
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
    if (this.customerId) {
      this.customer = this.customerId
    }
  },
  methods: {
    selectCustomer: function (customer) {
      this.customer = customer.id || 0
      this.loadData()
    },
    loadData: function () {
      this.loading = true
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      this.$http.get(`/api/sales?per_page=${itemsPerPage}&page=${page}&orderBy=${sortBy}&orderDesc=${sortDesc}&q=${this.searchText}&customer=${this.customer}`)
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
    deleteSale: function (sale) {
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
          this.$http.delete(`/api/sales/${sale.id}`).then(({ data }) => {
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
