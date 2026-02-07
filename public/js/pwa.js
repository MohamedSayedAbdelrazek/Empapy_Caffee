(()=>{let t=!1;function s(...e){t&&console.log(...e)}let a=null,i=!1,n="pwa-installed";function l(){return!!(window.matchMedia("(display-mode: standalone)").matches||window.matchMedia("(display-mode: fullscreen)").matches||window.matchMedia("(display-mode: minimal-ui)").matches||!0===window.navigator.standalone||document.referrer.includes("android-app://"))}function d(){return l()||o()}function o(){return"true"===localStorage.getItem(n)}function c(e){e?localStorage.setItem(n,"true"):localStorage.removeItem(n)}function r(){return/iPad|iPhone|iPod/.test(navigator.userAgent)&&!window.MSStream}function p(){if(i||d())s("[PWA] Already installed, not showing banner");else{if(localStorage.getItem("pwa-install-dismissed")){var t=parseInt(localStorage.getItem("pwa-install-dismissed"));if(s("[PWA] Days since dismiss:",(t=(Date.now()-t)/864e5).toFixed(2)),t<1)return s("[PWA] Dismissed recently, not showing banner")}let e=document.getElementById("pwaInstallBanner");e||(e=((t=document.createElement("div")).className="pwa-install-banner",t.id="pwaInstallBanner",t.innerHTML=`
            <div class="pwa-install-card">
                <button class="pwa-install-close" id="pwaCloseBtn" aria-label="إغلاق">
                    <i class="bi bi-x"></i>
                </button>
                
                <div class="pwa-install-header">
                <div class="pwa-install-icon">
                        <img src="/icons/android/android-launchericon-192-192.png" alt="إمبابي كافيه" onerror="this.src='/logo.jpg';">
                    </div>
                    <div class="pwa-install-info">
                        <div class="pwa-install-title">
                            إمبابي كافيه
                            <span class="verified-badge">
                                <i class="bi bi-check"></i>
                            </span>
                        </div>
                        <div class="pwa-install-subtitle">
                            ثبّت التطبيق للوصول السريع وتجربة أفضل
                        </div>
                    </div>
                </div>
                
                <div class="pwa-install-features">
                    <div class="pwa-feature">
                        <div class="pwa-feature-icon offline">
                            <i class="bi bi-wifi-off"></i>
                        </div>
                        <div class="pwa-feature-text">بدون إنترنت</div>
                    </div>
                    <div class="pwa-feature">
                        <div class="pwa-feature-icon fast">
                            <i class="bi bi-lightning-fill"></i>
                        </div>
                        <div class="pwa-feature-text">سرعة فائقة</div>
                    </div>
                    <div class="pwa-feature">
                        <div class="pwa-feature-icon notify">
                            <i class="bi bi-bell-fill"></i>
                        </div>
                        <div class="pwa-feature-text">إشعارات</div>
                    </div>
                </div>
                
                <div class="pwa-install-actions" id="pwaActions">
                    <button class="pwa-btn pwa-btn-install" id="pwaInstallBtn">
                        <i class="bi bi-download"></i>
                        تثبيت التطبيق
                    </button>
                    <button class="pwa-btn pwa-btn-later" id="pwaLaterBtn">
                        لاحقاً
                    </button>
                </div>
                
                <div class="pwa-ios-instructions" id="pwaIOSInstructions">
                    <div class="pwa-ios-title">
                        <i class="bi bi-phone"></i>
                        لتثبيت التطبيق على جهازك
                    </div>
                    <div class="pwa-ios-steps">
                        <div class="pwa-ios-step">
                            <div class="pwa-ios-step-number">1</div>
                            <div class="pwa-ios-step-content">
                                <span class="pwa-ios-step-text">اضغط على زر المشاركة</span>
                                <span class="pwa-ios-step-icon">
                                    <svg width="20" height="20" viewBox="0 0 50 50" fill="currentColor">
                                        <path d="M30.3 13.7L25 8.4l-5.3 5.3-1.4-1.4L25 5.6l6.7 6.7z"/>
                                        <path d="M24 7h2v21h-2z"/>
                                        <path d="M35 40H15c-1.7 0-3-1.3-3-3V19c0-1.7 1.3-3 3-3h7v2h-7c-.6 0-1 .4-1 1v18c0 .6.4 1 1 1h20c.6 0 1-.4 1-1V19c0-.6-.4-1-1-1h-7v-2h7c1.7 0 3 1.3 3 3v18c0 1.7-1.3 3-3 3z"/>
                                    </svg>
                                </span>
                                <span class="pwa-ios-step-hint">في أسفل الشاشة (Safari)</span>
                            </div>
                        </div>
                        <div class="pwa-ios-step">
                            <div class="pwa-ios-step-number">2</div>
                            <div class="pwa-ios-step-content">
                                <span class="pwa-ios-step-text">اختر "إضافة إلى الشاشة الرئيسية"</span>
                                <span class="pwa-ios-step-icon">
                                    <i class="bi bi-plus-square"></i>
                                </span>
                            </div>
                        </div>
                        <div class="pwa-ios-step">
                            <div class="pwa-ios-step-number">3</div>
                            <div class="pwa-ios-step-content">
                                <span class="pwa-ios-step-text">اضغط "إضافة" للتأكيد</span>
                                <span class="pwa-ios-step-icon">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,document.body.appendChild(t),t),document.getElementById("pwaInstallBtn").addEventListener("click",async()=>{var e;a&&(a.prompt(),s("[PWA] User choice:",e=(await a.userChoice).outcome),"accepted"===e&&(g(),c(i=!0)),a=null,v())}),document.getElementById("pwaLaterBtn").addEventListener("click",()=>{localStorage.setItem("pwa-install-dismissed",Date.now().toString()),v()}),document.getElementById("pwaCloseBtn").addEventListener("click",()=>{localStorage.setItem("pwa-install-dismissed",Date.now().toString()),v()})),r()&&(document.getElementById("pwaActions").style.display="none",document.getElementById("pwaIOSInstructions").classList.add("show")),setTimeout(()=>{e.classList.add("show"),s("[PWA] Install banner shown!")},800)}}function v(){var e=document.getElementById("pwaInstallBanner");e&&e.classList.remove("show")}function w(){var e,t,a;i||d()||(e=document.getElementById("footerInstallSection"),t=document.getElementById("navbarInstallBtn"),a=document.getElementById("adminNavbarInstallBtn"),e&&(e.style.display="block",r()&&e.classList.add("ios"),u("footerInstallBtn"),s("[PWA] Footer install section shown")),t&&(t.style.display="flex",u("navbarInstallBtn"),s("[PWA] User navbar install button shown")),a&&(a.style.display="flex",u("adminNavbarInstallBtn"),s("[PWA] Admin navbar install button shown")))}function u(e){(e=document.getElementById(e))&&!e.hasAttribute("data-setup")&&(e.setAttribute("data-setup","true"),e.addEventListener("click",async()=>{var e;a?(a.prompt(),s("[PWA] User choice:",e=(await a.userChoice).outcome),"accepted"===e&&(g(),c(i=!0),m()),a=null,v()):r()?p():s("[PWA] Install prompt not available yet")}))}function m(){["footerInstallSection","navbarInstallBtn","adminNavbarInstallBtn"].forEach(e=>{(e=document.getElementById(e))&&(e.style.display="none")})}function g(){let e=document.getElementById("pwaInstalledBadge"),t;(e=e||((t=document.createElement("div")).className="pwa-installed-badge",t.id="pwaInstalledBadge",t.innerHTML=`
            <i class="bi bi-check-circle-fill"></i>
            <span>تم تثبيت التطبيق بنجاح!</span>
        `,document.body.appendChild(t),t)).classList.add("show"),setTimeout(()=>{e.classList.remove("show"),setTimeout(()=>e.remove(),500)},5e3)}async function e(){var e=l(),t=await(async()=>{if(!o())return!1;if(!l()&&"getInstalledRelatedApps"in navigator)try{var e=await navigator.getInstalledRelatedApps();return!(!e||0===e.length)||(c(!1),!1)}catch(e){console.warn("[PWA] getInstalledRelatedApps failed:",e)}return!0})();i=e||t,s("[PWA] Init - Standalone:",e,"Stored:",t),(async()=>{if("serviceWorker"in navigator)try{let t=await navigator.serviceWorker.register("/sw.js",{scope:"/"});return s("[PWA] Service Worker registered:",t.scope),t.addEventListener("updatefound",()=>{let e=t.installing;e.addEventListener("statechange",()=>{"installed"===e.state&&navigator.serviceWorker.controller&&s("[PWA] New version available - will update on next reload")})}),t}catch(e){console.error("[PWA] Service Worker registration failed:",e)}})(),r()&&!i&&setTimeout(()=>{p(),w()},3e3),s("[PWA] Initialized. Installed:",i)}window.resetPWAInstall=function(){localStorage.removeItem("pwa-install-dismissed"),localStorage.removeItem("pwa-installed"),s("[PWA] All PWA states cleared. Reload the page to see the install buttons."),alert("تم إعادة ضبط حالة التثبيت. أعد تحميل الصفحة لرؤية أزرار التثبيت.")},window.addEventListener("beforeinstallprompt",e=>{e.preventDefault(),d()?s("[PWA] Already installed, ignoring beforeinstallprompt"):(a=e,s("[PWA] Install prompt available"),p(),w())}),window.addEventListener("appinstalled",e=>{s("[PWA] App installed!"),c(i=i=!0),s("[PWA] Marked as installed"),v(),m(),g(),a=null}),"loading"===document.readyState?document.addEventListener("DOMContentLoaded",e):e()})();