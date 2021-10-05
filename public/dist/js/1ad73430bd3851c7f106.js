"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[875],{5714:(e,t,s)=>{s.d(t,{ZP:()=>C});var r=s(9669),o=s.n(r),a=Object.defineProperty,i=Object.prototype.hasOwnProperty,n=Object.getOwnPropertySymbols,l=Object.prototype.propertyIsEnumerable,c=(e,t,s)=>t in e?a(e,t,{enumerable:!0,configurable:!0,writable:!0,value:s}):e[t]=s,m=(e,t)=>{for(var s in t||(t={}))i.call(t,s)&&c(e,s,t[s]);if(n)for(var s of n(t))l.call(t,s)&&c(e,s,t[s]);return e};const d=e=>void 0===e,u=e=>Array.isArray(e),f=e=>e&&"number"==typeof e.size&&"string"==typeof e.type&&"function"==typeof e.slice,p=(e,t,s,r)=>((t=t||{}).indices=!d(t.indices)&&t.indices,t.nullsAsUndefineds=!d(t.nullsAsUndefineds)&&t.nullsAsUndefineds,t.booleansAsIntegers=!d(t.booleansAsIntegers)&&t.booleansAsIntegers,t.allowEmptyArrays=!d(t.allowEmptyArrays)&&t.allowEmptyArrays,s=s||new FormData,d(e)||(null===e?t.nullsAsUndefineds||s.append(r,""):(e=>"boolean"==typeof e)(e)?t.booleansAsIntegers?s.append(r,e?1:0):s.append(r,e):u(e)?e.length?e.forEach(((e,o)=>{const a=r+"["+(t.indices?o:"")+"]";p(e,t,s,a)})):t.allowEmptyArrays&&s.append(r+"[]",""):(e=>e instanceof Date)(e)?s.append(r,e.toISOString()):!(e=>e===Object(e))(e)||(e=>f(e)&&"string"==typeof e.name&&("object"==typeof e.lastModifiedDate||"number"==typeof e.lastModified))(e)||f(e)?s.append(r,e):Object.keys(e).forEach((o=>{const a=e[o];if(u(a))for(;o.length>2&&o.lastIndexOf("[]")===o.length-2;)o=o.substring(0,o.length-2);p(a,t,s,r?r+"["+o+"]":o)}))),s);var h={serialize:p};function v(e){if(null===e||"object"!=typeof e)return e;const t=Array.isArray(e)?[]:{};return Object.keys(e).forEach((s=>{t[s]=v(e[s])})),t}function y(e){return Array.isArray(e)?e:[e]}function g(e){return e instanceof File||e instanceof Blob||e instanceof FileList||"object"==typeof e&&null!==e&&void 0!==Object.values(e).find((e=>g(e)))}class b{constructor(){this.errors={},this.errors={}}set(e,t){"object"==typeof e?this.errors=e:this.set(m(m({},this.errors),{[e]:y(t)}))}all(){return this.errors}has(e){return Object.prototype.hasOwnProperty.call(this.errors,e)}hasAny(...e){return e.some((e=>this.has(e)))}any(){return Object.keys(this.errors).length>0}get(e){if(this.has(e))return this.getAll(e)[0]}getAll(e){return y(this.errors[e]||[])}only(...e){const t=[];return e.forEach((e=>{const s=this.get(e);s&&t.push(s)})),t}flatten(){return Object.values(this.errors).reduce(((e,t)=>e.concat(t)),[])}clear(e){const t={};e&&Object.keys(this.errors).forEach((s=>{s!==e&&(t[s]=this.errors[s])})),this.set(t)}}class _{constructor(e={}){this.originalData={},this.busy=!1,this.successful=!1,this.recentlySuccessful=!1,this.recentlySuccessfulTimeoutId=void 0,this.errors=new b,this.progress=void 0,this.update(e)}static make(e){return new this(e)}update(e){this.originalData=Object.assign({},this.originalData,v(e)),Object.assign(this,e)}fill(e={}){this.keys().forEach((t=>{this[t]=e[t]}))}data(){return this.keys().reduce(((e,t)=>m(m({},e),{[t]:this[t]})),{})}keys(){return Object.keys(this).filter((e=>!_.ignore.includes(e)))}startProcessing(){this.errors.clear(),this.busy=!0,this.successful=!1,this.progress=void 0,this.recentlySuccessful=!1,clearTimeout(this.recentlySuccessfulTimeoutId)}finishProcessing(){this.busy=!1,this.successful=!0,this.progress=void 0,this.recentlySuccessful=!0,this.recentlySuccessfulTimeoutId=setTimeout((()=>{this.recentlySuccessful=!1}),_.recentlySuccessfulTimeout)}clear(){this.errors.clear(),this.successful=!1,this.recentlySuccessful=!1,this.progress=void 0,clearTimeout(this.recentlySuccessfulTimeoutId)}reset(){Object.keys(this).filter((e=>!_.ignore.includes(e))).forEach((e=>{this[e]=v(this.originalData[e])}))}get(e,t={}){return this.submit("get",e,t)}post(e,t={}){return this.submit("post",e,t)}patch(e,t={}){return this.submit("patch",e,t)}put(e,t={}){return this.submit("put",e,t)}delete(e,t={}){return this.submit("delete",e,t)}submit(e,t,s={}){return this.startProcessing(),s=m({data:{},params:{},url:this.route(t),method:e,onUploadProgress:this.handleUploadProgress.bind(this)},s),"get"===e.toLowerCase()?s.params=m(m({},this.data()),s.params):(s.data=m(m({},this.data()),s.data),g(s.data)&&(s.transformRequest=[e=>h.serialize(e)])),new Promise(((e,t)=>{(_.axios||o()).request(s).then((t=>{this.finishProcessing(),e(t)})).catch((e=>{this.handleErrors(e),t(e)}))}))}handleErrors(e){this.busy=!1,this.progress=void 0,e.response&&this.errors.set(this.extractErrors(e.response))}extractErrors(e){return e.data&&"object"==typeof e.data?e.data.errors?m({},e.data.errors):e.data.message?{error:e.data.message}:m({},e.data):{error:_.errorMessage}}handleUploadProgress(e){this.progress={total:e.total,loaded:e.loaded,percentage:Math.round(100*e.loaded/e.total)}}route(e,t={}){let s=e;return Object.prototype.hasOwnProperty.call(_.routes,e)&&(s=decodeURI(_.routes[e])),"object"!=typeof t&&(t={id:t}),Object.keys(t).forEach((e=>{s=s.replace(`{${e}}`,t[e])})),s}onKeydown(e){const t=e.target;t.name&&this.errors.clear(t.name)}}_.routes={},_.errorMessage="Something went wrong. Please try again.",_.recentlySuccessfulTimeout=2e3,_.ignore=["busy","successful","errors","progress","originalData","recentlySuccessful","recentlySuccessfulTimeoutId"];const C=_},8875:(e,t,s)=>{s.r(t),s.d(t,{default:()=>n});var r=s(5714),o=s(9669),a=s.n(o);const i={middleware:"auth",props:["employeeId"],data:function(){return{loading:!1,form:new r.ZP({name:"",email:"",phone:"",gender:"male",city:"",address:"",province:"",comments:""})}},mounted:function(){this.employeeId&&this.loadData()},methods:{loadData:function(){var e=this;this.loading=!0,a().get("/api/employees/"+this.employeeId).then((function(t){var s=t.data.data,o=s.name,a=s.email,i=s.phone,n=(s.gender,s.city),l=s.address,c=s.province,m=s.comments;e.form=new r.ZP({name:o,email:a,phone:i,gender:"male",city:n,address:l,province:c,comments:m}),e.loading=!1})).catch((function(t){console.log(t),e.loading=!1}))},submitForm:function(){var e=this;this.customerId?this.form.put("/api/employees/"+this.employeeId).then((function(t){e.$router.push({name:"employees"})})).catch((function(e){console.log(e)})):this.form.post("/api/employees").then((function(t){e.$router.push({name:"employees"})})).catch((function(e){console.log(e)}))}}};const n=(0,s(1900).Z)(i,(function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",{staticClass:"row my-4"},[s("div",{staticClass:"row"},[s("form",{on:{submit:function(t){return t.preventDefault(),e.submitForm.apply(null,arguments)},keydown:function(t){return e.form.onKeyDown(t)}}},[s("div",{staticClass:"card"},[s("div",{staticClass:"card-header"},[e._v("\n          "+e._s(e.customerId?"Update Employee":"Add Employee")+"\n        ")]),e._v(" "),e.loading?e._e():s("div",{staticClass:"card-body row g-3"},[s("AlertError",{attrs:{form:e.form}},[e._v("\n            There were some problems with your input.\n          ")]),e._v(" "),s("div",{staticClass:"col-md-4"},[s("label",{staticClass:"form-label",attrs:{for:"name"}},[e._v("Employee Name")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.name,expression:"form.name"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("name")},attrs:{id:"name",type:"text"},domProps:{value:e.form.name},on:{input:function(t){t.target.composing||e.$set(e.form,"name",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"name"}})],1),e._v(" "),s("div",{staticClass:"col-md-4"},[s("label",{staticClass:"form-label",attrs:{for:"email"}},[e._v("Email")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.email,expression:"form.email"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("email")},attrs:{id:"email",type:"text"},domProps:{value:e.form.email},on:{input:function(t){t.target.composing||e.$set(e.form,"email",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"email"}})],1),e._v(" "),s("div",{staticClass:"col-md-4"},[s("label",{staticClass:"form-label",attrs:{for:"phone"}},[e._v("phone")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.phone,expression:"form.phone"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("phone")},attrs:{id:"phone",type:"text"},domProps:{value:e.form.phone},on:{input:function(t){t.target.composing||e.$set(e.form,"phone",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"phone"}})],1),e._v(" "),s("div",{staticClass:"col-md-2"},[s("label",{staticClass:"form-label",attrs:{for:"city"}},[e._v("City")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.city,expression:"form.city"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("city")},attrs:{id:"city",type:"text"},domProps:{value:e.form.city},on:{input:function(t){t.target.composing||e.$set(e.form,"city",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"city"}})],1),e._v(" "),s("div",{staticClass:"col-md-2"},[s("label",{staticClass:"form-label",attrs:{for:"province"}},[e._v("Province")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.province,expression:"form.province"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("province")},attrs:{id:"province",type:"text"},domProps:{value:e.form.province},on:{input:function(t){t.target.composing||e.$set(e.form,"province",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"province"}})],1),e._v(" "),s("div",{staticClass:"col-md-8"},[s("label",{staticClass:"form-label",attrs:{for:"address"}},[e._v("Address")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.address,expression:"form.address"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("address")},attrs:{id:"address",type:"text"},domProps:{value:e.form.address},on:{input:function(t){t.target.composing||e.$set(e.form,"address",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"address"}})],1),e._v(" "),s("div",{staticClass:"mb-3"},[s("label",{staticClass:"form-label",attrs:{for:"comments"}},[e._v("Comments")]),e._v(" "),s("textarea",{directives:[{name:"model",rawName:"v-model",value:e.form.comments,expression:"form.comments"}],staticClass:"form-control",class:{"is-invalid":e.form.errors.has("comments")},attrs:{id:"comments",type:"text"},domProps:{value:e.form.comments},on:{input:function(t){t.target.composing||e.$set(e.form,"comments",t.target.value)}}}),e._v(" "),s("has-error",{attrs:{form:e.form,field:"comments"}})],1),e._v(" "),s("div",{staticClass:"mb-3"},[s("label",{staticClass:"form-label",attrs:{for:"supplier_gender"}},[e._v("Supplier Gender")]),s("br"),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.gender,expression:"form.gender"}],staticClass:"form-check-input",attrs:{id:"employee_gender_male",type:"radio",value:"male"},domProps:{checked:e._q(e.form.gender,"male")},on:{change:function(t){return e.$set(e.form,"gender","male")}}}),e._v(" "),s("label",{staticClass:"form-check-label",attrs:{for:"employee_gender_male"}},[e._v("Male")]),e._v(" "),s("input",{directives:[{name:"model",rawName:"v-model",value:e.form.gender,expression:"form.gender"}],staticClass:"form-check-input",attrs:{id:"employee_gender_female",type:"radio",value:"female"},domProps:{checked:e._q(e.form.gender,"female")},on:{change:function(t){return e.$set(e.form,"gender","female")}}}),e._v(" "),s("label",{staticClass:"form-check-label",attrs:{for:"employee_gender_female"}},[e._v("Female")]),e._v(" "),s("has-error",{attrs:{form:e.form,field:"item_type"}})],1)],1),e._v(" "),s("div",{staticClass:"card-footer"},[s("v-button",{attrs:{loading:e.form.busy,type:"success"},on:{click:e.submitForm}},[e._v("\n            "+e._s(e.customerId?"Update":"Submit")+"\n          ")]),e._v(" "),s("router-link",{staticClass:"btn btn-danger float-end",attrs:{to:{name:"employees"}}},[e._v("\n            Cancel\n          ")])],1)])])])])}),[],!1,null,null,null).exports}}]);