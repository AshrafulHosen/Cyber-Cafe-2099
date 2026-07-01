

/* ── PRELOADER ── */
const preloaderEl = document.getElementById('preloader');
if (preloaderEl) {
  const preTexts = ['INITIALIZING NEURAL LINK...','LOADING TOKYO NODE...','CONNECTING TO SECTOR 7...','BREWING SYNTHWAVE...','CAFÉ IS READY'];
  let pi=0;
  const preInterval = setInterval(()=>{
    pi++; if(pi>=preTexts.length){ clearInterval(preInterval); return; }
    const el = document.getElementById('pre-text');
    if (el) el.textContent=preTexts[pi];
  },440);
  setTimeout(()=>{ 
    preloaderEl.classList.add('done'); 
  }, 2400);
}
/* ── PARTICLES ── */
const pf = document.getElementById('particle-field');
if (pf) {
for(let i=0;i<50;i++){
  const p=document.createElement('div');
  p.className='fp';
  const size=1+Math.random()*3;
  const colors=['var(--cyan)','var(--purple)','var(--pink)','rgba(26,106,255,0.8)'];
  p.style.cssText=`
    width:${size}px;height:${size}px;
    left:${Math.random()*100}%;
    background:${colors[Math.floor(Math.random()*colors.length)]};
    box-shadow:0 0 ${size*4}px currentColor;
    animation-duration:${8+Math.random()*20}s;
    animation-delay:${Math.random()*12}s;
    --drift:${(Math.random()-0.5)*120}px;
  `;
  pf.appendChild(p);
}

/* ── CITY CANVAS ── */
}
const cityC = document.getElementById('city-canvas');
if (cityC) {
const cCtx = cityC.getContext('2d');
function resizeCity(){ cityC.width=window.innerWidth; cityC.height=window.innerHeight; drawCity(); }

function drawCity(){
  const W=cityC.width, H=cityC.height;
  // Sky gradient
  const sky=cCtx.createLinearGradient(0,0,0,H);
  sky.addColorStop(0,'#010308'); sky.addColorStop(0.5,'#040a14'); sky.addColorStop(1,'#080d1a');
  cCtx.fillStyle=sky; cCtx.fillRect(0,0,W,H);

  // Far buildings
  const seed = 42;
  const rng=(n)=>((Math.sin(n*seed)*10000)%1+1)%1;
  let x=0, layer=0;
  for(layer=0;layer<3;layer++){
    const alpha=[0.25,0.45,0.7][layer];
    const heights=[0.35,0.45,0.55][layer];
    const widths=[60,45,35][layer];
    x=0; let bi=layer*100;
    while(x<W+widths){
      const bw=widths*(0.5+rng(bi)*1.5);
      const bh=H*heights*(0.3+rng(bi+1)*0.7);
      const by=H-bh;
      cCtx.fillStyle=`rgba(${8+layer*6},${12+layer*8},${22+layer*12},${alpha})`;
      cCtx.fillRect(x,by,bw,bh);
      // Windows
      const wrows=Math.floor(bh/18), wcols=Math.floor(bw/12);
      for(let r=0;r<wrows;r++){
        for(let c=0;c<wcols;c++){
          if(rng(bi*10+r*50+c)<0.3){
            const wx=x+c*12+3, wy=by+r*18+4;
            const wc=rng(bi*20+r+c);
            const wcolor = wc<0.4?`rgba(0,245,255,${0.3+rng(bi+r+c)*0.5})`: wc<0.7?`rgba(180,79,255,${0.2+rng(bi+r+c)*0.4})`:`rgba(255,200,100,${0.3+rng(bi+r+c)*0.4})`;
            cCtx.fillStyle=wcolor;
            cCtx.fillRect(wx,wy,7,9);
          }
        }
      }
      x+=bw+2; bi++;
    }
  }

  // Neon sign reflections on wet ground
  const reflGrad=cCtx.createLinearGradient(0,H*0.82,0,H);
  reflGrad.addColorStop(0,'rgba(0,245,255,0.06)'); reflGrad.addColorStop(0.5,'rgba(180,79,255,0.04)'); reflGrad.addColorStop(1,'rgba(5,8,16,0.9)');
  cCtx.fillStyle=reflGrad; cCtx.fillRect(0,H*0.82,W,H*0.18);
}
window.addEventListener('resize',resizeCity); resizeCity();

/* ── RAIN CANVAS ── */
}
const rainC = document.getElementById('rain-canvas');
if (rainC) {
const rCtx=rainC.getContext('2d');
const drops=[];
function initRain(){
  rainC.width=window.innerWidth; rainC.height=window.innerHeight;
  drops.length=0;
  for(let i=0;i<200;i++){
    drops.push({x:Math.random()*rainC.width,y:Math.random()*rainC.height,l:8+Math.random()*24,s:4+Math.random()*8,o:0.1+Math.random()*0.4});
  }
}
function animRain(){
  rCtx.clearRect(0,0,rainC.width,rainC.height);
  rCtx.strokeStyle='rgba(150,200,255,0.15)';
  drops.forEach(d=>{
    rCtx.beginPath();
    rCtx.moveTo(d.x,d.y);
    rCtx.lineTo(d.x-d.l*0.2,d.y+d.l);
    rCtx.lineWidth=0.5+Math.random()*0.5;
    rCtx.globalAlpha=d.o;
    rCtx.stroke();
    d.y+=d.s; d.x-=d.s*0.2;
    if(d.y>rainC.height||d.x<0){ d.y=Math.random()*-200; d.x=Math.random()*rainC.width; }
  });
  rCtx.globalAlpha=1;
  requestAnimationFrame(animRain);
}
window.addEventListener('resize',initRain); initRain(); animRain();

/* ── STATS COUNTER ── */
}
const statTargets = [2847,1293,48291,892341];
if (document.getElementById('st1')) {
const statIds=['st1','st2','st3','st4'];
statIds.forEach((id,i)=>{
  let v=0;
  const target=statTargets[i];
  const step=Math.ceil(target/80);
  const iv=setInterval(()=>{
    v=Math.min(v+step,target);
    document.getElementById(id).textContent=v.toLocaleString();
    if(v>=target) clearInterval(iv);
  },20);
});

/* ── AUDIO VISUALIZER ── */
}
const vizC = document.getElementById('viz-canvas');
if (vizC) {
const vCtx=vizC.getContext('2d');
let vizMode='lofi';
const modeColors={lofi:['#00f5ff','#1a6aff'],synthwave:['#b44fff','#ff2d78'],rain:['#4488ff','#00f5ff'],cafe:['#ff9940','#ff2d78'],drive:['#ff2d78','#b44fff']};

function resizeViz(){
  vizC.width=vizC.offsetWidth; vizC.height=vizC.offsetHeight||100;
}
window.addEventListener('resize',resizeViz); resizeViz();

const bars=64;
let phases=Array.from({length:bars},(_,i)=>Math.random()*Math.PI*2);
const freqProfiles={
  lofi: i=>0.2+0.6*(1/(1+Math.abs(i-bars*0.3)/8)),
  synthwave: i=>0.1+0.8*(Math.sin(i/bars*Math.PI)*0.6+0.3*(i<bars*0.5?1:0.5)),
  rain: i=>0.05+0.3*(1-i/bars),
  cafe: i=>0.15+0.5*Math.abs(Math.sin(i/bars*Math.PI*3)),
  drive: i=>0.3+0.6*(i/bars),
};
function animViz(){
  const W=vizC.width,H=vizC.height;
  vCtx.clearRect(0,0,W,H);
  const t=Date.now()/1000;
  const [c1,c2]=modeColors[vizMode];
  const bw=W/bars-1;
  for(let i=0;i<bars;i++){
    phases[i]+=0.02+0.01*(i%3);
    const base=freqProfiles[vizMode](i);
    const h=(base+0.15*Math.sin(phases[i]+t*1.5)+0.1*Math.sin(phases[i]*2.3+t))*H*0.85;
    const x=i*(bw+1);
    const grad=vCtx.createLinearGradient(0,H-h,0,H);
    grad.addColorStop(0,c1); grad.addColorStop(1,c2+'44');
    vCtx.fillStyle=grad;
    const rr=Math.min(bw/2,4);
    vCtx.beginPath();
    vCtx.roundRect(x,H-h,bw,h,rr);
    vCtx.fill();
    // glow
    vCtx.shadowColor=c1; vCtx.shadowBlur=8;
    vCtx.fillStyle=c1+'88';
    vCtx.beginPath(); vCtx.roundRect(x,H-h,bw,3,rr); vCtx.fill();
    vCtx.shadowBlur=0;
  }
  requestAnimationFrame(animViz);
}
animViz();

document.querySelectorAll('.mode-btn').forEach(btn=>{
  btn.addEventListener('click',()=>{
    document.querySelectorAll('.mode-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    vizMode=btn.dataset.mode;
  });
});

/* ── DEMO CHAT ── */
const aiResponses = {
  default: [
    "Interesting input. Let me process that through my neural matrix...",
    "The neon rain speaks to me tonight. Here's what I synthesized for you:",
    "In 2099, every question has a neon-colored answer. Here's yours:",
    "My circuits are buzzing. Allow me to illuminate your path:",
  ],
  music: "For your current frequency, I recommend <span style='color:var(--purple)'>Synthwave Drive</span> — 85 BPM, pure flow state fuel. Or try <span style='color:var(--cyan)'>Lo-Fi Rain Mix #7</span> for something more introspective.",
  coffee: "Based on your biometric signature, I'd recommend a <span style='color:var(--pink)'>Neon Espresso</span> — double shot, synthetic milk foam. Or a <span style='color:var(--cyan)'>Cyber Matcha</span> for steady focus without the spike.",
  motivat: "You didn't come this far to stop now. The city never sleeps — and neither does your potential. Every line of code, every page turned — it's all data in your neural upgrade.",
  quote: "<em>'In a world of infinite noise, the focused mind is the rarest hardware.'</em> — Unknown Architect, 2087",
  study: "Optimal study protocol: 25min deep focus → 5min micro-break. I'll sync the Pomodoro to your table light. Blue = focus. You're already hardwired for this.",
};

function getAIResponse(input){
  const l=input.toLowerCase();
  if(l.includes('music')||l.includes('song')||l.includes('playlist')) return aiResponses.music;
  if(l.includes('coffee')||l.includes('drink')||l.includes('brew')) return aiResponses.coffee;
  if(l.includes('motiv')||l.includes('tired')||l.includes('give up')) return aiResponses.motivat;
  if(l.includes('quote')||l.includes('cyberpunk')) return aiResponses.quote;
  if(l.includes('study')||l.includes('focus')||l.includes('pomodoro')) return aiResponses.study;
  return aiResponses.default[Math.floor(Math.random()*aiResponses.default.length)];
}

function addMsg(container, text, role){
  const d=document.createElement('div'); d.className=`msg ${role}`;
  const b=document.createElement('div'); b.className='msg-bubble'; b.innerHTML=text;
  d.appendChild(b); container.appendChild(d); container.scrollTop=container.scrollHeight;
}
function addTyping(container){
  const d=document.createElement('div'); d.className='msg ai'; d.id='typing-indicator';
  const b=document.createElement('div'); b.className='msg-bubble'; b.innerHTML='<span style="opacity:0.6;font-family:var(--font-mono);font-size:0.75rem">NEXUS-7 is processing</span> <span id="dots">...</span>';
  d.appendChild(b); container.appendChild(d); container.scrollTop=container.scrollHeight;
  return d;
}

}
const demoChat = document.getElementById('demo-chat');
if (demoChat) {
const demoInp=document.getElementById('demo-inp');
const demoSend=document.getElementById('demo-send');
function sendDemoMsg(){
  const v=demoInp.value.trim(); if(!v) return;
  addMsg(demoChat,v,'user'); demoInp.value='';
  const t=addTyping(demoChat);
  setTimeout(()=>{ t.remove(); addMsg(demoChat,getAIResponse(v),'ai'); },900+Math.random()*600);
}
demoSend.addEventListener('click',sendDemoMsg);
demoInp.addEventListener('keydown',e=>{ if(e.key==='Enter') sendDemoMsg(); });

}