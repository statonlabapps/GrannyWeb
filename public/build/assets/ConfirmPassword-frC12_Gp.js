import{T as n,j as l,w as t,f as d,d as r,u as a,Z as p,g as e,h as f,n as u,p as c}from"./app-BxcjcWUP.js";import{_ as w}from"./GuestLayout-DzzjlUQm.js";import{_}from"./InputError-DRDuf6Qb.js";import{_ as g,a as x}from"./TextInput-DuGee2WU.js";import{P as b}from"./PrimaryButton-D02xCw2V.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";const y={class:"mt-4 flex justify-end"},T={__name:"ConfirmPassword",setup(P){const s=n({password:""}),i=()=>{s.post(route("password.confirm"),{onFinish:()=>s.reset()})};return(V,o)=>(d(),l(w,null,{default:t(()=>[r(a(p),{title:"Confirm Password"}),o[2]||(o[2]=e("div",{class:"mb-4 text-sm text-gray-600 dark:text-gray-400"}," This is a secure area of the application. Please confirm your password before continuing. ",-1)),e("form",{onSubmit:c(i,["prevent"])},[e("div",null,[r(g,{for:"password",value:"Password"}),r(x,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:a(s).password,"onUpdate:modelValue":o[0]||(o[0]=m=>a(s).password=m),required:"",autocomplete:"current-password",autofocus:""},null,8,["modelValue"]),r(_,{class:"mt-2",message:a(s).errors.password},null,8,["message"])]),e("div",y,[r(b,{class:u(["ms-4",{"opacity-25":a(s).processing}]),disabled:a(s).processing},{default:t(()=>o[1]||(o[1]=[f(" Confirm ")])),_:1},8,["class","disabled"])])],32)]),_:1}))}};export{T as default};
