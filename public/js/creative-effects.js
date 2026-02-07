function initCreativeEffects(){var e=window.matchMedia("(prefers-reduced-motion: reduce)").matches,t=window.matchMedia("(max-width: 768px)").matches;initCoffeeCursor(),initCoffeeLoader(),initCoffeeScrollProgress(),initTimeBasedTheme(),e||t||(initSteamEffect(),initTypingAnimation(),initInteractiveParticles(),initAdvanced3DCards(),initMagneticButtonsAdvanced())}function initCoffeeCursor(){}function initCoffeeLoader(){}function initCoffeeScrollProgress(){}function initSteamEffect(){document.querySelectorAll(".hero-image img, .product-image").forEach((e,t)=>{var n=document.createElement("div");n.className="steam-container";for(let e=0;e<5;e++){var i=document.createElement("div");i.className="steam-particle",i.style.animationDelay=.3*e+" s",i.style.left=20+60*Math.random()+"% ",n.appendChild(i)}(e.classList.contains("product-image")?e:e.parentElement).appendChild(n)})}function initTypingAnimation(){let i=document.querySelector(".hero-title");if(i){var a=i.innerHTML.split(/<span>|<\/span>/);if(3<=a.length){let e=a[0],t=a[1],n=a[2]||"";i.innerHTML="",i.style.opacity="1",typeText(i,e,50,()=>{var e=document.createElement("span");i.appendChild(e),typeText(e,t,50,()=>{typeText(i,n,50)})})}}}function typeText(t,n,e,i){let a=0,o=setInterval(()=>{var e;"<"===n[a]?(e=n.indexOf(">",a),t.innerHTML+=n.substring(a,e+1),a=e+1):(t.innerHTML+=n[a],a++),a>=n.length&&(clearInterval(o),i)&&i()},e)}function initInteractiveParticles(){let r=document.querySelector(".hero-section");if(r){let i=document.createElement("canvas"),e=(i.className="particles-canvas",i.style.cssText=`
        position: absolute;
        inset: 0;
        z-index: 5;
        pointer-events: none;
    `,r.appendChild(i),i.getContext("2d")),t=[],a=0,o=0,n=null,s=!1;d(),window.addEventListener("resize",d);class c{constructor(){this.reset()}reset(){this.x=Math.random()*i.width,this.y=Math.random()*i.height,this.size=8*Math.random()+4,this.speedX=.5*(Math.random()-.5),this.speedY=.5*Math.random()+.2,this.rotation=Math.random()*Math.PI*2,this.rotationSpeed=.02*(Math.random()-.5),this.opacity=.5*Math.random()+.3}update(){this.x+=this.speedX,this.y+=this.speedY,this.rotation+=this.rotationSpeed;var e=a-this.x,t=o-this.y,n=Math.sqrt(e*e+t*t);n<100&&(this.x-=e*(e=(100-n)/100)*.02,this.y-=t*e*.02),this.y>i.height&&(this.reset(),this.y=-this.size)}draw(){e.save(),e.translate(this.x,this.y),e.rotate(this.rotation),e.globalAlpha=this.opacity,e.fillStyle="#C9A227",e.beginPath(),e.ellipse(0,0,this.size,.6*this.size,0,0,2*Math.PI),e.fill(),e.strokeStyle="#8B5A2B",e.lineWidth=1,e.beginPath(),e.moveTo(.7*-this.size,0),e.quadraticCurveTo(0,.3*-this.size,.7*this.size,0),e.stroke(),e.restore()}}for(let e=0;e<20;e++)t.push(new c);function d(){i.width=r.offsetWidth,i.height=r.offsetHeight}function l(){n=s?(e.clearRect(0,0,i.width,i.height),t.forEach(e=>{e.update(),e.draw()}),requestAnimationFrame(l)):null}r.addEventListener("mousemove",e=>{var t=r.getBoundingClientRect();a=e.clientX-t.left,o=e.clientY-t.top}),new IntersectionObserver(e=>{e.forEach(e=>{(s=e.isIntersecting)&&!n&&l()})},{threshold:.1}).observe(r)}}function initTimeBasedTheme(){var t=20<=(t=(new Date).getHours())||t<6;if(!localStorage.getItem("theme")){document.documentElement.setAttribute("data-theme",t?"dark":"light");let e=document.createElement("div");e.className="time-theme-indicator",e.innerHTML=t?'<i class="bi bi-moon-stars"></i> الوضع المسائي':'<i class="bi bi-sun"></i> الوضع النهاري',document.body.appendChild(e),setTimeout(()=>{e.classList.add("visible"),setTimeout(()=>{e.classList.remove("visible"),setTimeout(()=>e.remove(),500)},3e3)},2e3)}}function initAdvanced3DCards(){document.querySelectorAll(".product-card").forEach(i=>{let a=document.createElement("div");a.className="card-shine",i.appendChild(a),i.addEventListener("mousemove",e=>{var t=i.getBoundingClientRect(),n=(e.clientX-t.left)/t.width*100,e=(e.clientY-t.top)/t.height*100;a.style.background=`radial - gradient(circle at ${n} % ${e} %, rgba(255, 255, 255, 0.3) 0 %, transparent 50 %)`}),i.addEventListener("mouseleave",()=>{a.style.background="none"})})}function initMagneticButtonsAdvanced(){window.innerWidth<1024||document.querySelectorAll(".btn-golden, .btn-outline-golden, .hero-badge").forEach(e=>{var t=e.innerHTML;e.innerHTML=`<span class="btn-inner">${t}</span>`;let i=e.querySelector(".btn-inner");e.addEventListener("mousemove",function(e){var t=this.getBoundingClientRect(),n=e.clientX-t.left-t.width/2,e=e.clientY-t.top-t.height/2;this.style.transform=`translate(${.3*n}px, ${.3*e}px)`,i.style.transform=`translate(${.1*n}px, ${.1*e}px)`}),e.addEventListener("mouseleave",function(){this.style.transform="",i.style.transform=""})})}function initAnimatedGradientText(){var e=document.querySelector(".hero-title span");e&&e.classList.add("gradient-text-animated")}function initSmoothReveal(){let e=document.querySelectorAll(".glass-card, .category-card, .product-card, .section-title"),t=new IntersectionObserver(e=>{e.forEach(e=>{e.isIntersecting&&(e.target.classList.add("revealed"),e.target.style.transitionDelay=.3*Math.random()+" s")})},{threshold:.1,rootMargin:"50px"});e.forEach(e=>{e.classList.add("reveal-element"),t.observe(e)})}document.addEventListener("DOMContentLoaded",function(){initCreativeEffects()}),window.CreativeEffects={initCoffeeCursor:initCoffeeCursor,initCoffeeLoader:initCoffeeLoader,initSteamEffect:initSteamEffect,initTypingAnimation:initTypingAnimation,initInteractiveParticles:initInteractiveParticles},document.addEventListener("DOMContentLoaded",initAnimatedGradientText),document.addEventListener("DOMContentLoaded",initSmoothReveal);let SoundManager={enabled:!1,sounds:{},init(){},enable(){this.enabled=!0},disable(){this.enabled=!1},play(e){this.enabled}};function initEasterEgg(){let t=[38,38,40,40,37,39,37,39,66,65],n=0;document.addEventListener("keydown",e=>{e.keyCode===t[n]?++n===t.length&&(activateEasterEgg(),n=0):n=0})}function activateEasterEgg(){let t=document.createElement("div");t.className="coffee-rain",t.style.cssText=`
    position: fixed;
    inset: 0;
    z - index: 999999;
    pointer - events: none;
    overflow: hidden;
    `;for(let e=0;e<50;e++){var n=document.createElement("div");n.innerHTML="☕",n.style.cssText=`
    position: absolute;
    font - size: ${20+30*Math.random()} px;
    left: ${100*Math.random()}%;
    top: -50px;
    animation: rainFall ${2+3*Math.random()}s linear forwards;
    animation - delay: ${2*Math.random()} s;
    `,t.appendChild(n)}document.body.appendChild(t);let e=document.createElement("style"),i=(e.textContent=`
    @keyframes rainFall {
            to {
            transform: translateY(120vh) rotate(720deg);
        }
    }
    `,document.head.appendChild(e),setTimeout(()=>{t.remove(),e.remove()},5e3),document.createElement("div")),a=(i.style.cssText=`
    position: fixed;
    top: 50 %;
    left: 50 %;
    transform: translate(-50 %, -50 %);
    background: rgba(44, 24, 16, 0.95);
    color: #C9A227;
    padding: 30px 50px;
    border - radius: 20px;
    font - size: 1.5rem;
    font - weight: bold;
    z - index: 9999999;
    text - align: center;
    animation: popIn 0.5s ease;
    `,i.innerHTML='☕ أنت من عشاق القهوة الحقيقيين! ☕<br><small style="color: rgba(255,255,255,0.7)">خصم 10% - استخدم كود: COFFEE10</small>',document.body.appendChild(i),document.createElement("style"));a.textContent=`
    @keyframes popIn {
        0 % { transform: translate(-50 %, -50 %) scale(0); }
        50 % { transform: translate(-50 %, -50 %) scale(1.1); }
        100 % { transform: translate(-50 %, -50 %) scale(1); }
    }
    `,document.head.appendChild(a),setTimeout(()=>{i.style.animation="popIn 0.3s ease reverse",setTimeout(()=>{i.remove(),a.remove()},300)},3e3)}function initMenuAnimation(){var e=document.querySelector(".navbar-toggler");e&&e.addEventListener("click",function(){this.classList.toggle("active")})}function initImageLens(){document.querySelectorAll(".product-image").forEach(s=>{let e=s.querySelector("img");if(e){let o=document.createElement("div");o.className="image-lens",o.style.cssText=`
    display: none;
    position: absolute;
    width: 100px;
    height: 100px;
    border - radius: 50 %;
    border: 3px solid #C9A227;
    pointer - events: none;
    z - index: 10;
    background - size: 300 %;
    box - shadow: 0 0 20px rgba(201, 162, 39, 0.5);
    `,s.appendChild(o),s.addEventListener("mouseenter",()=>{o.style.display="block",o.style.backgroundImage=`url(${e.src})`}),s.addEventListener("mouseleave",()=>{o.style.display="none"}),s.addEventListener("mousemove",e=>{var t=s.getBoundingClientRect(),n=e.clientX-t.left,i=n-50,a=(e=e.clientY-t.top)-50,i=(o.style.left=i+" px",o.style.top=a+" px",n/t.width*100),a=e/t.height*100;o.style.backgroundPosition=i+`% ${a}% `})}})}window.SoundManager=SoundManager,document.addEventListener("DOMContentLoaded",initEasterEgg),document.addEventListener("DOMContentLoaded",initMenuAnimation),window.initImageLens=initImageLens;