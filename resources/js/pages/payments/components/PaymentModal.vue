<template>
  <v-dialog
    v-model="isOpen"
    persistent
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="text-h5">Add Payment</span>
      </v-card-title>
      <v-card-text>
        <form-alert id="error-component" :form="payment" />
        <v-container>
          <v-row>
            <v-col
              cols="12"
              sm="12"
              md="12"
            >
              <user-lookup
                user-type="User"
                allow-create
                @selected="selectUser"
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              sm="6"
              md="6"
            >
              <date-picker
                v-model="payment.paymentTime"
                label="Payment Date"
              />
            </v-col>
            <v-col
              cols="12"
              sm="6"
              md="6"
            >
              <v-select
                v-model="payment.paymentMode"
                :items="['Cash', 'Bank Transfer']"
                label="Payment Date *"
                required
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="payment.description"
                label="Description"
                hint="Enter the description for later reference."
                persistent-hint
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="payment.reference"
                label="Reference *"
                hint="Enter the payment reference if there is any."
                persistent-hint
                required
              />
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="payment.amount"
                label="Amount *"
                type="number"
                required
              />
            </v-col>
          </v-row>
        </v-container>
        <small>* indicates required field</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          color="blue darken-1"
          text
          @click="closeModal"
        >
          Close
        </v-btn>
        <v-btn
          color="blue darken-1"
          text
          @click="addPayment"
        >
          Save
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import Form from 'vform'
import DatePicker from '../../../components/DatePicker.vue'

export default {
  components: { DatePicker },
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data: function () {
    return {
      payment: new Form({
        userId: 0,
        saleId: 0,
        paymentTime: '',
        paymentMode: 'Cash',
        description: '',
        reference: '',
        amount: 0
      }),
      isOpen: this.value
    }
  },
  watch: {
    value (newVal, oldVal) {
      if (newVal !== oldVal) {
        this.isOpen = newVal
      }
    }
  },
  methods: {
    closeModal () {
      this.payment.reset()
      this.payment.clear()
      this.isOpen = false
      this.$emit('input', this.isOpen)
    },
    selectUser: function (user) {
      this.payment.userId = user.id
    },
    addPayment: function () {
      this.payment
        .post('/api/payments')
        .then(response => {
          response = response.data
          this.$store.dispatch('snackbar/showMessage', response.message ?? 'Payment created')
          this.closeModal()
          this.$emit('reload', true)
        })
        .catch(error => {
          if (error.response && error.response.data) {
            this.$set(this.payment, 'errorMessage', error.response.data.message)
          }
          // Get a reference to the error component element
          const errorComponent = document.getElementById('error-component')

          if (errorComponent) {
            // Scroll to the error component
            errorComponent.scrollIntoView({ behavior: 'smooth', block: 'end', inline: 'nearest' }) // "smooth" for smooth scrolling
          }
        })
    }
  }
}
</script>
