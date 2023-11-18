<template>
  <v-row>
    <v-col
      cols="12"
      md="9"
    >
      <v-card>
        <v-card-text class="py-9 px-8">
          <form-alert id="error-component" :form="sale" />
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
                New Invoice
              </p>
              <p class="mb-2">
                <date-picker
                  v-model="sale.saleTime"
                  label="Sale Date"
                  :error-message="sale.errors.get('saleTime')"
                />
              </p>
              <p class="mb-2">
                <user-lookup
                  user-type="Customer"
                  @selected="selectCustomer"
                />
              </p>
            </div>
          </div>
        </v-card-text>
        <v-divider />
        <v-card-text class="py-9 px-8">
          <div class="payment-details d-flex justify-space-between flex-wrap flex-column flex-sm-row">
            <div v-if="sale.customer" class="mb-8 mb-sm-0">
              <p class="font-weight-semibold payment-details-header">
                Invoice To:
              </p>
              <p class="mb-1">
                {{ sale.customer.name }}
              </p>
              <p v-if="sale.customer.company_name" class="mb-1">
                {{ sale.customer.company_name }}
              </p>
              <p v-if="sale.customer.address" class="mb-1">
                {{ sale.customer.address }}
              </p>
              <p v-if="sale.customer.city || sale.customer.province" class="mb-1">
                {{ sale.customer.city }}, {{ sale.customer.province }}
              </p>
              <p v-if="sale.customer.phone" class="mb-1">
                {{ sale.customer.phone }}
              </p>
              <p v-if="sale.customer.email" class="mb-1">
                {{ sale.customer.email }}
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
        <invoice-items :invoice-items="sale.invoiceItems" edit @itemRemoved="handleItemRemoved" />
        <!-- <pre>
          {{ sale }}
        </pre> -->
        <v-card-text class="py-9 px-8">
          <div class="invoice-total d-flex justify-space-between flex-column flex-sm-row">
            <div class="mb-2 mb-sm-0 mr-16 flex-grow-1">
              <p><strong>Comments: </strong></p>
              <v-textarea
                v-model="sale.comments"
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
                      v-model="sale.discountAmount"
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
                      v-model="sale.amountPaid"
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
                    {{ (invoiceTotal - sale.discountAmount - sale.amountPaid) | currency }}
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
          <v-btn class="mb-3" block color="primary" @click="saveSale">
            Save
          </v-btn>
          <v-btn class="mb-3" block color="primary" @click="saveSale">
            Save as Draft
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
  name: 'AddSale',
  components: { DatePicker, InvoiceItems, ItemLookup },
  middleware: 'auth',
  data: function () {
    return {
      loading: false,
      sale: new Form({
        customer: null,
        customerId: 0,
        saleTime: '',
        invoiceItems: [],
        comments: '',
        discountAmount: 0,
        amountPaid: 0
      }),
      addItemDrawer: false
    }
  },
  computed: {
    invoiceTotal () {
      try {
        return this.sale.invoiceItems.reduce((sum, item) => sum + parseFloat(item.salePrice * item.quantity), 0)
      } catch (ex) {
        return 0
      }
    }
  },
  methods: {
    selectCustomer: function (customer) {
      const { id } = customer
      this.sale.customer = customer
      this.sale.customerId = id || 0
    },
    addItem (item) {
      let found = false
      for (const invoiceItem of this.sale.invoiceItems) {
        if (invoiceItem.itemId === item.id) {
          invoiceItem.quantity += 1
          found = true
        }
      }
      if (!found) {
        this.sale.invoiceItems.push({
          itemId: item.id,
          itemName: item.item_name,
          costPrice: parseFloat(item.cost_price ?? 0),
          salePrice: parseFloat(item.sale_price ?? 0),
          quantity: 1
        })
      }
    },
    handleItemRemoved (lineId) {
      this.sale.invoiceItems = this.sale.invoiceItems.filter(item => item.id !== lineId)
    },
    saveSale: function () {
      this.sale
        .post('/api/sales')
        .then(response => {
          response = response.data
          this.$store.dispatch('snackbar/showMessage', response.message ?? 'Invoice created')
          this.$router.push({ name: 'sales' })
        })
        .catch(error => {
          if (error.response && error.response.data) {
            this.$set(this.sale, 'errorMessage', error.response.data.message)
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
