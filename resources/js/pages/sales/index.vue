<template>
  <v-card
    rounded="lg"
  >
    <v-card-title>Sales</v-card-title>
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
      <v-spacer />
      <div class="d-flex align-center flex-wrap">
        <v-btn
          color="primary"
          :to="{ name : 'sales.add' }"
          class="me-3 mb-4"
        >
          <v-icon>
            mdi-plus
          </v-icon>
          New Sale
        </v-btn>
      </div>
    </v-card-text>
    <v-data-table
      :headers="headers"
      :items="items"
      :options.sync="options"
      :server-items-length="totalItems"
      :loading="loading"
      item-key="name"
    >
      <template #item.customer="{ item }">
        {{ item }}
      </template>
      <template #item.actions="{ item }">
        <!-- <v-icon
          small
          class="mr-2"
          @click="$router.push({ name : 'sales.edit', params: { itemId: item.id } })"
        >
          mdi-pencil
        </v-icon> -->
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
  data () {
    return {
      totalItems: 0,
      loading: true,
      options: {},
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Date', value: 'sale_time' },
        { text: 'Customer', value: 'customer.name' },
        { text: 'Qty', value: 'total_qty' },
        { text: 'Total', value: 'total_amount' },
        { text: 'Created At', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      items: [],
      searchText: ''
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
    this.loadData()
  },
  methods: {
    loadData: function () {
      this.loading = true
      const { sortBy, sortDesc, page, itemsPerPage } = this.options
      this.$http.get(`/api/sales?per_page=${itemsPerPage}&page=${page}&orderBy=${sortBy}&orderDesc=${sortDesc}&q=${this.searchText}`)
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
