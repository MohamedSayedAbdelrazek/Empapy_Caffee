(()=>{function o(){if("undefined"!=typeof confetti)confetti({particleCount:50,spread:70,origin:{y:.6},colors:["#C9A227","#E8C547","#A88B1F","#FFD700","#22C55E"]});else{let e=document.createElement("div");e.className="confetti",document.body.appendChild(e);var o=["#C9A227","#E8C547","#22C55E","#3B82F6","#F59E0B"];for(let t=0;t<50;t++){var i=document.createElement("div");i.className="confetti-piece",i.style.left=100*Math.random()+"vw",i.style.background=o[Math.floor(Math.random()*o.length)],i.style.animationDelay=2*Math.random()+"s",i.style.transform=`rotate(${360*Math.random()}deg)`,e.appendChild(i)}setTimeout(()=>e.remove(),3500)}}function e(t){var e=t.checkValidity();t.classList.remove("is-valid","is-invalid"),t.classList.add(e?"is-valid":"is-invalid"),e||(t.classList.add("shake"),setTimeout(()=>t.classList.remove("shake"),500))}window.Toast=new class{constructor(){this.container=null,this.toasts=[],this.init()}init(){document.querySelector(".toast-container")?this.container=document.querySelector(".toast-container"):(this.container=document.createElement("div"),this.container.className="toast-container",document.body.appendChild(this.container))}show(t={}){let{type:e="info",title:o="",message:i="",duration:s=5e3,action:a=null,icon:n=null,persistent:l=!1}=t,r=document.createElement("div");return r.className="toast-notification "+e,t={success:"bi-check-lg",error:"bi-x-lg",warning:"bi-exclamation-triangle",info:"bi-info-lg",cart:"bi-bag-check"},r.innerHTML=`
                <div class="toast-icon">
                    <i class="bi ${n||t[e]||t.info}"></i>
                </div>
                <div class="toast-content">
                    ${o?`<div class="toast-title">${o}</div>`:""}
                    ${i?`<div class="toast-message">${i}</div>`:""}
                    ${a?`
                        <div class="toast-action">
                            <button class="toast-action-btn">${a.text}</button>
                        </div>
                    `:""}
                </div>
                <button class="toast-close">
                    <i class="bi bi-x"></i>
                </button>
                ${l?"":'<div class="toast-progress"></div>'}
            `,this.container.appendChild(r),requestAnimationFrame(()=>{r.classList.add("show")}),this.playSound(e),r.querySelector(".toast-close").addEventListener("click",()=>this.hide(r)),a&&a.onClick&&r.querySelector(".toast-action-btn").addEventListener("click",()=>{a.onClick(),this.hide(r)}),!l&&0<s&&setTimeout(()=>this.hide(r),s),this.toasts.push(r),r}hide(e){e&&e.parentNode&&(e.classList.remove("show"),e.classList.add("hiding"),setTimeout(()=>{e.parentNode&&e.parentNode.removeChild(e),this.toasts=this.toasts.filter(t=>t!==e)},500))}hideAll(){this.toasts.forEach(t=>this.hide(t))}success(t,e,o={}){return this.show({type:"success",title:t,message:e,...o})}error(t,e,o={}){return this.show({type:"error",title:t,message:e,...o})}warning(t,e,o={}){return this.show({type:"warning",title:t,message:e,...o})}info(t,e,o={}){return this.show({type:"info",title:t,message:e,...o})}cart(t,e,o={}){return(t=this.show({type:"cart",title:t,message:e+'<div style="font-size:0.75rem;opacity:0.8;margin-top:4px;">اضغط لعرض السلة ←</div>',duration:3e3,...o}))&&(t.style.cursor="pointer",t.addEventListener("click",t=>{t.target.closest(".toast-close")||(window.location.href="/cart")})),t}playSound(t){}},window.Skeleton=new class{constructor(){this.templates={productCard:this.createProductCardSkeleton.bind(this),categoryCard:this.createCategoryCardSkeleton.bind(this),statCard:this.createStatCardSkeleton.bind(this),tableRow:this.createTableRowSkeleton.bind(this),text:this.createTextSkeleton.bind(this)}}createProductCardSkeleton(){var t=document.createElement("div");return t.className="skeleton-product-card",t.innerHTML=`
                <div class="skeleton-product-image skeleton"></div>
                <div class="skeleton-product-content">
                    <div class="skeleton skeleton-text" style="width: 40%;"></div>
                    <div class="skeleton skeleton-text title"></div>
                    <div class="skeleton skeleton-text subtitle"></div>
                    <div class="skeleton skeleton-text price"></div>
                </div>
            `,t}createCategoryCardSkeleton(){var t=document.createElement("div");return t.className="skeleton skeleton-category-card",t}createStatCardSkeleton(){var t=document.createElement("div");return t.className="skeleton-stat-card",t.innerHTML=`
                <div class="skeleton skeleton-stat-icon"></div>
                <div class="skeleton-stat-content">
                    <div class="skeleton skeleton-stat-value"></div>
                    <div class="skeleton skeleton-stat-label"></div>
                </div>
            `,t}createTableRowSkeleton(e=5){var o=document.createElement("div");o.className="skeleton-table-row";for(let t=0;t<e;t++){var i=document.createElement("div");i.className="skeleton skeleton-table-cell",i.style.flex=0===t?"2":"1",o.appendChild(i)}return o}createTextSkeleton(t="100%",e="14px"){var o=document.createElement("div");return o.className="skeleton skeleton-text",o.style.width=t,o.style.height=e,o}show(t,e="productCard",o=4){if((t="string"==typeof t?document.querySelector(t):t)&&this.templates[e]){t.dataset.originalContent=t.innerHTML,t.innerHTML="";var i=document.createElement("div");i.className="skeleton-wrapper row g-4";for(let t=0;t<o;t++){var s=document.createElement("div");s.className="categoryCard"===e?"col-6 col-lg-3":"col-6 col-md-4 col-lg-3",s.appendChild(this.templates[e]()),i.appendChild(s)}t.appendChild(i)}}hide(t){var e;(t="string"==typeof t?document.querySelector(t):t)&&((e=t.querySelector(".skeleton-wrapper"))&&e.remove(),t.dataset.originalContent)&&delete t.dataset.originalContent}},window.PageLoader=new class{constructor(){this.loader=null}init(){}show(){}hide(){}},window.ScrollEnhancements=new class{constructor(){this.scrollIndicator=null,this.backToTop=null,this.init()}init(){this.createScrollIndicator(),this.createBackToTop(),this.bindEvents()}createScrollIndicator(){this.scrollIndicator=document.createElement("div"),this.scrollIndicator.className="scroll-indicator",this.scrollIndicator.innerHTML='<div class="scroll-indicator-bar"></div>',document.body.appendChild(this.scrollIndicator)}createBackToTop(){this.backToTop=document.createElement("button"),this.backToTop.className="back-to-top",this.backToTop.setAttribute("aria-label","Back to top"),this.backToTop.innerHTML='<i class="bi bi-chevron-up"></i>',document.body.appendChild(this.backToTop),this.backToTop.addEventListener("click",()=>{window.scrollTo({top:0,behavior:"smooth"})})}bindEvents(){window.addEventListener("scroll",()=>{this.updateScrollIndicator(),this.updateBackToTop()},{passive:!0})}updateScrollIndicator(){var t=window.scrollY/(document.documentElement.scrollHeight-window.innerHeight)*100,e=this.scrollIndicator.querySelector(".scroll-indicator-bar");e&&(e.style.width=t+"%")}updateBackToTop(){400<window.scrollY?this.backToTop.classList.add("visible"):this.backToTop.classList.remove("visible")}},document.addEventListener("DOMContentLoaded",function(){document.addEventListener("click",function(i){let s=i.target.closest(".ripple, .btn-golden, .btn-action, .btn");if(s&&!s.classList.contains("cart-toggle")&&"cartToggle"!==s.id){let t=s.getBoundingClientRect(),e=document.createElement("span");e.className="ripple-effect";var a=Math.max(t.width,t.height);e.style.width=e.style.height=a+"px",e.style.left=i.clientX-t.left-a/2+"px",e.style.top=i.clientY-t.top-a/2+"px";let o=s.style.overflow;s.style.position="relative",s.style.overflow="hidden",s.appendChild(e),setTimeout(()=>{e.remove(),s.style.overflow=o||""},600)}});{let t=document.querySelectorAll(".count-up"),e=new IntersectionObserver(t=>{t.forEach(i=>{if(i.isIntersecting&&!i.target.classList.contains("counted")){var s=parseInt(i.target.dataset.target)||0;{var[a,n,s=2e3]=[i.target,s];let t=n/(s/16),e=0,o=setInterval(()=>{(e+=t)>=n?(a.textContent=n.toLocaleString("ar-EG"),clearInterval(o)):a.textContent=Math.floor(e).toLocaleString("ar-EG")},16)}i.target.classList.add("counted")}})},{threshold:.5});t.forEach(t=>e.observe(t))}document.querySelectorAll("form").forEach(t=>{t.querySelectorAll("input, textarea, select").forEach(t=>{t.addEventListener("blur",()=>e(t)),t.addEventListener("input",()=>{t.classList.contains("is-invalid")&&e(t)})})}),"function"==typeof window.addToCart&&(window.addToCart=function(t,e=1){fetch("/cart/add",{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({product_id:t,quantity:e})}).then(t=>t.json()).then(t=>{if(t.success){window.Toast.cart("تمت الإضافة! 🎉","تمت إضافة المنتج إلى سلة التسوق بنجاح"),o(),"function"==typeof updateCartCount&&updateCartCount();let t=document.querySelector(".cart-badge");t&&(t.classList.add("bounce"),setTimeout(()=>t.classList.remove("bounce"),500))}else window.Toast.error("خطأ",t.message||"حدث خطأ أثناء إضافة المنتج")}).catch(()=>{window.Toast.error("خطأ في الاتصال","يرجى التحقق من اتصالك بالإنترنت")})})}),window.ImageGallery=class{constructor(t,e={}){this.container="string"==typeof t?document.querySelector(t):t,this.container&&(this.options={images:[],enableZoom:!0,enableLightbox:!0,autoplay:!1,autoplayDelay:5e3,...e},this.currentIndex=0,this.lightbox=null,this.zoomLevel=1,this.maxZoom=3,this.minZoom=1,this.init())}init(){this.createGallery(),this.bindEvents(),this.options.autoplay&&this.startAutoplay()}createGallery(){var t=this.options.images;t.length&&(this.container.innerHTML=`
                <div class="product-gallery">
                    <div class="product-gallery-main">
                        <img src="${t[0]}" alt="Product Image" id="gallery-main-image">
                        ${1<t.length?`
                            <button class="gallery-nav prev" aria-label="Previous">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                            <button class="gallery-nav next" aria-label="Next">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <div class="gallery-counter">
                                <span id="gallery-current">1</span> / ${t.length}
                            </div>
                        `:""}
                    </div>
                    ${1<t.length?`
                        <div class="gallery-thumbnails">
                            ${t.map((t,e)=>`
                                <div class="gallery-thumb ${0===e?"active":""}" data-index="${e}">
                                    <img src="${t}" alt="Thumbnail ${e+1}">
                                </div>
                            `).join("")}
                        </div>
                    `:""}
                </div>
            `,this.options.enableLightbox)&&this.createLightbox()}createLightbox(){var t;document.querySelector(".lightbox")||(t=this.options.images,this.lightbox=document.createElement("div"),this.lightbox.className="lightbox",this.lightbox.innerHTML=`
                <button class="lightbox-close" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="lightbox-zoom-controls">
                    <button class="zoom-btn zoom-in" aria-label="Zoom In">
                        <i class="bi bi-zoom-in"></i>
                    </button>
                    <button class="zoom-btn zoom-out" aria-label="Zoom Out">
                        <i class="bi bi-zoom-out"></i>
                    </button>
                    <button class="zoom-btn zoom-reset" aria-label="Reset Zoom">
                        <i class="bi bi-arrows-angle-contract"></i>
                    </button>
                </div>
                <div class="lightbox-content">
                    <div class="zoom-container">
                        <img src="${t[0]}" alt="Product Image" class="lightbox-image" id="lightbox-image">
                    </div>
                    ${1<t.length?`
                        <button class="lightbox-nav prev" aria-label="Previous">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                        <button class="lightbox-nav next" aria-label="Next">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    `:""}
                </div>
                ${1<t.length?`
                    <div class="lightbox-thumbnails">
                        ${t.map((t,e)=>`
                            <div class="lightbox-thumb ${0===e?"active":""}" data-index="${e}">
                                <img src="${t}" alt="Thumbnail ${e+1}">
                            </div>
                        `).join("")}
                    </div>
                `:""}
            `,document.body.appendChild(this.lightbox))}bindEvents(){var t,e,o,i,s=this.container.querySelector(".product-gallery");s&&(o=s.querySelector(".product-gallery-main"),i=s.querySelectorAll(".gallery-thumb"),t=s.querySelector(".gallery-nav.prev"),e=s.querySelector(".gallery-nav.next"),o&&this.options.enableLightbox&&o.addEventListener("click",()=>this.openLightbox()),i.forEach(t=>{t.addEventListener("click",()=>{this.goTo(parseInt(t.dataset.index))})}),t&&t.addEventListener("click",t=>{t.stopPropagation(),this.prev()}),e&&e.addEventListener("click",t=>{t.stopPropagation(),this.next()}),this.lightbox&&(this.lightbox.querySelector(".lightbox-close").addEventListener("click",()=>this.closeLightbox()),this.lightbox.addEventListener("click",t=>{t.target===this.lightbox&&this.closeLightbox()}),o=this.lightbox.querySelector(".lightbox-nav.prev"),i=this.lightbox.querySelector(".lightbox-nav.next"),o&&o.addEventListener("click",()=>this.prev()),i&&i.addEventListener("click",()=>this.next()),this.lightbox.querySelectorAll(".lightbox-thumb").forEach(t=>{t.addEventListener("click",()=>{this.goTo(parseInt(t.dataset.index))})}),this.lightbox.querySelector(".zoom-in").addEventListener("click",()=>this.zoomIn()),this.lightbox.querySelector(".zoom-out").addEventListener("click",()=>this.zoomOut()),this.lightbox.querySelector(".zoom-reset").addEventListener("click",()=>this.zoomReset()),document.addEventListener("keydown",t=>{if(this.lightbox.classList.contains("active"))switch(t.key){case"Escape":this.closeLightbox();break;case"ArrowRight":this.prev();break;case"ArrowLeft":this.next();break;case"+":this.zoomIn();break;case"-":this.zoomOut()}}),this.lightbox.querySelector(".zoom-container").addEventListener("wheel",t=>{t.preventDefault(),t.deltaY<0?this.zoomIn():this.zoomOut()}),this.setupPanControls()),this.setupSwipeControls(s))}setupSwipeControls(t){let o=0,i=0;t.addEventListener("touchstart",t=>{o=t.touches[0].clientX,i=t.touches[0].clientY},{passive:!0}),t.addEventListener("touchend",t=>{var e=t.changedTouches[0].clientX,t=t.changedTouches[0].clientY,e=o-e,t=i-t;Math.abs(e)>Math.abs(t)&&50<Math.abs(e)&&(0<e?this.next():this.prev())},{passive:!0})}setupPanControls(){let e=this.lightbox.querySelector(".zoom-container"),o=this.lightbox.querySelector(".lightbox-image"),i=!1,s,a,n=0,l=0;e.addEventListener("mousedown",t=>{1<this.zoomLevel&&(i=!0,s=t.clientX-n,a=t.clientY-l,e.classList.add("zoomed"))}),document.addEventListener("mousemove",t=>{i&&(n=t.clientX-s,l=t.clientY-a,o.style.transform=`scale(${this.zoomLevel}) translate(${n/this.zoomLevel}px, ${l/this.zoomLevel}px)`)}),document.addEventListener("mouseup",()=>{i=!1,e.classList.remove("zoomed")})}goTo(o){let t=this.options.images,e=(this.currentIndex=o,this.container.querySelector("#gallery-main-image"));e&&(e.style.opacity="0",setTimeout(()=>{e.src=t[o],e.style.opacity="1"},200));var i=this.container.querySelector("#gallery-current");i&&(i.textContent=o+1),this.container.querySelectorAll(".gallery-thumb").forEach((t,e)=>{t.classList.toggle("active",e===o)}),this.lightbox&&this.lightbox.classList.contains("active")&&((i=this.lightbox.querySelector(".lightbox-image"))&&(i.src=t[o]),this.lightbox.querySelectorAll(".lightbox-thumb").forEach((t,e)=>{t.classList.toggle("active",e===o)}),this.zoomReset())}next(){var t=(this.currentIndex+1)%this.options.images.length;this.goTo(t)}prev(){var t=(this.currentIndex-1+this.options.images.length)%this.options.images.length;this.goTo(t)}openLightbox(){var t;this.lightbox&&(this.lightbox.classList.add("active"),document.body.style.overflow="hidden",t=this.lightbox.querySelector(".lightbox-image"))&&(t.src=this.options.images[this.currentIndex])}closeLightbox(){this.lightbox&&(this.lightbox.classList.remove("active"),document.body.style.overflow="",this.zoomReset())}zoomIn(){this.zoomLevel<this.maxZoom&&(this.zoomLevel=Math.min(this.maxZoom,this.zoomLevel+.5),this.updateZoom())}zoomOut(){this.zoomLevel>this.minZoom&&(this.zoomLevel=Math.max(this.minZoom,this.zoomLevel-.5),this.updateZoom())}zoomReset(){this.zoomLevel=1,this.updateZoom()}updateZoom(){var t=this.lightbox.querySelector(".lightbox-image");t&&(t.style.transform=`scale(${this.zoomLevel})`)}startAutoplay(){this.autoplayInterval=setInterval(()=>this.next(),this.options.autoplayDelay)}stopAutoplay(){this.autoplayInterval&&clearInterval(this.autoplayInterval)}},window.createConfetti=o})();