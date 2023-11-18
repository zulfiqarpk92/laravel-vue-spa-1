<template>
  <v-card v-if="customerInfo" class="pt-10">
    <v-card-title class="flex-column justify-center">
      <v-avatar color="grey darken-1" size="120" rounded class="mb-4" />
      <span class="mb-2">{{ customerInfo.name }}</span>
      <v-chip>
        {{ customerInfo.role }}
      </v-chip>
      <v-card-text>
        <h2 class="text-xl font-semibold mb-2" style="display: flex;justify-content: space-between;">
          Customer Details
          <v-btn color="primary" small class="me-3" @click="showModal = true">
            Edit
          </v-btn>
        </h2>
        <v-divider />
        <v-list dense>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Name:</span>
                <span class="text--secondary">{{ customerInfo.name }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Phone:</span>
                <span class="text--secondary">{{ customerInfo.phone }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Email:</span>
                <span class="text--secondary">{{ customerInfo.email }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Address:</span>
                <span class="text--secondary">{{ customerInfo.address }} {{ customerInfo.city }} {{ customerInfo.province }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Comments:</span>
                <span class="text--secondary">{{ customerInfo.comments }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Total Purchases:</span>
                <span class="text--secondary">{{ invoicesTotal | currency }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Total Payments:</span>
                <span class="text--secondary">{{ paymentsTotal | currency }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item class="px-0 mb-n2">
            <v-list-item-content>
              <v-list-item-title>
                <span class="font-weight-medium me-2">Balance:</span>
                <span class="text--secondary">{{ balanceTotal | currency }}</span>
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-card-text>
      <v-card-actions>
        <v-dialog
          v-model="showModal"
          persistent
          max-width="800px"
        >
          <v-card>
            <v-card-title>
              <span class="text-h5">Edit Customer</span>
            </v-card-title>
            <v-card-text>
              <v-row>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="customer.name"
                    :rules="nameRules"
                    :error-messages="customer.errors.get('name')"
                    label="Name"
                    required
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="customer.email"
                    :rules="emailRules"
                    :error-messages="customer.errors.get('email')"
                    label="Email"
                    required
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="customer.phone"
                    :error-messages="customer.errors.get('phone')"
                    label="Phone"
                    required
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="customer.address"
                    :error-messages="customer.errors.get('address')"
                    label="Address"
                    required
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="customer.city"
                    :error-messages="customer.errors.get('city')"
                    label="City"
                    required
                  />
                </v-col>

                <v-col
                  cols="12"
                  md="4"
                >
                  <v-text-field
                    v-model="customer.province"
                    :error-messages="customer.errors.get('province')"
                    label="Province"
                    required
                  />
                </v-col>
              </v-row>
              <v-row>
                <v-col
                  cols="12"
                  md="12"
                >
                  <v-textarea
                    v-model="customer.comments"
                    label="Comments"
                    rows="2"
                  />
                </v-col>
              </v-row>
              <small class="red--text">* indicates required field</small>
            </v-card-text>
            <v-card-actions>
              <v-spacer />
              <v-btn
                color="blue darken-1"
                text
                @click="showModal = false"
              >
                Close
              </v-btn>
              <v-btn
                color="blue darken-1"
                text
                @click="updateUser"
              >
                Update
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-card-actions>
    </v-card-title>
  </v-card>
  <v-skeleton-loader
    v-else
    class="mx-auto"
    type="card, list-item-three-line, list-item-two-line"
  />
</template>

<script>
import Form from 'vform'
export default {
  props: { customerInfo: { type: Object, default: null } },
  data () {
    return {
      showModal: false,
      nameRules: [
        v => !!v || 'Name is required',
        v => v.length <= 20 || 'Name must be less than 20 characters'
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+/.test(v) || 'E-mail must be valid'
      ],
      customer: new Form(this.customerInfo)
    }
  },
  computed: {
    // TODO: get sales, purchases, and, payments total efficiently from the backend
    invoicesTotal () {
      return this?.customerInfo?.invoices?.reduce((total, inv) => total + inv.totalAmount, 0) ?? 0
    },
    paymentsTotal () {
      return this?.customerInfo?.payments?.reduce((total, p) => total + p.amount, 0) ?? 0
    },
    balanceTotal () {
      return this.invoicesTotal - this.paymentsTotal
    }
  },
  methods: {
    updateUser () {
      this.customer
        .put('/api/customers/' + this.customerInfo.id)
        .then(({ data }) => {
          this.$store.dispatch('snackbar/showMessage', data.message)
          this.showModal = false
          this.$emit('customerUpdated')
        })
        .catch(error => {
          if (error.response && error.response.data) {
            this.$set(this.form, 'errorMessage', error.response.data.message)
          }
          console.log(error)
        })
    }
  }
}
</script>

<style>
.label{
  display: block;
  width: 250px;
  align-self: flex-start;
}
</style>
