function initScrollProgress(){let t=document.createElement("div");function e(){var e=window.scrollY/(document.documentElement.scrollHeight-window.innerHeight);t.style.transform=`scaleX(${e})`}t.className="scroll-progress",document.body.appendChild(t),window.addEventListener("scroll",e,{passive:!0}),e()}function initDarkMode(){let t=document.getElementById("themeToggleNavbar"),e;t&&(e=localStorage.getItem("theme")||"light",document.documentElement.setAttribute("data-theme",e),t.addEventListener("click",()=>{var e="dark"===document.documentElement.getAttribute("data-theme")?"light":"dark";document.documentElement.setAttribute("data-theme",e),localStorage.setItem("theme",e),t.style.transform="scale(0.8) rotate(180deg)",setTimeout(()=>{t.style.transform=""},300)}))}function init3DHoverEffect(){document.querySelectorAll(".product-card").forEach(n=>{var e=n.closest(".col-6, .col-md-4, .col-lg-3");e&&e.classList.add("product-card-3d"),n.addEventListener("mousemove",e=>{var t=n.getBoundingClientRect(),i=e.clientX-t.left;n.style.transform=`perspective(1000px) rotateX(${(e.clientY-t.top-t.height/2)/20}deg) rotateY(${(t.width/2-i)/20}deg) translateY(-10px)`}),n.addEventListener("mouseleave",()=>{n.style.transform=""})})}function initLazyLoading(){let e=document.querySelectorAll("img[data-src]"),t=new IntersectionObserver((e,i)=>{e.forEach(t=>{if(t.isIntersecting){let e=t.target;e.classList.add("lazy"),(t=new Image).src=e.dataset.src,t.onload=()=>{e.src=e.dataset.src,e.classList.remove("lazy"),e.classList.add("loaded"),e.parentElement?.classList.add("loaded")},i.unobserve(e)}})},{rootMargin:"50px"});e.forEach(e=>{e.parentElement?.classList.add("lazy-image"),t.observe(e)})}function initCustomCursor(){}function initParallax(){let t=document.querySelectorAll(".hero-bg img, .page-header::before");if(t.length){let e=!1;function i(){let i=window.scrollY;t.forEach(e=>{var t=-.5*i;e.style.transform=`translate3d(0, ${t}px, 0)`}),e=!1}window.addEventListener("scroll",()=>{e||(requestAnimationFrame(i),e=!0)},{passive:!0})}}function initMicroInteractions(){document.querySelectorAll(".btn-golden, .btn-outline-golden, .btn-action").forEach(e=>{e.addEventListener("click",function(e){var t=this.getBoundingClientRect(),i=e.clientX-t.left,e=e.clientY-t.top;let n=document.createElement("span");n.style.cssText=`
                position: absolute;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                pointer-events: none;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                left: ${i}px;
                top: ${e}px;
                width: 10px;
                height: 10px;
                margin-left: -5px;
                margin-top: -5px;
            `,this.appendChild(n),setTimeout(()=>n.remove(),600)})});var e=document.createElement("style");e.textContent=`
        @keyframes ripple {
            to {
                transform: scale(40);
                opacity: 0;
            }
        }
    `,document.head.appendChild(e)}function showConfetti(t,i){let n=document.createElement("div");n.className="confetti-container",document.body.appendChild(n);var o=["#C9A227","#E8C547","#A88B1F","#FFD700","#FFA500"];for(let e=0;e<30;e++){var a=document.createElement("div");a.className="confetti",a.style.cssText=`
            left: ${t}px;
            top: ${i}px;
            background: ${o[Math.floor(Math.random()*o.length)]};
            width: ${10*Math.random()+5}px;
            height: ${10*Math.random()+5}px;
            animation-delay: ${.3*Math.random()}s;
            animation-duration: ${+Math.random()+2}s;
            transform: translate(${200*(Math.random()-.5)}px, 0) rotate(${360*Math.random()}deg);
        `,n.appendChild(a)}setTimeout(()=>n.remove(),3e3)}document.addEventListener("DOMContentLoaded",function(){var e=window.matchMedia("(prefers-reduced-motion: reduce)").matches,t=window.matchMedia("(max-width: 768px)").matches;initScrollProgress(),initDarkMode(),initLazyLoading(),initMicroInteractions(),initQuickView(),initAnimatedCounters(),initScrollAnimations(),initBackToTop(),initPageTransitions(),e||t||(init3DHoverEffect(),initParallax(),initParticles(),initMagneticButtons())});let originalAddToCart=window.addToCart;function initParticles(){var e=document.querySelector(".hero-section");if(e){var t=document.createElement("div");t.className="particles-container",e.appendChild(t);for(let e=0;e<20;e++)createParticle(t)}}function createParticle(e){var t=document.createElement("div"),i=(t.className="particle",10*Math.random()+5);t.style.cssText=`
        width: ${i}px;
        height: ${i}px;
        left: ${100*Math.random()}%;
        top: ${100*Math.random()}%;
        animation-duration: ${10*Math.random()+10}s;
        animation-delay: ${5*Math.random()}s;
    `,e.appendChild(t)}function initQuickView(){var e=document.createElement("div");e.className="quick-view-modal",e.id="quickViewModal",e.innerHTML=`
        <div class="quick-view-overlay"></div>
        <div class="quick-view-content">
            <button class="quick-view-close"><i class="bi bi-x-lg"></i></button>
            <div class="quick-view-body">
                <div class="quick-view-image"></div>
                <div class="quick-view-details">
                    <span class="quick-view-category"></span>
                    <h2 class="quick-view-title"></h2>
                    <p class="quick-view-description"></p>
                    <div class="quick-view-price"></div>
                    <div class="quick-view-actions mt-4">
                        <button class="btn btn-golden btn-lg w-100 mb-3 quick-view-add-cart">
                            <i class="bi bi-bag-plus me-2"></i>أضف للسلة
                        </button>
                        <a href="#" class="btn btn-outline-golden w-100 quick-view-link">
                            عرض التفاصيل الكاملة
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `,document.body.appendChild(e),e.querySelector(".quick-view-overlay").addEventListener("click",closeQuickView),e.querySelector(".quick-view-close").addEventListener("click",closeQuickView),document.addEventListener("keydown",e=>{"Escape"===e.key&&closeQuickView()})}function openQuickView(t){var i=document.getElementById("quickViewModal");if(i){i.querySelector(".quick-view-image").style.backgroundImage=`url(${t.image})`,i.querySelector(".quick-view-category").textContent=t.category||"قهوة فاخرة",i.querySelector(".quick-view-title").textContent=t.name,i.querySelector(".quick-view-description").textContent=t.description;let e=`<span>${t.price}</span>`;t.oldPrice&&(e=`<span class="old-price">${t.oldPrice}</span>`+e),i.querySelector(".quick-view-price").innerHTML=e,i.querySelector(".quick-view-link").href=t.link||"#",i.querySelector(".quick-view-add-cart").onclick=()=>{t.id&&window.addToCart&&(window.addToCart(t.id),closeQuickView())},i.classList.add("open"),document.body.style.overflow="hidden"}}function closeQuickView(){var e=document.getElementById("quickViewModal");e&&(e.classList.remove("open"),document.body.style.overflow="")}function initAnimatedCounters(){let e=document.querySelectorAll(".stat-number"),t=new IntersectionObserver(e=>{e.forEach(e=>{e.isIntersecting&&!e.target.classList.contains("counted")&&(animateCounter(e.target),e.target.classList.add("counted"))})},{threshold:.5});e.forEach(e=>{e.classList.add("counter-animated"),t.observe(e)})}function animateCounter(c){var r=c.textContent,l=r.match(/(\d+)([K+★]?)/);if(l){let e=parseInt(l[1]),t=l[2]||"",i=r.includes("+")?"+":"",n=0,o=e/50,a=setInterval(()=>{(n+=o)>=e&&(n=e,clearInterval(a)),c.textContent=Math.floor(n)+t+i},40)}}function initScrollAnimations(){document.querySelectorAll("[data-aos]"),document.querySelectorAll(".glass-card, .category-card").forEach(e=>{e.hasAttribute("data-aos")||e.classList.add("scroll-zoom")});let e=document.querySelectorAll(".scroll-fade-up, .scroll-fade-left, .scroll-fade-right, .scroll-zoom"),t=new IntersectionObserver(e=>{e.forEach(e=>{e.isIntersecting&&e.target.classList.add("visible")})},{threshold:.1});e.forEach(e=>t.observe(e))}function initBackToTop(){if(!document.querySelector(".back-to-top")){let e=document.createElement("button"),t=(e.className="back-to-top",e.innerHTML='<i class="bi bi-chevron-up"></i>',e.setAttribute("aria-label","Back to top"),document.body.appendChild(e),!1);function i(){300<window.scrollY?e.classList.add("visible"):e.classList.remove("visible"),t=!1}window.addEventListener("scroll",()=>{t||(requestAnimationFrame(i),t=!0)},{passive:!0}),e.addEventListener("click",()=>{window.scrollTo({top:0,behavior:"smooth"})})}}function initPageTransitions(){}function initMagneticButtons(){window.innerWidth<1024||document.querySelectorAll(".btn-golden, .btn-outline-golden").forEach(e=>{e.classList.add("magnetic-btn"),e.addEventListener("mousemove",function(e){var t=this.getBoundingClientRect(),i=e.clientX-t.left-t.width/2;this.style.transform=`translate(${.2*i}px, ${.2*(e.clientY-t.top-t.height/2)}px)`}),e.addEventListener("mouseleave",function(){this.style.transform=""})})}originalAddToCart&&(window.addToCart=function(e,t=1){var i=document.querySelector(`[data-product-id="${e}"]`);return i&&showConfetti((i=i.getBoundingClientRect()).left+i.width/2,i.top),originalAddToCart(e,t)}),window.openQuickView=openQuickView,window.closeQuickView=closeQuickView,window.showConfetti=showConfetti;