import{r as a,X as d,b as _,j as e,a3 as g,B as f}from"./app-CtPM_jwq.js";import{u as h}from"./use-disclosure-CtTE-zc1.js";import{A as y,I as x}from"./IconInfoCircle-Bf8B7eZu.js";import{I as C}from"./IconCircleX-DtJ3Hs04.js";import{c as l}from"./createReactComponent-K8Xi1dg5.js";/**
 * @license @tabler/icons-react v3.36.1 - MIT
 *
 * This source code is licensed under the MIT license.
 * See the LICENSE file in the root directory of this source tree.
 */const j=[["path",{d:"M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0",key:"svg-0"}],["path",{d:"M12 8v4",key:"svg-1"}],["path",{d:"M12 16h.01",key:"svg-2"}]],k=l("outline","alert-circle","AlertCircle",j);/**
 * @license @tabler/icons-react v3.36.1 - MIT
 *
 * This source code is licensed under the MIT license.
 * See the LICENSE file in the root directory of this source tree.
 */const b=[["path",{d:"M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0",key:"svg-0"}],["path",{d:"M9 12l2 2l4 -4",key:"svg-1"}]],I=l("outline","circle-check","CircleCheck",b),v="_container_1uds7_1",w="_icon_1uds7_10",M="_title_1uds7_17",N="_label_1uds7_21",A="_message_1uds7_26",o={container:v,icon:w,title:M,label:N,message:A},s={style:{width:a(50),height:a(50)},stroke:2},i={info:{color:"blue",timeout:8e3,icon:e.jsx(x,{...s})},success:{color:"green",timeout:4e3,icon:e.jsx(I,{...s})},warning:{color:"yellow",timeout:1e4,icon:e.jsx(k,{...s})},error:{color:"red",timeout:1e4,icon:e.jsx(C,{...s})}};function O(){const[m,{open:u,close:c}]=h(!1),{flash:t}=d().props;_.useEffect(()=>{var r;u();const n=setTimeout(()=>c(),(r=i[t==null?void 0:t.type])==null?void 0:r.timeout);return()=>clearTimeout(n)},[t]);const p={in:{opacity:1,transform:"translate(-50%, 0)"},out:{opacity:0,transform:"translate(-50%, -100%)"},common:{transformOrigin:"top"},transitionProperty:"transform, opacity"};return e.jsx(g,{mounted:m,transition:p,duration:300,exitDuration:600,timingFunction:"easeOut",children:n=>e.jsx(f,{mb:"lg",style:n,className:o.container,children:t&&e.jsx(y,{variant:"filled",color:i[t.type].color,title:t.title,icon:i[t.type].icon,classNames:{icon:o.icon,title:o.title,label:o.label,message:o.message},radius:"md",withCloseButton:!0,onClose:c,children:t.message})})})}export{O as F};
