const o=(r,t="USD",e=2)=>new Intl.NumberFormat("en-US",{style:"currency",currency:t,minimumFractionDigits:e}).format(r/100);export{o as m};
