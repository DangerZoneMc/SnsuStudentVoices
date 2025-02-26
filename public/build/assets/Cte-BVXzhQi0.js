import{T as _,j as b,f as d,o,a as l,u as r,Z as w,w as m,b as a,d as y,k as u,v as c,e as k,F as p,l as C,c as B}from"./app-CT0Cshpd.js";import{_ as U}from"./Chirp-rybbmQ_Y.js";import{_ as f}from"./InputError-BsM-8fO8.js";import{P as V}from"./PrimaryButton-B9CIeimA.js";import{A as F}from"./AuthenticatedLayout-TamU-ss4.js";import"./_plugin-vue_export-helper-DlAUqK2U.js";import"./Modal-CMFGT4bC.js";const T={class:"max-w-2xl mx-auto p-4 sm:p-6 lg:p-8"},A={class:"mt-2"},L={class:"mt-6 bg-white shadow-sm rounded-lg divide-y"},$={__name:"Cte",props:["chirps"],setup(g){const e=_({message:"",media:null,department:"cte"}),i=b(null),h=n=>{const s=n.target.files[0];if(s){e.media=s;const t=new FileReader;t.onload=x=>{i.value=x.target.result},t.readAsDataURL(s)}},v=()=>{e.post(route("chirps.store"),{preserveScroll:!0,onSuccess:()=>{e.reset(),e.department="cte",i.value=null}})};return(n,s)=>(o(),d(p,null,[l(r(w),{title:"CTE Chirps"}),l(F,null,{default:m(()=>[a("div",T,[a("form",{onSubmit:y(v,["prevent"]),class:"space-y-4"},[u(a("input",{type:"hidden","onUpdate:modelValue":s[0]||(s[0]=t=>r(e).department=t)},null,512),[[c,r(e).department]]),u(a("textarea",{"onUpdate:modelValue":s[1]||(s[1]=t=>r(e).message=t),placeholder:"What's on your mind?",class:"block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"},null,512),[[c,r(e).message]]),l(f,{message:r(e).errors.message,class:"mt-2"},null,8,["message"]),a("div",A,[a("input",{type:"file",onChange:h,accept:"image/*,video/*",class:"block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"},null,32)]),l(f,{message:r(e).errors.media,class:"mt-2"},null,8,["message"]),l(V,{class:"mt-4"},{default:m(()=>s[2]||(s[2]=[k("Chirp")])),_:1})],32),a("div",L,[(o(!0),d(p,null,C(g.chirps,t=>(o(),B(U,{key:t.id,chirp:t},null,8,["chirp"]))),128))])])]),_:1})],64))}};export{$ as default};
