import{r as a,X as d,b as _,j as e,a3 as g,B as f}from"./app-Cz2I6k8K.js";import{u as h}from"./use-disclosure-BSMPrSES.js";import{A as y,I as j}from"./IconInfoCircle-gok3CJJG.js";import{I as x}from"./IconCircleX-BMdU3LxE.js";import{c as l}from"./createReactComponent-Ba3tZY_s.js";/**
 * @license @tabler/icons-react v3.36.1 - MIT
 *
 * This source code is licensed under the MIT license.
 * See the LICENSE file in the root directory of this source tree.
 */const C=[["path",{d:"M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0",key:"svg-0"}],["path",{d:"M12 8v4",key:"svg-1"}],["path",{d:"M12 16h.01",key:"svg-2"}]],k=l("outline","alert-circle","AlertCircle",C);/**
 * @license @tabler/icons-react v3.36.1 - MIT
 *
 * This source code is licensed under the MIT license.
 * See the LICENSE file in the root directory of this source tree.
 */const b=[["path",{d:"M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0",key:"svg-0"}],["path",{d:"M9 12l2 2l4 -4",key:"svg-1"}]],I=l("outline","circle-check","CircleCheck",b),v="_container_nj83a_1",w="_icon_nj83a_19",M="_title_nj83a_33",N="_label_nj83a_41",A="_message_nj83a_51",o={container:v,icon:w,title:M,label:N,message:A},n={style:{width:a(50),height:a(50)},stroke:2},i={info:{color:"blue",timeout:8e3,icon:e.jsx(j,{...n})},success:{color:"green",timeout:4e3,icon:e.jsx(I,{...n})},warning:{color:"yellow",timeout:1e4,icon:e.jsx(k,{...n})},error:{color:"red",timeout:1e4,icon:e.jsx(x,{...n})}};function O(){const[m,{open:u,close:c}]=h(!1),{flash:t}=d().props;_.useEffect(()=>{var r;u();const s=setTimeout(()=>c(),(r=i[t==null?void 0:t.type])==null?void 0:r.timeout);return()=>clearTimeout(s)},[t]);const p={in:{opacity:1,transform:"translate(-50%, 0)"},out:{opacity:0,transform:"translate(-50%, -100%)"},common:{transformOrigin:"top"},transitionProperty:"transform, opacity"};return e.jsx(g,{mounted:m,transition:p,duration:300,exitDuration:600,timingFunction:"easeOut",children:s=>e.jsx(f,{mb:"lg",style:s,className:o.container,children:t&&e.jsx(y,{variant:"filled",color:i[t.type].color,title:t.title,icon:i[t.type].icon,classNames:{icon:o.icon,title:o.title,label:o.label,message:o.message},radius:"md",withCloseButton:!0,onClose:c,children:t.message})})})}export{O as F};
