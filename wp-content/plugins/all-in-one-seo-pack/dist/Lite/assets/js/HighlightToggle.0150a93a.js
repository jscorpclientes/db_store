import{B as y}from"./Checkbox.ec923721.js";import{S as p}from"./Checkmark.edd0f833.js";import{r as h,o as i,c,e as r,a as s,n as u,b as g,f as b,J as d,i as k,w as C,D as v}from"./vue.runtime.esm-bundler.0bc3eabf.js";import{_ as m}from"./_plugin-vue_export-helper.8823f7c1.js";const B={components:{SvgCheckmark:p},props:{modelValue:[String,Boolean],name:String,labelClass:{type:String,default(){return""}},inputClass:{type:String,default(){return""}},id:String,size:String,disabled:Boolean,type:{type:Number,default(){return 1}}},computed:{typeClass(){return`type-${this.type}`}},methods:{labelToggle(){this.$refs.input.click()}}},S=["onClick"],V={class:"form-radio-wrapper"},z={class:"form-radio"},x=["checked","disabled","name","id"],T={class:"fancy-radio"};function w(a,t,e,_,f,n){const o=h("svg-checkmark");return i(),c("label",{class:u(["aioseo-radio",[e.labelClass,{[e.size]:e.size},n.typeClass,{disabled:e.disabled}]]),onKeydown:[t[1]||(t[1]=d((...l)=>n.labelToggle&&n.labelToggle(...l),["enter"])),t[2]||(t[2]=d((...l)=>n.labelToggle&&n.labelToggle(...l),["space"]))],onClick:k(()=>{},["stop"])},[r(a.$slots,"header"),s("span",V,[s("span",z,[s("input",{type:"radio",onInput:t[0]||(t[0]=l=>a.$emit("update:modelValue",l.target.checked)),checked:e.modelValue,disabled:e.disabled,name:e.name,id:e.id,class:u(e.inputClass),ref:"input"},null,42,x),s("span",T,[e.type===1?(i(),g(o,{key:0})):b("",!0)])])]),r(a.$slots,"default")],42,S)}const q=m(B,[["render",w]]);const A={components:{BaseCheckbox:y,BaseRadio:q},props:{type:{type:String,required:!0},name:{type:String,required:!0},modelValue:{type:[Boolean,String,Event],required:!0},active:Boolean,size:String,round:Boolean},methods:{toggleCheckbox(){this.$refs.toggle.labelToggle()}}};function N(a,t,e,_,f,n){return i(),c("div",{class:u(["aioseo-highlight-toggle",[{active:e.active},{[e.size]:e.size}]]),onClick:t[1]||(t[1]=(...o)=>n.toggleCheckbox&&n.toggleCheckbox(...o))},[(i(),g(v(`base-${e.type}`),{ref:"toggle",name:e.name,modelValue:e.modelValue,size:e.size,round:e.round,"onUpdate:modelValue":t[0]||(t[0]=o=>a.$emit("update:modelValue",o))},{default:C(()=>[r(a.$slots,"default")]),_:3},8,["name","modelValue","size","round"]))],2)}const K=m(A,[["render",N]]);export{K as B};
