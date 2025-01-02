import{_ as p}from"./InputError-DRDuf6Qb.js";import{_ as x}from"./_plugin-vue_export-helper-DlAUqK2U.js";import{r as u,T as y,b as c,j as V,w as i,f as w,g as n,d as t,u as r,h as b,c as k,k as g}from"./app-BxcjcWUP.js";const C={class:"d-flex align-center"},I={key:0,class:"font text-p text-success ml-2"},P={__name:"UpdatePasswordForm",setup(U){const d=u(null),f=u(null),o=u(!1),e=y({current_password:"",password:"",password_confirmation:""}),v=()=>{e.put(route("password.update"),{preserveScroll:!0,onSuccess:()=>e.reset(),onError:()=>{e.errors.password&&(e.reset("password","password_confirmation"),d.value.focus()),e.errors.current_password&&(e.reset("current_password"),f.value.focus())}})};return(N,s)=>{const l=c("v-text-field"),_=c("v-btn"),m=c("v-card");return w(),V(m,{class:"card"},{default:i(()=>[s[7]||(s[7]=n("div",{class:"header-w-sub"},[n("h2",{class:"text-h5 font text-white"},"Update Password"),n("p",{class:"text-subtitle-1 font text-secondary"}," Update password periodically to stay secure. ")],-1)),t(m,{class:"profile-inner-card"},{default:i(()=>[n("div",null,[t(p,{message:r(e).errors.current_password,class:"mb-2"},null,8,["message"]),t(l,{label:"Current Password",id:"current_password",ref_key:"currentPasswordInput",ref:f,"append-inner-icon":o.value?"mdi-eye-off":"mdi-eye",type:o.value?"text":"password","onClick:appendInner":s[0]||(s[0]=a=>o.value=!o.value),modelValue:r(e).current_password,"onUpdate:modelValue":s[1]||(s[1]=a=>r(e).current_password=a),class:"font text-accent focus:ring-0"},null,8,["append-inner-icon","type","modelValue"]),t(p,{message:r(e).errors.password,class:"mb-2"},null,8,["message"]),t(l,{label:"New Password",class:"font text-accent","append-inner-icon":o.value?"mdi-eye-off":"mdi-eye",type:o.value?"text":"password","onClick:appendInner":s[2]||(s[2]=a=>o.value=!o.value),id:"password",ref_key:"passwordInput",ref:d,modelValue:r(e).password,"onUpdate:modelValue":s[3]||(s[3]=a=>r(e).password=a)},null,8,["append-inner-icon","type","modelValue"]),t(p,{message:r(e).errors.password_confirmation,class:"mb-2"},null,8,["message"]),t(l,{label:"Confirm Password",class:"font text-accent","append-inner-icon":o.value?"mdi-eye-off":"mdi-eye",type:o.value?"text":"password","onClick:appendInner":s[4]||(s[4]=a=>o.value=!o.value),ref_key:"passwordInput",ref:d,id:"password_confirmation",modelValue:r(e).password_confirmation,"onUpdate:modelValue":s[5]||(s[5]=a=>r(e).password_confirmation=a)},null,8,["append-inner-icon","type","modelValue"])]),n("div",C,[t(_,{class:"font text-accent",color:"secondary",onClick:v},{default:i(()=>s[6]||(s[6]=[b("Save")])),_:1}),r(e).recentlySuccessful?(w(),k("div",I," Saved! ")):g("",!0)])]),_:1})]),_:1})}}},E=x(P,[["__scopeId","data-v-26645e65"]]);export{E as default};
