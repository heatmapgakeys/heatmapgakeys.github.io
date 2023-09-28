<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Home;
use App\Services\SmsManagerServiceInterface;
use App\Services\TelegramServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;

class Guest extends Home
{
    public $telegram;
    public $autoresponder;

    public function __construct()
    {
        $this->set_auth_variables();
    }

    /* http://example.com/script/tme-link.js?code=54848488 */
    public function tme_link_js_code_generator(Request $request)
    {

        if (! headers_sent()) {
            header("Content-Type: application/javascript; charset=utf-8");
        }

        $code = $request->code;
        if($code=="")
        {
            echo "console.error('Error : t.me link plugin is failed to load, no unique code found.');";
            exit();
        }

        $plugin_data = DB::table('telegram_bot_engagement_tme_links')->where(['link_code'=>$code])->first();
        if(empty($plugin_data))
        {
            echo "console.error('Error : t.me link plugin is failed to load, invalid unique code.');";
            exit();
        }

        $user_id = $plugin_data->user_id;
        $user_data = DB::table('users')->select('is_default','parent_user_id')
            ->where(['users.id'=>$user_id,'users.status'=>'1'])
            ->leftJoin('packages','users.package_id','=','packages.id')
            ->first();
        if(empty($user_data))
        {
            echo "console.error('Error : t.me link plugin is failed to load, the requesting user is no longer valid.');";
            exit();
        }

        $agency_config = initiate_agency_config(false,true);
        $is_default = $user_data->is_default ?? '0';
        $brand_app_name = $agency_config['app_name'] ?? config('app.name');
        $agent_domain = isset($agency_config['agent_domain']) ? 'https://'.$agency_config['agent_domain'] : url('/');
        $brand_app_link = '<a target="_BLANK" href="' . $agent_domain . '">' . $brand_app_name . '</a>';

        $brand_powered_by_text = '';
        if($is_default=='1' || $user_id===1 || $user_id==get_agent_id())
        $brand_powered_by_text = '<div class="xit-widget-tele-modal-brand">&copy;'.__('Powered by').' '.$brand_app_link.'</div>';

        switch ($plugin_data->btn_size)
        {
            case 'small':
                $fontsize='12';
                $paddingtop='7';
                $paddingleft='9';
                $radius='3';
                break;

            case 'medium':
                $fontsize='14';
                $paddingtop='9';
                $paddingleft='12';
                $radius='5';
                break;

            case 'large':
                $fontsize='15';
                $paddingtop='11';
                $paddingleft='17';
                $radius='10';
                break;

            case 'xlarge':
                $fontsize='16';
                $paddingtop='14';
                $paddingleft='22';
                $radius='10';
                break;

            default:
                $fontsize='14';
                $paddingtop='9';
                $paddingleft='12';
                $radius='5';
                break;
        }
        $paddingtop+=2;
        $paddingtop+=2;
        $radius=11.2;

        $html_content='';
        $style1='.xit-widget-tele-wrapper .button{text-decoration:none;border:none;border-radius:'.$radius.'px;-moz-border-radius:'.$radius.'px;-webkit-border-radius:'.$radius.'px;cursor:pointer;text-align:center;background:'.$plugin_data->new_button_bg_color.';color:'.$plugin_data->new_button_color.';font-size:'.$fontsize.'px;padding:'.$paddingtop.'px '.$paddingleft.'px;margin:0 20px;display:block;font-family: "PTSans", sans-serif;}';
        $style2='.xit-widget-tele-wrapper .button:hover{background:'.$plugin_data->new_button_bg_color_hover.';color:'.$plugin_data->new_button_color_hover.';}>';
        $style3='.xit-widget-tele-wrapper .button:focus{outline:none}';
        $style4='.xit-widget-tele-modal-header {background-color:'.$plugin_data->chatbox_bg_color.';color:'.$plugin_data->chatbox_text_color.'}';

        $page_info = DB::table('telegram_bots')->where(['id'=>$plugin_data->telegram_bot_id,'status'=>'1'])->first();
        if(empty($page_info))
        {
            echo "console.error('Error : t.me link plugin is failed to load, the bot is no longer valid.');";
            exit();
        }
        $username = $page_info->username ?? '';

        $href='https://t.me/'.$username.'?start='.urlencode($plugin_data->reference);

        $init_delay = isset($plugin_data->init_delay)
            ? ($cast_init_delay = (int) $plugin_data->init_delay <= 0)
                ? 1
                : $cast_init_delay
            : 1;
?>
"use strict";

const widgetPosition = '<?php echo $plugin_data->chatbox_position;?>';
let widgetPositionOffsetX = <?php echo $plugin_data->xoffset;?>;
let widgetPositionOffsetY = <?php echo $plugin_data->yoffset;?>;
let widgetPoweredbyText = '<?php echo $brand_powered_by_text;?>';
const widgetVisisbility = 99999999;
const modalBodyHtml = '<div class="xit-widget-tele-modal-header"><?php echo $plugin_data->chatbox_display_name;?></div><p class="xit-widget-tele-modal-welcome"><?php echo !empty($plugin_data->chatbox_welcome_msg) ? $plugin_data->chatbox_welcome_msg : __('Hi there, how can we help?');?></p><a class="button" target="_BLANK" href="<?php echo $href;?>"><?php echo $plugin_data->new_button_display;?></a>'+widgetPoweredbyText;

function executionHandler() {
    setTimeout(function() {
        addStyleLink('<?php echo asset('assets/css/widget-telegram.css'); ?>');
        addStyle('<?php echo $style1; ?>');
        addStyle('<?php echo $style2; ?>');
        addStyle('<?php echo $style3; ?>');
        addStyle('<?php echo $style4; ?>');

        createButtonWithModal(modalBodyHtml);
    }, <?php echo $init_delay * 1000; ?>);

    setTimeout(function() {
        const modalElement = document.getElementById('xit-widget-tele-modal');
        modalElement.style.display = '<?php echo $plugin_data->chatbox_visibility == '1' ? 'block' : 'none' ?>';
    }, <?php echo $init_delay * 1100 ?>);
}

function addStyleLink(url) {
    var head = document.head;
    var link = document.createElement("link");

    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;

    head.appendChild(link);
}

function addStyle(css_content) {
    var head = document.head;
    var style_tag = document.createElement("style");
    style_tag.textContent = css_content;
    head.appendChild(style_tag);
}

function createButtonWithModal(modalBodyHtml) {
    const wrapper = document.createElement('div');
    wrapper.className = 'xit-widget-tele-wrapper';

    const floatingButton = document.createElement('div');
    floatingButton.className = 'xit-widget-tele-button';
    floatingButton.addEventListener("click", toggleModal);

    fixWidgetPosition(floatingButton, null);
    floatingButton.style.zIndex = parseInt(widgetVisisbility, 10);

    wrapper.appendChild(floatingButton);

    const modal = createModal(modalBodyHtml);
    wrapper.appendChild(modal);

    document.body.appendChild(wrapper);
}

function toggleModal() {
    const element = document.getElementById('xit-widget-tele-modal');
    if (isHidden(element)) {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

function isHidden(el) {
    const style = window.getComputedStyle(el);
    return (style.display === 'none');
}

function createModal(html) {
    const modal = document.createElement('div');
    modal.style.display = 'none';
    modal.id = 'xit-widget-tele-modal';

    fixWidgetPosition(modal, 'modal')
    modal.style.zIndex = parseInt(widgetVisisbility, 10);

    const child = document.createElement('div');
    child.innerHTML = html;
    child.className = 'xit-widget-tele-modal-content';

    modal.appendChild(child);

    return modal;
}

function fixWidgetPosition(element, type) {
    let {
        position,
        widgetPositionOffsetX: offsetX,
        widgetPositionOffsetY: offsetY
    } = getValidPositionsAndOffsets();

    offsetY = ('modal' === type) ? offsetY + 80 : offsetY;

    if ('leftTop' === position) {
        element.style.top = offsetY + 'px';
        element.style.left = offsetX + 'px';
    } else if ('leftBottom' === position) {
        element.style.left = offsetX + 'px';
        element.style.bottom = offsetY + 'px';
    } else if ('rightTop' === position) {
        element.style.top = offsetY + 'px';
        element.style.right = offsetX + 'px';
    } else if ('rightBottom' === position) {
        element.style.right = offsetX + 'px';
        element.style.bottom = offsetY + 'px';
    }
}

function getValidPositionsAndOffsets() {
    const positions = ['leftTop', 'leftBottom', 'rightTop', 'rightBottom'];
    const position = (positions.indexOf(widgetPosition) > -1) ? widgetPosition : 'rightBottom';

    widgetPositionOffsetX = parseInt(widgetPositionOffsetX, 10) < 0
        ? 50
        : parseInt(widgetPositionOffsetX, 10)

    widgetPositionOffsetY = parseInt(widgetPositionOffsetY, 10) < 0
        ? 50
        : parseInt(widgetPositionOffsetY, 10)

    return {
        position,
        widgetPositionOffsetX,
        widgetPositionOffsetY,
    }
}

(function() {
    (document.readyState !== 'loading')
        ? executionHandler()
        : document.addEventListener('DOMContentLoaded', function() {
            executionHandler();
        });
})();

<?php }

    /* http://example.com/script/wa-link.js?code=54848488 */
    public function wa_link_js_code_generator(Request $request)
    {

        if (! headers_sent()) {
            header("Content-Type: application/javascript; charset=utf-8");
        }

        $code = $request->code;
        if($code=="")
        {
            echo "console.error('Error : t.me link plugin is failed to load, no unique code found.');";
            exit();
        }

        $plugin_data = DB::table('whatsapp_bot_engagement_short_links')->where(['link_code'=>$code])->first();
        if(empty($plugin_data))
        {
            echo "console.error('Error : WhatsApp short-link plugin is failed to load, invalid unique code.');";
            exit();
        }

        $user_id = $plugin_data->user_id;
        $user_data = DB::table('users')->select('is_default','parent_user_id')
            ->where(['users.id'=>$user_id,'users.status'=>'1'])
            ->leftJoin('packages','users.package_id','=','packages.id')
            ->first();
        if(empty($user_data))
        {
            echo "console.error('Error : WhatsApp short-link link plugin is failed to load, the requesting user is no longer valid.');";
            exit();
        }

        $agency_config = initiate_agency_config(false,true);
        $is_default = $user_data->is_default ?? '0';
        $brand_app_name = $agency_config['app_name'] ?? config('app.name');
        $agent_domain = isset($agency_config['agent_domain']) ? 'https://'.$agency_config['agent_domain'] : url('/');
        $brand_app_link = '<a target="_BLANK" href="' . $agent_domain . '">' . $brand_app_name . '</a>';

        $brand_powered_by_text = '';
        if($is_default=='1' || $user_id===1 || $user_id==get_agent_id())
        $brand_powered_by_text = '<div class="xit-widget-tele-modal-brand">&copy;'.__('Powered by').' '.$brand_app_link.'</div>';

        switch ($plugin_data->btn_size)
        {
            case 'small':
                $fontsize='12';
                $paddingtop='7';
                $paddingleft='9';
                $radius='3';
                break;

            case 'medium':
                $fontsize='14';
                $paddingtop='9';
                $paddingleft='12';
                $radius='5';
                break;

            case 'large':
                $fontsize='15';
                $paddingtop='11';
                $paddingleft='17';
                $radius='10';
                break;

            case 'xlarge':
                $fontsize='16';
                $paddingtop='14';
                $paddingleft='22';
                $radius='10';
                break;

            default:
                $fontsize='14';
                $paddingtop='9';
                $paddingleft='12';
                $radius='5';
                break;
        }
        $paddingtop+=2;
        $paddingtop+=2;
        $radius=11.2;

        $html_content='';
        $style1='.xit-widget-wa-wrapper .button{text-decoration:none;border:none;border-radius:'.$radius.'px;-moz-border-radius:'.$radius.'px;-webkit-border-radius:'.$radius.'px;cursor:pointer;text-align:center;background:'.$plugin_data->new_button_bg_color.';color:'.$plugin_data->new_button_color.';font-size:'.$fontsize.'px;padding:'.$paddingtop.'px '.$paddingleft.'px;margin:0 20px;display:block;font-family: "PTSans", sans-serif;}';
        $style2='.xit-widget-wa-wrapper .button:hover{background:'.$plugin_data->new_button_bg_color_hover.';color:'.$plugin_data->new_button_color_hover.';}>';
        $style3='.xit-widget-wa-wrapper .button:focus{outline:none}';
        $style4='.xit-widget-wa-modal-header {background-color:'.$plugin_data->chatbox_bg_color.';color:'.$plugin_data->chatbox_text_color.'}';

        $page_info = DB::table('whatsapp_bots')->where(['id'=>$plugin_data->whatsapp_bot_id,'status'=>'1'])->first();
        if(empty($page_info))
        {
            echo "console.error('Error : WhatsApp short-link link plugin is failed to load, the bot is no longer valid.');";
            exit();
        }
        $username = $page_info->display_phone_number ?? '';
        $username = str_replace(' ','',$username);
        $text = '/start:'.$plugin_data->reference.'/';
        $chatbox_prefill_msg = $plugin_data->chatbox_prefill_msg ?? '';
        $chatbox_prefill_msg = urlencode($chatbox_prefill_msg).$text;

        $href = is_mobile_device() ? 'whatsapp://send/?phone='.$username.'&text='.$chatbox_prefill_msg : 'https://wa.me/'.str_replace(['+','-',' '],'',$username).'?text='.$chatbox_prefill_msg;

        $init_delay = isset($plugin_data->init_delay)
            ? ($cast_init_delay = (int) $plugin_data->init_delay <= 0)
                ? 1
                : $cast_init_delay
            : 1;
?>
"use strict";

const widgetPositionWa = '<?php echo $plugin_data->chatbox_position;?>';
let widgetPositionOffsetXWa = <?php echo $plugin_data->xoffset;?>;
let widgetPositionOffsetYWa = <?php echo $plugin_data->yoffset;?>;
let widgetPoweredbyTextWa = '<?php echo $brand_powered_by_text;?>';
const widgetVisisbilityWa = 99999999;
const modalBodyHtmlWa = '<div class="xit-widget-wa-modal-header"><?php echo $plugin_data->chatbox_display_name;?></div><p class="xit-widget-wa-modal-welcome"><?php echo !empty($plugin_data->chatbox_welcome_msg) ? $plugin_data->chatbox_welcome_msg : __('Hi there, how can we help?');?></p><a class="button" target="_BLANK" href="<?php echo $href;?>"><?php echo $plugin_data->new_button_display;?></a>'+widgetPoweredbyTextWa;

function executionHandlerWa() {
setTimeout(function() {
    addStyleLinkWa('<?php echo asset('assets/css/widget-whatsapp.css'); ?>');
    addStyleWa('<?php echo $style1; ?>');
    addStyleWa('<?php echo $style2; ?>');
    addStyleWa('<?php echo $style3; ?>');
    addStyleWa('<?php echo $style4; ?>');

    createButtonWithModalWa(modalBodyHtmlWa);
}, <?php echo $init_delay * 1000; ?>);

setTimeout(function() {
    const modalElement = document.getElementById('xit-widget-wa-modal');
    modalElement.style.display = '<?php echo $plugin_data->chatbox_visibility == '1' ? 'block' : 'none' ?>';
    }, <?php echo $init_delay * 1100 ?>);
}

function addStyleLinkWa(url) {
    var head = document.head;
    var link = document.createElement("link");

    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;

    head.appendChild(link);
}

function addStyleWa(css_content) {
    var head = document.head;
    var style_tag = document.createElement("style");
    style_tag.textContent = css_content;
    head.appendChild(style_tag);
}

function createButtonWithModalWa(modalBodyHtmlWa) {
    const wrapper = document.createElement('div');
    wrapper.className = 'xit-widget-wa-wrapper';

    const floatingButton = document.createElement('div');
    floatingButton.className = 'xit-widget-wa-button';
    floatingButton.addEventListener("click", toggleModalWa);

    fixWidgetPositionWa(floatingButton, null);
    floatingButton.style.zIndex = parseInt(widgetVisisbilityWa, 10);

    wrapper.appendChild(floatingButton);

    const modal = createModalWa(modalBodyHtmlWa);
    wrapper.appendChild(modal);

    document.body.appendChild(wrapper);
}

function toggleModalWa() {
    const element = document.getElementById('xit-widget-wa-modal');
    if (isHiddenWa(element)) {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

function isHiddenWa(el) {
    const style = window.getComputedStyle(el);
    return (style.display === 'none');
}

function createModalWa(html) {
    const modal = document.createElement('div');
    modal.style.display = 'none';
    modal.id = 'xit-widget-wa-modal';

    fixWidgetPositionWa(modal, 'modal')
    modal.style.zIndex = parseInt(widgetVisisbilityWa, 10);

    const child = document.createElement('div');
    child.innerHTML = html;
    child.className = 'xit-widget-wa-modal-content';

    modal.appendChild(child);

    return modal;
}

function fixWidgetPositionWa(element, type) {
    let {
        positionWa,
        widgetPositionOffsetXWa: offsetXWa,
        widgetPositionOffsetYWa: offsetYWa
    } = getValidPositionsAndOffsetsWa();

    offsetYWa = ('modal' === type) ? offsetYWa + 80 : offsetYWa;

    if ('leftTop' === positionWa) {
        element.style.top = offsetYWa + 'px';
        element.style.left = offsetXWa + 'px';
    } else if ('leftBottom' === positionWa) {
        element.style.left = offsetXWa + 'px';
        element.style.bottom = offsetYWa + 'px';
    } else if ('rightTop' === positionWa) {
        element.style.top = offsetYWa + 'px';
        element.style.right = offsetXWa + 'px';
    } else if ('rightBottom' === positionWa) {
        element.style.right = offsetXWa + 'px';
        element.style.bottom = offsetYWa + 'px';
    }
}

function getValidPositionsAndOffsetsWa() {
    const positions = ['leftTop', 'leftBottom', 'rightTop', 'rightBottom'];
    const positionWa = (positions.indexOf(widgetPositionWa) > -1) ? widgetPositionWa : 'rightBottom';

    widgetPositionOffsetXWa = parseInt(widgetPositionOffsetXWa, 10) < 0
    ? 50
    : parseInt(widgetPositionOffsetXWa, 10)

    widgetPositionOffsetYWa = parseInt(widgetPositionOffsetYWa, 10) < 0
    ? 50
    : parseInt(widgetPositionOffsetYWa, 10)

    return {
        positionWa,
        widgetPositionOffsetXWa,
        widgetPositionOffsetYWa,
    }
}

(function() {
    (document.readyState !== 'loading')
    ? executionHandlerWa()
    : document.addEventListener('DOMContentLoaded', function() {
        executionHandlerWa();
    });
})();
<?php }

}
