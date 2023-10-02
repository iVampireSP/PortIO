import{r as l,o as t,c as o,a as e,t as v,j as y,v as w,F as b,e as C,b as p,g as f,l as B}from"./app-65af0faa.js";import{i as m}from"./http-74266e01.js";const G=e("div",null,[e("h3",null,"流量充值")],-1),N=e("h5",null,"您要充值多少元的流量？",-1),T=f(" 每 GB 价格: "),U=f(" 元。 "),D={class:"input-group mb-3"},F=e("div",{class:"input-group-append"},[e("span",{class:"input-group-text"},"GB")],-1),M={key:0},j=f("大约 "),E=["textContent"],L=f(" 元。"),P={key:0},R=e("h5",{class:"mt-3"},"您将要使用哪个平台充值？",-1),S=e("p",null,"如果您在选中的平台没有账号，我们将会帮您自动创建一个。",-1),q={class:"form-group form-check"},z=["id","value"],A=["for","textContent"],H={key:1},I=e("h5",{class:"mt-3"},"暂时没有可用的",-1),J=[I],K={key:2},O=e("h5",{class:"mt-3"},"让我们来选择支付方式。",-1),Q=e("p",null,"在支付后，您的流量大概需要数秒钟到账。",-1),W={class:"form-group form-check"},X=["id","value"],Y=["for","textContent"],Z={key:3},$=["disabled","textContent"],ee={key:0},te={key:1,class:"mt-3"},oe=e("h5",null,"完成",-1),ne=e("p",null,"如果您浏览器没有打开新的创建，请点击以下链接来打开。",-1),se=["href"],ie={name:"Charge",setup(le){const g=l(0),u=l([]),i=l(""),r=l({}),d=l(""),c=l(10),h=l(""),_=l(!1);m.get("price").then(s=>{g.value=s.data.price_per_gb}),m.get("providers").then(s=>{u.value=s.data,u.value.length>0&&(i.value=u.value[0],x())});function x(){m.get("providers/"+i.value+"/payments").then(s=>{r.value=s.data,r.value.length>0&&(d.value=r.value[0].name)})}function V(){_.value=!0,m.post("providers/"+i.value+"/charge",{payment:d.value,traffic:c.value}).then(s=>{h.value=s.data.redirect_url,setTimeout(()=>{window.open(h.value,"_blank")})}).finally(()=>{_.value=!1})}return(s,a)=>(t(),o(b,null,[G,e("div",null,[N,e("p",null,[T,e("span",null,v(g.value),1),U]),e("div",D,[y(e("input",{"onUpdate:modelValue":a[0]||(a[0]=n=>c.value=n),autofocus:"",class:"form-control",placeholder:"输入您要的流量 (单位: GB)",type:"number"},null,512),[[w,c.value]]),F]),c.value?(t(),o("div",M,[e("p",null,[j,e("span",{textContent:v(c.value*g.value)},null,8,E),L]),u.value?(t(),o("div",P,[R,S,(t(!0),o(b,null,C(u.value,n=>(t(),o("div",q,[y(e("input",{id:"providers_"+n,"onUpdate:modelValue":a[1]||(a[1]=k=>i.value=k),value:n,class:"form-check-input",name:"provider",type:"radio",onChange:x},null,40,z),[[B,i.value,void 0,{value:!0}]]),e("label",{for:"providers_"+n,class:"form-check-label",textContent:v(n)},null,8,A)]))),256))])):(t(),o("div",H,J)),r.value?(t(),o("div",K,[O,Q,(t(!0),o(b,null,C(r.value,n=>(t(),o("div",W,[y(e("input",{id:"payments_"+n.name,"onUpdate:modelValue":a[2]||(a[2]=k=>d.value=k),value:n.name,class:"form-check-input",name:"payment",type:"radio"},null,8,X),[[B,d.value]]),e("label",{for:"payments_"+n.name,class:"form-check-label",textContent:v(n.title)},null,8,Y)]))),256))])):p("",!0),d.value?(t(),o("div",Z,[e("button",{disabled:_.value,class:"btn btn-primary mt-3",onClick:V,textContent:v(_.value?"请稍后":"立即支付")},null,8,$)])):p("",!0)])):p("",!0)]),_.value?(t(),o("p",ee,"正在创建订单...")):p("",!0),h.value?(t(),o("div",te,[oe,ne,e("a",{href:h.value,class:"link",target:"_blank"},"支付",8,se)])):p("",!0)],64))}};export{ie as default};
