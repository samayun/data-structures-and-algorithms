<?php

if ($db) {
    $Web = new Web();
    $Login = new Login();

    if ($Login->is_user_loggedin()) {
        $email = $Login->loginUser();
        $LogUser = new User($email);
    }

    if ($Login->is_admin_loggedin()) {
        $email = $Login->loginAdmin();
        $LogAdmin = new User($email);
    }
}


class CsException extends \Exception
{
    public function getError()
    {
        return $this->getMessage();
    }
}

class Functions
{ 
    public $users_tbl = "users";
    public $otp_tbl = "otps";
    public $setting_tbl = "setting";
    public $login_session_tbl = "login_session";
    public $files_tbl = "images";
    public $check_google_signup_tbl = 'check_google_signup';
    public $transactions_tbl = 'transactions';
    public $ecommerce_category_tbl = "ecommerce_category";
    public $ecommerce_category_details_tbl = "ecommerce_category_details";
    public $ecommerce_select_tbl = "ecommerce_select";
    public $ecommerce_listing_tbl = "ecommerce_listing";
    public $ecommerce_listing_variations_tbl = "ecommerce_listing_variations";
    public $ecommerce_product_questions_tbl = "ecommerce_product_questions_tbl";
    public $ecommerce_product_answers_tbl = "ecommerce_product_answers_tbl";
    public $ecommerce_answer_rating_tbl = "ecommerce_answer_rating";
    public $ecommerce_reviews_tbl = "ecommerce_reviews_tbl";
    public $ecommerce_reviews_rating_tbl = "ecommerce_reviews_rating";
    public $ecommerce_courier_tbl = "ecommerce_courier";
    public $ecommerce_qc_tbl = "ecommerce_qc";
    public $ecommerce_products_tbl = "ecommerce_products";
    public $ecommerce_variations_tbl = "ecommerce_variations";
    public $ecommerce_cart_tbl = "ecommerce_user_cart";
    public $ecommerce_orders_tbl = "ecommerce_orders";
    public $ecommerce_search_history_tbl = "ecommerce_user_search_history";
    public $ecommerce_product_views_tbl = "ecommerce_product_views";
    public $ecommerce_seller_users_tbl = "seller_users";
    public $ecommerce_seller_verification_tbl = "ecommerce_seller_verification";
    public $ecommerce_search_relavance_tbl = "ecommerce_search_relavance";
    public $ecommerce_withdraw_gateways_tbl = "ecommerce_withdraw_gateways";
    public $ecommerce_sellers_withdraw_gateways_tbl = "ecommerce_users_withdraw_gateways";
    public $ecommerce_sellers_withdraw_tbl = "ecommerce_sellers_withdraw";
    public $ecommerce_return_orders_tbl = "ecommerce_return_orders";
    public $ecommerce_replacement_orders_tbl = "ecommerce_replacement_orders";
    public $ecommerce_service_tbl = "ecommerce_service_charges";
    public $ecommerce_order_transactions_tbl = "ecommerce_order_transactions";
    public $ecommerce_address_tbl = "user_billing_addresses";
    public $payment_methods_tbl = "payment_methods";
    public $support_tickets_tbl = "support_tickets";
    public $support_ticket_messages_tbl = "support_ticket_messages";
    public $components_tbl = "sliders_banners";
    public $layouts_tbl = "layouts";

    // *** ---------- Basic Functions Start ----- ***** \\

    public function db()
    {
        global $db;
        return $db;
    }

    public function base_url()
    {
        $url = Basics::$base_url;
        return $url;
    }

    public function seller_url()
    {
        return Basics::$seller_url;
    }

    public function absolute_url()
    {
        return Basics::$absolute_base_url;
    }

    public function seller_absolute_url()
    {
        return Basics::$seller_absolute_url;
    }

    public function admin_url()
    {
        return Basics::$admin_url;
    }

    public  function meta_title()
    {
    }

    public  function meta_keywords()
    {
    }

    public  function meta_description()
    {
    }

    public function include($url)
    {
        return ROOT . "/assets/$url";
    }

    public function locate_to($url)
    {
        if (empty($url)) $url = "/";
        header("location:$url");
        exit();
    }

    public function current_year()
    {
        return date("Y");
    }

    public  function host()
    {
        return parse_url($this->absolute_url(), PHP_URL_HOST);
    }

    public  function get_domain()
    {
        $parse = parse_url($this->absolute_url());
        return  $parse['host'];
    }

    public  function max_mobile_length()
    {
        return 10;
    }

    public function sanitize_text($string)
    {

        if (is_array($string)) {
            foreach ($string as $key => $value) {
                $string[$key] = $this->sanitize_text($value);
            }
            return $string;
        }

        if (!empty($string)) {
            $string = trim(htmlspecialchars($string));
        }
        return $string;
    }

    public  function unsanitize_text($string)
    {
        if (empty($string)) return;
        // $string = htmlspecialchars_decode($string);
        $string = str_replace('\r\n', "\r\n", $string);
        $string = str_replace('\r', "\r\n", $string);
        $string = str_replace('\n', "\r\n", $string);
        $string = stripslashes($string);
        return $string;
    }


    public function is_empty()
    {
        $strings = func_get_args();
        $output = false;
        foreach ($strings as $string) {
            if (is_array($string)) {
                if (empty($string)) {
                    $output =  true;
                }
            } else {
                $string = $this->sanitize_text($string);
                if (($string != '') && ($string != "undefined") && ($string != null) && (!empty($string))) {
                } elseif ($string == '0') {
                } else {
                    $output =  true;
                }
            }
        }
        return $output;
    }

    public  function is_isset()
    {
        $requests =  func_get_args();
        $output = true;
        foreach ($requests as $request) {
            if (!isset($_POST[$request])) {
                $output = false;
            }
        }
        return $output;
    }

    public function get_assets($file, $absolute = false)
    {
        $base_url = $absolute ? $this->absolute_url() : $this->base_url();
        return $base_url . '/assets/' . $file;
    }

    public function validate_post_input($input, $type, $input_name, $is_required)
    {
        switch ($type) {

            case 'number':
                if (!preg_match("/^[0-9]*$/", $input)) {
                    Errors::response("$input_name has invalid characters");
                }
                break;

            case 'alpha':
                if (!preg_match("/^[a-zA-Z ]*$/", $input)) {
                    Errors::response("$input_name has invalid characters");
                }
                break;

            case 'alpha_numeric':
                if (!preg_match("/^[a-zA-Z0-9 ]*$/", $input)) {
                    Errors::response("$input_name has invalid characters");
                }
                break;

            case 'decimal_numeric':
                if (!preg_match("/^[0-9. ]*$/", $input)) {
                    Errors::response("$input_name has invalid characters");
                }
                break;

            case 'mobile_number':
                if (!preg_match("/^[0-9]*$/", $input)) {
                    Errors::response("$input_name has invalid characters");
                }
                if (strlen($input) != $this->max_mobile_length()) {
                    Errors::response("Invalid mobile number");
                }
                break;

            case 'postcode':
                if (!preg_match("/^[0-9]*$/", $input)) {
                    Errors::response("$input_name has invalid characters");
                }
                if (strlen($input) != 6) {
                    Errors::response("Invalid postcode length");
                }
                break;

            case 'email':
                if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                    Errors::response("$input_name format is invalid");
                }
                break;

            case "url":
                if (!filter_var(
                    $input,
                    FILTER_VALIDATE_URL
                )) {
                    Errors::response("$input_name format is invalid");
                }
                break;

            default:
                break;
        }

        if ($is_required && $this->is_empty($input)) {
            Errors::response("$input_name is required");
        }
    }

    public  function check_web_validation()
    {
        $headers = apache_request_headers();
        $host = $headers["Host"];
        if ($host !== $this->host()) {
            Errors::response_404();
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $refer_url = $headers["Referer"];
            $refer_host = parse_url($refer_url, PHP_URL_HOST);
            if ($refer_host !== $this->host()) {
                Errors::response_404();
            }
        }
    }


    public  function request_url()
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function is_file_id($file_id)
    {
        $sql = $this->db()->prepare("SELECT * FROM $this->files_tbl WHERE file_id = ? ");
        $sql->bindParam(1, $file_id);
        $sql->execute();
        return $sql->rowCount() ? true : false;
    }

    public function get_file_src($file_id, $absolute = false)
    {
        $sql = $this->db()->prepare("SELECT * FROM $this->files_tbl WHERE file_id = ? ");
        $sql->execute([$file_id]);
        if (!$sql->rowCount())  return;

        $row = $sql->fetch();
        $file_src = $row->file_src;
        $type = $row->type;
        if ($type == "file") return $this->get_assets("files/" . $file_src, $absolute);
        if ($type == "web") return $this->get_assets("images/web/" . $file_src, $absolute);
        if ($type == "url") return $file_src;
    }

    public function file_preview_url($file_id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM $this->files_tbl WHERE file_id = ? ");
        $stmt->execute([$file_id]);
        if (!$stmt->rowCount())  return;

        $row = $stmt->fetch();
        $file_src = $row->file_src;
        $type = $row->type;
        $extension = pathinfo($file_src, PATHINFO_EXTENSION);
        if (!in_array($extension, ["png", "webp", "jpg", "jpeg"])) {
            return $this->get_assets("images/web/file.png");
        } else {
            if ($type == "file") return $this->get_assets("files/" . $file_src);
            if ($type == "web") return $this->get_assets("images/web/" . $file_src);
            if ($type == "url") return $file_src;
        }
    }

    public function get_file_name($file_id)
    {
        $sql = $this->db()->prepare("SELECT * FROM $this->files_tbl WHERE file_id = ? ");
        $sql->execute([$file_id]);
        if (!$sql->rowCount())  return;

        $row = $sql->fetch();
        $file_name = $row->file_name;
        $file_name = $this->unsanitize_text($file_name);
        return $file_name;
    }

    public function get_file_ext($file_src)
    {
        $extension = pathinfo($file_src, PATHINFO_EXTENSION);
        return $extension;
    }

    public function get_file_data($id)
    {
        $file_src = $this->get_file_src($id);
        $output = new stdClass;
        $output->id = $id;
        $output->name = basename($this->get_file_src($id));
        $output->url = $this->get_file_src($id);
        $output->preview_url = $this->file_preview_url($id);
        $output->ext = $this->get_file_ext($this->get_file_src($id));
        $output->showExt = FileUpload::is_image($this->get_file_src($id));
        return $output;
    }

    public function date_time($date)
    {
        if ($this->is_empty($date)) return;
        return date("d F Y H:i:s", $date);
    }

    public  function to_date($date)
    {
        if ($this->is_empty($date)) return;
        return date("d F Y", $date);
    }

    public  function short_date($date)
    {
        if ($this->is_empty($date)) return;
        return date("D, j M y", $date);
    }

    public  function sluggify($url)
    {
        # Prep string with some basic normalization
        $url = strtolower($url);
        $url = strip_tags($url);
        $url = stripslashes($url);
        $url = html_entity_decode($url);

        # Remove quotes (can't, etc.)
        $url = str_replace('\'', '', $url);

        # Replace non-alpha numeric with hyphens
        $match = '/[^a-z0-9]+/';
        $replace = '-';
        $url = preg_replace($match, $replace, $url);

        $url = trim($url, '-');

        return $url;
    }

    public  function int_with_suffix($input)
    {
        $suffixes = array('', 'K', 'M', 'B', 'T');
        $suffixIndex = 0;

        while (abs($input) >= 1000 && $suffixIndex < sizeof($suffixes)) {
            $suffixIndex++;
            $input /= 1000;
        }

        return $this->currency_format($input) . $suffixes[$suffixIndex];
    }
    public function currency_format($value)
    {
        $value = (float)$value;
        return (float) number_format($value, 2, '.', '');
    }

    public function time_to_Ago($datetime, $full = false)
    {
        $datetime = $this->date_time($datetime);
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public function status_to_label($text, $status, $class = false)
    {
        return '<div class="badge ' . $class . ' badge-light-' . $status . '">' . $text . '</div>';
    }


    public function unique_id()
    {
        $rand = rand(1111111, 9999999);
        $time = time();
        return $rand . $time;
    }

    public function get_uuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
    function to_decimal($number, $digits = false)
    {
        $digits = $digits == false ? 2 : $digits;
        return number_format((float)$number, $digits, '.', '');
    }
    // *** ---------- Basic Functions End ----- ***** \\



    function insert_pagination($page_url, $page, $total_pages, $prev_next = false, $url_seperator = false, $attr = false)
    {
        $ends_count = 1;
        $middle_count = 2;
        $dots = false;
        $url_seperator = $url_seperator === false ? '&' : $url_seperator;

        // echo $page_url;

        $output = '
        <ul class="pagination py-4">
        ';
        if ($prev_next && $page && 1 < $page) {
            $url = $page_url . $url_seperator . 'page=' . ($page - 1);
            $output .= ' <li class="paginate_button page-item previous"><a ' . $attr . ' data-page="' . ($page - 1) . '" class="page-link" href=\'' . $url . '\' ><i class="previous"></i></a></li>';
        }
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i == $page) {
                $output .= '<li class="paginate_button page-item active" ><a data-page="' . $i . '"  class="page-link">' . $i . '</a></lic>';
                $dots = true;
            } else {
                if ($i <= $ends_count || ($page && $i >= $page - $middle_count && $i <= $page + $middle_count) || $i > $total_pages - $ends_count) {
                    $url = $page_url . $url_seperator . 'page=' . $i;
                    $output .= '<li class="paginate_button page-item " ><a ' . $attr . ' data-page="' . $i . '"  class="text-primary-alt page-link" href=\'' . $url . '\' >' . $i . '</a></lic>';
                    $dots = true;
                } elseif ($dots) {
                    $output .= ' <li class="paginate_button page-item " ><a>…</a></li>';
                    $dots = false;
                }
            }
        }
        if ($prev_next && $page && ($page < $total_pages || -1 == $total_pages)) {
            $url = $page_url . $url_seperator . 'page=' . ($page + 1);
            $output .= '<li class="paginate_button page-item next"><a ' . $attr . ' data-page="' . ($page + 1) . '"  class="page-link"  href=\'' . $url . '\' ><i class="next" ></i></a></li>';
        }
        $output .= '
        </ul>
        ';
        return $output;
    }

    public function email_setting($error = true)
    {
        $row = new stdClass;
        $stmt = $this->db()->query("SELECT * FROM $this->setting_tbl");
        if ($error && !$stmt->rowCount()) Errors::response("Please check out the Email Setting");
        $row = $stmt->fetch();
        return $row;
    }

    public function validate_post_length($text, $max_limit, $error_text)
    {
       // FIXME: SAM
       // BUG: if (strlen(utf8_decode($text)) > $max_limit) Errors::response($error_text);
       if (strlen($text) > $max_limit) Errors::response($error_text);
    }
}

class Setting extends Functions
{
    private $row;

    function __construct()
    {
        $this->row = $this->row();
    }

    private function row()
    {
        if (!empty($this->row)) return $this->row;
        $stmt = $this->db()->query("SELECT * FROM $this->setting_tbl");
        return $stmt->fetch();
    }

    public function web_name()
    {
        $row = $this->row();
        $web_name = $row->web_name ?? Basics::$web_name;
        return $web_name;
    }

    public function currency()
    {
        $row = $this->row();
        $currency = $row->currency ?? Basics::$currency;
        return $currency;
    }

    public function formatCurrency($text)
    {
        if ($text < 0) {
            $text = -$text;
            $text = $this->currency_format($text);
            $text = "-" . $this->currency() . $text;
            return $text;
        }
        return $this->currency() . $this->currency_format($text);
    }

    public function timezone()
    {
        $row = $this->row();
        $timezone = $row->timezone ?? Basics::$timezone;
        return $timezone;
    }

    public function current_date()
    {
        $current_date = strtotime(date("d-m-Y"));
        $row = $this->row();
        $setting_date = $row->date ?? 0;
        $setting_date = (int) $setting_date;
        return $current_date - $setting_date;
    }

    public function current_time()
    {
        $current_time = $this->current_date() + (strtotime(date("d-m-Y H:i:s")) - strtotime(date("d-m-Y")));
        return $current_time;
    }

    public function real_current_date()
    {
        return strtotime(date("d-m-Y"));
    }

    public function real_current_time()
    {
        return strtotime(date("d-m-Y H:i:s"));
    }

    public function currency_position()
    {
        $row = $this->row();
        $currency_position = $row->currency_position ?? Basics::$currency_position;
        return $currency_position;
    }

    public function primary_color()
    {
        $row = $this->row();
        $primary_color = $row->primary_color ?? Basics::$primary_color;
        return $primary_color;
    }

    function rgb_to_hex($rgb)
    {
        $rgb = explode(",", $rgb);
        $color = sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
        return $color;
    }

    function hex_to_rgb($hex)
    {
        list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
        return "$r,$g,$b";
    }

    public function logos()
    {
        $output = new stdClass;

        $row = $this->row();
        $logos = $row->web_logos ?? '[]';
        $logos = json_decode($logos, true);
        $output->logo_id = empty($logos["logo_id"]) ? 0 : $logos["logo_id"];
        $output->favicon_id = empty($logos["favicon_id"]) ? 0 : $logos["favicon_id"];
        $output->seller_logo_id = empty($logos["seller_logo_id"]) ? 0 : $logos["seller_logo_id"];
        $output->seller_favicon_id = empty($logos["seller_favicon_id"]) ? 0 : $logos["seller_favicon_id"];
        $output->admin_logo_id = empty($logos["admin_logo_id"]) ? 0 : $logos["admin_logo_id"];
        $output->admin_favicon_id = empty($logos["admin_favicon_id"]) ? 0 : $logos["admin_favicon_id"];
        return $output;
    }

    public function logo()
    {
        $logos = $this->logos();
        $logo_id = $logos->logo_id;
        return empty($logo_id) ? $this->get_assets("images/web/logo.png", true) : $this->get_file_src($logo_id, true);
    }

    public function favicon()
    {
        $logos = $this->logos();
        $favicon_id = $logos->favicon_id;
        return empty($favicon_id) ? $this->get_assets("images/web/favicon.png") : $this->get_file_src($favicon_id);
    }
    public function seller_logo()
    {
        $logos = $this->logos();
        $seller_logo_id = $logos->seller_logo_id;
        return empty($seller_logo_id) ? $this->get_assets("images/web/seller_logo.png") : $this->get_file_src($seller_logo_id);
    }
    public function seller_favicon()
    {
        $logos = $this->logos();
        $seller_favicon_id = $logos->seller_favicon_id;
        return empty($seller_favicon_id) ? $this->get_assets("images/web/favicon.png") : $this->get_file_src($seller_favicon_id);
    }
    public function admin_logo()
    {
        $logos = $this->logos();
        $admin_logo_id = $logos->admin_logo_id;
        return empty($admin_logo_id) ? $this->get_assets("images/web/admin_logo.png") : $this->get_file_src($admin_logo_id);
    }
    public function admin_favicon()
    {
        $logos = $this->logos();
        $admin_favicon_id = $logos->admin_favicon_id;
        return empty($admin_favicon_id) ? $this->get_assets("images/web/favicon.png") : $this->get_file_src($admin_favicon_id);
    }

    private static function  format_GMT_offset($offset)
    {
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }

    private static function format_timezone_name($name)
    {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);
        return $name;
    }

    public static function timezone_list()
    {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new DateTime('now', new DateTimeZone('UTC'));

            foreach (DateTimeZone::listIdentifiers() as $timezone) {
                $now->setTimezone(new DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = '(' . self::format_GMT_offset($offset) . ') ' . self::format_timezone_name($timezone);
            }

            array_multisort($offsets, $timezones);
        }

        return $timezones;
    }
}

class Web extends Setting
{
    public $last_updated;
    public $testingEmail;
    public $testingEmailPassword;

     function __construct()
    {
     $this->last_updated = Basics::$last_updated;   
     $this->testingEmail = Basics::$testingEmail;   
     $this->testingEmailPassword = Basics::$testingEmailPassword;   
    }

    public function is_profile_tab_active($tab)
    {
        global $active_profile_tab;
        if ($active_profile_tab == $tab) return " active ";
    }

    public function is_file_id($file_id)
    {
        $stmt = $this->db()->prepare("SELECT file_id FROM $this->files_tbl WHERE file_id = ? ");
        $stmt->execute([$file_id]);
        if ($stmt->rowCount()) return true;
        return false;
    }


    public function addSearchHistory($text)
    {
        if (empty($text)) return;
        global $Login;
        if ($Login->is_user_loggedin()) {
            global $LogUser;
            $stmt = $this->db()->prepare("SELECT * FROM $this->ecommerce_search_history_tbl WHERE search_text = ? AND user_id = ? ");
            $stmt->execute([$text, $LogUser->user_id]);

            if (!$stmt->rowCount()) {
                $stmt = $this->db()->prepare("INSERT INTO $this->ecommerce_search_history_tbl (`search_text`, `user_id`, `last_updated`)  VALUES (?,?,?)");
                $stmt->execute([$text, $LogUser->user_id, $this->current_time()]);
            } else {
                $stmt = $this->db()->prepare("UPDATE $this->ecommerce_search_history_tbl SET last_updated = ? WHERE search_text = ? AND user_id = ? ");
                $stmt->execute([$this->current_time(), $text, $LogUser->user_id]);
            }
        }

        $stmt = $this->db()->prepare("INSERT INTO $this->ecommerce_search_history_tbl (`search_text`, `last_updated`) VALUES (?,?) ");
        $stmt->execute([$text, $this->current_time()]);
    }
}

class Errors extends Web
{

    public static function return_exit($text)
    {
        echo $text;
        exit();
    }

    public static function response($message)
    {
        http_response_code(400);
        $output = new stdClass;
        $output->message = $message;
        self::return_exit(json_encode($output));
    }

    public static function force_login($role = false)
    {
        global $Web;
        $role = $role == false ? "visitor" : "seller";
        $googleLogin = new googleLogin("login", $role);
        $googleLoginLink = $googleLogin->getUrl();
        $card = '<div class="modal fade" id="loginModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content min-w-lg-600px">
                <div class="d-flex flex-center flex-column flex-column-fluid p-10">
                    <div style="position: absolute;right: 7px;top: 12px;" class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <span class="svg-icon svg-icon-2x svg-icon-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="black"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="black"></rect>
                            </svg>
                        </span>
                    </div>
                    <a href=' . $Web->base_url() . '" class="mb-2">
                        <img alt="Logo" src="' . $Web->logo() . '" class="h-40px" />
                    </a>
                    <div id="loginCard" class="w-100 mw-500px shadow-none login-card border-0 card mx-auto">
                        <form class="form w-100" novalidate="novalidate" id="loginForm" action="#">
                            <div class="text-center mb-10">
                                <h1 class="text-dark mb-3">Log In to ' . $Web->web_name() . ' </h1>
                                <div class="text-gray-700 fw-bold fs-4">New Here?
                                    <a href="' . $Web->base_url() . '/register" class="link-primary fw-bolder">Create an Account</a>
                                </div>
                            </div>
                            <div class="fv-row mb-10">
                                <label class="form-label fs-6 text-dark">Email</label>
                                <input required class="form-control form-control-lg" type="email" name="email" />
                                <div class="invalid-feedback" >Email is required</div>
                            </div>
                            <div class="d-flex flex-stack mb-2">
                            <label class="form-label text-dark fs-6 mb-0">Password</label>
                            <a href="' . $Web->base_url() . '/forgot-password" class="link-primary fs-6">Forgot Password?</a>
                        </div>
                        <div class="fv-row mb-10">
                            <div class="position-relative">
                                <input required class="form-control no-bg form-control-lg password-input" value="" type="password" name="password" autocomplete="off" />
                                <div class="show password-toggle">
                                    <svg class="show" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="-10 -10 47 38" style="stroke:#0c0c0d">
                                        <g style="stroke-width:2;fill:none;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round" transform="translate(1 1)">
                                            <path d="m0 8c0-2 5.5-8 11-8s11 6 11 8-5.5 8-11 8-11-6-11-8z" />
                                            <path d="m11 12c2.2091 0 4-1.7909 4-4 0-2.20914-1.7909-4-4-4-2.20914 0-4 1.79086-4 4 0 2.2091 1.79086 4 4 4z" />
                                            <path d="m13 7c.5523 0 1-.44772 1-1s-.4477-1-1-1-1 .44772-1 1 .4477 1 1 1z" fill="#000" fill-rule="nonzero" />
                                        </g>
                                    </svg>
                                    <svg class="hide" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="-10 -7 47 38" style="stroke:#0c0c0d">
                                        <g style="stroke-width:2;fill:none;fill-rule:evenodd;stroke-linecap:round;stroke-linejoin:round" transform="translate(1 1)">
                                            <path d="m4.14 15.76c-2.44-1.76-4.14-4.13-4.14-5.22 0-1.91 5.27-7.66 10.54-7.66 1.8042928.06356301 3.559947.60164173 5.09 1.56m3.53 2.85c.954643.86366544 1.6242352 1.9970896 1.92 3.25 0 1.92-5.27 7.67-10.54 7.67-.82748303-.0073597-1.64946832-.1353738-2.44-.38" />
                                            <path d="m11.35 14.29c1.3567546-.2923172 2.4501897-1.2939955 2.86-2.62m-1.56-4.33c-1.5090443-.9785585-3.49511641-.77861361-4.77882585.48110127-1.28370945 1.25971488-1.52108848 3.24166123-.57117415 4.76889873" />
                                            <path d="m13.08 7.9c-.1699924-.15256531-.3916348-.2347875-.62-.23-.5522847 0-1 .44771525-1 1 .0046671.23144917.0894903.45410992.24.63" />
                                            <path d="m21.08 0-21.08 21.08" />
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="invalid-feedback">Password is required</div>
                        </div>
                            <div class="fv-row mb-10">
                                <label class="form-check form-check-custom form-check-inline">
                                    <input checked class="form-check-input" type="checkbox" id="keeplogged" />
                                    <span class="form-check-label fw-bold text-gray-700 fs-6">
                                        <a class="ms-1 link-primary">Remember Me</a></span>
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="submit" id="submit" class="btn btn-lg btn-primary w-100 mb-5">Continue</button>
                                <div class="d-flex align-items-center my-6">
                                    <div class="border-bottom border-gray-300 mw-50 w-100"></div>
                                    <span class="fw-bold text-gray-400 fs-7 mx-2">OR</span>
                                    <div class="border-bottom border-gray-300 mw-50 w-100"></div>
                                </div>
    
                                <a false-url href="' . $googleLoginLink . '" type="button" class="btn btn-light-primary btn-active fw-bolder w-100 ">
                                    <img alt="Logo" src="' . $Web->get_assets('images/web/google-icon.svg') . '" class="h-20px me-3" />Login with Google</a>
                            </div>
                        </form>
    
                    </div>
                </div>
            </div>
        </div>
    </div>';

        http_response_code(401);
        $output = new stdClass;
        $output->message = "Sorry! This action can't be done because You are not loggedin";
        $output->card = $card;
        $output->role = $role;
        self::return_exit(json_encode($output));
    }
    public static function force_admin_login()
    {
        Errors::response("Login is required to proceed this request");
    }

    public static function response_404()
    {
        $bt = debug_backtrace();
        $caller = array_shift($bt);

        //   echo $caller['file'];
        //   echo $caller['line'];

        global $Web;
        http_response_code(404);
        $file = file_get_contents("{$Web->absolute_url()}/404/");
        echo $file;
        exit();
    }
    public static function response_500($message)
    {
        http_response_code(500);
        $output = new stdClass;
        $output->message = $message;
        self::return_exit(json_encode($output));
        exit();
    }
}

class googleLogin extends Web
{

    private static $google_client;
    private static $role;
    private static $redirect_url;

    function __construct($action, $role)
    {
        global $Web;
        require_once $this->include('vendor/autoload.php');
        self::$google_client = new Google_Client();
        self::$google_client->setPrompt('consent');
        self::$google_client->setClientId('40945239204-tobloe0cg6ecevta7ul2p03mfe6lookf.apps.googleusercontent.com');
        self::$google_client->setClientSecret('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');
        self::$google_client->addScope('email');
        self::$google_client->addScope('profile');
        self::$role = $role;
        switch ($role) {
            case "visitor":
                if ($action == "login") {
                    self::$redirect_url = $Web->base_url() . '/login';
                    self::$google_client->setRedirectUri($Web->absolute_url() . '/login');
                } else {
                    self::$redirect_url = $Web->base_url() . '/register';
                    self::$google_client->setRedirectUri($Web->absolute_url() . '/register');
                }
                break;
            case "seller":
                if ($action == "login") {
                    self::$redirect_url = $Web->seller_url() . '/login';
                    self::$google_client->setRedirectUri($Web->seller_absolute_url() . '/login');
                } else {
                    self::$redirect_url = $Web->seller_url() . '/register';
                    self::$google_client->setRedirectUri($Web->seller_absolute_url() . '/register');
                }
                break;
        }
    }

    public static function getUrl()
    {
        return self::$google_client->createAuthUrl();
    }

    private static function googleId()
    {
        global $Web;
        $google_id = $Web->unique_id();
        $stmt = $Web->db()->query("SELECT google_id FROM $Web->check_google_signup_tbl WHERE google_id = '$google_id' ");
        return $stmt->rowCount() ? self::googleId() : $google_id;
    }

    public static function verifyRegister($code)
    {
        global $Web;
        $output = new stdClass;
        $output->message = "";
        $output->url = "";

        $token = self::$google_client->fetchAccessTokenWithAuthCode($code);
        $access_token = $token['access_token'] ?? '';
        if ($Web->is_empty($access_token)) return;

        $google_service = new Google_Service_Oauth2(self::$google_client);
        $google_outh_data = $google_service->userinfo->get();
        $first_name = $google_outh_data['given_name'];
        $last_name = $google_outh_data['family_name'];
        $user_email = $google_outh_data['email'];
        $user_email = strtolower($user_email);

        $google_id = self::googleId();
        if (User::is_user_email($user_email)) {
            $details = array(
                'user_email' => $user_email,
                'event' => '1'
            );
            $details = serialize($details);

            $stmt = $Web->db()->prepare(" INSERT INTO $Web->check_google_signup_tbl (`google_id`,`google_email`,`type`,`details`) VALUES (:google_id,:user_email,'register',:details)");

            $stmt->execute([
                ":google_id" => $google_id,
                ":user_email" => $user_email,
                ":details" => $details,
            ]);
            $output->url = self::$redirect_url . '?err=' . $google_id;
            return $output;
        }

        $details = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'user_email' => $user_email,
            'event' => '2'
        );
        $details = serialize($details);

        $stmt = $Web->db()->prepare(" INSERT INTO $Web->check_google_signup_tbl (`google_id`,`google_email`,`type`,`details`) VALUES (:google_id,:user_email,'register',:details)");

        $stmt->execute([
            ":google_id" => $google_id,
            ":user_email" => $user_email,
            ":details" => $details,
        ]);

        $output->url =  self::$redirect_url . '?google=' . $google_id;
        return $output;
    }

    public static function verifyLogin($code)
    {
        global $Web;
        global $Login;

        $output = new stdClass;
        $output->message = "";
        $output->url = "";

        $token = self::$google_client->fetchAccessTokenWithAuthCode($code);
        $access_token = $token['access_token'] ?? '';
        if ($Web->is_empty($access_token)) {
            $output->message = "Invalid token";
            return $output;
        };

        $google_service = new Google_Service_Oauth2(self::$google_client);
        $google_outh_data = $google_service->userinfo->get();
        $user_email = $google_outh_data['email'];
        $user_email = strtolower($user_email);
        $google_id = self::googleId();

        if (!User::is_user_email($user_email)) {
            $details = array(
                'user_email' => $user_email,
                'error' => "Account does not exist"
            );
            $details = serialize($details);

            $stmt = $Web->db()->prepare(" INSERT INTO $Web->check_google_signup_tbl (`google_id`,`google_email`,`type`,`details`) VALUES (:google_id,:user_email,'login',:details)");

            $stmt->execute([
                ":google_id" => $google_id,
                ":user_email" => $user_email,
                ":details" => $details,
            ]);

            $output->url =  self::$redirect_url . '?err=' . $google_id;
            return $output;
        } else {

            $user_id = User::user_id($user_email);
            $User = new User($user_id);
            $login_with_google = $User->google_login();
            $login_with_email = $User->email_login();

            if ($login_with_google == "off" && $login_with_email == "on") {
                $details = array(
                    'user_email' => $user_email,
                    'error' => "This account uses email Login"
                );
                $details = serialize($details);

                $stmt = $Web->db()->prepare(" INSERT INTO $Web->check_google_signup_tbl (`google_id`,`google_email`,`type`,`details`) VALUES (:google_id,:user_email,'login',:details)");

                $stmt->execute([
                    ":google_id" => $google_id,
                    ":user_email" => $user_email,
                    ":details" => $details,
                ]);

                $output->url = self::$redirect_url . '?err=' . $google_id;
                return $output;
            }
            if (self::$role == "seller" && !$User->is_seller()) $User->create_seller_account();

            $query = $Login->insert_user_login_session($user_id, 1, self::$role);
            if (!$query) {
                $output->message = "Error in login with google";
                return $output;
            }
            $output->message = "Login Successfull";
            $output->url = self::$role == "visitor" ?  $Web->base_url() : $Web->seller_url();
            return $output;
        }
    }
}


class Login extends Web
{

    function is_session_id_valid($session_id)
    {
        $stmt = $this->db()->prepare("SELECT * FROM $this->login_session_tbl WHERE session_id = ? ");
        $stmt->execute([$session_id]);
        if (!$stmt->rowCount()) return false;
        $data = $stmt->fetch();
        $valid_till = $data->valid_till;
        $status = $data->status;
        $user_id = $data->user_id;
        if ($valid_till < $this->current_time())  return false;
        if ($status == "expired") return false;
        if (User::is_user_id($user_id))   return true;
        return false;
    }


    public function is_user_loggedin()
    {
        if (!isset($_COOKIE['_usession_id'])) return false;
        $session_id = $_COOKIE['_usession_id'];
        if ($this->is_empty($session_id)) return false;

        $sql = $this->db()->prepare("SELECT * FROM $this->login_session_tbl WHERE session_id = ? AND status = 'active' ");
        $sql->execute([$session_id]);
        if (!$sql->rowCount()) return false;

        $data = $sql->fetch();
        $valid_till = $data->valid_till;
        $user_id = $data->user_id;

        if ($valid_till < $this->current_time())  return false;
        if (User::is_user_id($user_id)) return true;
        return false;
    }


    public function loginUser()
    {
        if ($this->is_user_loggedin()) {
            $session_id = $_COOKIE['_usession_id'];
            $stmt = $this->db()->prepare("SELECT * FROM $this->login_session_tbl WHERE session_id = ? AND status = 'active' ");
            $stmt->execute([$session_id]);
            $data = $stmt->fetch();
            $user_id = $data->user_id;
            return $user_id;
        }
    }

    public  function check_user_already_loggedin()
    {
        if ($this->is_user_loggedin()) {
            $this->locate_to($this->base_url());
        }
    }

    public function check_user_login()
    {
        if (!$this->is_user_loggedin())
            $this->locate_to($this->base_url() . "/login?redirect_url=" . $this->request_url());
    }

    public  function get_new_otp($user_email, $purpose)
    {

        $valid_purposes = ['registration', 'reset_password', 'enable_loginverification', 'login_verification'];
        if (!in_array($purpose, $valid_purposes)) Errors::response("Otp Purpose is invalid");

        $stmt = $this->db()->prepare("SELECT * FROM $this->otp_tbl WHERE otp_sender = ? AND `status` = 'valid' AND `purpose` = ? ORDER BY otp_id DESC ");
        $query = $stmt->execute([$user_email, $purpose]);
        if (!$query) throw new \Error("Error in sending otp");

        if (!$stmt->rowCount()) {
            $otp = $this->generate_otp($user_email, $purpose);
            if ($otp) return $otp;
            return false;
        }

        $row = $stmt->fetch();
        $otp = $row->otp;
        if ($this->is_valid_otp($otp, $user_email, $purpose)) return $otp;
        $otp = $this->generate_otp($user_email, $purpose);
        if ($otp) return $otp;
        return false;
    }

    public function is_valid_otp($otp, $user_email, $purpose)
    {
        $stmt = $this->db()->prepare("SELECT * FROM $this->otp_tbl WHERE otp = ? AND otp_sender = ? AND `status` = 'valid'  AND `purpose` = ? ");
        $stmt->execute([$otp, $user_email, $purpose]);

        if (!$stmt->rowCount())  return false;

        $data = $stmt->fetch();
        $otp_valid_date = $data->valid_till;
        if ($otp_valid_date > $this->current_time())  return true;
        return false;
    }

    public  function generate_otp($user_email, $purpose)
    {
        $otp = rand(111111, 999999);
        $stmt = $this->db()->prepare("SELECT * FROM $this->otp_tbl WHERE otp = ? AND `status` = 'valid'  AND `purpose` = ?");
        $stmt->execute([$otp, $purpose]);

        if ($stmt->rowCount()) {
            $otp = $this->generate_otp($user_email, $purpose);
            return $otp;
        }

        $valid_till = strtotime("+15 minutes", ($this->current_time()));
        $stmt = $this->db()->prepare("INSERT INTO $this->otp_tbl (`otp`, `otp_sender`, `valid_till`, `status`, `purpose`)
         VALUES (?,?,?,'valid',?) ");
        $stmt->execute([$otp, $user_email, $valid_till, $purpose]);

        if (!$stmt) return false;
        return $otp;
    }

    public  function get_new_user_id()
    {
        $user_id = 1006090;
        while (User::is_user_id($user_id)) {
            $user_id += 1;
        }
        if (!User::is_user_id($user_id)) {
            return $user_id;
        }
    }
    public  function update_otp_status($otp, $user_email, $otp_purpose)
    {
        $stmt = $this->db()->prepare("UPDATE $this->otp_tbl SET `status` = 'invalid' WHERE `purpose` = ? AND `otp_sender` = ? AND `otp` = ? ");
        $stmt->execute([$otp_purpose, $user_email, $otp]);
    }


    public  function new_session_id()
    {
        return sprintf(
            '%04x%04x%04x%04x%04x%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    public function insert_user_login_session($user_id, $keeploggedin, $role)
    {
        global $Login;

        $max_login_sessions = 100;

        $user_ip = $this->get_ip();
        $user_browser = $this->get_browser();
        $user_os = $this->get_os();
        $user_device = $this->get_device();
        $session_id = $Login->new_session_id();

        $query = @unserialize(file_get_contents('http://ip-api.com/php/' . $user_ip));
        $user_location = '';
        if ($query && $query['status'] == 'success') {
            $countryName  = $query['country'];
            $countryRegion  = $query['regionName'];
            $countryCity     = $query['city'];
            $user_location =  $countryCity . ', ' . $countryRegion . ', ' . $countryName;
        }

        $current_time = $this->current_time();
        if ($keeploggedin == 1) {
            $valid_till_date = (strtotime("+30 days", ($current_time)));
        } else {
            $valid_till_date = (strtotime("+30 minutes", ($current_time)));
        }
        if ($keeploggedin == 1) {
            $time = time() + (86400 * 30);
        } else {
            $time =  time() + (1800);
        }

        switch ($role) {
            case "visitor":
                $name = "_usession_id";
                break;
            case "seller":
                $name = "_ssession_id";
                break;
            case "admin":
                $name = "_asession_id";
                break;
        }

        try {
            $setcookie = setcookie($name, $session_id, [
                'expires' => $time,
                'path' => "/",
                'domain' => $this->host(),
                'secure' => true,
                'httponly' => true,
                'samesite' => 'None',
            ]);

            if (!$setcookie) throw new Exception("Error in setting cookie");

            $stmt = $this->db()->prepare("INSERT INTO $this->login_session_tbl (`user_id`, `session_id`,`date`, `valid_till`, `user_ip`, `user_browser`, `user_os`, `user_device`,`user_location`,`role`) 
            VALUES (?,?,?,?,?,?,?,?,?,?) ");

            $query  = $stmt->execute([$user_id, $session_id, $current_time, $valid_till_date, $user_ip, $user_browser, $user_os, $user_device, $user_location, $role]);
            if (!$query) throw new Exception("Error in Login");

            $stmt = $this->db()->prepare("SELECT COUNT(*) as sessions FROM $this->login_session_tbl WHERE user_id = ? ");
            $stmt->execute([$user_id]);
            $current_login_sessions = $stmt->fetch()->sessions;

            if ($current_login_sessions > $max_login_sessions) {
                $stmt = $this->db()->prepare("DELETE FROM $this->login_session_tbl WHERE user_id = ? ORDER BY id ASC LIMIT 1");
                $stmt->execute([$user_id]);
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public  function logout_all_user($user_id)
    {
        $this->db()->query("UPDATE $this->login_session_tbl SET status = 'expired' WHERE user_id = '$user_id' ");
    }

    public  function logout_with_current($user_id)
    {
        $session_id = $_COOKIE['_usession_id'];
        $this->db()->query("UPDATE $this->login_session_tbl SET status = 'expired' WHERE user_id = '$user_id' AND session_id != '$session_id' ");
    }


    public function get_login_verification_content($user_email, $keeplogged, $role)
    {
        return '
                                        <form id="confirm_login_with_otp_form" class="form w-100 mb-6 form-with-otp" novalidate="novalidate" >
                                            <div class="text-center mb-10">
                                                <i class="fal fa-mobile-android"></i>
                                                <img alt="Logo" class="mh-125px" src="' . $this->get_assets("images/web/smartphone.svg") . '">
                                            </div>
                                            <div class="text-center mb-10">
                                                <h1 class="text-dark mb-3">Two Step Verification</h1>
                                            </div>

                                            <div class="overflow-auto pb-5">
                                                <div class="notice d-flex bg-light-success rounded border-success border border-2 border-dashed flex-shrink-0 p-6">
                                                    <span class="justify-align-center svg-icon svg-icon-2tx svg-icon-success me-4">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                            <path d="M13 5.91517C15.8 6.41517 18 8.81519 18 11.8152C18 12.5152 17.9 13.2152 17.6 13.9152L20.1 15.3152C20.6 15.6152 21.4 15.4152 21.6 14.8152C21.9 13.9152 22.1 12.9152 22.1 11.8152C22.1 7.01519 18.8 3.11521 14.3 2.01521C13.7 1.91521 13.1 2.31521 13.1 3.01521V5.91517H13Z" fill="black"></path>
                                                            <path opacity="0.3" d="M19.1 17.0152C19.7 17.3152 19.8 18.1152 19.3 18.5152C17.5 20.5152 14.9 21.7152 12 21.7152C9.1 21.7152 6.50001 20.5152 4.70001 18.5152C4.30001 18.0152 4.39999 17.3152 4.89999 17.0152L7.39999 15.6152C8.49999 16.9152 10.2 17.8152 12 17.8152C13.8 17.8152 15.5 17.0152 16.6 15.6152L19.1 17.0152ZM6.39999 13.9151C6.19999 13.2151 6 12.5152 6 11.8152C6 8.81517 8.2 6.41515 11 5.91515V3.01519C11 2.41519 10.4 1.91519 9.79999 2.01519C5.29999 3.01519 2 7.01517 2 11.8152C2 12.8152 2.2 13.8152 2.5 14.8152C2.7 15.4152 3.4 15.7152 4 15.3152L6.39999 13.9151Z" fill="black"></path>
                                                        </svg>
                                                    </span>
                                                    <div class="text-center w-100">
                                                        <h4 class="text-gray-900 fw-bolder">Enter the otp sent to</h4>
                                                        <input name="role" type="hidden" value="' . $role . '" > 
                                                        <input id="email_sent_to" type="hidden" value="' . $user_email . '" > 
                                                        <a class="fs-6 text-success pe-7">' . $user_email . '</a>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-8 px-md-10">
                                                <div class="fw-bolder text-start text-dark fs-6 mb-3 ">Enter the 6 digit otp</div>
                                                <input type="hidden" name="keeplogged" value="' . $keeplogged . '" >
                                                <div class="otp-form-group justify-align-center gap-2 gap-md-4">
                                                    <input maxlength="1" autocomplete="off" required name="1" type="text" class="form-control no-bg h-50px w-50px fs-2 text-center" inputmode="text">
                                                    <input maxlength="1" autocomplete="off" required name="2" type="text" class="form-control no-bg h-50px w-50px fs-2 text-center" inputmode="text">
                                                    <input maxlength="1" autocomplete="off" required name="3" type="text" class="form-control no-bg h-50px w-50px fs-2 text-center" inputmode="text">
                                                    <input maxlength="1" autocomplete="off" required name="4" type="text" class="form-control no-bg h-50px w-50px fs-2 text-center" inputmode="text">
                                                    <input maxlength="1" autocomplete="off" required name="5" type="text" class="form-control no-bg h-50px w-50px fs-2 text-center" inputmode="text">
                                                    <input maxlength="1" autocomplete="off" required name="6" type="text" class="form-control no-bg h-50px w-50px fs-2 text-center" inputmode="text">
                                                </div>
                                            </div>
                                            <div class="d-flex flex-center">
                                                <button type="submit" id="submit" class="w-100 btn btn-lg btn-primary fw-bolder">
                                                    <span class="indicator-label">Submit</span>
                                                    <span class="indicator-progress">Please wait...
                                                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                </button>
                                            </div>
                                        </form>
                                        <div class="text-center fw-bold fs-5">
                                            <span class="text-muted me-1">Didn’t get the otp ?</span>
                                            <a id="resendOtp" class="cursor-pointer link-primary fw-bolder fs-5 me-1">Resend</a>
                                        </div>';
    }


    private function get_user_agent()
    {
        return  $_SERVER['HTTP_USER_AGENT'];
    }

    public function get_ip()
    {
        $mainIp = '';
        if (getenv('HTTP_CLIENT_IP'))
            $mainIp = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $mainIp = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $mainIp = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $mainIp = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $mainIp = getenv('REMOTE_ADDR');
        else
            $mainIp = 'UNKNOWN';
        return $mainIp;
    }

    public function get_os()
    {

        $user_agent = $this->get_user_agent();
        $os_platform    =   "Unknown OS Platform";
        $os_array       =   array(
            '/windows nt 10/i'         =>  'Windows 10',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/windows nt 5.0/i'     =>  'Windows 2000',
            '/windows me/i'         =>  'Windows ME',
            '/win98/i'              =>  'Windows 98',
            '/win95/i'              =>  'Windows 95',
            '/win16/i'              =>  'Windows 3.11',
            '/macintosh|mac os x/i' =>  'Mac OS X',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iPhone',
            '/ipod/i'               =>  'iPod',
            '/ipad/i'               =>  'iPad',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        );

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os_platform    =   $value;
            }
        }
        return $os_platform;
    }

    public function  get_browser()
    {

        $user_agent = $this->get_user_agent();

        $browser        =   "Unknown Browser";

        $browser_array  =   array(
            '/msie/i'       =>  'Internet Explorer',
            '/Trident/i'    =>  'Internet Explorer',
            '/firefox/i'    =>  'Firefox',
            '/safari/i'     =>  'Safari',
            '/chrome/i'     =>  'Chrome',
            '/edge/i'       =>  'Edge',
            '/opera/i'      =>  'Opera',
            '/netscape/i'   =>  'Netscape',
            '/maxthon/i'    =>  'Maxthon',
            '/konqueror/i'  =>  'Konqueror',
            '/ubrowser/i'   =>  'UC Browser',
            '/mobile/i'     =>  'Handheld Browser'
        );

        foreach ($browser_array as $regex => $value) {

            if (preg_match($regex, $user_agent)) {
                $browser    =   $value;
            }
        }

        return $browser;
    }

    public function  get_device()
    {

        $tablet_browser = 0;
        $mobile_browser = 0;

        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $tablet_browser++;
        }

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
            $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']), 'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
            $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($this->get_user_agent(), 0, 4));
        $mobile_agents = array(
            'w3c ', 'acs-', 'alav', 'alca', 'amoi', 'audi', 'avan', 'benq', 'bird', 'blac',
            'blaz', 'brew', 'cell', 'cldc', 'cmd-', 'dang', 'doco', 'eric', 'hipt', 'inno',
            'ipaq', 'java', 'jigs', 'kddi', 'keji', 'leno', 'lg-c', 'lg-d', 'lg-g', 'lge-',
            'maui', 'maxo', 'midp', 'mits', 'mmef', 'mobi', 'mot-', 'moto', 'mwbp', 'nec-',
            'newt', 'noki', 'palm', 'pana', 'pant', 'phil', 'play', 'port', 'prox',
            'qwap', 'sage', 'sams', 'sany', 'sch-', 'sec-', 'send', 'seri', 'sgh-', 'shar',
            'sie-', 'siem', 'smal', 'smar', 'sony', 'sph-', 'symb', 't-mo', 'teli', 'tim-',
            'tosh', 'tsm-', 'upg1', 'upsi', 'vk-v', 'voda', 'wap-', 'wapa', 'wapi', 'wapp',
            'wapr', 'webc', 'winw', 'winw', 'xda ', 'xda-'
        );

        if (in_array($mobile_ua, $mobile_agents)) {
            $mobile_browser++;
        }

        if (strpos(strtolower($this->get_user_agent()), 'opera mini') > 0) {
            $mobile_browser++;
            //Check for tablets on opera mini alternative headers
            $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA']) ? $_SERVER['HTTP_X_OPERAMINI_PHONE_UA'] : (isset($_SERVER['HTTP_DEVICE_STOCK_UA']) ? $_SERVER['HTTP_DEVICE_STOCK_UA'] : ''));
            if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
                $tablet_browser++;
            }
        }

        if ($tablet_browser > 0) {
            // do something for tablet devices
            return 'Tablet';
        } else if ($mobile_browser > 0) {
            // do something for mobile devices
            return 'Mobile';
        } else {
            // do something for everything else
            return 'Computer';
        }
    }

    // ***---- Mail Functions Start ---****\\\\
    public  function send_test_email($user_email)
    {
        $subject = "Email testing";
        $body_heading = 'This is a testing email.';
        $body_content = '<p style="font-size:16px;">Hey, we received a request to send testing email.</p>
                                      <p style="font-size:16px;">Email service is working fine!</p>  
                                      <p style="font-size: 14px;">Didn’t request it? You can ignore this message.</p>
                                      <p style="font-size: 14px;">Sincerely,<br/>' . $this->web_name() . ' </p>';
        $action = $this->send_email($user_email, $subject, $body_heading, $body_content);
        if ($action) {
            return true;
        } else {
            return false;
        }
    }

    public  function send_registration_successfull_email($user_id)
    {
        $subject = "Registration Successfull";
        $user_data = new User($user_id);
        $name = $user_data->full_name();
        $body_heading = $subject;
        $body_content = "<p style='margin:20px 0;'>Hi $name,</p>
                        <p style='margin:20px 0;'>You have successfully created your account.</p>
                        <p>User Id: <b> $user_id</b></p>
                        <p>Thanks for becoming a member of " . $this->web_name() . " </p>
                        <p style='margin:20px 0;'>Regards,<br>
                        " . $this->web_name() . "</p> ";


        $this->send_email($user_data->email(), $subject, $body_heading, $body_content);
    }

    public  function send_reset_password_successfull_email($user_id)
    {
        $user_data = new User($user_id);
        $name = $user_data->full_name();
        $subject = "Password reset successfully";
        $body_heading = 'Password reset successfully';
        $body_content = '<p style="font-size:16px;">Hi ' . $name . ',</p>
                        <p style="font-size:16px;">Your password has successfully reset.</p>
                        <p style="font-size:16px;">Regards,<br>
                        ' . $this->web_name() . '</p> ';
        $this->send_email($user_data->email(), $subject, $body_heading, $body_content);
    }

    public  function send_change_password_successfull_email($user_id)
    {
        $user_data = new User($user_id);
        $name = $user_data->full_name();
        $subject = "Password changed successfully";
        $body_heading = 'Your password has changed successfully';
        $body_content = '<p style="font-size:16px;">Hi ' . $name . ',</p>
                        <p style="font-size:16px;">Your password has successfully changed.</p>
                        <p style="font-size:16px;">Regards,<br>
                        ' . $this->web_name() . '</p> ';
        $this->send_email($user_data->email(), $subject, $body_heading, $body_content);
    }

    public  function send_registration_otp($user_email, $otp)
    {
        $subject = 'Otp validatation';
        $body_heading = "You're almost there! Just validate otp";
        $body_content = '<p style="font-size:16px;">Hi,</p>
                        <p style="font-size:16px;">Your otp is:- ' . $otp . ' </p>
                        <p style="font-size:16px;">The otp will expire in 15 minutes. </p>
                        <p style="font-size:16px;">Regards,<br>
                        ' . $this->web_name() . '</p>';
        $action = $this->send_email($user_email, $subject, $body_heading, $body_content);
        if ($action) {
            return true;
        } else {
            return false;
        }
    }


    public  function send_login_verification_code($user_id, $otp)
    {
        $user_data = new User($user_id);
        $subject = 'Two Step Verification';
        $body_heading = "Two Step Verification Code";
        $body_content = '<p style="font-size:16px;">Hi,</p>
                        <p style="font-size:16px;">Your code is:- ' . $otp . ' </p>
                        <p style="font-size:16px;">The code will expire in 15 minutes. </p>
                        <p style="font-size:16px;">Regards,<br>
                        ' . $this->web_name() . '</p>';
        $action = $this->send_email($user_data->email(), $subject, $body_heading, $body_content);
        if ($action) {
            return true;
        } else {
            return false;
        }
    }

    public  function send_enable_login_verification_code($user_id, $otp)
    {
        $user_data = new User($user_id);
        $subject = 'Enable Two Step Verification';
        $body_heading = "Enable Two Step Verification Otp";
        $body_content = '<p style="font-size:16px;">Hi,</p>
                        <p style="font-size:16px;">Your otp is:- ' . $otp . ' </p>
                        <p style="font-size:16px;">The otp will expire in 15 minutes. </p>
                        <p style="font-size:16px;">Regards,<br>
                        ' . $this->web_name() . '</p>';
        $action = $this->send_email($user_data->email(), $subject, $body_heading, $body_content);
        if ($action) {
            return true;
        } else {
            return false;
        }
    }


    public  function send_forgot_password_otp($user_id, $otp)
    {
        $user_data = new User($user_id);
        $name = $user_data->full_name();
        $subject = 'Reset Your Account Password';
        $body_heading = 'Reset Your Account Password';
        $body_content = '<p style="font-size:16px;">Hi ' . $name . ',</p>
                        <p style="font-size:16px;">We heard that you lost your password. Sorry about that !</p>
                       <p style="font-size:16px;">Your otp is:- ' . $otp . ' </p>
                        <p style="font-size:16px;">The otp will expire in 15 minutes.</p>
                        <p style="font-size:16px;">Regards,<br>
                       ' . $this->web_name() . '</p>';
        $action = $this->send_email($user_data->email(), $subject, $body_heading, $body_content);
        if ($action) {
            return true;
        } else {
            return false;
        }
    }

    public  function send_email($receiver_email, $subject, $email_heading, $email_body_content)
    {
        require "PHPMailer/PHPMailerAutoload.php";

        $body = '<body style="background-color: #cfd6f4; margin: 0; padding: 0; -webkit-text-size-adjust: none; text-size-adjust: none;">
            <table border="0" cellpadding="0" cellspacing="0" class="nl-container" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
                <tbody>
                <tr>
                    <td>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-1" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
                        <tbody>
                        <tr>
                            <td>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000;" width="600">
                                <tbody>
                                <tr>
                                    <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 20px; padding-bottom: 0px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                    <table border="0" cellpadding="10" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                        <tr>
                                        <td>
                                            <div align="center" style="line-height:10px">
                                            <a target="_blank" href="' . $this->absolute_url() . '" style="outline:none" tabindex="-1" target="_blank">
                                                <img alt="' . $this->web_name() . '" src="' . $this->logo(true) . '" style="display: block; height: auto; border: 0; width: 160px; max-width: 100%;" title="' . $this->web_name() . '"  />
                                            </a>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                        <tr>
                                        <td style="width:100%;padding-right:0px;padding-left:0px;">
                                            <div align="center" style="line-height:10px">
                                            <img alt="Card Header with Border and Shadow Animated" class="big" src="' . $this->get_assets("images/web/animated_header.gif", true) . '" style="display: block; height: auto; border: 0; width: 600px; max-width: 100%;" title="Card Header with Border and Shadow Animated" width="600" />
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-2" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
                        <tbody>
                        <tr>
                            <td>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-image: url(\'' . $this->get_assets("images/web/body_background_2.png", true) . '\'); background-position: top center; background-repeat: repeat; color: #000000;" width="600">
                                <tbody>
                                <tr>
                                    <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-left: 50px; padding-right: 50px; padding-top: 15px; padding-bottom: 15px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                    <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                        <tr>
                                        <td>
                                            <div style="font-family: sans-serif">
                                            <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #506bec; line-height: 1.2; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
                                                <p style="margin: 0; font-size: 14px;">
                                                <strong>
                                                    <span style="font-size:32px;">' . $email_heading . '</span>
                                                </strong>
                                                </p>
                                            </div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                        <tr>
                                        <td>
                                            <div style="font-family: sans-serif">
                                            <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #40507a; line-height: 1.2; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
                                                <div style="margin: 0; font-size: 14px;">
                                                ' . $email_body_content . '
                                                </p>
                                            </div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-3" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
                        <tbody>
                        <tr>
                            <td>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000;" width="600">
                                <tbody>
                                <tr>
                                    <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-top: 0px; padding-bottom: 5px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                    <table border="0" cellpadding="0" cellspacing="0" class="image_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt;" width="100%">
                                        <tr>
                                        <td style="width:100%;padding-right:0px;padding-left:0px;">
                                            <div align="center" style="line-height:10px">
                                            <img alt="Card Bottom with Border and Shadow Image" class="big" src="' . $this->get_assets("images/web/bottom_img.png", true) . '" style="display: block; height: auto; border: 0; width: 550px; max-width: 100%;" title="Card Bottom with Border and Shadow Image" />
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <table align="center" border="0" cellpadding="0" cellspacing="0" class="row row-4" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; background-color: #cfd6f4;" width="100%">
                        <tbody>
                        <tr>
                            <td>
                            <table align="center" border="0" cellpadding="0" cellspacing="0" class="row-content stack" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; color: #000000;" width="600">
                                <tbody>
                                <tr>
                                    <td class="column" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; font-weight: 400; text-align: left; vertical-align: top; padding-left: 10px; padding-right: 10px; padding-top: 10px; padding-bottom: 20px; border-top: 0px; border-right: 0px; border-bottom: 0px; border-left: 0px;" width="100%">
                                    <table border="0" cellpadding="10" cellspacing="0" class="text_block" role="presentation" style="mso-table-lspace: 0pt; mso-table-rspace: 0pt; word-break: break-word;" width="100%">
                                        <tr>
                                        <td>
                                            <div style="font-family: sans-serif">
                                            <div style="font-size: 14px; mso-line-height-alt: 16.8px; color: #97a2da; line-height: 1.2; font-family: Helvetica Neue, Helvetica, Arial, sans-serif;">
                                                <p style="margin: 0; text-align: center; font-size: 12px;">
                                                <span style="font-size:12px;">Copyright© ' . $this->current_year() . ', <a style="color:#506bec;"
                                                        href="' . $this->absolute_url() . '">' . $this->web_name() . '</a>.</span>
                                                </p>
                                            </div>
                                            </div>
                                        </td>
                                        </tr>
                                    </table>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                
                    </td>
                </tr>
                </tbody>
            </table>
                </body>';


        $stmt = $this->db()->query("SELECT * FROM $this->setting_tbl");
        if (!$stmt->rowCount()) Errors::response("Please check out the Email Setting");
        $row = $stmt->fetch();
        $mail_encryption = $row->mail_encryption;
        $mail_host = $row->mail_host;
        $mail_username = $row->mail_username;
        $mail_port = $row->mail_port;
        $mail_password = $row->mail_password;

        if ($this->is_empty($mail_encryption, $mail_host, $mail_username, $mail_password, $mail_port))  Errors::response("Please check out the Email Setting");


        $sender_email = $mail_username;
        $password = $mail_password;
        $host = $mail_host;

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = $mail_encryption;
        $mail->Host = $host;
        $mail->Port = $mail_port;
        $mail->Username = $sender_email;
        $mail->Password = $password;
        $mail->IsHTML(true);
        $mail->From = $sender_email;
        $mail->FromName = $this->web_name();
        $mail->Sender = $sender_email;
        $mail->AddReplyTo($sender_email, $this->web_name());
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($receiver_email);

        if ($mail->send()) {
            return true;
        } else {
            return false;
        }
    }

    // ***---- Mail Functions End ---****\\\\




    // ***-----Admin Functions Start ----****


    function is_admin_loggedin()
    {
        if (!isset($_COOKIE['_asession_id'])) return false;
        $session_id = $_COOKIE['_asession_id'];
        if ($this->is_empty($session_id)) return false;

        $stmt = $this->db()->prepare("SELECT * FROM $this->login_session_tbl WHERE session_id = ? AND status = 'active' ");
        $stmt->execute([$session_id]);
        if (!$stmt->rowCount()) return false;

        $data = $stmt->fetch();
        $valid_till = $data->valid_till;
        if ($valid_till < $this->current_time())  return false;
        $user_id = $data->user_id;
        return User::is_admin_id($user_id) ? true : false;
    }

    public function loginAdmin()
    {
        if ($this->is_admin_loggedin()) {
            $session_id = $_COOKIE['_asession_id'];
            $stmt = $this->db()->prepare("SELECT * FROM $this->login_session_tbl WHERE session_id = ? AND status = 'active' ");
            $stmt->execute([$session_id]);
            $data = $stmt->fetch();
            $user_id = $data->user_id;
            return $user_id;
        }
    }


    public function check_admin_login()
    {
        if (!$this->is_admin_loggedin()) {
            $this->locate_to($this->admin_url() . "/login?redirect_url=" . $this->request_url());
        }
    }

    public function uncheck_admin_login()
    {
        if ($this->is_admin_loggedin()) {
            $this->locate_to($this->admin_url() . '/dashboard/');
        }
    }
    // ***-----Admin Functions End ----****

    /**
     * 
     * Seller 
     * 
     */

    public function is_seller_loggedin()
    {
        if (!isset($_COOKIE['_ssession_id'])) return false;
        $session_id = $_COOKIE['_ssession_id'];
        if ($this->is_empty($session_id)) return false;

        $sql = $this->db()->prepare("SELECT user_id,valid_till FROM $this->login_session_tbl WHERE session_id = ? AND status = 'active' AND role = 'seller' ");
        $sql->bindParam(1, $session_id);
        $sql->execute();
        if (!$sql->rowCount()) return false;

        $data = $sql->fetch();
        $valid_till = $data->valid_till;
        if ($valid_till < $this->current_time()) return false;
        $user_id = $data->user_id;

        if (User::is_user_id($user_id)) {
            $User = new User($user_id);
            return $User->is_seller() ? true : false;
        };
        return false;
    }

    public function check_seller_login()
    {
        if (!$this->is_seller_loggedin()) $this->locate_to($this->seller_url() . "/login?redirect_url=" . $this->request_url());
    }

    public function uncheck_seller_login()
    {

        if ($this->is_seller_loggedin()) $this->locate_to($this->seller_url() . "/dashboard/");
    }

    public function loginSeller()
    {
        $session_id = $_COOKIE['_ssession_id'];
        $stmt = $this->db()->query("SELECT user_id FROM $this->login_session_tbl WHERE session_id = '$session_id' AND status = 'active' AND role = 'seller' ");
        $data = $stmt->fetch();
        $user_id = $data->user_id;
        return $user_id;
    }
}

class User extends Web
{
    public $user_id;
    private $row;

    public static function is_user_email($user_email)
    {
        global $Web;
        $sql = $Web->db()->prepare("SELECT * FROM $Web->users_tbl WHERE user_email = ? ");
        $sql->bindParam(1, $user_email);
        $sql->execute();
        return $sql->rowCount() ?  true : false;
    }

    public static function is_user_id($user_id)
    {
        global $Web;
        $stmt = $Web->db()->prepare("SELECT * FROM $Web->users_tbl WHERE user_id = ? ");
        $stmt->execute([$user_id]);
        return $stmt->rowCount() ? true : false;
    }

    public static function user_id($user_email)
    {
        global $Web;
        $sql = $Web->db()->prepare("SELECT user_id FROM $Web->users_tbl WHERE user_email = ? ");
        $sql->execute([$user_email]);
        return $sql->fetch()->user_id;
    }

    public static function is_admin_id($user_id)
    {
        global $Web;
        $stmt = $Web->db()->prepare("SELECT * FROM $Web->users_tbl WHERE user_id = ? AND user_role = 'admin' ");
        $stmt->execute([$user_id]);
        if ($stmt->rowCount()) return true;
        return false;
    }

    public static function is_admin_email($user_email)
    {
        global $Web;
        $stmt = $Web->db()->prepare("SELECT user_email FROM $Web->users_tbl WHERE user_email = ? AND user_role = 'admin' ");
        $stmt->execute([$user_email]);
        if ($stmt->rowCount()) return true;
        return false;
    }

    public function __construct($user_id)
    {
        if (!self::is_user_id($user_id)) Errors::response("$user_id is not a valid user id");
        $this->user_id = $user_id;
        $this->row = $this->row();
    }

    private function row()
    {
        if (!empty($this->row)) return $this->row;
        $stmt = $this->db()->prepare("SELECT * FROM $this->users_tbl WHERE user_id = ? ");
        $stmt->execute([$this->user_id]);
        return $stmt->fetch();
    }

    public  function full_name()
    {
        $first_name = $this->row()->first_name;
        $last_name = $this->row()->last_name;
        $full_name = $first_name . ' ' . $last_name;
        return $this->unsanitize_text($full_name);
    }
    public  function email()
    {
        $email = $this->row()->user_email;
        return $this->unsanitize_text($email);
    }

    public  function first_name()
    {
        $first_name = $this->row()->first_name;
        return $this->unsanitize_text($first_name);
    }


    public  function last_name()
    {
        $last_name = $this->row()->last_name;
        return $this->unsanitize_text($last_name);
    }

    public  function password()
    {
        $password = $this->row()->password;
        return $password;
    }

    public  function status()
    {
        $status = $this->row()->status;
        return $status;
    }

    public  function avatar_id()
    {
        $avatar_id = $this->row()->avatar_id ?? 0;
        return $avatar_id;
    }

    public  function avatar()
    {
        $avatar_id = $this->avatar_id();
        if (empty($avatar_id)) return $this->get_assets("images/web/avatar.png");
        return $this->get_file_src($avatar_id);
    }

    public  function google_login()
    {

        $google_login = $this->row()->google_login;
        return $google_login;
    }

    public  function email_login()
    {
        $email_login = $this->row()->email_login;
        return $email_login;
    }

    public  function login_verification()
    {
        $login_verification = $this->row()->login_verification;
        return $login_verification;
    }
    function contact_number()
    {
        $contact_number = $this->row()->contact_number;
        return $contact_number;
    }
    function contact_email()
    {
        $contact_email = $this->row()->contact_email;
        return $contact_email;
    }

    public function registration_date()
    {
        $registration_date = $this->row()->registration_date;
        $registration_date = $this->to_date($registration_date);
        return $registration_date;
    }

    public static function header_profile_img()
    {
        $Login = new Login();
        global $Web;
        if ($Login->is_user_loggedin()) {
            global $LogUser;
            return $LogUser->avatar();
        } else {
            return $Web->get_assets("/images/web/avatar.png");
        }
    }

    public static function admin_header_profile_img()
    {
        $Login = new Login();
        global $Web;
        if ($Login->is_admin_loggedin()) {
            global $LogAdmin;
            return $LogAdmin->avatar();
        } else {
            return $Web->get_assets("/images/web/avatar.png");
        }
    }

    public static function seller_header_profile_img()
    {
        $Login = new Login();
        global $Web;
        if ($Login->is_seller_loggedin()) {
            global $LogSeller;
            return $LogSeller->store_logo();
        } else {
            return $Web->get_assets("/images/web/avatar.png");
        }
    }

    public static function ecommerce_header_profile()
    {
        $Login = new Login();
        global $Web;
        if ($Login->is_user_loggedin()) {
            $loginUser = new User($Login->loginUser());
            return '
                        <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                    <div class="symbol symbol-50px me-5">
                        <img alt="Logo" src="' . $loginUser->avatar() . '" />
                    </div>
                    <div class="d-flex flex-column">
                        <div class="fw-bolder d-flex align-items-center fs-6">' . $loginUser->full_name() . '
                            <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span>
                        </div>
                        <a href="#" class="fw-bold text-muted text-hover-primary fs-7">' . $Login->loginUser() . '</a>
                    </div>
                </div>
            </div>
            <div class="separator d-none d-lg-block my-2"></div>
            <div class="menu-item">
                <a class="menu-link py-3" href="' . $Web->base_url() . '/profile/" >
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z" fill="black" />
                                <path d="M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z" fill="black" />
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">My Profile</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link py-3" href="' . $Web->base_url() . '/wishlist/" >
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M18.3721 4.65439C17.6415 4.23815 16.8052 4 15.9142 4C14.3444 4 12.9339 4.73924 12.003 5.89633C11.0657 4.73913 9.66 4 8.08626 4C7.19611 4 6.35789 4.23746 5.62804 4.65439C4.06148 5.54462 3 7.26056 3 9.24232C3 9.81001 3.08941 10.3491 3.25153 10.8593C4.12155 14.9013 9.69287 20 12.0034 20C14.2502 20 19.875 14.9013 20.7488 10.8593C20.9109 10.3491 21 9.81001 21 9.24232C21.0007 7.26056 19.9383 5.54462 18.3721 4.65439Z" fill="black" />
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">Wishlist</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link py-3" href="' . $Web->base_url() . '/cart/" >
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M18.041 22.041C18.5932 22.041 19.041 21.5932 19.041 21.041C19.041 20.4887 18.5932 20.041 18.041 20.041C17.4887 20.041 17.041 20.4887 17.041 21.041C17.041 21.5932 17.4887 22.041 18.041 22.041Z" fill="black"></path>
                                <path opacity="0.3" d="M6.04095 22.041C6.59324 22.041 7.04095 21.5932 7.04095 21.041C7.04095 20.4887 6.59324 20.041 6.04095 20.041C5.48867 20.041 5.04095 20.4887 5.04095 21.041C5.04095 21.5932 5.48867 22.041 6.04095 22.041Z" fill="black"></path>
                                <path opacity="0.3" d="M7.04095 16.041L19.1409 15.1409C19.7409 15.1409 20.141 14.7409 20.341 14.1409L21.7409 8.34094C21.9409 7.64094 21.4409 7.04095 20.7409 7.04095H5.44095L7.04095 16.041Z" fill="black"></path>
                                <path d="M19.041 20.041H5.04096C4.74096 20.041 4.34095 19.841 4.14095 19.541C3.94095 19.241 3.94095 18.841 4.14095 18.541L6.04096 14.841L4.14095 4.64095L2.54096 3.84096C2.04096 3.64096 1.84095 3.04097 2.14095 2.54097C2.34095 2.04097 2.94096 1.84095 3.44096 2.14095L5.44096 3.14095C5.74096 3.24095 5.94096 3.54096 5.94096 3.84096L7.94096 14.841C7.94096 15.041 7.94095 15.241 7.84095 15.441L6.54096 18.041H19.041C19.641 18.041 20.041 18.441 20.041 19.041C20.041 19.641 19.641 20.041 19.041 20.041Z" fill="black"></path>
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">Cart</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link py-3" href="' . $Web->base_url() . '/orders/" >
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M5 8.04999L11.8 11.95V19.85L5 15.85V8.04999Z" fill="black" />
                                <path d="M20.1 6.65L12.3 2.15C12 1.95 11.6 1.95 11.3 2.15L3.5 6.65C3.2 6.85 3 7.15 3 7.45V16.45C3 16.75 3.2 17.15 3.5 17.25L11.3 21.75C11.5 21.85 11.6 21.85 11.8 21.85C12 21.85 12.1 21.85 12.3 21.75L20.1 17.25C20.4 17.05 20.6 16.75 20.6 16.45V7.45C20.6 7.15 20.4 6.75 20.1 6.65ZM5 15.85V7.95L11.8 4.05L18.6 7.95L11.8 11.95V19.85L5 15.85Z" fill="black" />
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">Orders</span>
                </a>
            </div>
            <div class="menu-item">
                <a class="menu-link py-3" href="' . $Web->base_url() . '/logout" >
                    <span class="menu-icon">
                        <span class="svg-icon svg-icon-2">
                            <svg style="transform: rotate(180deg);" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.3" x="4" y="11" width="12" height="2" rx="1" fill="black" />
                                <path d="M5.86875 11.6927L7.62435 10.2297C8.09457 9.83785 8.12683 9.12683 7.69401 8.69401C7.3043 8.3043 6.67836 8.28591 6.26643 8.65206L3.34084 11.2526C2.89332 11.6504 2.89332 12.3496 3.34084 12.7474L6.26643 15.3479C6.67836 15.7141 7.3043 15.6957 7.69401 15.306C8.12683 14.8732 8.09458 14.1621 7.62435 13.7703L5.86875 12.3073C5.67684 12.1474 5.67684 11.8526 5.86875 11.6927Z" fill="black" />
                                <path d="M8 5V6C8 6.55228 8.44772 7 9 7C9.55228 7 10 6.55228 10 6C10 5.44772 10.4477 5 11 5H18C18.5523 5 19 5.44772 19 6V18C19 18.5523 18.5523 19 18 19H11C10.4477 19 10 18.5523 10 18C10 17.4477 9.55228 17 9 17C8.44772 17 8 17.4477 8 18V19C8 20.1046 8.89543 21 10 21H19C20.1046 21 21 20.1046 21 19V5C21 3.89543 20.1046 3 19 3H10C8.89543 3 8 3.89543 8 5Z" fill="#C4C4C4" />
                            </svg>
                        </span>
                    </span>
                    <span class="menu-title">Log Out</span>
                </a>
            </div>

                                ';
        } else {
            return '
                        <div class="menu-item px-5">
                                        <a href="' . $Web->base_url() . '/login" class="menu-link px-5">Login</a>
                                    </div><div class="menu-item px-5">
                                        <a href="' . $Web->base_url() . '/register" class="menu-link px-5">Register</a>
                                    </div>
                        ';
        }
    }

    public static function admin_header_profile()
    {
        $Login = new Login();
        global $Web;
        if ($Login->is_admin_loggedin()) {
            global $LogAdmin;
            return '<div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" src="' . $LogAdmin->avatar() . '" />
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-6">' . $LogAdmin->full_name() . '
                                        <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Pro</span>
                                    </div>
                                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">' . $Login->loginAdmin() . '</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator  my-2"></div>
                       <div class="menu-item px-5">
                            <a href="' . $Web->admin_url() . '/logout" class="menu-link px-5">Log Out</a>
                        </div>
                    ';
        } else {
            return '
            <div class="menu-item px-5">
                            <a href="' . $Web->admin_url() . '/login" class="menu-link px-5">Login</a>
                        </div><div class="menu-item px-5">
                            <a href="' . $Web->admin_url() . '/register" class="menu-link px-5">Register</a>
                        </div>
            ';
        }
    }
    public static function seller_header_profile()
    {
        $Login = new Login();
        global $Web;
        if ($Login->is_seller_loggedin()) {
            global $LogSeller;
            return '<div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-50px me-5">
                                    <img alt="Logo" src="' . $LogSeller->store_logo() . '" />
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bolder d-flex align-items-center fs-6">' . $LogSeller->full_name() . '
                                        <span class="badge badge-light-success fw-bolder fs-8 px-2 py-1 ms-2">Seller</span>
                                    </div>
                                    <a href="#" class="fw-bold text-muted text-hover-primary fs-7">' . $LogSeller->user_id . '</a>
                                </div>
                            </div>
                        </div>
                        <div class="separator  my-2"></div>
                         <div class="menu-item px-5">
                            <a href="' . $Web->seller_url() . '/manage-account/" class="menu-link px-5">Manage Account</a>
                        </div>
                         <div class="menu-item px-5">
                            <a href="' . $Web->seller_url() . '/support/" class="menu-link px-5">Support</a>
                        </div>
                       <div class="menu-item px-5">
                            <a href="' . $Web->seller_url() . '/logout?redirect_url=' . $Web->seller_url() . '/login?redirect_url=' . $Web->request_url() . '" class="menu-link px-5">Log Out</a>
                        </div>
                    ';
        } else {
            return '
            <div class="menu-item px-5">
                            <a href="' . $Web->seller_url() . '/login?redirect_url=' . $Web->request_url() . '" class="menu-link px-5">Login</a>
                        </div><div class="menu-item px-5">
                            <a href="' . $Web->seller_url() . '/register?redirect_url=' . $Web->request_url() . '" class="menu-link px-5">Register</a>
                        </div>
            ';
        }
    }

    public function session_id()
    {
        $session_id = $_COOKIE['_usession_id'];
        return $session_id;
    }

    public function is_seller()
    {
        $row = $this->row();
        $is_seller = $row->is_seller;
        return $is_seller == "yes" ? true : false;
    }


    public function create_seller_account()
    {
        $stmt = $this->db()->query("UPDATE $this->users_tbl SET is_seller = 'yes' WHERE user_id = '$this->user_id' ");
        if (!$stmt) Errors::response("Something went wrong in creating seller account");

        $stmt = $this->db()->prepare("INSERT INTO $this->ecommerce_seller_users_tbl (`user_id`,`status`,`seller_since`) VALUES (?,?,?) ");
        $query = $stmt->execute([$this->user_id, 'unverified', $this->current_time()]);
        if (!$query) Errors::response("Something went wrong in creating seller account");
    }

    public function purchased_products()
    {
        $stmt = $this->db()->query("SELECT COUNT(*) as products FROM $this->ecommerce_orders_tbl WHERE buyer_id = '$this->user_id' AND status = 'DELIVERED' ");
        return $stmt->fetch()->products;
    }
}

class FileUpload extends Web
{
    public $name;
    public $validExt = ["png", "jpg", "jpeg", "webp"];
    public $errorText = "Something went wrong in uploading file";
    public $fileType = "image";
    public $changeFileName = true;
    public $file_extension;

    function __construct($name)
    {
        $this->name = $name;
    }

    public static function is_image($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        return !in_array($extension, ["png", "webp", "jpg", "jpeg"]);
    }

    private function response($type, $text, $id = false)
    {
        $output = new stdClass;
        $output->type = $type;
        $output->message = $text;

        if ($id !== false) {
            $output->id = $id;
            $output->name = basename($this->get_file_src($id));
            $output->url = $this->get_file_src($id);
            $output->preview_url = $this->file_preview_url($id);
            $output->ext = $this->file_extension;
            $output->showExt = $this->is_image($this->get_file_src($id));
        }
        return $output;
    }

    private function extErrText()
    {
        $validExt = $this->validExt;
        $validExt = implode(",", $validExt);
        return "Upload a valid image.  Only $validExt are allowed";
    }

    private function new_file_name($ext)
    {
        $rand = $this->get_uuid();
        $unique_id = $this->unique_id();
        return $unique_id . $this->current_time() . $rand . '.' . $ext;
    }

    private function new_file_original_name($filename)
    {
        $unique_id = $this->unique_id();
        return  $filename . "_" . $unique_id . $this->current_time();
    }

    public function upload()
    {
        if (!isset($_FILES[$this->name])) return $this->response("error", $this->errorText);

        $file = $_FILES[$this->name];
        $file = preg_replace("/\s+/", "_", $file);
        $real_filename = $file['name'];
        $filepath = $file['tmp_name'];
        $file_extension =  strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $fileinfo = getimagesize($file['tmp_name']);
        $fileerror = $file['error'];
        if ($fileerror != 0) return $this->response("error", $this->errorText);
        $validExt = $this->validExt;
        $real_filename = $this->sanitize_text($real_filename);

        $filename = $this->changeFileName == true ? $this->new_file_name($file_extension) : $this->new_file_original_name($real_filename);
        $destfile = $this->include("files/$filename");

        $this->file_extension = $file_extension;

        if (!empty($file_extension)) {
            if (!empty($validExt) && !in_array($file_extension, $validExt)) return $this->response("error", $this->extErrText());
            if (isset($fileinfo['mime'])) {
                if ($fileinfo['mime'] == 'image/jpeg') {
                    $image = imagecreatefromjpeg($filepath);
                    imagejpeg($image, $destfile, 60);
                } elseif ($fileinfo['mime'] == 'image/png') {
                    $image = imagecreatefrompng($filepath);
                    imagealphablending($image, false);
                    imagesavealpha($image, true);
                    imagepng($image, $destfile, 9);
                } else if ($fileinfo['mime'] == 'image/webp') {
                    $image = imagecreatefromwebp($filepath);
                    imagewebp($image, $destfile, 60);
                } else {
                    if (!@move_uploaded_file($file['tmp_name'], $destfile)) return $this->response("error", $this->errorText);
                }
            } else {
                if (!@move_uploaded_file($file['tmp_name'], $destfile)) return $this->response("error", $this->errorText);
            }
        } else {
            if (!@move_uploaded_file($file['tmp_name'], $destfile)) return $this->response("error", $this->errorText);
        }

        $stmt = $this->db()->prepare("INSERT INTO $this->files_tbl (`file_src`,`file_name`,`type`) VALUES (?,?,?)  ");
        $query = $stmt->execute([$filename, $real_filename, 'file']);
        if (!$query)  return $this->response("error", $this->errorText);
        $image_inserted_id = $this->db()->lastInsertId();

        return $this->response("success", "$this->fileType has been uploaded", $image_inserted_id);
    }
}

class Address extends Web
{
    public $address_id;
    private $row;

    function __construct($id)
    {
        if (!self::is_address_id($id)) throw new \Error("$id is not a valid address id");
        $this->address_id = $id;
        $this->row = $this->row();
    }

    public static function is_address_id($address_id)
    {
        global $Web;
        $stmt = $Web->db()->prepare("SELECT * FROM $Web->ecommerce_address_tbl WHERE address_id = ? ");
        $stmt->execute([$address_id]);
        return $stmt->rowCount() ? true : false;
    }

    public static function has_address($user_id)
    {
        global $Web;
        $stmt = $Web->db()->prepare("SELECT address_id FROM $Web->ecommerce_address_tbl WHERE user_id = ?  ");
        $stmt->execute([$user_id]);
        return $stmt->rowCount() ? true : false;
    }

    public static function primary_id($user_id)
    {
        global $Web;
        if (!self::has_address($user_id)) return;
        $stmt = $Web->db()->prepare("SELECT address_id FROM $Web->ecommerce_address_tbl WHERE user_id = ? AND is_default = 'yes' ");
        $stmt->execute([$user_id]);
        if (!$stmt->rowCount()) return;
        return $stmt->fetch()->address_id;
    }

    public function row()
    {
        if (!empty($this->row)) return $this->row;
        $stmt = $this->db()->prepare("SELECT * FROM $this->ecommerce_address_tbl WHERE address_id = ? ");
        $stmt->execute([$this->address_id]);
        return $stmt->fetch();
    }

    public function update()
    {
        $this->row = $this->row();
    }

    public function user()
    {
        $row = $this->row();
        $user_id = $row->user_id;
        return new User($user_id);
    }

    public function full_name()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->full_name);
    }

    public function mobile_number()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->mobile_number);
    }

    public function postcode()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->postcode);
    }

    public function state()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->state);
    }

    public function city()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->city);
    }

    public function area()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->area);
    }

    public function flat()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->flat);
    }

    public function address_type()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->address_type);
    }

    public function landmark()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->landmark);
    }

    public function is_default()
    {
        $row = $this->row();
        return $this->unsanitize_text($row->is_default);
    }

    public function full_address()
    {
        $full_address = '';
        if (!$this->is_empty($this->flat())) $full_address .= ", " . $this->flat();
        $full_address .=  "," . $this->area();
        $full_address .= ", " . $this->landmark();
        $full_address .= ", " . $this->city();
        $full_address .= ", " . $this->state();
        $full_address .= " - " . $this->postcode();

        $main_address = '';
        $full_address = explode(",", $full_address);
        foreach ($full_address as $address) {
            if (!$this->is_empty($address)) {
                $main_address .= $address . ",";
            }
        }
        $main_address = rtrim($main_address, ", ");
        return $main_address;
    }

    public static function all_cards($user_id)
    {
        global $Web;
        $output = '';
        $stmt = $Web->db()->prepare("SELECT address_id FROM $Web->ecommerce_address_tbl WHERE user_id = ? ");
        $stmt->execute([$user_id]);
        $row = $stmt->fetchAll();
        foreach ($row as $data) {
            $output .= (new Address($data->address_id))->card();
        }
        return $output;
    }

    public function card()
    {
        $class = $this->is_default()  == "yes" ? " success-active" : "";
        $primary = $this->is_default() == "yes" ? "" : '<div class="menu-item px-3">
                        <a data-action="mark" class="menu-link px-3">Mark as primary</a>
                    </div>';
        return '<div data-id="' . $this->address_id . '" class="address-card col-lg-6 ">
        <div class="border ' . $class . ' align-justify-between p-6">
                <div class="d-flex flex-column py-2">
                    <div class="d-flex align-items-center fs-5 fw-bolder mb-5">' . $this->full_name() . ' ' . $this->mobile_number() . '
                        <span class="badge badge-light-success text-uppercase fs-7 ms-2">' . $this->address_type() . '</span>
                    </div>
                    <div class="fs-6 fw-bold word-break text-gray-600">' . $this->full_address() . ' </div>
                </div>
                <div class="d-flex align-items-center py-2">
                <div class="drop-container">
                <button type="button" class="btn btn-sm btn-icon btn-active-white btn-active-color- border-0 me-n3" data-lx-menu-trigger="click" data-lx-menu-placement="bottom-end">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-bold w-200px py-3" data-lx-menu="true" style="">
                    <div class="menu-item px-3">
                        <a data-action="edit" class="menu-link px-3">Edit</a>
                    </div>
                     ' . $primary . '
                    <div class="menu-item px-3">
                        <a data-action="delete" class="menu-link px-3">Delete</a>
                    </div>
                </div>
            </div>
                </div>
            </div></div>';
    }

    public function edit_form()
    {
        return '
        <form class="form" novalidate>
    <div class="modal-header">
        <h2>Edit Address</h2>
        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
            <span class="svg-icon svg-icon-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor" />
                    <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor" />
                </svg>
            </span>
        </div>
    </div>
    <div class="modal-body py-10 px-lg-17">
        <div class="scroll-y px-7" id="address_scroll" data-lx-scroll="true" data-lx-scroll-activate="{default: false, lg: true}" data-lx-scroll-max-height="auto" data-lx-scroll-wrappers="#address_scroll" data-lx-scroll-offset="300px">
            <div class="d-flex flex-column mb-5 fv-row">
                <label class="required fs-5 fw-bold mb-2">Full Name</label>
                <input data-max="40" required class="form-control form-control-solid" value="' . $this->full_name() . '" name="full_name" />
                <div class="invalid-feedback">Full Name is required</div>
            </div>
            <div class="d-flex flex-column mb-5 fv-row">
                <label class="required fs-5 fw-bold mb-2">Mobile Number </label>
                <input required class="form-control form-control-solid" value="' . $this->mobile_number() . '" name="mobile_number" />
                <div class="invalid-feedback">Mobile Number is required</div>
            </div>
            <div class="d-flex flex-column mb-5 fv-row">
                <label class="required fs-5 fw-bold mb-2"> Town/City </label>
                <input data-max="100" required class="form-control form-control-solid" value="' . $this->city() . '" name="city" />
                <div class="invalid-feedback">Town/City is required</div>
            </div>
            <div class="d-flex flex-column mb-5 fv-row">
                <label class="required fs-5 fw-bold mb-2">Area, Colony, Street, Sector, Village </label>
                <input data-max="100" required class="form-control form-control-solid" value="' . $this->area() . '" name="area" />
                <div class="invalid-feedback">Area is required</div>
            </div>
            <div class="d-flex flex-column mb-5 fv-row">
                <label class="fs-5 fw-bold mb-2"> Flat, House no., Building, Company, Apartment </label>
                <input data-max="100" class="form-control form-control-solid" value="' . $this->flat() . '" name="flat" />
            </div>
            <div class="d-flex flex-column mb-5 fv-row">
                <label class="required fs-5 fw-bold mb-2"> Landmark</label>
                <input data-max="100" required class="form-control form-control-solid" value="' . $this->landmark() . '" name="landmark" />
                <div class="invalid-feedback">Landmark is required</div>
            </div>
            <div class="row g-9 mb-5">
                <div class="col-md-6 fv-row">
                    <label class="required fs-5 fw-bold mb-2">State / Province</label>
                    <input data-max="30" required class="form-control form-control-solid" value="' . $this->state() . '" name="state" />
                    <div class="invalid-feedback">State is required</div>
                </div>
                <div class="col-md-6 fv-row">
                    <label class="required fs-5 fw-bold mb-2">Post Code</label>
                    <input required class="form-control form-control-solid" value="' . $this->postcode() . '" name="postcode" />
                    <div class="invalid-feedback">Post Code is required</div>
                </div>
            </div>
            <div class="fv-row">
                <div class="d-flex">
                    <label class="fs-5 fw-bold"> Address Type</label>
                    <div class="ms-4">
                        <div class="form-check form-check-custom form-check-solid">
                            <input required class="form-check-input me-1" name="address_type" data-value="' . $this->address_type() . '" type="radio" value="office" id="office">
                            <label class="form-check-label" for="office">
                                <div class="fw-bold text-gray-800">Office(Delivery between 10 AM - 5 PM)</div>
                            </label>
                        </div>
                        <div class="mt-4 form-check form-check-custom form-check-solid">
                            <input required class="form-check-input me-1" name="address_type" data-value="' . $this->address_type() . '" type="radio" value="home" id="home">
                            <label class="form-check-label" for="home">
                                <div class="fw-bold text-gray-800">Home(All day delivery)</div>
                            </label>
                            <div class="invalid-feedback">Address Type is required</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer flex-center">
        <button data-bs-dismiss="modal" type="button" class="btn btn-light me-3">Cancel</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>';
    }
}


class Transaction extends Functions
{
    public $transaction_id;
    private $row;

    function __construct($transaction_id)
    {
        if (!self::is_transaction_id($transaction_id)) throw new Error("$transaction_id is not a vaild transaction id");
        $this->transaction_id = $transaction_id;
        $this->row = $this->row();
    }

    public static function is_transaction_id($transaction_id)
    {
        $Web = $GLOBALS["Web"];
        $stmt = $Web->db()->prepare("SELECT transaction_id FROM $Web->transactions_tbl WHERE transaction_id = ? ");
        $stmt->execute([$transaction_id]);
        return $stmt->rowCount() ? true : false;
    }

    private function row()
    {
        if (!empty($this->row)) return $this->row;
        $stmt = $this->db()->prepare("SELECT * FROM $this->transactions_tbl WHERE transaction_id = ? ");
        $stmt->execute([$this->transaction_id]);
        return $stmt->fetch();
    }

    public function final_date()
    {
        $date = $this->row()->final_date();
        return $this->date_time($date);
    }
    public function type()
    {
        $type = $this->row()->type;
        return $type;
    }

    public function withdraw_id()
    {
        $stmt = $this->db()->query("SELECT withdraw_id FROM $this->ecommerce_sellers_withdraw_tbl WHERE transaction_id = '$this->transaction_id'  ");
        return $stmt->fetch()->withdraw_id ?? 0;
    }

    // public function 
}

if ($db) {
    include $Web->include("php/Ecommerce.php");
}
if ($db && $Login->is_seller_loggedin()) {
    $seller_id = $Login->loginSeller();
    $LogSeller = new Ecommerce\Seller($seller_id);
    $LogSeller->check_clearing_payments();
}
