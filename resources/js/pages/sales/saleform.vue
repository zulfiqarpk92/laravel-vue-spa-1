<template>
  <div>
    <v-card rounded="lg">
      <v-app-bar
        dense
        flat
      >
        <v-btn
          class="mr-4"
          text
          to="/sales?list"
        >
          <v-icon>
            mdi-arrow-left
          </v-icon>
        </v-btn>

        <v-toolbar-title>{{ "New Bill" }}</v-toolbar-title>
      </v-app-bar>
      <v-form v-if="!loading">
        <v-container>
          <form-alert :form="form" />
          <v-row>
            <v-col
              cols="12"
              md="6"
            >
              <customer-lookup @selected="selectCustomer" />
            </v-col>

            <v-col
              cols="12"
              md="6"
            >
              <date-picker
                v-model="form.sale_time"
                :error-message="form.errors.get('sale_time')"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <item-lookup @selected="addItem" />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-simple-table dense>
                <template #default>
                  <thead>
                    <tr>
                      <th style="width:100px" />
                      <th style="width:100px">
                        Item #
                      </th>
                      <th>Name</th>
                      <th style="width:100px">
                        Price
                      </th>
                      <th style="width:100px">
                        Qty
                      </th>
                      <th style="width:100px">
                        Total
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="item in form.invoiceItems"
                      :key="item.id"
                    >
                      <td>
                        <v-btn
                          class="ma-2"
                          text
                          icon
                          color="red"
                          @click="removeItem(item.item_id)"
                        >
                          <v-icon>
                            mdi-delete
                          </v-icon>
                        </v-btn>
                      </td>
                      <td>{{ item.item_id }}</td>
                      <td>
                        <v-text-field
                          v-model="item.name"
                        />
                      </td>
                      <td>
                        <v-text-field
                          v-model="item.sale_price"
                        />
                      </td>
                      <td>
                        <v-text-field
                          v-model="item.quantity"
                          type="number"
                        />
                      </td>
                      <td>{{ parseFloat(item.sale_price * item.quantity) }}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th />
                      <th />
                      <th />
                      <th />
                      <th>{{ form.invoiceItems.reduce((sum, item) => sum + parseFloat(item.quantity), 0) }}</th>
                      <th>{{ form.invoiceItems.reduce((sum, item) => sum + parseFloat(item.sale_price * item.quantity), 0) }}</th>
                    </tr>
                  </tfoot>
                </template>
              </v-simple-table>
              <span class="error--text">{{ form.errors.get('invoiceItems') }}</span>
            </v-col>
          </v-row>
          <v-row>
            <v-col />
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-textarea
                v-model="form.comment"
                label="Comments"
                rows="2"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col>
              <v-btn
                color="success"
                class="mr-4"
                :loading="form.busy"
                :disabled="form.busy"
                @click="submitForm"
              >
                Submit
              </v-btn>
              <v-btn color="error" to="/sales">
                Cancel
              </v-btn>
            </v-col>
          </v-row>
        </v-container>
      </v-form>
    </v-card>
  </div>
</template>
<script>
import Form from 'vform'
import CustomerLookup from './components/CustomerLookup.vue'
import ItemLookup from './components/ItemLookup.vue'
import DatePicker from '../../components/DatePicker.vue'
import FormAlert from '../../components/FormAlert.vue'

export default {
  components: { FormAlert, DatePicker, CustomerLookup, ItemLookup },
  middleware: 'auth',
  data: function () {
    return {
      loading: false,
      form: new Form({
        customer_id: '',
        sale_time: '',
        comment: '',
        invoiceItems: []
      })
    }
  },
  methods: {
    onSaleDateChange (saleTime) {
      this.form.sale_time = saleTime
    },
    selectCustomer: function (customer) {
      const { id } = customer
      this.form.customer_id = id || 0
    },
    addItem: function (item) {
      let found = false
      for (const invoiceItem of this.form.invoiceItems) {
        if (invoiceItem.item_id === item.id) {
          invoiceItem.quantity += 1
          found = true
        }
      }
      if (!found) {
        console.log(item)
        this.form.invoiceItems.push({
          item_id: item.id,
          name: item.item_name,
          cost_price: parseFloat(item.cost_price ?? 0),
          sale_price: parseFloat(item.sale_price ?? 0),
          quantity: 1,
          total: 0
        })
      }
    },
    removeItem (itemId) {
      this.form.invoiceItems = this.form.invoiceItems.filter(item => item.id !== itemId)
    },
    submitForm: function () {
      this.form
        .post('/api/sales')
        .then(response => {
          this.$router.push({ name: 'sales' })
        })
        .catch(error => {
          console.log(error)
        })
    }
  }
}
</script>
