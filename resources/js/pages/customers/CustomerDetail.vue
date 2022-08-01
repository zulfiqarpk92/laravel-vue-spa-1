<template>
  <v-row>
    <v-col lg="4" md="5">
      <v-row>
        <v-col cols="12">
          <v-card class="pt-10">
            <v-card-title class="flex-column justify-center">
              <v-avatar color="grey darken-1" size="120" rounded class="mb-4" />
              <span class="mb-2">{{ customerInfo.name }}</span>
              <v-chip>
                Admin
              </v-chip>
              <v-card-text>
                <h2 class="text-xl font-semibold mb-2">
                  Customer Details
                </h2>
                <v-divider />
                <v-list dense>
                  <v-list-item class="px-0 mb-n2">
                    <span class="font-weight-medium me-2">Name:</span>
                    <span class="text--secondary">{{ customerInfo.name }}</span>
                  </v-list-item>
                  <v-list-item class="px-0 mb-n2">
                    <span class="font-weight-medium me-2">Phone:</span>
                    <span class="text--secondary">{{ customerInfo.phone }}</span>
                  </v-list-item>
                  <v-list-item class="px-0 mb-n2">
                    <span class="font-weight-medium me-2">Email:</span>
                    <span class="text--secondary">{{ customerInfo.email }}</span>
                  </v-list-item>
                  <v-list-item class="px-0 mb-n2">
                    <span class="font-weight-medium me-2">Address</span>
                    <span class="text--secondary">{{ customerInfo.address }} {{ customerInfo.city }} {{ customerInfo.province }}</span>
                  </v-list-item>
                  <v-list-item class="px-0 mb-n2">
                    <span class="font-weight-medium me-2">Comments:</span>
                    <span class="text--secondary">{{ customerInfo.comments }}</span>
                  </v-list-item>
                </v-list>
              </v-card-text>
              <v-card-actions>
                <v-btn color="primary" class="me-3">
                  Edit
                </v-btn>
                <v-btn outlined color="red">
                  Suspended
                </v-btn>
              </v-card-actions>
            </v-card-title>
          </v-card>
        </v-col>
      </v-row>
    </v-col>
    <v-col lg="8" md="7">
      <v-tabs
        v-model="currentTab"
        centered
        background-color="transparent"
      >
        <v-tab>Quotations</v-tab>
        <v-tab>Invoices</v-tab>
        <v-tab>Payments</v-tab>
      </v-tabs>
      <v-tabs-items v-model="currentTab" class="mt-5 pa-1" style="background-color: transparent;">
        <v-tab-item>
          <v-card>
            <v-card-title>
              Quotations
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="desserts"
              item-key="name"
              class="elevation-1"
            />
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card>
            <v-card-title>
              Invoices
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="desserts"
              item-key="name"
              class="elevation-1"
            />
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card>
            <v-card-title>
              Payments
            </v-card-title>
            <v-data-table
              :headers="headers"
              :items="desserts"
              item-key="name"
              class="elevation-1"
            />
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-col>
  </v-row>
</template>

<script>
export default {
  middleware: 'auth',
  props: { customerId: Number },
  data () {
    return {
      currentTab: null,
      customerInfo: null,
      headers: [
        {
          text: 'Dessert (100g serving)',
          align: 'start',
          sortable: false,
          value: 'name'
        },
        { text: 'Calories', value: 'calories' },
        { text: 'Fat (g)', value: 'fat' },
        { text: 'Carbs (g)', value: 'carbs' },
        { text: 'Protein (g)', value: 'protein' },
        { text: 'Iron (%)', value: 'iron' }
      ],
      desserts: [
        {
          name: 'Frozen Yogurt',
          calories: 159,
          fat: 6.0,
          carbs: 24,
          protein: 4.0,
          iron: '1%'
        },
        {
          name: 'Gingerbread',
          calories: 356,
          fat: 16.0,
          carbs: 49,
          protein: 3.9,
          iron: '16%'
        },
        {
          name: 'KitKat',
          calories: 518,
          fat: 26.0,
          carbs: 65,
          protein: 7,
          iron: '6%'
        }
      ]
    }
  },
  mounted () {
    if (this.customerId) {
      this.loadData()
    }
  },
  methods: {
    loadData: function () {
      this.loading = true
      this.$http
        .get('/api/customers/' + this.customerId)
        .then(({ data }) => {
          this.customerInfo = data
          this.loading = false
        })
        .catch(error => {
          console.log(error)
          this.loading = false
        })
    }
  }
}
</script>
