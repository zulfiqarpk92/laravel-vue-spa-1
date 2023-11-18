<template>
  <v-card
    rounded="lg"
  >
    <v-card-title>Suppliers</v-card-title>
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
          :to="{ name : 'suppliers.add' }"
          class="me-3 mb-4"
        >
          <v-icon>
            mdi-plus
          </v-icon>
          Add Supplier
        </v-btn>
      </div>
    </v-card-text>
    <v-data-table
      :headers="headers"
      :items="suppliers"
      :options.sync="options"
      :server-items-length="totalSuppliers"
      :loading="loading"
      item-key="name"
    >
      <template #item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          label="ledger"
          @click="$router.push({ name : 'customers.ledger', params: { customerId: item.id } })"
        >
          mdi-book-open-variant
        </v-icon>
        <v-icon
          small
          class="mr-2"
          label="edit"
          @click="$router.push({ name : 'suppliers.edit', params: { supplierId: item.id } })"
        >
          mdi-eye
        </v-icon>
        <v-icon
          small
          @click="deleteSupplier(item.id)"
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
      totalSuppliers: 0,
      loading: true,
      options: {},
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'Name', value: 'name' },
        { text: 'Email', value: 'email' },
        { text: 'Phone', value: 'phone' },
        { text: 'Created At', value: 'created_at' },
        { text: 'Actions', value: 'actions', sortable: false }
      ],
      suppliers: [],
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
      this.$http.get(`/api/suppliers?per_page=${itemsPerPage}&page=${page}&orderBy=${sortBy}&orderDesc=${sortDesc}&q=${this.searchText}`)
        .then(response => {
          const { meta, data } = response.data
          this.totalSuppliers = meta && meta.total
          this.suppliers = data || []
          this.loading = false
        })
        .catch(error => {
          this.totalSuppliers = 0
          this.loading = false
          if (error.response && error.response.data) {
            this.$set(this.form, 'errorMessage', error.response.data.message)
          }
          console.log(error)
        })
    },
    deleteSupplier: function (supplierId) {
      this.$confirm.fire({
        icon: 'warning',
        title: 'Delete Confirm',
        text: 'Are you sure you want to delete this customer?',
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel'
      }).then((result) => {
        if (result.isConfirmed) {
          this.$http.delete(`/api/suppliers/${supplierId}`).then(({ data }) => {
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
