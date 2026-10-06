<?php
/**
 * Plugin Highlight Code giao diện Mac cho Typecho
 * 
 * Tích hợp nút sao chép có thể tùy chỉnh, đánh số dòng, 
 * tương thích hoàn hảo với PJAX, tối ưu thanh cuộn trên mobile
 * và hiển thị nổi bật trên giao diện Dark Mode.
 * 
 * @package MacCodePrettify
 * @author Đặng Minh Đông
 * @version 1.0.7
 * @link https://github.com/dangmjnhdong/MacCodePrettify
 * @copyright Copyright (c) 2026 by Đông
 */

class MacCodePrettify_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('MacCodePrettify_Plugin', 'header');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('MacCodePrettify_Plugin', 'footer');
        return _t('Kích hoạt thành công plugin MacCodePrettify!');
    }

    public static function deactivate()
    {
        return _t('Đã tắt plugin.');
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $copyText = new Typecho_Widget_Helper_Form_Element_Text('copyText', NULL, 'Sao chép', _t('Chữ trên nút Copy'));
        $form->addInput($copyText);
        
        $copiedText = new Typecho_Widget_Helper_Form_Element_Text('copiedText', NULL, 'Đã sao chép!', _t('Chữ báo Copy thành công'));
        $form->addInput($copiedText);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    public static function header()
    {
        echo '<!-- MacCodePrettify Plugin by Đông -->';
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />';
        echo '<link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.css" rel="stylesheet" />';
        echo '<style>
            /* THÊM VIỀN SÁNG MỜ VÀ ĐIỀU CHỈNH NỀN KHỐI CODE TỐI ƯU DARK MODE */
            .mac-code-block { background: #252526; border-radius: 8px; margin: 20px 0; overflow: hidden; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.4); border: 1px solid rgba(255, 255, 255, 0.12); }
            
            /* LÀM TỐI THANH HEADER HƠN MỘT CHÚT ĐỂ TẠO CHIỀU SÂU */
            .mac-header { background: #1e1e1e; height: 35px; display: flex; align-items: center; padding: 0 15px; position: relative; z-index: 2; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
            
            .mac-dots { display: flex; gap: 8px; }
            .mac-dots span { width: 12px; height: 12px; border-radius: 50%; }
            .mac-dots span:nth-child(1) { background: #ff5f56; }
            .mac-dots span:nth-child(2) { background: #ffbd2e; }
            .mac-dots span:nth-child(3) { background: #27c93f; }
            .mac-lang { color: #ccc; font-size: 13px; font-weight: bold; position: absolute; left: 50%; transform: translateX(-50%); text-transform: uppercase; }
            
            .mac-copy-btn { 
                position: absolute; right: 10px; background: transparent !important; 
                border: 1px solid rgba(255, 255, 255, 0.2) !important; color: #eee !important; 
                border-radius: 4px !important; padding: 0 8px !important; 
                height: 24px !important; display: inline-flex !important; 
                align-items: center !important; justify-content: center !important;
                font-size: 12px !important; cursor: pointer; transition: 0.3s; 
                margin: 0 !important; box-sizing: border-box !important; 
                text-transform: none !important; box-shadow: none !important;
                line-height: normal !important; font-family: inherit !important;
                z-index: 10;
            }
            .mac-copy-btn:hover { background: rgba(255,255,255,0.1) !important; border-color: rgba(255, 255, 255, 0.4) !important; color: #fff !important; }
            .mac-copy-btn svg { margin-right: 4px; }
            
            .mac-code-block pre { position: relative !important; margin: 0 !important; padding: 15px 15px 15px 50px !important; background: transparent !important; border-radius: 0 !important; overflow-x: auto; }
            .mac-code-block code { position: static !important; display: block !important; padding: 0 !important; margin: 0 !important; font-family: Consolas, Monaco, monospace; font-size: 14px; line-height: 1.6; }
            
            /* TÁCH BIỆT CỘT SỐ DÒNG */
            .mac-code-block .line-numbers-rows { position: absolute !important; top: 0 !important; left: 0 !important; width: 40px !important; padding: 15px 0 !important; margin: 0 !important; border-right: 1px solid rgba(255, 255, 255, 0.08) !important; background: rgba(0, 0, 0, 0.1) !important; }
            .mac-code-block .line-numbers-rows > span:before { color: #777 !important; padding-right: 10px !important; }
            
            /* TỐI ƯU THANH CUỘN */
            .mac-code-block pre { scrollbar-width: none !important; -ms-overflow-style: none !important; }
            .mac-code-block pre::-webkit-scrollbar { display: none !important; width: 0 !important; height: 0 !important; }
            .mac-code-block pre.is-scrolling, .mac-code-block:hover pre, .mac-code-block pre:active { scrollbar-width: thin !important; -ms-overflow-style: auto !important; }
            .mac-code-block pre.is-scrolling::-webkit-scrollbar, .mac-code-block:hover pre::-webkit-scrollbar, .mac-code-block pre:active::-webkit-scrollbar { display: block !important; height: 6px !important; }
            .mac-code-block pre::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.25) !important; border-radius: 10px !important; }
        </style>';
    }

    public static function footer()
    {
        $options = Helper::options()->plugin('MacCodePrettify');
        $copyTxt = $options->copyText ? $options->copyText : 'Sao chép';
        $copiedTxt = $options->copiedText ? $options->copiedText : 'Đã sao chép!';

        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/line-numbers/prism-line-numbers.min.js"></script>';
        echo '<script>
        window.initMacCodeBlocks = function() {
            var pres = document.querySelectorAll("pre");
            var isNewAdded = false;
            
            pres.forEach(function(pre) {
                if (pre.parentNode.classList.contains("mac-code-block")) return; 
                
                var code = pre.querySelector("code");
                if (!code) return;
                
                isNewAdded = true;
                
                var lang = "CODE";
                var allClasses = pre.className + " " + code.className;
                var langMatch = allClasses.match(/(?:language|lang)-([a-zA-Z0-9_+]+)/i);
                
                if (langMatch && langMatch[1]) {
                    lang = langMatch[1].toUpperCase();
                    var normalizedClass = "language-" + langMatch[1].toLowerCase();
                    if (!pre.classList.contains(normalizedClass)) pre.classList.add(normalizedClass);
                    if (!code.classList.contains(normalizedClass)) code.classList.add(normalizedClass);
                }
                
                pre.classList.add("line-numbers");

                var wrapper = document.createElement("div");
                wrapper.className = "mac-code-block";
                
                var header = document.createElement("div");
                header.className = "mac-header";
                
                var dots = document.createElement("div");
                dots.className = "mac-dots";
                dots.innerHTML = "<span></span><span></span><span></span>";
                
                var langLabel = document.createElement("div");
                langLabel.className = "mac-lang";
                langLabel.innerText = lang;
                
                var btn = document.createElement("button");
                btn.className = "mac-copy-btn";
                btn.innerHTML = \'<svg viewBox="0 0 24 24" width="12" height="12" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg><span>' . $copyTxt . '</span>\';
                
                btn.addEventListener("click", function() {
                    navigator.clipboard.writeText(code.innerText).then(function() {
                        var span = btn.querySelector("span");
                        span.innerText = "' . $copiedTxt . '";
                        btn.style.color = "#27c93f";
                        btn.style.borderColor = "#27c93f";
                        btn.querySelector("svg").style.stroke = "#27c93f";
                        
                        setTimeout(function() {
                            span.innerText = "' . $copyTxt . '";
                            btn.style.color = "#eee";
                            btn.style.borderColor = "rgba(255, 255, 255, 0.2)";
                            btn.querySelector("svg").style.stroke = "currentColor";
                        }, 2000);
                    });
                });

                header.appendChild(dots);
                header.appendChild(langLabel);
                header.appendChild(btn);
                
                pre.parentNode.insertBefore(wrapper, pre);
                wrapper.appendChild(header);
                wrapper.appendChild(pre);

                pre.addEventListener("touchstart", function() {
                    pre.classList.add("is-scrolling");
                }, {passive: true});
                pre.addEventListener("touchend", function() {
                    setTimeout(function() { pre.classList.remove("is-scrolling"); }, 1500);
                });
            });
            
            if (isNewAdded && typeof Prism !== "undefined") {
                Prism.highlightAll();
            }
        };
        
        document.addEventListener("DOMContentLoaded", window.initMacCodeBlocks);
        document.addEventListener("pjax:complete", window.initMacCodeBlocks);
        document.addEventListener("pjax:end", window.initMacCodeBlocks);
        
        var observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length) {
                    for (var i = 0; i < mutation.addedNodes.length; i++) {
                        var node = mutation.addedNodes[i];
                        if (node.nodeType === 1 && (node.tagName === "PRE" || node.querySelector("pre"))) {
                            setTimeout(window.initMacCodeBlocks, 50);
                            return;
                        }
                    }
                }
            });
        });
        observer.observe(document.body, { childList: true, subtree: true });
        </script>';
    }
}
