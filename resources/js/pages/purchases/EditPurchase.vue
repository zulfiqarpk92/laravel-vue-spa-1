<template>
  <v-row>
    <v-col
      cols="12"
      md="9"
    >
      <v-card>
        <v-card-text class="py-9 px-8">
          <form-alert id="error-component" :form="purchase" />
          <div class="d-flex flex-wrap justify-space-between flex-column flex-sm-row">
            <div class="mb-8 mb-sm-0">
              <div class="d-flex align-center mb-6">
                <v-img
                  lazy-src="https://picsum.photos/id/11/10/6"
                  max-height="30"
                  max-width="30"
                  src="https://picsum.photos/id/11/500/300"
                />
                <span class="text--primary font-weight-bold text-xl ml-6"> {{ $settings.company }} </span>
              </div>
              <div class="d-block">
                {{ $settings.address }}
              </div>
              <div class="d-block">
                <strong>Phone: </strong>{{ $settings.phone }}
              </div>
              <div class="d-block">
                <strong>Email: </strong>{{ $settings.email }}
              </div>
              <div class="d-block">
                <strong>Website: </strong>{{ $settings.website }}
              </div>
            </div>
            <div>
              <p class="font-weight-medium text-xl text--primary mb-4">
                Purchase # {{ purchase.id }}
              </p>
              <p class="mb-2">
                <date-picker
                  v-model="purchase.purchaseTime"
                  label="Purchase Date"
                  :error-message="purchase.errors.get('purchaseTime')"
                />
              </p>
              <p class="mb-2">
                <user-lookup
                  user-type="Supplier"
                  @selected="selectCustomer"
                />
              </p>
            </div>
          </div>
        </v-card-text>
        <v-divider />
        <v-card-text class="py-9 px-8">
          <div class="payment-details d-flex justify-space-between flex-wrap flex-column flex-sm-row">
            <div v-if="purchase.supplier" class="mb-8 mb-sm-0">
              <p class="font-weight-semibold payment-details-header">
                Supplier:
              </p>
              <p class="mb-1">
                {{ purchase.supplier.name }}
              </p>
              <p v-if="purchase.supplier.company_name" class="mb-1">
                {{ purchase.supplier.company_name }}
              </p>
              <p v-if="purchase.supplier.address" class="mb-1">
                {{ purchase.supplier.address }}
              </p>
              <p v-if="purchase.supplier.city || purchase.supplier.province" class="mb-1">
                {{ purchase.supplier.city }}, {{ purchase.supplier.province }}
              </p>
              <p v-if="purchase.supplier.phone" class="mb-1">
                {{ purchase.supplier.phone }}
              </p>
              <p v-if="purchase.supplier.email" class="mb-1">
                {{ purchase.supplier.email }}
              </p>
            </div>
          </div>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <item-lookup @selected="addItem" />
            </v-col>
          </v-row>
        </v-card-text>
        <invoice-items :invoice-items="purchase.purchaseItems" edit @itemRemoved="handleItemRemoved" />
        <!-- <pre>
          {{ purchase }}
        </pre> -->
        <v-card-text class="py-9 px-8">
          <div class="invoice-total d-flex justify-space-between flex-column flex-sm-row">
            <div class="mb-2 mb-sm-0 mr-16 flex-grow-1">
              <p><strong>Comments: </strong></p>
              <v-textarea
                v-model="purchase.comments"
                outlined
                rows="2"
              />
            </div>
            <div>
              <table>
                <tr>
                  <td class="pe-16">
                    Total:
                  </td>
                  <th class="text-right">
                    {{ invoiceTotal | currency }}
                  </th>
                </tr>
                <tr>
                  <td class="pe-16">
                    Discount:
                  </td>
                  <th class="text-right">
                    <v-text-field
                      v-model="purchase.discountAmount"
                      type="number"
                    />
                  </th>
                </tr>
                <tr>
                  <td class="pe-16">
                    Payment:
                  </td>
                  <th class="text-right">
                    <v-text-field
                      v-model="purchase.amountPaid"
                      type="number"
                    />
                  </th>
                </tr>
              </table>
              <hr role="separator" aria-orientation="horizontal" class="mt-4 mb-3 v-divider theme--light">
              <table class="w-full">
                <tr>
                  <td class="pe-16">
                    Balance:
                  </td>
                  <th class="text-right">
                    {{ (invoiceTotal - purchase.discountAmount - purchase.amountPaid) | currency }}
                  </th>
                </tr>
              </table>
            </div>
          </div>
        </v-card-text>
        <v-divider />
        <v-card-text class="py-9 px-8">
          <p class="mb-0">
            <span class="font-weight-semibold">Return Policy: </span><span>{{ $settings.return_policy }}</span>
          </p>
        </v-card-text>
      </v-card>
    </v-col>
    <v-col cols="12" md="3">
      <v-card class="invoice-options">
        <v-card-text>
          <v-btn class="mb-3" block color="primary" @click="loadData">
            Refresh
          </v-btn>
          <v-btn class="mb-3" block color="primary" @click="updateInvoice">
            Update
          </v-btn>
          <v-btn class="mb-3" block color="error" to="/sales">
            Cancel
          </v-btn>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import Form from 'vform'
import InvoiceItems from './components/InvoiceItems.vue'
import ItemLookup from './components/ItemLookup.vue'
import DatePicker from '../../components/DatePicker.vue'

export default {
  name: 'EditPurchase',
  components: { DatePicker, InvoiceItems, ItemLookup },
  middleware: 'auth',
  props: {
    purchaseId: {
      type: String,
      default: ''
    }
  },
  data: function () {
    return {
      loading: false,
      purchase: new Form({
        id: 0,
        supplier: null,
        supplierId: 0,
        purchaseTime: '',
        purchaseItems: [],
        comments: '',
        discountAmount: 0,
        amountPaid: 0
      })
    }
  },
  computed: {
    invoiceTotal () {
      try {
        return this.purchase.purchaseItems.reduce((sum, item) => sum + parseFloat(item.purchasePrice * item.quantity), 0)
      } catch (ex) {
        return 0
      }
    }
  },
  watch: {
  },
  mounted () {
    if (this.purchaseId) {
      this.loadData()
    }
  },
  methods: {
    loadData: function () {
      this.loading = true
      this.$http
        .get('/api/purchases/' + this.purchaseId)
        .then(({ data }) => {
          this.purchase.reset()
          this.purchase.fill(data.data)
          this.purchase.supplierId = data.data.supplier.id
          this.purchase.purchaseItems = data.data.items
          this.loading = false
        })
        .catch(error => {
          console.log(error)
          this.loading = false
        })
    },
    selectCustomer: function (purchase) {
      const { id } = purchase
      this.purchase.supplier = purchase
      this.purchase.supplierId = id || 0
    },
    addItem (item) {
      let found = false
      for (const purchaseItem of this.purchase.purchaseItems) {
        if (purchaseItem.itemId === item.id) {
          purchaseItem.quantity += 1
          found = true
        }
      }
      if (!found) {
        const c = this.purchase.purchaseItems.length + 1
        this.purchase.purchaseItems.push({
          id: 'temp-' + c,
          itemId: item.id,
          itemName: item.item_name,
          purchasePrice: parseFloat(item.cost_price ?? 0),
          quantity: 1
        })
      }
    },
    handleItemRemoved (lineId) {
      this.purchase.purchaseItems = this.purchase.purchaseItems.filter(item => item.id !== lineId)
    },
    updateInvoice: function () {
      this.purchase
        .put('/api/purchases/' + this.purchaseId)
        .then(response => {
          response = response.data
          this.$store.dispatch('snackbar/showMessage', response.message ?? 'Purchase updated')
          this.loadData()
          this.$router.push({ name: 'purchases' })
        })
        .catch(error => {
          if (error.response && error.response.data) {
            this.$set(this.purchase, 'errorMessage', error.response.data.message)
          }
          // Get a reference to the error component element
          const errorComponent = document.getElementById('error-component')

          if (errorComponent) {
            // Scroll to the error component
            errorComponent.scrollIntoView({ behavior: 'smooth', block: 'end', inline: 'nearest' }) // "smooth" for smooth scrolling
          }
          console.log(error)
        })
    }
  }
}
</script>

<style>
.invoice-options{
  position: sticky;
  top: 80px;
}
</style>
