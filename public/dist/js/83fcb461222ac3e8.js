"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[933],{7933:(t,s,a)=>{a.r(s),a.d(s,{default:()=>n});const e={middleware:"auth",props:{customerId:Number},data:function(){return{currentTab:null,customerInfo:null,headers:[{text:"Dessert (100g serving)",align:"start",sortable:!1,value:"name"},{text:"Calories",value:"calories"},{text:"Fat (g)",value:"fat"},{text:"Carbs (g)",value:"carbs"},{text:"Protein (g)",value:"protein"},{text:"Iron (%)",value:"iron"}],desserts:[{name:"Frozen Yogurt",calories:159,fat:6,carbs:24,protein:4,iron:"1%"},{name:"Gingerbread",calories:356,fat:16,carbs:49,protein:3.9,iron:"16%"},{name:"KitKat",calories:518,fat:26,carbs:65,protein:7,iron:"6%"}]}},mounted:function(){this.customerId&&this.loadData()},methods:{loadData:function(){var t=this;this.loading=!0,this.$http.get("/api/customers/"+this.customerId).then((function(s){var a=s.data;t.customerInfo=a,t.loading=!1})).catch((function(s){console.log(s),t.loading=!1}))}}};const n=(0,a(1900).Z)(e,(function(){var t=this,s=t.$createElement,a=t._self._c||s;return a("v-row",[a("v-col",{attrs:{lg:"4",md:"5"}},[a("v-row",[a("v-col",{attrs:{cols:"12"}},[a("v-card",{staticClass:"pt-10"},[a("v-card-title",{staticClass:"flex-column justify-center"},[a("v-avatar",{staticClass:"mb-4",attrs:{color:"grey darken-1",size:"120",rounded:""}}),t._v(" "),a("span",{staticClass:"mb-2"},[t._v(t._s(t.customerInfo.name))]),t._v(" "),a("v-chip",[t._v("\n              Admin\n            ")]),t._v(" "),a("v-card-text",[a("h2",{staticClass:"text-xl font-semibold mb-2"},[t._v("\n                Customer Details\n              ")]),t._v(" "),a("v-divider"),t._v(" "),a("v-list",{attrs:{dense:""}},[a("v-list-item",{staticClass:"px-0 mb-n2"},[a("span",{staticClass:"font-weight-medium me-2"},[t._v("Name:")]),t._v(" "),a("span",{staticClass:"text--secondary"},[t._v(t._s(t.customerInfo.name))])]),t._v(" "),a("v-list-item",{staticClass:"px-0 mb-n2"},[a("span",{staticClass:"font-weight-medium me-2"},[t._v("Phone:")]),t._v(" "),a("span",{staticClass:"text--secondary"},[t._v(t._s(t.customerInfo.phone))])]),t._v(" "),a("v-list-item",{staticClass:"px-0 mb-n2"},[a("span",{staticClass:"font-weight-medium me-2"},[t._v("Email:")]),t._v(" "),a("span",{staticClass:"text--secondary"},[t._v(t._s(t.customerInfo.email))])]),t._v(" "),a("v-list-item",{staticClass:"px-0 mb-n2"},[a("span",{staticClass:"font-weight-medium me-2"},[t._v("Address")]),t._v(" "),a("span",{staticClass:"text--secondary"},[t._v(t._s(t.customerInfo.address)+" "+t._s(t.customerInfo.city)+" "+t._s(t.customerInfo.province))])]),t._v(" "),a("v-list-item",{staticClass:"px-0 mb-n2"},[a("span",{staticClass:"font-weight-medium me-2"},[t._v("Comments:")]),t._v(" "),a("span",{staticClass:"text--secondary"},[t._v(t._s(t.customerInfo.comments))])])],1)],1),t._v(" "),a("v-card-actions",[a("v-btn",{staticClass:"me-3",attrs:{color:"primary"}},[t._v("\n                Edit\n              ")]),t._v(" "),a("v-btn",{attrs:{outlined:"",color:"red"}},[t._v("\n                Suspended\n              ")])],1)],1)],1)],1)],1)],1),t._v(" "),a("v-col",{attrs:{lg:"8",md:"7"}},[a("v-tabs",{attrs:{centered:"","background-color":"transparent"},model:{value:t.currentTab,callback:function(s){t.currentTab=s},expression:"currentTab"}},[a("v-tab",[t._v("Quotations")]),t._v(" "),a("v-tab",[t._v("Invoices")]),t._v(" "),a("v-tab",[t._v("Payments")])],1),t._v(" "),a("v-tabs-items",{staticClass:"mt-5 pa-1",staticStyle:{"background-color":"transparent"},model:{value:t.currentTab,callback:function(s){t.currentTab=s},expression:"currentTab"}},[a("v-tab-item",[a("v-card",[a("v-card-title",[t._v("\n            Quotations\n          ")]),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.desserts,"item-key":"name"}})],1)],1),t._v(" "),a("v-tab-item",[a("v-card",[a("v-card-title",[t._v("\n            Invoices\n          ")]),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.desserts,"item-key":"name"}})],1)],1),t._v(" "),a("v-tab-item",[a("v-card",[a("v-card-title",[t._v("\n            Payments\n          ")]),t._v(" "),a("v-data-table",{staticClass:"elevation-1",attrs:{headers:t.headers,items:t.desserts,"item-key":"name"}})],1)],1)],1)],1)],1)}),[],!1,null,null,null).exports}}]);