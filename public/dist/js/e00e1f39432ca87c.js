"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[217],{7217:(t,e,n)=>{n.r(e),n.d(e,{default:()=>l});var r=n(7757),a=n.n(r),s=n(9669),c=n.n(s);function i(t,e,n,r,a,s,c){try{var i=t[s](c),o=i.value}catch(t){return void n(t)}i.done?e(o):Promise.resolve(o).then(r,a)}var o=function(t){return Object.keys(t).map((function(e){return"".concat(e,"=").concat(t[e])})).join("&")};const u={beforeRouteEnter:function(t,e,n){return(r=a().mark((function e(){var r,s;return a().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.prev=0,e.next=3,c().post("/api/email/verify/".concat(t.params.id,"?").concat(o(t.query)));case 3:r=e.sent,s=r.data,n((function(t){t.success=s.status})),e.next=11;break;case 8:e.prev=8,e.t0=e.catch(0),n((function(t){t.error=e.t0.response.data.status}));case 11:case"end":return e.stop()}}),e,null,[[0,8]])})),function(){var t=this,e=arguments;return new Promise((function(n,a){var s=r.apply(t,e);function c(t){i(s,n,a,c,o,"next",t)}function o(t){i(s,n,a,c,o,"throw",t)}c(void 0)}))})();var r},middleware:"guest",metaInfo:function(){return{title:this.$t("verify_email")}},data:function(){return{error:"",success:""}}};const l=(0,n(1900).Z)(u,(function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"row"},[n("div",{staticClass:"col-lg-7 m-auto"},[n("card",{attrs:{title:t.$t("verify_email")}},[t.success?[n("div",{staticClass:"alert alert-success",attrs:{role:"alert"}},[t._v("\n          "+t._s(t.success)+"\n        ")]),t._v(" "),n("router-link",{staticClass:"btn btn-primary",attrs:{to:{name:"login"}}},[t._v("\n          "+t._s(t.$t("login"))+"\n        ")])]:[n("div",{staticClass:"alert alert-danger",attrs:{role:"alert"}},[t._v("\n          "+t._s(t.error||t.$t("failed_to_verify_email"))+"\n        ")]),t._v(" "),n("router-link",{staticClass:"small float-end",attrs:{to:{name:"verification.resend"}}},[t._v("\n          "+t._s(t.$t("resend_verification_link"))+"\n        ")])]],2)],1)])}),[],!1,null,null,null).exports}}]);