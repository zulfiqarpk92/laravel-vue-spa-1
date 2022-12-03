<template>
  <v-row>
    <v-col md="9">
      <v-card>
        <v-card-text class="d-flex justify-space-between">
          <div class="mb-6">
            <div class="mb-6">
              <div class="d-flex align-center mb-6">
                <h6 class="font-weight-semibold text-xl text-uppercase">
                  Invoice Heading
                </h6>
              </div>
              <p class="mb-0">
                Office 149, 450 South Brand Brooklyn
              </p>
              <p class="mb-0">
                San Diego County, CA 91905, USA
              </p>
              <p class="mb-0">
                +1 (123) 456 7891, +44 (876) 543 2198
              </p>
            </div>
          </div>
          <div class="mb-3">
            test
          </div>
        </v-card-text>
        <v-divider />
        <v-card-text>
          <div class="mb-4">
            <div class="mb-2 d-none d-md-flex">
              <v-row class="font-weight-medium px-5">
                <v-col md="6">
                  Description
                </v-col>
                <v-col md="2">
                  Price
                </v-col>
                <v-col md="2">
                  Qty
                </v-col>
                <v-col md="2">
                  Total
                </v-col>
              </v-row>
            </div>
            <v-card flat outlined class="d-flex flex-sm-row flex-column-reverse">
              <div class="pa-5 flex-grow-1">
                <v-row>
                  <v-col md="6">
                    item description
                  </v-col>
                  <v-col md="2">
                    Cost
                  </v-col>
                  <v-col md="2">
                    Hours
                  </v-col>
                  <v-col md="2">
                    Total
                  </v-col>
                </v-row>
              </div>
              <div class="d-flex flex-column item-actions pa-1 border-s">
                <v-btn icon text x-small>
                  <v-icon>mdi-close</v-icon>
                </v-btn>
              </div>
            </v-card>
          </div>
          <v-btn small color="primary">
            <v-icon small>
              mdi-plus
            </v-icon>
            Add Item
          </v-btn>
        </v-card-text>
      </v-card>
    </v-col>
    <div class="col-md-9 col-12">
      <div class="v-card v-sheet theme--light">
        <v-card-text class="py-9 px-8">
          <div
            class="
              d-flex
              justify-space-between
              flex-wrap flex-column flex-sm-row
            "
          >
            <div class="mb-6 mb-sm-0">
              <p class="font-weight-semibold">
                Invoice From:
              </p>
              <v-select
                v-model="select"
                theme--light
                outlined
                dense
                label="Select Customer"
                :items="items"
                :hint="`${select.state}, ${select.abbr}`"
                item-text="state"
                item-value="abbr"
              />
              <p class="mb-1" />
              <p class="mb-1" />
              <!---->
              <p class="mb-1" />
              <p class="mb-0" />
            </div>
            <div>
              <p class="font-weight-semibold payment-details-header">
                Bill To:
              </p>
              <table>
                <tr>
                  <td class="pe-6">
                    Total Due:
                  </td>
                  <td>$12,110.55</td>
                </tr>
                <tr>
                  <td class="pe-6">
                    Bank Name:
                  </td>
                  <td>American Bank</td>
                </tr>
                <tr>
                  <td class="pe-6">
                    Country:
                  </td>
                  <td>United States</td>
                </tr>
                <tr>
                  <td class="pe-6">
                    IBAN:
                  </td>
                  <td>ETD95476213874685</td>
                </tr>
                <tr>
                  <td class="pe-6">
                    SWIFT Code:
                  </td>
                  <td>BR91905</td>
                </tr>
              </table>
            </div>
          </div>
        </v-card-text>
        <hr
          role="separator"
          aria-orientation="horizontal"
          class="v-divider theme--light"
        >
        <div class="add-products-form pt-9 pb-10 px-8">
          <div class="single-product-form">
            <div class="add-products-header mb-2 d-none d-md-flex">
              <div class="px-5 flex-grow-1 font-weight-semibold">
                <div class="row">
                  <div class="col-md-6 col-12">
                    <span>Item</span>
                  </div>
                  <div class="col-md-2 col-12">
                    <span>Cost</span>
                  </div>
                  <div class="col-md-2 col-12">
                    <span>Hours</span>
                  </div>
                  <div class="col-md-2 col-12">
                    <span>Price</span>
                  </div>
                </div>
              </div>
              <div class="header-spacer" />
            </div>
            <div class="d-flex v-card v-sheet v-sheet--outlined theme--light">
              <div class="pa-5 flex-grow-1">
                <div class="row">
                  <div class="col-md-6 col-12">
                    <v-select label="Select Item" outlined dense />
                    <v-textarea
                      outlined
                      label="Description"
                      rows="3"
                    />
                  </div>
                  <div class="col-sm-4 col-md-2 col-12">
                    <v-text-field outlined label="Cost" dense />
                  </div>
                  <div class="col-sm-4 col-md-2 col-12">
                    <v-text-field outlined label="Hours" dense />
                  </div>
                  <div class="col-sm-4 col-md-2 col-12">
                    <p class="my-2">
                      <span class="d-inline d-md-none">Price: </span><span>$0</span>
                    </p>
                  </div>
                </div>
              </div>
              <div class="d-flex flex-column item-actions rounded-0 pa-1">
                <v-icon class="pa-3">
                  mdi-close
                </v-icon>
                <div class="spacer" />
                <v-icon class="pa-3">
                  mdi-cog-outline
                </v-icon>
              </div>
            </div>
          </div>
          <v-btn
            class="mt-4 primary--text"
            outlined
            theme--light
            default
            primary
          >
            <span> Add Item </span>
          </v-btn>
        </div>
        <hr
          role="separator"
          aria-orientation="horizontal"
          class="v-divider theme--light"
        >
        <v-card-text class="py-9 px-8">
          <div
            class="
              d-flex
              justify-space-between
              flex-wrap flex-column flex-sm-row
            "
          >
            <div class="mb-6 mb-sm-0">
              <div class="mb-4 d-flex align-center">
                <span class="font-weight-semibold me-2 mb-5">Salesperson:</span>
                <v-text-field outlined dense />
              </div>
              <v-text-field
                placeholder="Thanks for your business"
                outlined
                dense
              />
            </div>
            <div>
              <table class="w-full">
                <tr>
                  <td class="pe-16">
                    Subtotal:
                  </td>
                  <th class="text-right">
                    $1800
                  </th>
                </tr>
                <tr>
                  <td class="pe-16">
                    Discount:
                  </td>
                  <th class="text-right">
                    $28
                  </th>
                </tr>
                <tr>
                  <td class="pe-16">
                    Tax:
                  </td>
                  <th class="text-right">
                    21%
                  </th>
                </tr>
              </table>
              <hr
                role="separator"
                aria-orientation="horizontal"
                class="mt-4 mb-3 v-divider theme--light"
              >
              <table class="w-full">
                <tr>
                  <td class="pe-16">
                    Total:
                  </td>
                  <th class="text-right">
                    $1690
                  </th>
                </tr>
              </table>
            </div>
          </div>
        </v-card-text>
        <hr
          role="separator"
          aria-orientation="horizontal"
          class="v-divider theme--light"
        >
        <div class="v-card__text px-8 py-6">
          <div class="mb-0">
            <p class="font-weight-semibold mb-2">
              Note:
            </p>
            <v-textarea
              outlined
              rows="3"
              placeholder="It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!"
            />
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-12">
      <div class="mb-6 v-card v-sheet theme--light">
        <div class="v-card__text">
          <v-btn class="mb-3 primary" block>
            <span class="v-btn__content"><v-icon class="me-2 left theme--light">mdi-send</v-icon><span>Send Invoice</span></span>
          </v-btn>
          <v-btn class="mb-3" outlined block color="grey">
            Send Invoice
          </v-btn>
          <v-btn class="mb-3" outlined block color="grey">
            Save
          </v-btn>
        </div>
      </div>
      <v-select dense outlined placeholder="Accept Payment Via" />

      <div class="d-flex align-center justify-space-between">
        <span>Payment Terms</span>
        <v-switch dense small />
      </div>
      <div class="d-flex align-center justify-space-between">
        <span>Client Notes</span>
        <v-switch dense small />
      </div>
      <div class="d-flex align-center justify-space-between">
        <span>Payment Stub</span>
        <v-switch dense small />
      </div>
    </div>
  </v-row>
</template>

<script>
export default {
  data () {
    return {
      select: { state: 'Florida', abbr: 'FL' },
      items: [
        { state: 'Florida', abbr: 'FL' },
        { state: 'Georgia', abbr: 'GA' },
        { state: 'Nebraska', abbr: 'NE' },
        { state: 'California', abbr: 'CA' },
        { state: 'New York', abbr: 'NY' }
      ]
    }
  }
}
</script>

<style>
</style>
